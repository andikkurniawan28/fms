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
        Schema::create('income_journals', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('code')->unique();
            $table->foreignId('account_id')->constrained();
            $table->text('description');
            $table->foreignId('user_id')->constrained();
            $table->double('total');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('income_journals');
    }
};
