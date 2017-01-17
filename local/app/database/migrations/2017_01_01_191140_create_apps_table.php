<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    // App types (Business, Music, Events, Restaurants, Blog, Education, Photography, Other)
    Schema::create('app_types', function($table)
    {
      $table->increments('id')->unsigned();
      $table->integer('sort')->unsigned();
      $table->string('name', 32);
      $table->text('icon');
      $table->tinyInteger('icon_width')->unsigned(45);
      $table->string('app_icon', 255)->nullable();
      $table->boolean('active')->default(true);
    });

    Schema::create('apps', function($table)
    {
      $table->bigIncrements('id');
      $table->tinyInteger('status')->default(1);
      
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->bigInteger('campaign_id')->unsigned();
      $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('restrict');
      $table->integer('app_type_id')->unsigned();
      $table->foreign('app_type_id')->references('id')->on('app_types');
      $table->string('theme', 64)->nullable();
      $table->string('layout', 64)->nullable();
      $table->string('icon', 64)->nullable();
      
      $table->string('name', 128);
      $table->string('header_text', 128)->nullable();
      
      $table->string('local_domain', 255)->nullable();
      $table->string('domain', 255)->nullable();
      $table->string('language', 5)->default('en');
      $table->string('timezone', 32)->default('UTC');
      $table->text('robots')->nullable();
      
      $table->string('color1', 6)->nullable();
      $table->string('color2', 6)->nullable();
      $table->string('color3', 6)->nullable();
      $table->string('color4', 6)->nullable();
      $table->string('color5', 6)->nullable();
      $table->string('color6', 6)->nullable();
      $table->string('color7', 6)->nullable();
      $table->string('color_overlay', 6)->nullable();
      $table->string('color_header_background', 6)->nullable();
      $table->string('color_header_text', 6)->nullable();
      
      // Images
      $table->string('header_file_name')->nullable();
      $table->integer('header_file_size')->nullable();
      $table->string('header_content_type')->nullable();
      $table->timestamp('header_updated_at')->nullable();
      
      $table->string('background_smarthpones_file_name')->nullable();
      $table->integer('background_smarthpones_file_size')->nullable();
      $table->string('background_smarthpones_content_type')->nullable();
      $table->timestamp('background_smarthpones_updated_at')->nullable();
      
      $table->dateTime('expires')->nullable();
      $table->boolean('active')->default(true);
      $table->text('settings')->nullable();
      $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
      $table->timestamp('updated_at')->nullable();
      $table->softDeletes();
      $table->integer('created_by')->nullable();
      $table->integer('updated_by')->nullable();
    });

  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('apps', function(Blueprint $table) {
      $table->dropForeign('apps_app_type_id_foreign');
      $table->dropForeign('apps_user_id_foreign');
      $table->dropForeign('apps_campaign_id_foreign');
    });
    Schema::drop('apps');

    Schema::drop('app_types');
  }

}