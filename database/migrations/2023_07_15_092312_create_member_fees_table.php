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
            $table->integer('Mem_ID')->nullable();
            $table->string('Mem_Name')->nullable();
            $table->string('Mem_Code')->nullable();
            $table->string('Mem_Address')->nullable();
            $table->string('Mem_HomePhone')->nullable();
            $table->string('Mem_Mobile')->nullable();
            $table->string('Mem_WorkPhone')->nullable();
            $table->string('Fee_ID')->nullable();
            $table->string('Fee_Year')->nullable();
            $table->string('Fee_Amount', 8, 2)->nullable();
            $table->string('Fee_Date')->nullable();
            $table->string('Fee_RecieptNumber')->nullable();
            $table->string('Fee_Status')->nullable();
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('fees');
    }
}
