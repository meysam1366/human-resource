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
        Schema::create('personal_info_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_info_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['photo', 'national_card', 'id_card', 'education_doc']);
            $table->string('path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_info_documents');
    }
};
