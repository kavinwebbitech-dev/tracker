<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branches;
use App\Models\Customers;
use App\Models\GSuite;
use App\Models\EmailList;
use Illuminate\Http\Request;
use PDF;
use Dompdf\Dompdf;

class AdminGSuiteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function view()
    {

        $start_date = '';

        if(request('start_date') && request('start_date')!=''){
          $start_date = request('start_date');
        }
        if ($start_date) {

            $date = \Carbon\Carbon::now()->addDays($start_date);
            $to_date = $date->format('Y-m-d');
            $domainhosting = GSuite::where('fld_status', 1)->where("fld_gsuite_end_date", '<', $to_date)->paginate(100);



            // $to_date = date('Y-m-d');
            // $date = \Carbon\Carbon::now()->addDays($start_date);
            // $domainhosting = \App\Models\GSuite::where('fld_status', 1)->whereDate('fld_gsuite_end_date', '>=', $to_date)->whereDate('fld_gsuite_end_date', '<=', $date->format('Y-m-d'))->paginate(50);   
        }
        else
        {
            $domainhosting = GSuite::where('fld_status', 1)->paginate(100);
        }

        // $domainhosting = GSuite::where('fld_status', 1)->get();
        return view('sub_admin.gsuide.view', compact('domainhosting', 'start_date'));
    }

    public function create()
    {
        $branches = Branches::where('fld_status', 1)->get();
        $customers = Customers::where('fld_status', 1)->get();
        return view('sub_admin.gsuide.add', compact('branches', 'customers'));
    }

    public function edit($id)
    {
        $branches = Branches::where('fld_status', 1)->get();
        $customers = Customers::where('fld_status', 1)->get();

        $domainhosting = GSuite::where('id', $id)->first();
        $customer_details = Customers::where('id', $domainhosting->fld_cust_id)->first();
        return view('sub_admin.gsuide.edit', compact('branches', 'customers', 'domainhosting', 'customer_details'));
    }

    public function NotInterest($id)
    {
        $domainhosting                          = GSuite::find($id);
        $domainhosting->fld_status              = 0;

        if ($domainhosting->save()) 
        {
            return redirect()->route('sub_admin.gsuide.view')->with('success', 'GSuite Updated Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function Interest($id)
    {
        $domainhosting                          = GSuite::find($id);
        $domainhosting->fld_status              = 1;

        if ($domainhosting->save()) 
        {
            return redirect()->back()->with('success', 'GSuite Updated Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function delete($id)
    {
        if ($id) {
            $user  = GSuite::find($id);
            // dd($user);
            if ($user->delete()) {
                return redirect()->route('sub_admin.gsuide.view')->with('error', 'GSuite Deleted Successfully');
            }
            else{
                return redirect()->back()->with('error', 'Something Wrong');
            }
        }
        else{
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function store(Request $request)
    {
        // dd($request->all());

        
        $request->validate([
            'fld_branch_id' => 'required|max:255',
            'fld_cust_id' => 'required|max:255',
            'fld_domain_name' => 'required',
            'fld_tax_percentage' => 'required',
            'fld_amount' => 'required',
            'fld_total_amount' => 'required'
        ]);

        $domainhosting                          = new GSuite();
        $domainhosting->fld_branch_id           = $request->fld_branch_id;
        $domainhosting->fld_cust_id             = $request->fld_cust_id;
        $domainhosting->fld_domain_name         = $request->fld_domain_name;
        $domainhosting->fld_gsuite_start_date   = $request->fld_gsuite_start_date;
        $domainhosting->fld_gsuite_tenure       = $request->fld_gsuite_tenure;
        $domainhosting->fld_gsuite_end_date     = $request->fld_gsuite_end_date;
        $domainhosting->fld_tax_percentage      = $request->fld_tax_percentage;
        $domainhosting->fld_amount              = $request->fld_amount;
        $domainhosting->fld_total_amount        = $request->fld_total_amount;
        $domainhosting->fld_description         = $request->fld_description;
        // $domainhosting->fld_email_count         = count($request->addmore) ?? 0;
        $domainhosting->fld_email_count         = 0;
        $domainhosting->fld_createdon           = date('Y-m-d');
        $domainhosting->fld_updatedon           = date('Y-m-d');
        $domainhosting->fld_status              = 1;

        if ($domainhosting->save()) 
        {
            if($request->addmore)     
            {
                foreach($request->addmore as $key => $admm)
                {
                    $emailList                  = New EmailList;
                    $emailList->fld_gsuite_id   = $domainhosting->id;
                    $emailList->fld_email_input = 0;
                    $emailList->fld_email       = $admm['images'];
                    $emailList->fld_email_date  = $admm['price'];
                    $emailList->fld_email_desc  = $admm['stock'];
                    $emailList->fld_status      = 1;
                    $emailList->save();
                }
            }
            return redirect()->route('sub_admin.gsuide.view')->with('success', 'GSuite Created Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $request->validate([
            'fld_branch_id' => 'required|max:255',
            'fld_cust_id' => 'required|max:255',
            'fld_domain_name' => 'required',
            'fld_tax_percentage' => 'required',
            'fld_amount' => 'required',
            'fld_total_amount' => 'required'
        ]);

        $domainhosting                          = GSuite::find($id);
        $domainhosting->fld_branch_id           = $request->fld_branch_id;
        $domainhosting->fld_cust_id             = $request->fld_cust_id;
        $domainhosting->fld_domain_name         = $request->fld_domain_name;
        $domainhosting->fld_gsuite_start_date   = $request->fld_gsuite_start_date;
        $domainhosting->fld_gsuite_tenure       = $request->fld_gsuite_tenure;
        $domainhosting->fld_gsuite_end_date     = $request->fld_gsuite_end_date;
        $domainhosting->fld_tax_percentage      = $request->fld_tax_percentage;
        $domainhosting->fld_amount              = $request->fld_amount;
        $domainhosting->fld_total_amount        = $request->fld_total_amount;
        $domainhosting->fld_description         = $request->fld_description;
        // $domainhosting->fld_email_count         = count($request->addmore);
        $domainhosting->fld_createdon           = date('Y-m-d');
        $domainhosting->fld_updatedon           = date('Y-m-d');
        $domainhosting->fld_status              = 1;
        
        if ($domainhosting->save()) 
        {
            EmailList::Where('fld_gsuite_id',$domainhosting->id)->forceDelete();
            
            if($request->addmore)     
            {
                foreach($request->addmore as $key => $admm)
                {
                    $emailList                  = New EmailList;
                    $emailList->fld_gsuite_id   = $domainhosting->id;
                    $emailList->fld_email_input = 0;
                    $emailList->fld_email       = $admm['images'];
                    $emailList->fld_email_date  = $admm['price'];
                    $emailList->fld_email_desc  = $admm['stock'];
                    $emailList->fld_status      = 1;
                    $emailList->save();
                }
            }
            return redirect()->route('sub_admin.gsuide.view')->with('success', 'GSuite Updated Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }

    }

    public function UserDetails(Request $request)
    {
        $UserDetails = Customers::where('id', $request->id)->first();
        $view =  view('sub_admin.amc.customer', compact('UserDetails'))->render();
        return response()->json(['html'=>$view]);
    }

    public function notview()
    {
        $domainhosting = GSuite::where('fld_status', 0)->paginate(100);
        return view('sub_admin.gsuide.not-view', compact('domainhosting'));
    }

    public function invoice($id)
    {
        $domainhosting = GSuite::where('id', $id)->first();

        // $product_description = $domainhosting->client_name ?? '';
        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        $data = [
                'domainhosting' => $domainhosting,
            ];
        
        // Render the PDF view into a variable
        $dompdf = new Dompdf();
        $html = view('sub_admin.gsuide.gsuite_invoice', $data)->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Get the PDF content
        // $pdfContent = $dompdf->output();
        // $pdf = PDF::loadView('admin.domainhosting.gsuite_invoice', compact('domainhosting'));
        // $pdfname = 'tetss.pdf';

        return view('sub_admin.gsuide.gsuite_invoice', compact('domainhosting'));

    }

}
