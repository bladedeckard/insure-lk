<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->string('title_ru')->nullable()->after('guard_name');
            $table->text('description')->nullable()->after('title_ru');
        });
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('title_ru')->nullable()->after('guard_name');
            $table->string('group')->nullable()->after('title_ru');
        });
    }
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn(['title_ru','description']);
        });
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn(['title_ru','group']);
        });
    }
};
