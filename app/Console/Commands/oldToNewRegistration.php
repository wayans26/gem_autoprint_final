<?php

namespace App\Console\Commands;

use App\Models\exhibitions;
use App\Models\Old\old_exhibition;
use App\Models\Old\old_registration;
use App\Models\Old\old_sub_exhibition;
use App\Models\registration_visitor;
use App\Models\sub_exhibitions;
use Illuminate\Console\Command;

class oldToNewRegistration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:registration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert Old Registration to New Registration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        // sync Exhibition
        $index = 1;
        $old_exhibition = old_exhibition::get();
        $this->info("Start Sync Exhibition");
        foreach ($old_exhibition as $key => $value) {
            $ex = exhibitions::updateOrCreate([
                'code'          => $value->idexhibitions,
            ], [
                'name'          => $value->name,
                'full_name'     => $value->keterangan,
                'location'      => $value->event_name,
                'date'          => $value->tanggal,
                'team'          => "GEM Indonesia Team",
                'opening_hours' => $value->opening_hours,
                'host'          => $value->web_own,
                'page'          => $value->type == "REG" ? "reguler" : ($value->type == "VIP" ? "vip" : "busworld"),
                'type'          => $value->type == "REG" ? "reguler" : ($value->type == "VIP" ? "vip" : "reguler"),
                'status'        => $value->status,
            ]);

            $sub_exhibition = old_sub_exhibition::where("idexhibitions", $value->idexhibitions)->get();

            foreach ($sub_exhibition as $key1 => $value1) {
                sub_exhibitions::updateOrCreate([
                    'exhibitions_id'    => $ex->id,
                    'code'              => $value1->idsubexhibitions,
                ], [
                    'path'              => $value1->path,
                    'name'              => $value1->nama,
                ]);
            }
        }
        // $temp_ex = exhibitions::where('code', 'Agriculture2025')->first();
        // sub_exhibitions::updateOrCreate([
        //     'exhibitions_id'    => $temp_ex->id,
        //     'code'              => "Inagreentech",
        // ], [
        //     'path'              => "SubExhibitions/Agriculture2025-INAGRITECH.png",
        //     'name'              => "INAGRITECH",
        // ]);

        $this->info("Finish Sync Exhibition");

        $this->info("Start Sync Registration");
        $registration = old_registration::get();

        foreach ($registration as $key => $value) {
            $sub = sub_exhibitions::where('code', $value->SubExhibition)->first();
            if (empty($sub)) {
                $this->error($value->SubExhibition);
                $subEx = empty($value->SubExhibition) ? $value->Exhibition : $value->SubExhibition;
                $ex = exhibitions::updateOrCreate([
                    'code'          => $subEx,
                ], [
                    'name'          => $subEx,
                    'full_name'     => $subEx,
                    'location'      => "JIExpo Kemayoran",
                    'date'          => "28 - 30 July 2025",
                    'team'          => "GEM Indonesia Team",
                    'opening_hours' => "<p>28 July 2026 : 10.00 am - 6.00 pm</p><p>29 July 2026 : 10.00 am - 6.00 pm</p><p>30 July 2026 : 10.00 am - 4.30 pm</p>",
                    'host'          => "Cargo2025",
                    'page'          => "reguler",
                    'type'          => "reguler",
                    'status'        => "0",
                ]);

                sub_exhibitions::updateOrCreate([
                    'exhibitions_id'    => $ex->id,
                    'code'              => $subEx,
                ], [
                    'path'              => "SubExhibitions/Agriculture2025-INAGRITECH.png",
                    'name'              => $subEx,
                ]);
                $sub = sub_exhibitions::where('code', $subEx)->first();
            }
            $exhibition = exhibitions::find($sub->exhibitions_id);
            $reg = registration_visitor::updateOrCreate([
                'sub_exhibitions_id'                        => $sub->id,
                'barcode'                                   => $value->Barcode,
            ], [
                'name_title'                                => $value->NameTitle,
                'name'                                      => $value->Name,
                'company'                                   => $value->Company,
                'job_title'                                 => $value->JobTitle,
                'address'                                   => $value->Address,
                'state'                                     => $value->State,
                'country'                                   => $value->Country,
                'telephone'                                 => $value->Telephone,
                'mobile_phone'                              => $value->MobilePhone,
                'fax'                                       => strlen($value->Fax) > 20 ? null : $value->Fax,
                'email'                                     => $value->Email,
                'business_type'                             => $value->BusinessType,
                'job_function'                              => $value->JobFunction,
                'job_function_other'                        => $value->JobFunctionOther,
                'visit_purpose'                             => $value->VisitPurpose,
                'visit_purpose_other'                       => $value->VisitPurposeOther,
                'purchasing_role'                           => $value->PurchasingRole,
                'purchasing_role_other'                     => $value->PurchasingRoleOther,
                'event_find'                                => $value->EventFind,
                'event_find_other'                          => $value->EventFindOther,
                'is_received_invitation_next'               => $value->IsReceivedInvitationNext,
                'is_received_invitation_next_address_same'  => $value->IsReceivedInvitationNextAddressSame,
                'received_invitation_next_address'          => $value->ReceivedInvitationNextAddress,
                'is_printed'                                => $value->IsPrinted,
                'register_date'                             => $value->RegisterDate,
                'last_checkin_time'                         => $value->LastCheckinTime,
                'last_checkin_location'                     => $value->LastCheckinLocation,
                'first_name'                                => $value->FirstName,
                'last_name'                                 => $value->LastName,
                'company_type'                              => $value->Companytype,
                'job_level'                                 => $value->JobLevel,
                'job_level_other'                           => $value->job_level_other,
                'departement'                               => $value->Departement,
                'website'                                   => $value->Website,
                'how_know'                                  => $value->HowKnow,
                'line_of_business'                          => $value->LineOfBusiness,
                'city'                                      => $value->City,
                'is_receive_news_letter'                    => $value->IsReciveNewsletter ?? "0",
                'is_agree_policy'                           => $value->IsAgreePolicy ?? "0",
                'job_level_other'                           => $value->JobLevelOther,
                'departement_other'                         => $value->DepartementOther,
                'company_type_other'                        => $value->CompanytypeOther,
            ]);
            $this->info($index . "." . $value->Name . " - " . $value->SubExhibition . " - Success");
            $index++;
        }
        $this->info("Finish Sync Registration");
    }
}
