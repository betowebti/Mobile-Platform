<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppWidgetDataTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('app_widget_data', function(Blueprint $table)
    {
      $table->bigIncrements('id');
      $table->bigInteger('sort')->nullable();
      $table->bigInteger('app_page_id')->unsigned();
      $table->foreign('app_page_id')->references('id')->on('app_pages')->onDelete('cascade');
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
    Schema::drop('app_widget_data');
  }

}
