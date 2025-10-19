<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'amount',
        'title',
        'description',
        'date',
        'type',
        'recurring_frequency',
        'recurring_start_date',
        'recurring_end_date',
        'parent_expense_id',
        'is_auto_generated',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
        'recurring_start_date' => 'date',
        'recurring_end_date' => 'date',
        'is_auto_generated' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function parentExpense(): BelongsTo
    {
        return $this->belongsTo(Expense::class, 'parent_expense_id');
    }

    public function childExpenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'parent_expense_id');
    }
    
     public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeRecurring($query)
    {
        return $query->where('type', 'recurring');
    }

    public function scopeOneTime($query)
    {
        return $query->where('type', 'one-time');
    }

    public function scopeInMonth($query, $month, $year)
    {
        return $query->whereMonth('date', $month)
                     ->whereYear('date', $year);
    }

    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function isRecurring(): bool
    {
        return $this->type === 'recurring';
    }

    public function shouldGenerateNextOccurrence(): bool
    {
        if (!$this->isRecurring()) {
            return false;
        }

        if ($this->recurring_end_date && now()->isAfter($this->recurring_end_date)) {
            return false;
        }

        return true;
    }

     public function getNextOccurrenceDate(): ?\Carbon\Carbon
    {
        if (!$this->isRecurring()) {
            return null;
        }

        $lastChildExpense = $this->childExpenses()
            ->orderBy('date', 'desc')
            ->first();

        $baseDate = $lastChildExpense ? $lastChildExpense->date : $this->recurring_start_date;

        return match($this->recurring_frequency) {
            'daily' => $baseDate->copy()->addDay(),
            'weekly' => $baseDate->copy()->addWeek(),
            'monthly' => $baseDate->copy()->addMonth(),
            'yearly' => $baseDate->copy()->addYear(),
            default => null,
        };
    }

}
