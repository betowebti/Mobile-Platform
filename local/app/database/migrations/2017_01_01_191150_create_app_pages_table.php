<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppPagesTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
  Schema::create('app_pages', function(Blueprint $table) {
    $table->bigIncrements('id');
    $table->bigInteger('app_id')->unsigned();
    $table->foreign('app_id')->references('id')->on('apps')->onDelete('cascade');
    $table->string('widget', 64)->nullable();
    $table->string('theme_variation', 64)->nullable();

    // These columns are needed for Baum's Nested Set implementation to work.
    // Column names may be changed, but they *must* all exist and be modified
    // in the model.
    // Take a look at the model scaffold comments for details.
    // We add indexes on parent_id, lft, rgt columns by default.
    $table->bigInteger('parent_id')->nullable()->index();
    $table->bigInteger('lft')->nullable()->index();
    $table->bigInteger('rgt')->nullable()->index();
    $table->integer('depth')->nullable();

    $table->string('name', 255)->nullable();
    $table->text('meta_title')->nullable();
    $table->text('meta_desc')->nullable();
    $table->string('meta_robots', 32)->nullable();

    $table->string('slug', 255)->nullable();
    $table->string('icon', 64)->nullable();
    $table->string('link', 255)->nullable();
    $table->boolean('hidden')->default(false);
    $table->boolean('hidden_parent')->default(false);
    $table->boolean('secured')->default(false);
    $table->boolean('secured_parent')->default(false);

    $table->mediumText('settings')->nullable();

    // Images
    $table->string('header_file_name')->nullable();
    $table->integer('header_file_size')->nullable();
    $table->string('header_content_type')->nullable();
    $table->timestamp('header_updated_at')->nullable();

    $table->string('background_smarthpones_file_name')->nullable();
    $table->integer('background_smarthpones_file_size')->nullable();
    $table->string('background_smarthpones_content_type')->nullable();
    $table->timestamp('background_smarthpones_updated_at')->nullable();

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
  public function down() {
    Schema::drop('app_pages');
  }

}
