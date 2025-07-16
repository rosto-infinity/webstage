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
      
         $roles = config('roles.roles', ['user', 'admin', 'superadmin']);

        Schema::table('users', function (Blueprint $table) use ($roles) {
            $table->enum('role', $roles)
                ->after('email')
                ->default('user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
