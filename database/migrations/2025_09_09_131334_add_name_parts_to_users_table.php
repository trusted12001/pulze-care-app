<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add new columns after the existing 'name'
            $table->string('first_name', 100)->nullable()->after('name');
            $table->string('last_name', 100)->nullable()->after('first_name');
            $table->string('other_names', 255)->nullable()->after('last_name');

            // Helpful index for searching by last name within a tenant
            $table->index(['tenant_id', 'last_name']);
        });

        // Backfill from 'name' → first_name / last_name / other_names
        DB::table('users')
            ->select('id', 'name')
            ->orderBy('id')
            ->chunkById(500, function ($users) {
                foreach ($users as $u) {
                    $full = trim((string) $u->name);
                    $first = null; $last = null; $other = null;

                    if ($full !== '') {
                        // Split on whitespace. Example handling:
                        // "Mary Jane Smith" → first=Mary, other=Jane, last=Smith
                        // "Ade Yusuf"       → first=Ade, last=Yusuf
                        // "Cher"            → first=Cher
                        $parts = preg_split('/\s+/', $full);
                        if (count($parts) === 1) {
                            $first = $parts[0];
                        } elseif (count($parts) === 2) {
                            $first = $parts[0];
                            $last  = $parts[1];
                        } else {
                            $first = array_shift($parts);
                            $last  = array_pop($parts);
                            $other = implode(' ', $parts);
                        }
                    }

                    DB::table('users')
                        ->where('id', $u->id)
                        ->update([
                            'first_name'  => $first,
                            'last_name'   => $last,
                            'other_names' => $other,
                        ]);
                }
            });

        // Optional: also refresh the 'name' column to the new joined form (keeps older views stable)
        DB::table('users')
            ->select('id', 'first_name', 'other_names', 'last_name')
            ->orderBy('id')
            ->chunkById(500, function ($users) {
                foreach ($users as $u) {
                    $joined = trim(implode(' ', array_filter([$u->first_name, $u->other_names, $u->last_name])));
                    DB::table('users')->where('id', $u->id)->update(['name' => $joined ?: null]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'last_name']);
            $table->dropColumn(['first_name', 'last_name', 'other_names']);
        });
    }
};
