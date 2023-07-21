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
            $table->string('RegNum')->nullable();
            $table->string('Name')->nullable();
            $table->string('FamilyId')->nullable();
            $table->string('Category')->nullable();
            $table->string('JobCategory')->nullable();
            $table->string('Relation')->nullable();
            $table->string('City')->nullable();
            $table->string('State')->nullable();
            $table->string('Gender')->nullable();
            $table->string('Relegion')->nullable();
            $table->string('Address')->nullable();
            $table->string('Profession')->nullable();
            $table->string('Status')->nullable();
            $table->string('ExcludedCategories')->nullable();
            $table->string('Email')->nullable();
            $table->string('Phone')->nullable();
            $table->string('Photo')->nullable();
            $table->string('Receiver')->nullable();
            $table->date('CreatedAt')->nullable();
            $table->string('AppDecision')->nullable();
            $table->string('Number')->nullable();
            $table->string('NationalId')->nullable();
            $table->string('Bod')->nullable();
            $table->integer('Age')->nullable();
            $table->integer('CountryId')->nullable();
            $table->string('RenewalStatus')->nullable();
            $table->string('PostalCode')->nullable();
            $table->string('Facebook')->nullable();
            $table->string('Twitter')->nullable();
            $table->string('DateOfSubscription')->nullable();
            $table->string('HomePhone')->nullable();
            $table->string('WorkPhone')->nullable();
            $table->string('LastPayedFees')->nullable();
            $table->string('MemberCardName')->nullable();
            $table->string('MemberGraduationDescription')->nullable();
            $table->string('Remarks')->nullable();
            $table->string('Note1')->nullable();
            $table->string('Note2')->nullable();
            $table->string('Note3')->nullable();
            $table->string('Note4')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('members');
    }
}
