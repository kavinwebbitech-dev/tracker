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

    public function collection(collection $rows)
    {
        // dd($rows[1]);
        foreach ($rows as $key => $value) {
            // dd($value);
            if($value[0] == "Name" || $value[1] == "Contact" || $value[2] == "Businnes Name")
            {
                
            }
            else
            {
                // dd($value);
                $user_details      = LeadsFrom::where('contact_no', $value[1])->first();
                if ($user_details) {
                    $pages             = LeadsFrom::find($user_details->id);
                }
                else
                {
                    $pages             = new LeadsFrom();
                }
                $pages->added_by       = Auth::user()->id;
                $pages->name           = $value[0];
                $pages->business_name  = $value[2];
                $pages->contact_no     = $value[1];
                $pages->service        = $this->service_id;
                $pages->status         = $value[3];
                // dd($pages);
                $pages->save();
                
            }
        }
        
    }
}
