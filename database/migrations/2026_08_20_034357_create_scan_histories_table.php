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
        Schema::create('scan_histories', function (Blueprint $table) {
            $table->id();
            $table->string('barcode');
            $table->string('format')->nullable();
            $table->enum('mode', ['member', 'redeem', 'event-ticket'])->default('redeem');
            $table->enum('status', ['success', 'failed']);
            $table->string('reason')->nullable();
            $table->foreignId('passcode_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();

            $table->index(['mode', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scan_histories');
    }
};
