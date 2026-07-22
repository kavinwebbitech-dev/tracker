<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\LeadsFrom;
use App\Models\LeadStatus;
use App\Imports\ImportCampagin;
use Auth;
use Excel;
use DB;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function view()
    {
        $campaign_details = Campaign::latest()->paginate(100);
        $lead_status = LeadStatus::get();
        return view('admin.Campaign.index', compact('campaign_details', 'lead_status'));
    }

    public function add()
    {
        $leads_details = \App\Models\LeadsFrom::latest()->get();
        return view('admin.Campaign.add', compact('leads_details'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $this->validate($request, [
          'upload_csv'  => 'required|mimes:xls,xlsx'
         ]);
        $path = $request->file('upload_csv');

        $banner_image1 = "";
        if ($request->campaign_image) {
            $image1 = $request->campaign_image;
            $banner_image1 = time().'event.'.$image1->extension();  
            $image1->move(public_path('event_image'), $banner_image1);
        }
        $image_url                  = url('/public/event_image').'/'.$banner_image1;
        // $image_url = "https://www.usedbookr.com/demo//public/upload/admin_images/banner/1740834617.png";

        $data['image_url']          = $image_url;
        $data['template_name']      = $request->template_name;
        $data['campaign_content']   = $request->description;
        $data['campaign_name']      = $request->campaign_name;

        $check_data = Excel::import(new ImportCampagin($data), $path);

        if ($check_data) {
            return redirect()->route('admin.campaign.details.view')->with('success', 'Campaign Created Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
        
    }

    public function edit(Request $request)
    {

        $pages                      = EventDetail::find($request->id);
        $image_url                  = url('/public/event_image').'/'.$pages->event_image;
        $data['image_url']          = $image_url;
        $data['old_image']          = $pages->event_image;
        $data['event_name']         = $pages->event_name;
        $data['start_date']         = $pages->start_date;
        $data['end_date']           = $pages->end_date;
        $data['user_type']          = $pages->user_type;
        $data['description']        = $pages->description;
        $data['status']             = $pages->status;
        $data['conference_id']      = $request->id;

        return $data;
    }

    public function delete($id)
    {

        Campaign::findOrFail($id)->delete();
        return redirect()->back()->with('error', 'Campaign Deleted Successfully');

    }

    public function Subview()
    {
        $campaign_details = Campaign::latest()->paginate(100);
        $lead_status = LeadStatus::get();
        return view('sub_admin.Campaign.index', compact('campaign_details', 'lead_status'));
    }

    public function Subadd()
    {
        $leads_details = \App\Models\LeadsFrom::latest()->get();
        return view('sub_admin.Campaign.add', compact('leads_details'));
    }

    public function Substore(Request $request)
    {
        // dd($request->all());

        $this->validate($request, [
          'upload_csv'  => 'required|mimes:xls,xlsx'
         ]);
        $path = $request->file('upload_csv');

        $banner_image1 = "";
        if ($request->campaign_image) {
            $image1 = $request->campaign_image;
            $banner_image1 = time().'event.'.$image1->extension();  
            $image1->move(public_path('event_image'), $banner_image1);
        }
        $image_url                  = url('/public/event_image').'/'.$banner_image1;
        // $image_url = "https://www.usedbookr.com/demo//public/upload/admin_images/banner/1740834617.png";

        $data['image_url']          = $image_url;
        $data['template_name']      = $request->template_name;
        $data['campaign_content']   = $request->description;
        $data['campaign_name']      = $request->campaign_name;

        $check_data = Excel::import(new ImportCampagin($data), $path);

        if ($check_data) {
            return redirect()->route('sub_admin.campaign.details.view')->with('success', 'Campaign Created Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
        
    }

    public function Subdelete($id)
    {

        Campaign::findOrFail($id)->delete();
        return redirect()->back()->with('error', 'Campaign Deleted Successfully');

    }

}
