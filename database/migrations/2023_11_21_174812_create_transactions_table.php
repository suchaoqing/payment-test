<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('transaction_id', 20)->index();
            $table->integer('account_id')->index();
            $table->enum('operation', ['DEBIT', 'CREDIT']);
            $table->decimal('amount', 8, 2);
            $table->decimal('tax_amount', 8, 2)->default(0)->default(0);
            $table->decimal('tax_percentage', 2, 2)->default(0)->default(0);
            $table->decimal('total_amount', 8, 2);
            $table->char('payment_method', 2)->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
