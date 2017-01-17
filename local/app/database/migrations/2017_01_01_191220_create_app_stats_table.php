<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppStatsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('app_stats', function($table)
    {
      $table->bigIncrements('id');
      $table->bigInteger('app_id')->unsigned();
      $table->foreign('app_id')->references('id')->on('apps')->onDelete('cascade');
      $table->bigInteger('page_id')->unsigned()->nullable();
      $table->foreign('page_id')->references('id')->on('app_pages')->onDelete('cascade');
      $table->string('page_widget', 24)->nullable();
      $table->string('page_name', 64)->nullable();
      $table->string('ip', 40)->nullable();
      $table->string('os', 32)->nullable();
      $table->string('client', 32)->nullable();
      $table->string('device', 32)->nullable();
      $table->string('brand', 32)->nullable();
      $table->string('model', 32)->nullable();
      $table->decimal('latitude', 10, 7)->nullable();
      $table->decimal('longitude', 11, 8)->nullable();
      $table->string('city', 32)->nullable();
      $table->string('region', 32)->nullable();
      $table->string('country', 32)->nullable();
      $table->string('countryCode', 5)->nullable();
      $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
      $table->text('meta')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('app_stats');
  }

}