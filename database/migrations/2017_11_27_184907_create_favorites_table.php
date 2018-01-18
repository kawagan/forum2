<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFavoritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favorites', function (Blueprint $table) {
            // we can make favorite for reply and thread and user for that
            // we make polymorphich relation
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('favorited_id');
            $table->string('favorited_type',50);
            $table->timestamps();
            
            // we protect it as unique in tow level here and in code in Reply class
            $table->unique(['user_id','favorited_id','favorited_type']);
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('favorites');
    }
}
