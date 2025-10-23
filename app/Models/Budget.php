<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Budget extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'amount',
        'month',
        'year',
    ];
    protected $casts = [
        'amount' => 'decimal:2',
        'month' => 'integer',
        'year' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getSpentAmount(): float
    {
        if ($this->category_id) {
            return $this->category->getTotalSpentForMonth($this->month, $this->year);
        }

        return Expense::forUser($this->user_id)
            ->inMonth($this->month, $this->year)
            ->sum('amount');
    }

    public function getRemainingAmount(): float
    {
        return $this->amount - $this->getSpentAmount();
    }

    public function getPercentageUsed(): float
    {
        if ($this->amount == 0) {
            return 0;
        }

        return ($this->getSpentAmount() / $this->amount) * 100;
    }

    public function isOverBudget(): bool
    {
        return $this->getSpentAmount() > $this->amount;
    }
}