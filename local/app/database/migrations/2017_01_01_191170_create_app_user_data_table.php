<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppUserDataTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('app_user_data', function(Blueprint $table)
    {
      $table->bigIncrements('id');
      $table->bigInteger('sort')->nullable();
      $table->bigInteger('app_id')->unsigned();
      $table->foreign('app_id')->references('id')->on('apps')->onDelete('cascade');
      $table->bigInteger('app_page_id')->unsigned()->nullable();
      $table->foreign('app_page_id')->references('id')->on('app_pages')->onDelete('set null');
      $table->string('name', 250);
      $table->mediumText('value')->nullable();
      $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
      $table->timestamp('updated_at')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('app_user_data');
  }

}
