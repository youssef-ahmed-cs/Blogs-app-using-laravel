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
        Schema::table('posts', function (Blueprint $table) {
            $table->boolean('is_reshare')->default(false);
            $table->unsignedBigInteger('original_post_id')->nullable();
            $table->text('quote')->nullable();
            
            // Add foreign key constraint
            $table->foreign('original_post_id')
                  ->references('id')
                  ->on('posts')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['original_post_id']);
            $table->dropColumn(['is_reshare', 'original_post_id', 'quote']);
        });
    }
};
