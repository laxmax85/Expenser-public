<?php

use App\Models\Category;
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
        Schema::create('recurring_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Category::class);
            $table->string('iconId')->nullable();
            $table->string('name');
            $table->string('description')->nullable();
            $table->decimal('amount', 10,  2);
            $table->date('expense_date');
            $table->enum('recurrence_frequency', ['monthly', 'yearly', 'none']);
            $table->integer('recurrence_interval')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recurring_expenses');
    }
};
