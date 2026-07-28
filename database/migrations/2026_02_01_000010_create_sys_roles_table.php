<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sys_roles', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 30)->unique();
            $table->string('nama', 100);
            $table->unsignedTinyInteger('lini')->nullable(); // 1, 2, atau 3 (null = lintas-lini)
            $table->timestamps();
        });

        Schema::create('sys_user_roles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('sys_users')->onDelete('cascade');
            $table->foreignId('role_id')->constrained('sys_roles')->onDelete('cascade');
            $table->foreignId('upt_id')->nullable()->constrained('sys_upts'); // null = lintas-UPT (mis. Inspektorat Utama)
            $table->timestamps();
            $table->unique(['user_id', 'role_id', 'upt_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sys_user_roles');
        Schema::dropIfExists('sys_roles');
    }
};
