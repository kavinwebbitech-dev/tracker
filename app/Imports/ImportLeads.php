<?php

namespace App\Imports;

use App\Models\Client;
use App\Models\LeadsFrom;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Auth;

class ImportLeads implements ToCollection
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    private $data;

    public function __construct(array $data = [])
    {
        $this->service_id = $data['service_id'];
    }

    public function collection(Collection $rows)
    {
        // dd($$rows);
        foreach ($rows as $key => $value) {
            if ($value[0] == "Name" || $value[1] == "Contact" || $value[2] == "Businnes Name") {
                continue;
            }

            $user_details = LeadsFrom::where('contact_no', $value[1])->first();
            $pages = $user_details ?: new LeadsFrom();

            $pages->added_by      = Auth::user()->id;
            $pages->name          = $value[0];
            $pages->contact_no    = $value[1];
            $pages->business_name = $value[2];

            // ✅ use the sheet's own Service column per row; fall back to the dropdown only if that cell is empty
            // $pages->service = $value[3] !== '' ? $value[3] : $this->service_id;
            $serviceName = trim($value[3]);
                    $serviceId = $serviceName !== ''
                        ? \App\Models\Service::where('name', $serviceName)->value('id')
                        : $this->service_id;
                    $pages->service = $serviceId;

            // ✅ Status is column E (index 4), not column D
            $pages->status = $value[4] ?? null;

            $pages->save();
        }
    }
}
