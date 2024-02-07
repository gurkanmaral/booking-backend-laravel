<?php

use App\Models\User;
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
        Schema::create('houses', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('name');
            $table->text('description');
            $table->unsignedInteger('price');
            $table->string('location');
            $table->string('image_url_1')->nullable(); 
            $table->string('image_url_2')->nullable();
            $table->string('image_url_3')->nullable(); 
            $table->string('image_url_4')->nullable(); 
            $table->foreignIdFor(User::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('houses');
    }
};
