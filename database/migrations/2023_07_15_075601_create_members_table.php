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
            $table->string('Name')->nullable();
            $table->string('MemberId')->nullable();
            $table->string('DatOfBirth')->nullable();
            $table->string('Age')->nullable();
            $table->string('NationalId')->nullable();
            $table->string('RelationId')->nullable();
            $table->string('FamilyId')->nullable();
            $table->string('CategoryId')->nullable();
            $table->string('JobCategory')->nullable();
            $table->string('Address')->nullable();
            $table->string('City')->nullable();
            $table->string('State')->nullable();
            $table->string('Gender')->nullable();
            $table->string('Relegion')->nullable();
            $table->string('Profession')->nullable();
            $table->string('StatusId')->nullable();
            $table->string('ExcludedCategorieId')->nullable();
            $table->string('Email')->nullable();
            $table->string('Phone')->nullable();
            $table->string('Photo')->nullable();
            $table->string('CreatedAt')->nullable();
            $table->string('AppDecision')->nullable();
            $table->string('CountryId')->nullable();
            $table->string('RenewalStatus')->nullable();
            $table->string('PostalCode')->nullable();
            $table->string('DateOfSubscription')->nullable();
            $table->string('HomePhone')->nullable();
            $table->string('WorkPhone')->nullable();
            $table->string('LastPayedFees')->nullable();
            $table->string('MemberCardName')->nullable();
            $table->string('MemberGraduationDescription')->nullable();
            $table->string('Remarks')->nullable();
            $table->string('MemIsMainMember')->nullable();
            $table->string('MainMember_ID')->nullable();
            $table->string('BoardDecision_Date')->nullable();
            $table->string('BoardDecisionNumber')->nullable();
            $table->string('player')->nullable();
            $table->string('TeamId')->nullable();
            $table->json('notes')->nullable(); // New JSON column to store notes
            				
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('members');
    }
}
