<?php

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Model;

class old_registration extends Model
{
    //
    protected $connection = 'old_db';
    protected $table = 'tb_registrations';
    // public $incrementing = false;
    protected $primaryKey = 'id';
    protected $keyType = 'integer';
    protected $fillable = [
        'Exhibition',
        'NameTitle',
        'Name',
        'Company',
        'JobTitle',
        'Address',
        'State',
        'Country',
        'Telephone',
        'MobilePhone',
        'Fax',
        'Email',
        'BusinessType',
        'JobFunction',
        'JobFunctionOther',
        'VisitPurpose',
        'VisitPurposeOther',
        'PurchasingRole',
        'PurchasingRoleOther',
        'EventFind',
        'EventFindOther',
        'IsReceivedInvitationNext',
        'IsReceivedInvitationNextAddressSame',
        'ReceivedInvitationNextAddress',
        'Barcode',
        'IsPrinted',
        'RegisterDate',
        'LastCheckinTime',
        'LastCheckinLocation',
        'SubExhibition',
        'SubExhibitionName',
        'FirstName',
        'LastName',
        'Companytype',
        'JobLevel',
        'Departement',
        'Website',
        'SubExhibitionName',
        'SubExhibitionName',
        'HowKnow',
        'LineOfBusiness',
        'City',
        'IsReciveNewsletter',
        'IsAgreePolicy',
        'JobLevelOther',
        'DepartementOther',
        'CompanytypeOther',
    ];
}
