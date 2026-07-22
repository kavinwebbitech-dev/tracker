<?php

namespace App\Imports;

use App\Models\Client;
use App\Models\Service;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Contracts\Queue\ShouldQueue;

class ImportClients implements ToCollection
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
            if($value[1] == "Name" || $value[2] == "Email")
            {
                
            }
            else
            {
                // dd($value);
                if($value[0])
                {
                    $get_service = Service::where('name', $value[0])->first();

                    $pages          = new Client();
                    $pages->cat_id      = $get_service->id;
                    $pages->name        = $value[1];
                    $pages->email       = $value[2];
                    $pages->phone       = $value[3];
                    $pages->address     = $value[4];
                    $pages->gst_number  = $value[5];
                    $pages->company_name = $value[6];
                    $pages->status      = $value[8];
                    $pages->description = $value[7];
                    $pages->save();

                }
                
            }
        }
        
    }
}
