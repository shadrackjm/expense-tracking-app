<?php

namespace App\Console\Commands;

use App\Models\Expense;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateRecurringExpense extends Command
{
    protected $signature = 'expenses:generate-recurring-expense';
    protected $description = 'Generate recurring expenses based on their schedule';

    public function handle(): int
    {
        $this->info('Starting to generate recurring expenses...');

        $recurringExpenses = Expense::recurring()
            ->whereNull('deleted_at')
            ->get();

        $generatedCount = 0;

        foreach ($recurringExpenses as $expense) {
            $generated = $this->generateExpensesForRecurring($expense);
            $generatedCount += $generated;
        }

        $this->info("Successfully generated {$generatedCount} recurring expenses.");
        
        Log::info("Generated {$generatedCount} recurring expenses", [
            'command' => 'expenses:generate-recurring',
            'timestamp' => now(),
        ]);

        return Command::SUCCESS;
    }

    private function generateExpensesForRecurring(Expense $recurringExpense): int
    {
        if (!$recurringExpense->shouldGenerateNextOccurrence()) {
            return 0;
        }

        $nextDate = $recurringExpense->getNextOccurrenceDate();
        Log::info("next occurance {$nextDate}");
        $generatedCount = 0;

        // Generate all missing occurrences up to today
        while ($nextDate && $nextDate->lte(now())) {
            // Check if already generated
            $exists = Expense::where('parent_expense_id', $recurringExpense->id)
                ->whereDate('date', $nextDate)
                ->exists();

            if (!$exists) {
                $this->createExpenseOccurrence($recurringExpense, $nextDate);
                $generatedCount++;
                
                $this->line("Generated: {$recurringExpense->title} for {$nextDate->format('Y-m-d')}");
            }

            // Calculate next occurrence
            $nextDate = match($recurringExpense->recurring_frequency) {
                'daily' => $nextDate->copy()->addDay(),
                'weekly' => $nextDate->copy()->addWeek(),
                'monthly' => $nextDate->copy()->addMonth(),
                'yearly' => $nextDate->copy()->addYear(),
                default => null,
            };

            // Check if we've passed the end date
            if ($recurringExpense->recurring_end_date && 
                $nextDate && 
                $nextDate->gt($recurringExpense->recurring_end_date)) {
                break;
            }

            // Safety check: don't generate future expenses
            if ($nextDate && $nextDate->gt(now())) {
                break;
            }
        }

        return $generatedCount;
    }

    private function createExpenseOccurrence(Expense $recurringExpense, $date): void
    {
        Expense::create([
            'user_id' => $recurringExpense->user_id,
            'category_id' => $recurringExpense->category_id,
            'amount' => $recurringExpense->amount,
            'title' => $recurringExpense->title,
            'description' => $recurringExpense->description,
            'date' => $date,
            'type' => 'one-time',
            'parent_expense_id' => $recurringExpense->id,
            'is_auto_generated' => true,
        ]);
    }
}
