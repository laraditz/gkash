<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGkashPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gkash_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code', 100)->nullable();
            $table->string('ref_no', 100)->nullable();
            $table->string('currency_code', 5)->nullable();
            $table->decimal('amount', 11, 2)->nullable();
            $table->smallInteger('status')->nullable();
            $table->string('status_description')->nullable();
            $table->smallInteger('refund_status')->nullable();
            $table->decimal('refund_amount', 11, 2)->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->text('description')->nullable();
            $table->string('return_url')->nullable();
            $table->string('callback_url')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gkash_payments');
    }
}
