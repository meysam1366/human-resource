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
        Schema::create('personal_infos', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->enum('gender', ['male', 'female']);
            $table->string('father_name');
            $table->string('national_code')->unique();
            $table->enum('education_level', ['diploma', 'associate', 'bachelor', 'master', 'phd']);
            $table->string('id_number');
            $table->string('id_serial'); // حرف-اعداد
            $table->foreignId('issue_place_id')->constrained('cities');
            $table->foreignId('birth_place_id')->constrained('cities');
            $table->date('birth_date');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_infos');
    }
};
