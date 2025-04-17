<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patient_session_medication', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_session_id')->constrained('patient_sessions')->onDelete('cascade');
            $table->foreignId('medication_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_session_medication');
    }
};