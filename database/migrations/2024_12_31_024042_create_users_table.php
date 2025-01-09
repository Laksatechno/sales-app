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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('customer'); // superadmin, marketing, logistik, customer
            $table->string('foto')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('address')->nullable();
            $table->string('tipe_pelanggan')->nullable(); // reguler, subdis
            $table->string('jenis_institusi')->nullable(); // pmi, non-pmi
            $table->unsignedBigInteger('marketing_id')->nullable();
            $table->foreign('marketing_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
