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
            $table->integer('member_id')->nullable();
            $table->string('fee_id')->nullable();
            $table->string('name')->nullable();
            $table->string('fee_year')->nullable();
            $table->string('fee_amount', 8, 2)->nullable();
            $table->string('fee_date')->nullable();
            $table->string('fee_recieptNumber')->nullable();
            $table->string('fee_status')->nullable();
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('fees');
    }
}
