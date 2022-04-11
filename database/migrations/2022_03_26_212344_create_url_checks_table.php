<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUrlChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('url_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('url_id')
                ->nullable(false)
                ->references('id')
                ->on('urls')
                ->onDelete('cascade');
            $table->smallInteger('status_code')->nullable(true);
            $table->string('title')->nullable(true);
            $table->string('description')->nullable(true);
            $table->string('h1')->nullable(true);
            $table->dateTime('created_at')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('url_checks');
    }
}
