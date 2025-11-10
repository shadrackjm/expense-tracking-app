<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Expense;
use App\Models\Category;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Log;

class BudgetAIService {
    public function getBudgetRecommendation(?int $categoryId, int $userId, int $month, int $year): ?array {
       try {
            // get hostorical spending data
            $historicalData = $this->getHistoricalSpending( $categoryId,  $userId,  $month,  $year);

            if (empty($historicalData)) {
                return null;
            }

            //create prompt
            $prompt = $this->createPrompt( $historicalData,
                    $categoryId,
                    $month,
                    $year);

            // get recommendation from Gemini Ai
            $response =  Gemini::generativeModel(model: 'gemini-2.0-flash')->generateContent($prompt);

            return $this->parseAIResponse($response->text(), $historicalData);
            
       } catch (\Exception $e) {
            Log::error('Budget AI Recommendation Error:' . $e->getMessage());
            return null;
       } 
    }

    private function getHistoricalSpending(?int $categoryId, int $userId, int $targetMonth, int $targetYear){
        $expenses = [];
        $monthlyTotals = [];

        // get the last 3 months of data (exclude the target month)
        for($i = 1; $i <= 3; $i++){
            $date = Carbon::create($targetYear,$targetMonth,1)->subMonths($i);
            
            $query = Expense::where('user_id', $userId)
            ->whereMonth('date', $date->month)
            ->whereYear('date', $date->year);

            if ($categoryId) {
                $query->where('category_id', $categoryId);
            }

            $monthExpenses = $query->get();
            $total = $monthExpenses->sum('amount');

            if ($total > 0) {
                $expenses[] = [
                    'month' => $date->format('F Y'),
                    'total' => $total,
                    'count' => $monthExpenses->count(),
                    'expenses' => $monthExpenses->pluck('title')->take(10)->toArray(),
                ];
                $monthlyTotals[] = $total;
            }

            return [
                'expenses'=> $expenses,
                'average'=> !empty($monthlyTotals) ? array_sum($monthlyTotals) / count($monthlyTotals) : 0,
                'min'=> !empty($monthlyTotals) ? min($monthlyTotals) : 0,
                'max'=> !empty($monthlyTotals) ? max($monthlyTotals) : 0,
                'trend' => $this->calculateTrend($monthlyTotals),
            ];
        }
        
    }

    private function calculateTrend($monthlyTotals){
        if (count($monthlyTotals) < 2) {
            return 'stable';
        }

        // compare most recent month with the oldest month
        $recent = $monthlyTotals[0];
        $oldest = $monthlyTotals[count($monthlyTotals) -1];

        $percentageChange = (($recent - $oldest) /$oldest) *100;

        if ($percentageChange > 10) {
            return 'increasing';
        }elseif ($percentageChange < -10) {
            return 'decreasing';
        }

        return 'stable';
    }

    // create the prompt for Gemini AI
    private function createPrompt(array $historicalData, ?int $categoryId, int $month, int $year){
        $categoryName = $categoryId ? Category::find($categoryId)?->name ?? 'this category' :'overall spending';

        $targetMonth = Carbon::create($year, $month, 1)->format('F Y');

        $prompt = "You are a personal finance advisor. Analyze the following spending data and provide a budget recommendation.\n\n";

        $prompt .= "Category: {$categoryName}\n";
        $prompt .= "Target Month: {$targetMonth}";
        $prompt .= "Historical Spending (Last 3 Months): \n";
        foreach ($historicalData['expenses'] as $expense) {
            $prompt .= "- {$expense['month']}: \${expense['total']} ({$expense['count']}) expenses)\n";
            if (!empty($expense['expenses'])) {
                $prompt .= " Top items: ". implode(', ', array_slice($expense['expenses'],0, 5));
            }
        }

        $prompt .= "\nSpending Statistics:\n";
        $prompt .= "- Average: \$" . number_format($historicalData['average'], 2). "\n";
        $prompt .= "- Minimum: \$" . number_format($historicalData['min'], 2) . "\n";
        $prompt .= "- Maximum: \$" . number_format($historicalData['max'], 2) . "\n";
        $prompt .= "- Trend: {$historicalData['trend']}\n\n";

        $prompt .= "Based on this data, provide:\n";
        $prompt .= "1. A recommended budget amount (single number)\n";
        $prompt .= "2. A minimum safe amount\n";
        $prompt .= "3. A maximum comfortable amount\n";
        $prompt .= "4. A brief explanation (2-3 sentences) why you recommend this amount\n";
        $prompt .= "5. One actionable tip to stay within budget\n\n";

        $prompt .= "Format your response as JSON with these exact keys:\n";
        $prompt .= '{"recommended": 500, "min": 450, "max": 550, "explanation": "...", "tip": "..."}';
        
        return $prompt;
    }

    private function parseAIResponse(string $response, array $historicalData){
        try {
            //json
            if (preg_match('/\{[^}]+\}/', $response, $matches)) {
                $json = json_decode($matches[0], true);
                
                if ($json && isset($json['recommended'])) {
                    return [
                        'recommended' => (float) $json['recommended'],
                        'min' => (float) ($json['min'] ?? $json['recommended'] * 0.9),
                        'max' => (float) ($json['max'] ?? $json['recommended'] * 1.1),
                        'explanation' => $json['explanation'] ?? 'Based on your spending patterns.',
                        'tip' => $json['tip'] ?? 'Track your expenses regularly to stay on budget.',
                        'confidence' => $this->calculateConfidence($historicalData),
                    ];
                }
            }

            return $this->getFallbackRecommendation($historicalData); 
        } catch (\Exception $e) {
            Log::error('Failed to Parse AI response'. $e->getMessage());
            return $this->getFallbackRecommendation($historicalData);
        }
    }

    private function getFallbackRecommendation(array $historicalData){
        $average = $historicalData['average'];
        
        // Add 10% buffer for safety
        $recommended = round($average * 1.1, 2);
        
        return [
            'recommended' => $recommended,
            'min' => round($average * 0.95, 2),
            'max' => round($average * 1.2, 2),
            'explanation' => "Based on your average spending of $" . number_format($average, 2) . " over the last 3 months, with a 10% buffer for unexpected expenses.",
            'tip' => "Review your expenses weekly to catch any overspending early.",
            'confidence' => $this->calculateConfidence($historicalData),
        ];
    }

    private function calculateConfidence(array $historicalData){
        $monthsWithData = count($historicalData['expenses']);
        
        if ($monthsWithData >= 3) {
            return 'high';
        } elseif ($monthsWithData === 2) {
            return 'medium';
        } else {
            return 'low';
        }
    }

    // check if the user has enough historical data
    public function hasEnoughHistoricalData(?int $categoryId, int $userId){
        $threeMonthsAgo = now()->subMonths(3)->startOfDay();

        $query = Expense::where('user_id', $userId)
        ->where('date', '>=', $threeMonthsAgo);

        if ($categoryId) {
            $query = $query->where('category_id', $categoryId);
        }

        return $query->count() > 5;
    }
}