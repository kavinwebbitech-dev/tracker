<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Contracts\Queue\ShouldQueue;

class ImportUsers implements ToCollection
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    private $data; 

    public function __construct(array $data = [])
    {
        // dd($data);
        $this->sub_admin_id = $data['sub_admin_id']; 
        $this->admin_id     = $data['admin_id'];
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
                if($value[0])
                {
                    $password           = Hash::make($value[2]);
                    $user               = new User();
                    $user->name         = $value[0];
                    $user->admin_id     = $this->admin_id;
                    $user->email        = $value[1];
                    $user->user_type    = "staff";
                    $user->status       = "active";
                    $user->password     = $password;
                    $user->sub_admin_id = $this->sub_admin_id;
                    $user->save();
                }
                
            }
        }
        
    }
}
