<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    /** @use HasFactory<\Database\Factories\ExpenseFactory> */
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'amount',
        'expense_date',
        'recurrence_frequency',
        'recurrence_interval',
        'recurring_expense_id'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function recurringExpense() : BelongsTo
    {
        return $this->belongsTo(RecurringExpense::class);
    }
}
