<?php

use App\Livewire\Dashboard;
use App\Models\Budget;
use App\Livewire\BudgetForm;
use App\Livewire\BudgetList;
use App\Livewire\Categories;
use App\Livewire\ExpenseForm;
use App\Livewire\ExpenseList;
use Laravel\Fortify\Features;
use App\Livewire\RecurringExpense;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\TwoFactor;
use App\Livewire\Settings\Appearance;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboard',Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('categories', Categories::class)->name('categories.index');
    Route::get('budgets',BudgetList::class,)->name('budgets.index');
    Route::get('budgets/create', BudgetForm::class)->name('budget.create');
    Route::get('budgets/{budgetId}/edit', BudgetForm::class)->name('budgtes.edit');

    //expenses
    Route::get('expenses', ExpenseList::class)->name('expenses.index');
    Route::get('/expenses/create',ExpenseForm::class)->name('expenses.create');
    Route::get('expenses/{expenseId}/edit',ExpenseForm::class)->name('expenses.edit');
    Route::get('recurring-expenses',RecurringExpense::class)->name('recurring-expenses.index');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

require __DIR__.'/auth.php';