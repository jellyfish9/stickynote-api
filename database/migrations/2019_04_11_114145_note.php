<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Note extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('note', function (Blueprint $table) {
            //$table->primary('id');
            $table->unsignedMediumInteger('id', true);
            $table->string('note', 300);
            $table->string('mark', 2000);
			$table->string('tag', 60);
            $table->unsignedInteger('created');
		}
		);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
		Schema::drop('note');
    }
}
