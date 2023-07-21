<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

                Schema::create('sub_categories', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('category_id');
                    $table->string('name_en');
                    $table->string('name_ar');
                    $table->timestamps();

                    $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

        });
    }

    public function down()
    {
        Schema::dropIfExists('sub_categories');
        Schema::dropIfExists('categories');
    }
}
