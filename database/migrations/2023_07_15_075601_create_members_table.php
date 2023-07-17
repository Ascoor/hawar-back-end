<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();

            $table->string('Mem_Name')->nullable();
            $table->string('Mem_Code')->nullable();
            $table->string('Mem_BOD')->nullable();
            $table->string('Mem_NID')->nullable();
            $table->string('Graduation')->nullable();
            $table->string('Mem_ParentMember')->nullable();
            $table->string('Gender')->nullable();
            $table->string('Mem_Job')->nullable();
            $table->string('JobCategory')->nullable();
            $table->string('MembershipType')->nullable();
            $table->string('Relegion')->nullable();
            $table->string('Mem_Address')->nullable();
            $table->string('Mem_JoinDate')->nullable();
            $table->string('Class')->nullable();
            $table->string('Mem_HomePhone')->nullable();
            $table->string('Mem_Mobile')->nullable();
            $table->string('Mem_Receiver')->nullable();
            $table->string('Mem_WorkPhone')->nullable();
            $table->string('Mem_Photo')->nullable();
            $table->string('Mem_Notes')->nullable();
            $table->string('Mem_LastPayedFees')->nullable();
            $table->string('Status')->nullable();
            $table->string('MemCard_MemberName')->nullable();
            $table->string('MemCard_MemberJobTitle')->nullable();
            $table->string('Mem_GraduationDesc')->nullable();
            $table->string('Mem_Notes_2')->nullable();
            $table->string('Mem_Notes_3')->nullable();
            $table->string('Mem_Notes_4')->nullable();
            $table->string('Mem_Relation')->nullable();
            $table->string('parentName')->nullable();
            $table->string('Mem_IsMainMember')->nullable();
            $table->string('Mem_BoardDecision_Date')->nullable();
            $table->string('Mem_BoardDecision_Number')->nullable();

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
}
