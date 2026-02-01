<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_triggers', function (Blueprint $table) {
            $table->id();

            // Foreign key to workflows table
            $table->foreignId('workflow_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Event name that triggers the workflow
            $table->string('event_name');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_triggers');
    }
};
