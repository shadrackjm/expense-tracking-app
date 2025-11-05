<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class ExpenseList extends Component
{
    use WithPagination;
    //public properties
    public $search = "";
    public $selectedCategory = "";
    public $startDate = "";
    public $endDate = "";
    public $sortBy = "date";
    public $sortDirection = "desc";
    public $showFilters = false;

    public function mount(){
        //default to current month
        if(empty($this->startDate)){
            $this->startDate = now()->startOfMonth()->format("Y-m-d");
        }
        if(empty($this->startDate)){
            $this->startDate = now()->endOfMonth()->format("Y-m-d");
        }
    }

    //sorting
    public function sortBy($field){
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection == "asc"?"desc":"asc";
        }else{
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    //deleting the expense
    public function deleteExpense($expenseId){
        $expense = Expense::findOrFail($expenseId);

        if ($expense->user_id !== Auth::user()->id) {
            abort(403,'Your Not Authorized to Perform this function');
        }

        $expense->delete();

        session()->flash('message','Expense deleted successfully!');
    }

    //computed property of expenses
    #[Computed]
    public function expenses(){
         $query = Expense::with('category')
            ->forUser(auth()->id());

        if ($this->search) {
    //      $query->where('title', 'like', '%' . $this->search . '%')
    //      ->orWhere('description', 'like', '%' . $this->search . '%'); // (title LIKE '%term%') OR (description LIKE '%term%' AND category_id = 5)
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });

            //WHERE (title LIKE '%term%' OR description LIKE '%term%') AND category_id = 5

        }

        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }

        if ($this->startDate) {
            $query->whereDate('date', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('date', '<=', $this->endDate);
        }

        return $query->orderBy($this->sortBy, $this->sortDirection)
                     ->paginate(10);
        
    }
  #[Computed]
    public function total(){
        $query = Expense::forUser(Auth::user()->id);
        //apply search & filters
        if($this->search){
            $query->where('title','like','%'.$this->search.'%')
            ->orWhere('description','like','%'.$this->search.'%');
        }
        if($this->selectedCategory){
            $query->where('category_id', $this->selectedCategory);
        }

        if($this->startDate){
            $query->whereDate('date',">=", $this->startDate);
        }
        if($this->startDate){
            $query->whereDate('date',"<=", $this->startDate);
        }

        return $query->sum('amount');
    }
  #[Computed]
    public function categories(){
        return Category::where('user_id', Auth::user()->id)
        ->orderBy('name')
        ->get();
    }

    public function updatingSearch(){
        $this->resetPage();
    }

    public function updatingSelectedCategory()
    {
        $this->resetPage();
    }

    public function updatingStartDate()
    {
        $this->resetPage();
    }

    public function updatingEndDate()
    {
        $this->resetPage();
    }

     public function clearFilters()
    {
        $this->search = '';
        $this->selectedCategory = '';
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
        $this->resetPage();
    }
    public function render()
    {
        return view('livewire.expense-list',[
            'expenses'=> $this->expenses,
            'total' => $this->total,
            'categories' => $this->categories
        ]);
    }
}