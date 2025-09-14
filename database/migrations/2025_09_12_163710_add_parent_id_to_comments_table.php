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
        Schema::table('comments', function (Blueprint $table) {
            // Check if the column exists before trying to add it
            if (!Schema::hasColumn('comments', 'parent_id')) {
                $table->unsignedBigInteger('parent_id')->nullable()->after('user_id');
                $table->foreign('parent_id')->references('id')->on('comments')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            // Only drop if the column exists
            if (Schema::hasColumn('comments', 'parent_id')) {
                $table->dropForeign(['parent_id']);
                $table->dropColumn('parent_id');
            }
        });
    }
};
