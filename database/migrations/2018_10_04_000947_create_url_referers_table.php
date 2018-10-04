<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUrlReferersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('url_referers', function (Blueprint $table) {
            $table->unsignedInteger('short_url_id');
            $table->string('referer')->nullable();
            $table->timestamps();

            $table->foreign('short_url_id')->references('id')->on('short_urls');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('url_referers');
    }
}
