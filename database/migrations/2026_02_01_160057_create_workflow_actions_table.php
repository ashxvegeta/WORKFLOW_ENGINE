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
        Schema::create('workflow_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->string('action_type');          // send_email, update_status, webhook
            $table->json('payload')->nullable();    // action data
            $table->unsignedInteger('delay_seconds')->default(0);
            $table->unsignedInteger('sequence');    // execution order
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_actions');
    }
};
