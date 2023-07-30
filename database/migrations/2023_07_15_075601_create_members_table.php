<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('member_id')->nullable();
            $table->string('family_id')->nullable();
            $table->string('name')->nullable();
            $table->string('national_id')->nullable();
            $table->string('user_id')->nullable();
            $table->string('gender')->nullable();
            $table->string('category_id')->nullable();
            $table->string('relation_id')->nullable();
            $table->string('status_id')->nullable();
            $table->string('phone')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('age')->nullable();
            $table->string('profession')->nullable();
            $table->string('relegion')->nullable();
            $table->string('country_id')->nullable();
            $table->string('nationalty_id')->nullable();
            $table->string('renewal_status')->nullable();
            $table->string('postal_code')->nullable();
            $table->json('notes')->nullable(); // New JSON column to store notes
            $table->string('last_payed_fiscal_year')->nullable();
            $table->string('date_of_the_board_of_director_Decisions')->nullable();
            $table->string('decision_number')->nullable();
            $table->string('memCard_MemberName')->nullable();
            $table->string('Remarks')->nullable();
            $table->string('mem_GraduationDesc')->nullable();
            $table->string('mem_WorkPhone')->nullable();
            $table->string('mem_HomePhone')->nullable();
            $table->string('player')->nullable();
            $table->string('team_id')->nullable();
            $table->string('Photo')->nullable();
            $table->string('excluded_categories_id')->nullable();
            $table->string('date_of_subscription')->nullable();
            				
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('members');
    }
}
