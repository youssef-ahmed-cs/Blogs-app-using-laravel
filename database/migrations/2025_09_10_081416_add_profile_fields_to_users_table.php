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
    Schema::table('users', function (Blueprint $table) {
        if (!Schema::hasColumn('users', 'profile_image')) {
            $table->string('profile_image')->nullable()->after('role');
        }

        if (!Schema::hasColumn('users', 'bio')) {
            $table->text('bio')->nullable()->after('profile_image');
        }
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        if (Schema::hasColumn('users', 'profile_image')) {
            $table->dropColumn('profile_image');
        }
        if (Schema::hasColumn('users', 'bio')) {
            $table->dropColumn('bio');
        }
    });
}

};
