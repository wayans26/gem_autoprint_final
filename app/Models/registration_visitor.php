<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class registration_visitor extends Model
{
    //
    protected $connection = 'mysql';
    protected $table = 'registration_visitors';
    public $incrementing = true;
    protected $primaryKey = 'id';
    protected $keyType = 'integer';
    protected $fillable = [
        'sub_exhibitions_id',
        'barcode',
        'name_title',
        'name',
        'company',
        'job_title',
        'address',
        'state',
        'country',
        'telephone',
        'mobile_phone',
        'fax',
        'email',
        'business_type',
        'job_function',
        'job_function_other',
        'visit_purpose',
        'visit_purpose_other',
        'purchasing_role',
        'purchasing_role_other',
        'event_find',
        'event_find_other',
        'is_received_invitation_next',
        'is_received_invitation_next_address_same',
        'received_invitation_next_address',
        'is_printed',
        'register_date',
        'last_checkin_time',
        'last_checkin_location',
        'first_name',
        'last_name',
        'company_type',
        'company_type_other',
        'line_of_business',
        'city',
        'is_receive_news_letter',
        'is_agree_policy',
        'job_level',
        'job_level_other',
        'departement',
        'departement_other',
        'website',
        'how_know',
    ];
}
