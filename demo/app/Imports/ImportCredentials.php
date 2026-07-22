<?php

namespace App\Imports;

use App\Models\Credential;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Auth;

class ImportCredentials implements ToCollection
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
            if($value[0] == "Name" || $value[1] == "Contact" || $value[2] == "Businnes Name")
            {
                
            }
            else
            {
                // dd($value);
                if($value[0])
                {
                    $pages                 = new Credential();
                    $pages->added_by       = Auth::user()->id;
                    $pages->name           = $value[0];
                    $pages->username       = $value[1];
                    $pages->password       = $value[2];
                    $pages->description    = $value[3];
                    $pages->save();

                }
                
            }
        }
        
    }
}
