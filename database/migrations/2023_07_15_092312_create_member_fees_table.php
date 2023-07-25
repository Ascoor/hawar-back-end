<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberFeesTable extends Migration
{
    public function up()
    {
        Schema::create('member_fees', function (Blueprint $table) {

            $table->id();
            $table->integer('Member_ID')->nullable();
            $table->integer('FeeId')->nullable();
            $table->string('Name')->nullable();
            $table->string('RegNum')->nullable();
            $table->string('FeeYear')->nullable();
            $table->string('FeeAmount', 8, 2)->nullable();
            $table->string('FeeDate')->nullable();
            $table->string('FeeRecieptNumber')->nullable();
            $table->string('FeeStatus')->nullable();
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('fees');
    }
}
