<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_type')->nullable()->after('status');
            $table->string('payment_status')->default('pending')->after('payment_type'); // pending, paid, failed, expired
            $table->string('transaction_id')->nullable()->after('payment_status');
            $table->string('snap_token')->nullable()->after('transaction_id');
            $table->timestamp('paid_at')->nullable()->after('snap_token');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_type', 'payment_status', 'transaction_id', 'snap_token', 'paid_at']);
        });
    }
};