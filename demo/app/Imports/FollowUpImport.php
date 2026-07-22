<?php

namespace App\Imports;

use App\Models\Customers;
use App\Models\Service;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Contracts\Queue\ShouldQueue;

class FollowUpImport implements ToCollection
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    private $data; 

    public function __construct(array $data = [])
    {
        
    }

    public function collection(collection $rows)
    {
        // dd($rows[1]);
        foreach ($rows as $key => $value) {
            // dd($value);
            if($value[0] == "Name" || $value[1] == "Email")
            {
                
            }
            else
            {
                // dd($value);
                if($value[0])
                {
                   
                    $pages                      = new Customers();
                    $pages->customer_type       = 2;
                    $pages->fld_name            = $value[0];
                    $pages->fld_email           = $value[1];
                    $pages->fld_phone           = $value[2];
                    $pages->fld_address         = $value[3];
                    $pages->fld_company_name    = $value[4];
                    $pages->fld_customer_gstno  = $value[5];
                    $pages->fld_createdon       = date('Y-m-d');
                    $pages->fld_updatedon       = date('Y-m-d');
                    $pages->fld_status          = 1;
                    $pages->save();

                }
                
            }
        }
        
    }
}
