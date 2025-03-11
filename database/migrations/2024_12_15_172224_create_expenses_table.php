<?php

use App\Models\Category;
use App\Models\RecurringExpense;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Category::class);
            $table->string('name');
            $table->string('description')->nullable();
            $table->decimal('amount', 10,  2);
            $table->date('expense_date');
            $table->foreignIdFor(RecurringExpense::class)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
