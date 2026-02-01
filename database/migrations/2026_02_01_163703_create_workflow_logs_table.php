<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('workflow_run_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('status');      // info, success, failed
            $table->text('message');       // log message

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_logs');
    }
};
