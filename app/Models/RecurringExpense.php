<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RecurringExpense extends Model
{
    /** @use HasFactory<\Database\Factories\RecurringExpenseFactory> */
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'amount',
        'expense_date',
        'recurrence_frequency',
        'recurrence_interval',
    ];

    use HasFactory;

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}
