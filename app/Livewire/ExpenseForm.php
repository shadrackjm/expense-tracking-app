<?php

namespace App\Livewire;

use App\Models\Expense;
use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
#[Title("Expense - ExpenseApp")]
class ExpenseForm extends Component
{
    public $expenseId;
    // #[Validate('required|numeric|min:0.01')]
    public $amount = '';
    public $title = '';
    public $description = '';
    public $date;
    public $category_id = '';
    public $type = 'one-time';
    public $recurring_frequency = 'monthly';
    public $recurring_start_date;
    public $recurring_end_date; 
    
    public $isEdit = false;

    protected function rules()
    {
        $rules = [
            'amount' => 'required|numeric|min:0.01',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'category_id' => 'nullable|exists:categories,id',
            'type' => 'required|in:one-time,recurring',
        ];

        if ($this->type === 'recurring') {
            $rules['recurring_frequency'] = 'required|in:daily,weekly,monthly,yearly';
            $rules['recurring_start_date'] = 'required|date';
            $rules['recurring_end_date'] = 'nullable|date|after:recurring_start_date';
        }

        return $rules;
    }

    public function mount($expenseId = null){
       if($expenseId){
        $this->isEdit = true;
        $this->expenseId = $expenseId;
        $this->loadExpense();
       }else{
        $this->date = now()->format('Y-m-d');
        $this->recurring_start_date = now()->format('Y-m-d');
       }
    }

    public function loadExpense(){
         $expense = Expense::findOrFail($this->expenseId);
        
        if ($expense->user_id !== Auth::user()->id) {
            abort(403);
        }
        $this->amount = $expense->amount;
        $this->title = $expense->title;
        $this->description = $expense->description;
        $this->date = $expense->date->format('Y-m-d');
        $this->category_id = $expense->category_id;
        $this->type = $expense->type;
        $this->recurring_frequency = $expense->recurring_frequency;
        $this->recurring_start_date = $expense->recurring_start_date->format('Y-m-d');
        $this->recurring_end_date = $expense->recurring_end_date;
    }

    #[Computed]
    public function categories(){
        return Category::where('user_id',Auth::user()->id)
        ->orderBy('name','asc')
        ->get();
    }

    public function save(){
        $this->validate();
        $data = [
            'user_id' => Auth::user()->id,
            'amount' => $this->amount,
            'title' => $this->title,
            'description' => $this->description,
            'date' => $this->date,
            'category_id' => $this->category_id ?: null,
            'type' => $this->type,
        ];
        if ($this->type === 'recurring') {
            $data['recurring_frequency'] = $this->recurring_frequency;
            $data['recurring_start_date'] = $this->recurring_start_date;
            $data['recurring_end_date'] = $this->recurring_end_date ?: null;
        } else {
            $data['recurring_frequency'] = null;
            $data['recurring_start_date'] = null;
            $data['recurring_end_date'] = null;
        }

        if($this->isEdit){
            $expense = Expense::findOrFail($this->expenseId);
            if ($expense->user_id !== Auth::user()->id) {
                abort(403);
            }
            $expense->update($data);
            session()->flash('message', 'Expense updated successfully.');
        }else{
            Expense::create($data);
            session()->flash('message', 'Expense created successfully.');
        }

        return redirect()->route('expenses.index');

    }
    public function render()
    {
        return view('livewire.expense-form',[
            'categories'=> $this->categories,
        ]);
    }
}