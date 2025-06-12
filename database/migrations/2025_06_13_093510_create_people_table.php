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
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('created_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();

            // All fields are required
            $table->string('name');
            $table->string('surname');
            $table->string('sa_id_number', 13)->index();
            $table->string('mobile_number', 50);
            $table->string('email', 320)->index(); // Maximum length, according to RFC 3696
            $table->date('birth_date');
            $table->string('language_code', 35); // RFC 5646 states the longest character code to be 35 characters
            $table->json('interests');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
