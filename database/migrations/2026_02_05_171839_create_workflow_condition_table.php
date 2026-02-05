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
        Schema::create('workflow_condition', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')
                  ->constrained('workflows')
                  ->onDelete('cascade');
            $table->string('field');        // amount
            $table->string('operator');     // >=
            $table->string('value'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_condition');
    }
};
