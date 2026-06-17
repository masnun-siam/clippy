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

        // Make url nullable — driver-aware
        $driver = DB::getDriverName();
        if ($driver === 'sqlite') {
            // SQLite doesn't support MODIFY; url is already nullable in most SQLite schemas
            // but if it isn't, we recreate the table
            $hasNotNull = DB::selectOne("PRAGMA table_info(clips)") ?? [];
            $columns = DB::select("PRAGMA table_info(clips)");
            foreach ($columns as $col) {
                if ($col->name === 'url' && $col->notnull) {
                    // Rebuild table without NOT NULL on url
                    DB::statement('CREATE TABLE clips_backup AS SELECT * FROM clips');
                    DB::statement('DROP TABLE clips');
                    // Recreate with the full schema — url nullable
                    Schema::create('clips', function (Blueprint $table) {
                        $table->id();
                        $table->timestamps();
                        $table->string('slug')->unique();
                        $table->string('type')->default('url');
                        $table->string('url')->nullable();
                        $table->longText('html')->nullable();
                        $table->string('password')->nullable();
                        $table->timestamp('expires_at')->nullable();
                    });
                    DB::statement('INSERT INTO clips SELECT * FROM clips_backup');
                    DB::statement('DROP TABLE clips_backup');
                }
            }
        } else {
            DB::statement('ALTER TABLE clips MODIFY url VARCHAR(255) NULL');
        }
    }

    public function down(): void
    {
        // Make url NOT NULL — driver-aware
        $driver = DB::getDriverName();
        if ($driver !== 'sqlite') {
            DB::statement('ALTER TABLE clips MODIFY url VARCHAR(255) NOT NULL');
        }

        Schema::table('clips', function (Blueprint $table) {
            $table->dropColumn(['type', 'html']);
        });
    }
};
