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
        // Schema::table('users', function (Blueprint $table) {
        //     $table->enum('role',['user','admin','superadmin'])
        //     ->after('email')
        //     ->default('user');
        // });
        $roles = config('roles.roles', ['ty','nf','er']);
    
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
