<?php

namespace App\Livewire;

use App\Models\Expense;
use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
#[Title("Recurring Expenses - ExpenseApp")]
class RecurringExpense extends Component
{
    public $showDeleteModal = false;
    public $expenseToDelete = null;

    public function confirmDelete($expenseId){
        $this->expenseToDelete = $expenseId;
        $this->showDeleteModal = true;
    }

    public function deleteExpense(){
        if($this->expenseToDelete){
            $expense = Expense::findOrFail($this->expenseToDelete);
            if($expense->user_id !== Auth::user()->id){
                abort(403);
            }
            // delete the dependent expenses
            $expense->childExpenses()->delete();
            $expense->delete();

            session()->flash('message','Recurring Expense deleted successfully!');

            $this->showDeleteModal = false;
            $this->expenseToDelete = null;
        }
    }
    #[Computed()]
    public function recurringExpenses(){
        return Expense::with(['category','childExpenses'])
        ->forUser(Auth::user()->id)
        ->recurring()
        ->get();
    }
    #[Computed()]
    public function categories(){
        return Category::where('user_id', Auth::user()->id)
        ->orderBy('name')
        ->get();
    }

    public function render()
    {
        return view('livewire.recurring-expense',[
            'recurringExpenses' => $this->recurringExpenses(),
            'categories' => $this->categories()
        ]);
    }
}
