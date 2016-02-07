<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->uuid('uuid');
            $table->integer('public_id')->unsigned();
            $table->integer('client_id')->unsigned();
            $table->date('due_date');
            $table->date('try_on_date');
            $table->tinyInteger('num_tries')->default(0);
            $table->enum('status', ['pending', 'paid', 'overdue', 'error'])->default('pending');
            $table->enum('repeat', ['no', 'month', 'year'])->default('no');
            $table->string('charge_id')->nullable();
            $table->date('charge_date')->nullable();
            $table->float('charge_fee')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['uuid', 'public_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('invoices');
    }
}
