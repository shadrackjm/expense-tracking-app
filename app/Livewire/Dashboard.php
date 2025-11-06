<?php

namespace App\Livewire;

use App\Models\Budget;
use App\Models\Expense;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public $selectedMonth;
    public $selectedYear;
    public $totalSpent;
    public $monthlyBudget;
    public $percentageUsed;
    public $expenseByCategory;
    public $recentExpenses;
    public $monthlyComparison;
    public $topCategories;
    public $recurringExpenseCount;

    public function mount()
    {
        $this->selectedMonth = now()->month;
        $this->selectedYear = now()->year;
        $this->loadDashboardData();
    }

    public function loadDashboardData(){
        $userId = Auth::user()->id;

        // total amount spent in a month
        $this->totalSpent = Expense::forUser($userId)
        ->inMonth($this->selectedMonth,$this->selectedYear)
        ->sum('amount');

        // monthly budget
        $budget = Budget::where('user_id', $userId)
        ->where('month', $this->selectedMonth)
        ->where('year', $this->selectedYear)
        ->first();

        $this->monthlyBudget = $budget ? $budget->amount : 0;

        // percentage used
        $this->percentageUsed = $this->monthlyBudget > 0 
                                    ? round(($this->totalSpent / $this->monthlyBudget) * 100, 1)
                                     : 0;

        // Expense by category
        $this->expenseByCategory = Expense::select('categories.name','categories.color', DB::raw('SUM(expenses.amount) as total'))
        ->join('categories','expenses.category_id','=','categories.id')
        ->where('expenses.user_id',$userId)
        ->whereMonth('expenses.date', $this->selectedMonth)
        ->whereYear('expenses.date', $this->selectedYear)
        ->groupBy('categories.id','categories.name','categories.color')
        ->orderBy('total','desc')
        ->get();


        // recent expenses
        $this->recentExpenses = Expense::with('category')
        ->forUser($userId)
        ->whereMonth('date', $this->selectedMonth)
        ->whereYear('date', $this->selectedYear)
        ->orderBy('date','desc')
        ->orderBy('created_at','desc')
        ->take(5)
        ->get();

        //monthly comparison
        $this->monthlyComparison = collect();
        for ($i=5; $i >= 0; $i--) { 
            $date = Carbon::create($this->selectedYear,$this->selectedMonth,1)->subMonths($i);
            $total = Expense::forUser($userId)
                    ->inMonth($date->month,$date->year)
                    ->sum('amount');
            $this->monthlyComparison->push([
                'month'=> $date->format('M'),
                'total'=> $total,
            ]);

        }

        // top categories
        $this->topCategories = $this->expenseByCategory->take(3);

        // recurring expenses
        $this->recurringExpenseCount = Expense::forUser($userId)
            ->recurring()
            ->count();


    }

    public function updatedSelectedMonth(){
        $this->loadDashboardData();
    }
    public function updatedSelectedYear(){
        $this->loadDashboardData();
    }
    public function previousMonth(){
        $date = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->subMonth();
        $this->selectedMonth = $date->month;
        $this->selectedYear = $date->year;
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->addMonth();
        $this->selectedMonth = $date->month;
        $this->selectedYear = $date->year;
    }   

    public function render()
    {
        return view('livewire.dashboard');
    }
}
