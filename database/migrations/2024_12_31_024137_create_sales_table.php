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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // Nomor faktur unik
            $table->foreignId('customer_id')->nullable() // Foreign key untuk customer
                ->constrained('customers') // Relasi dengan tabel 'customers'
                ->onDelete('cascade'); // Hapus sales jika customer dihapus
            $table->foreignId('user_customer_id')->nullable() // Foreign key untuk user customer
                ->constrained('users') // Relasi dengan tabel 'users'
                ->onDelete('cascade'); // Hapus sales jika customer dihapus
            $table->foreignId('user_id') // Foreign key untuk user (jika ada)
                ->nullable() // User_id bisa null jika tidak ada
                ->constrained('users') // Relasi dengan tabel 'users'
                ->onDelete('set null'); // Set null jika user dihapus
            $table->bigInteger('total'); // Total harga tanpa pajak
            $table->bigInteger('tax')->nullable(); // Kolom untuk menyimpan pajak (PPN)
            $table->bigInteger('diskon')->nullable(); // Kolom untuk menyimpan diskon
            $table->enum('tax_status', ['ppn', 'non-ppn'])->default('non-ppn'); // Status pajak, apakah PPN atau non-PPN
            $table->date('due_date'); // Tanggal jatuh tempo
            $table->enum('status', ['pending', 'completed']) // Status penjualan
                ->default('pending'); // Default status 'pending'
            $table->timestamps(); // Timestamps untuk created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
