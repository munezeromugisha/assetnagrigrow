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
        Schema::create('input_outputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_id')->constrained()->onDelete('cascade');
            $table->foreignId('field_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['input', 'output']);
            $table->string('name'); // e.g., "Fertilizer", "Maize"
            $table->integer('quantity');
            $table->string('unit'); // e.g., "kg", "liters"
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('input_outputs');
    }
};
