<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('name');
        });

        DB::table('users')->orderBy('id')->get(['id', 'email'])->each(function ($user) {
            $base = Str::before($user->email, '@');
            $username = Str::of($base)->replaceMatches('/[^A-Za-z0-9_-]/', '')->lower()->toString();

            if ($username === '') {
                $username = 'user';
            }

            $candidate = $username;
            $suffix = 1;

            while (DB::table('users')->where('username', $candidate)->where('id', '!=', $user->id)->exists()) {
                $candidate = $username.$suffix++;
            }

            DB::table('users')->where('id', $user->id)->update(['username' => $candidate]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['email']);
            $table->dropColumn(['email', 'email_verified_at']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->unique()->after('name');
            $table->timestamp('email_verified_at')->nullable()->after('email');
        });

        DB::table('users')->orderBy('id')->get(['id', 'username'])->each(function ($user) {
            DB::table('users')->where('id', $user->id)->update([
                'email' => $user->username.'@apotek.local',
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn('username');
        });
    }
};
