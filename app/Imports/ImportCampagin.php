<?php

namespace App\Imports;

use App\Models\Client;
use App\Models\Campaign;
use App\Models\LeadsFrom;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Auth;

class ImportCampagin implements ToCollection
{
    /**
    * @param Collection $collection
    */
    private $data; 

    public function __construct(array $data = [])
    {
        $this->image_url        = $data['image_url'];
        $this->template_name    = $data['template_name'];
        $this->campaign_content = $data['campaign_content'];
        $this->campaign_name    = $data['campaign_name'];
    }

    public function collection(collection $rows)
    {
        
        foreach ($rows as $key => $value) {
           
            if($value[0] == "Name" || $value[1] == "Phone")
            {
                
            }
            else
            {
                if ($value[0]) {
                    $user_name          = $value[0];
                }
                else
                {
                    $user_name          = "Customer";
                }
                
                $phone_number       = $value[1];
                $image_url          = $this->image_url;
                $template_name      = $this->template_name;
                $campaign_content   = $this->campaign_content;

                $whats_app_camp = CampaignWhats($user_name, $phone_number, $image_url, $template_name, $campaign_content);
                
            }
        }

        $pages                      = new Campaign();
        $pages->template_name       = $this->template_name;
        $pages->campaign_image      = $this->image_url;
        $pages->campaign_content    = $this->campaign_content;
        $pages->campaign_name       = $this->campaign_name;
        $pages->campaign_user       = count($rows) - 1;
        $pages->save(); 

    }
}
