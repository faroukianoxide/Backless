<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {

            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 64);
            $table->string('auth_token', 600)->nullable();
            $table->dateTime('date_issued')->nullable();
            $table->dateTime('date_changed')->nullable();
            $table->dateTime('expiry_date')->nullable();
            $table->dateTime('last_activated')->nullable();
            $table->dateTime('last_suspended')->nullable();
            $table->rememberToken()->nullable();
            $table->timestamps();


        });

        //create default admin details;
       /* DB::table('users')->insert([
            'name' => 'Demo Admin',
            'email' => 'admin@backless.test',
            'password' => bcrypt('password'),
            'status' => 'admin'
        ]); */
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
