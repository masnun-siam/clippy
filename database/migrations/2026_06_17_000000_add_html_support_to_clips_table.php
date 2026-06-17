<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clips', function (Blueprint $table) {
            $table->string('type')->default('url')->after('slug');
            $table->longText('html')->nullable()->after('url');
        });

        // Make url nullable without doctrine/dbal
        DB::statement('ALTER TABLE clips MODIFY url VARCHAR(255) NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE clips MODIFY url VARCHAR(255) NOT NULL');

        Schema::table('clips', function (Blueprint $table) {
            $table->dropColumn(['type', 'html']);
        });
    }
};
