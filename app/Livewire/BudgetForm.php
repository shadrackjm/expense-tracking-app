<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Budget;
use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
#[Title("Budget - ExpenseApp")]
class BudgetForm extends Component
{
    public $budgetId;
    public $amount = '';
    public $month;
    public $year;
    public $category_id = '';
    
    public $isEdit = false;

    protected function rules()
    {
        $rules = [
            'amount' => 'required|numeric|min:0.01',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2100',
            'category_id' => 'nullable|exists:categories,id',
        ];

        // Check for duplicate budget
        $uniqueRule = 'unique:budgets,category_id,NULL,id,user_id,' . Auth::user()->id . ',month,' . $this->month . ',year,' . $this->year;
        
        if ($this->isEdit) {
            $uniqueRule = 'unique:budgets,category_id,' . $this->budgetId . ',id,user_id,' . Auth::user()->id . ',month,' . $this->month . ',year,' . $this->year;
        }

        $rules['category_id'] = $this->category_id ? 'required|exists:categories,id|' . $uniqueRule : 'nullable|' . $uniqueRule;

        return $rules;
    }
    protected $messages = [
        'amount.required' => 'Please enter a budget amount.',
        'amount.min' => 'Budget amount must be greater than 0.',
        'month.required' => 'Please select a month.',
        'year.required' => 'Please select a year.',
        'category_id.unique' => 'You already have a budget for this category in this month.',
    ];

    public function mount($budgetId = null)
    {
        if ($budgetId) {
            $this->isEdit = true;
            $this->budgetId = $budgetId;
            $this->loadBudget();
        } else {
            $this->month = now()->month;
            $this->year = now()->year;
        }
    }

    public function loadBudget()
    {
        $budget = Budget::findOrFail($this->budgetId);
        
        if ($budget->user_id !== Auth::user()->id) {
            abort(403);
        }

        $this->amount = $budget->amount;
        $this->month = $budget->month;
        $this->year = $budget->year;
        $this->category_id = $budget->category_id;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'user_id' => Auth::user()->id,
            'amount' => $this->amount,
            'month' => $this->month,
            'year' => $this->year,
            'category_id' => $this->category_id ?: null,
        ];

        if ($this->isEdit) {
            $budget = Budget::findOrFail($this->budgetId);
            
            if ($budget->user_id !== Auth::user()->id) {
                abort(403);
            }

            $budget->update($data);
            session()->flash('message', 'Budget updated successfully.');
        } else {
            Budget::create($data);
            session()->flash('message', 'Budget created successfully.');
        }

        return redirect()->route('budgets.index');
    }
    #[Computed]
    public function months(){
        return collect(range(1,12))->map(function ($month) {
            return [
                'value' => $month,
                'name' => Carbon::create(null,$month, 1)->format('F'),
            ];
        });
    }
    #[Computed]
    public function years(){
        $currentYear = now()->year;
        return collect(range($currentYear - 1, $currentYear + 2));
    }
       #[Computed]
       public function categories(){
         return Category::where('user_id', Auth::user()->id)
                      ->orderBy('name')
                      ->get();
       }
    public function render()
    {
        return view('livewire.budget-form',[
        'categories'=> $this->categories,
        'months' => $this->months,
        'years' => $this->years
        ]);
    }
}
