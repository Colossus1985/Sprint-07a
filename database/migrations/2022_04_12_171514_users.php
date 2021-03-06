<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table -> id('id');
            $table -> string('firstName');
            $table -> string('lastName');
            $table -> string('pseudo') -> unique();
            $table -> string('email') -> unique();
            $table -> timestamp('email_verified-at') -> nullable();
            $table -> string('password');
            $table -> boolean('admin')->default(0);
            $table -> integer('likes')->default(0);
            $table -> integer('comments')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
