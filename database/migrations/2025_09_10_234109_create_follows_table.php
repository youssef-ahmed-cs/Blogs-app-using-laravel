<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('follows', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('follower_id');
        $table->unsignedBigInteger('followed_id');
        $table->timestamps();

        $table->foreign('follower_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('followed_id')->references('id')->on('users')->onDelete('cascade');
        $table->unique(['follower_id', 'followed_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follows');
    }
};
