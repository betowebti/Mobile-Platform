<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicUsersTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    // Creates the public users table
    Schema::create('public_users', function(Blueprint $table)
    {
      $table->bigIncrements('id');
      $table->integer('user_id')->unsigned()->nullable();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->bigInteger('app_id')->unsigned()->nullable();
      $table->foreign('app_id')->references('id')->on('apps')->onDelete('set null');
      $table->string('email', 32);
      $table->string('password', 64)->nullable();
      $table->string('confirmation_code', 32)->nullable();
      $table->string('remember_token', 64)->nullable();
      $table->boolean('confirmed')->default(false);
      $table->tinyInteger('gender')->unsigned()->nullable();
      $table->string('company', 32)->nullable();
      $table->string('company_number', 32)->nullable();
      $table->string('first_name', 32)->nullable();
      $table->string('last_name', 32)->nullable();
      $table->string('street1', 32)->nullable();
      $table->string('street2', 32)->nullable();
      $table->string('zip', 32)->nullable();
      $table->string('city', 32)->nullable();
      $table->string('state', 32)->nullable();
      $table->string('country', 32)->nullable();
      $table->string('language', 5)->default('en');
      $table->string('timezone', 32)->default('UTC');
      $table->mediumText('settings')->nullable();
      $table->integer('logins')->default(0)->unsigned();
      $table->dateTime('last_login')->nullable();
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
    Schema::drop('public_users');
  }

}
