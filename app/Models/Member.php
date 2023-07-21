<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'Name',
        'FamilyId',
        'Category',
        'Relation',
        'City',
        'State',
        'Address',
        'Profession',
        'Status',
        'ExcludedCategories',
        'Email',
        'Phone',
        'CreatedAt',
        'AppDecision',
        'Number',
        'NationalId',
        'Age',
        'Religion',
        'CountryId',
        'RenewalStatus',
        'PostalCode',
        'Facebook',
        'Twitter',
        'ExcludedCategoriesId',
        'DateOfSubscription',
        'HomePhone',
        'WorkPhone',
        'MemberCardName',
        'MemberGraduationDescription',
        'Remarks',
        'Note2',
        'Note3',
        'Note4',
    ];
    public function fees()
    {
        return $this->hasMany(MemberFee::class, 'member_id', 'id');
    }
}
