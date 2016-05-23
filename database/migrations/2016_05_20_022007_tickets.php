<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Tickets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sender');
			$table->string('topic');
            $table->string('subject');
			$table->string('summary');			          
			$table->string('priority');
			$table->string('status');
			$table->string('department');
			$table->string('assigned_support');
			$table->string('closed_by');
			$table->string('closing_report');
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
        Schema::drop('tickets');
    }
}
