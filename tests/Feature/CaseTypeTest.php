<?php

namespace Tests\Feature;

use App\Models\CaseSubType;
use App\Models\CaseType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CaseTypeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_case_type_has_case_sub_types()
    {
        $caseType = CaseType::factory()->create();
        $caseSubTypes = CaseSubType::factory()->count(2)->create(['case_type_id' => $caseType->id]);

        $this->assertInstanceOf(Collection::class, $caseType->case_sub_types);
        $this->assertEquals($caseSubTypes->pluck('id')->toArray(), $caseType->case_sub_types->pluck('id')->toArray());
    }

}
