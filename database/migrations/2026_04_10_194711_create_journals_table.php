<?php

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
        Schema::create('journals', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->date('date');
            $table->foreignId('user_id')->constrained();
            $table->text('description');
            $table->double('debit');
            $table->double('credit');

            $table->foreignId('order_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('payment_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('expense_journal_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('income_journal_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journals');
    }
};
