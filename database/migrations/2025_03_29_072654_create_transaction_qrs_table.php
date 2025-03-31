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
        Schema::create('transaction_qrs', function (Blueprint $table) {
            $table->id();
            $table->string('reference_id');
            $table->text('qr_string');
            $table->string('qr_id');
            $table->float('amount');
            $table->string('currency');
            $table->string('channel_code');
            $table->timestamp('expire_at');
            $table->string('business_id');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_qrs');
    }
};
