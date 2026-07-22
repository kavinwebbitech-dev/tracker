<?php

namespace App\Http\Controllers;

use App\Models\EventDetail;
use Illuminate\Http\Request;

class EventDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function view()
    {
        $search_date = '';
        if(request('search_date') && request('search_date')!=''){
          $search_date = request('search_date');
        }
        $IncomeAmount = EventDetail::latest();

        if($search_date)
        {
            $IncomeAmount = $IncomeAmount->where('expensive_date', $search_date);
        }
        else
        {
            $IncomeAmount = $IncomeAmount;
        }

        $IncomeAmount = $IncomeAmount->paginate(100);

        return view('admin.EventDetail', compact('IncomeAmount', 'search_date'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        if ($request->event_image) {
            $image1 = $request->event_image;
            $banner_image1 = time().'event.'.$image1->extension();  
            $image1->move(public_path('event_image'), $banner_image1);
        }
        elseif ($request->old_image)
        {
            $banner_image1 = $request->old_image;
        }
        else
        {
            $banner_image1 = "";
        }

        if ($request->conference_id) {
            $pages              = EventDetail::find($request->conference_id);
        }
        else
        {
            $pages              = new EventDetail();
        }
        $pages->event_name      = $request->event_name;
        $pages->event_image     = $banner_image1;
        $pages->start_date      = $request->start_date;
        $pages->end_date        = $request->end_date;
        $pages->user_type       = $request->user_type;
        $pages->description     = $request->description;
        $pages->status          = $request->status;

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'Event Details Updated Successfully');
            }
            return redirect()->back()->with('success', 'Event Details Added Successfully');
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
        $pages                  = EventDetail::find($id);
        if ($pages->delete()) {
            return redirect()->back()->with('error', 'Event Detail Deleted Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }


}
