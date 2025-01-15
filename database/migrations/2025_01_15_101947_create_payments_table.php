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
        Schema::create('payments', function (Blueprint $table) {
            $table->id(); // Kolom id (primary key, auto-increment)
            $table->unsignedBigInteger('sales_id'); // Kolom sales_id (foreign key ke tabel sales)
            $table->string('photo'); // Kolom photo (path atau URL gambar bukti pembayaran)
            $table->string('pph')->nullable(); // Kolom pph (path atau URL file PPH, nullable)
            $table->string('ppn')->nullable(); // Kolom ppn (path atau URL file PPN, nullable)
            $table->timestamps(); // Kolom created_at dan updated_at

            // Foreign key constraint
            $table->foreign('sales_id')->references('id')->on('sales')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
