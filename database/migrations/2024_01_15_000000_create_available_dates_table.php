<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('available_dates', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Index for fast lookups
            $table->unique(['date', 'start_time', 'end_time']);
            $table->index('date');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('available_dates');
    }
};
