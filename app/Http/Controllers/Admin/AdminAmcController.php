<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branches;
use App\Models\Customers;
use App\Models\AdminAmc;
use App\Models\PaymentList;
use Illuminate\Http\Request;
use PDF;
use Dompdf\Dompdf;

class AdminAmcController extends Controller
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
            $domainhosting = AdminAmc::where('fld_status', 1)->where('fld_amc_end_date', '<', $to_date)->paginate(100);
            
        }
        else
        {
            $domainhosting = AdminAmc::where('fld_status', 1)->paginate(100);
        }

        return view('sub_admin.amc.view', compact('domainhosting', 'start_date'));
    }

    public function create()
    {
        $branches = Branches::where('fld_status', 1)->get();
        $customers = Customers::where('fld_status', 1)->get();
        return view('sub_admin.amc.add', compact('branches', 'customers'));
    }

    public function edit($id)
    {
        $branches = Branches::where('fld_status', 1)->get();
        $customers = Customers::where('fld_status', 1)->get();

        $domainhosting = AdminAmc::where('id', $id)->first();
        $customer_details = Customers::where('id', $domainhosting->fld_cust_id)->first();
        return view('sub_admin.amc.edit', compact('branches', 'customers', 'domainhosting', 'customer_details'));
    }

    public function NotInterest($id)
    {
        $domainhosting                          = AdminAmc::find($id);
        $domainhosting->fld_status              = 0;

        if ($domainhosting->save()) 
        {
            return redirect()->route('sub_admin.amc.view')->with('success', 'AMC Updated Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function Interest($id)
    {
        $domainhosting                          = AdminAmc::find($id);
        $domainhosting->fld_status              = 1;

        if ($domainhosting->save()) 
        {
            return redirect()->back()->with('success', 'AMC Updated Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function delete($id)
    {
        if ($id) {
            $user  = AdminAmc::find($id);
            // dd($user);
            if ($user->delete()) {
                return redirect()->route('sub_admin.amc.view')->with('error', 'AMC Deleted Successfully');
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
            'fld_amc_dh_details' => 'required',
            'fld_amc_tax_rate' => 'required',
            'fld_amc_amount' => 'required',
            'fld_amc_total_amount' => 'required'
        ]);

        $domainhosting                          = new AdminAmc();
        $domainhosting->fld_branch_id           = $request->fld_branch_id;
        $domainhosting->fld_cust_id             = $request->fld_cust_id;
        $domainhosting->fld_amc_dh_details      = $request->fld_amc_dh_details;
        $domainhosting->fld_amc_reg_date        = $request->fld_amc_reg_date;
        $domainhosting->fld_amc_dh_tenure       = $request->fld_amc_dh_tenure;
        $domainhosting->fld_amc_end_date        = $request->fld_amc_end_date;
        $domainhosting->fld_amc_tax_rate        = $request->fld_amc_tax_rate;
        $domainhosting->fld_amc_amount          = $request->fld_amc_amount;
        $domainhosting->fld_amc_total_amount    = $request->fld_amc_total_amount;
        $domainhosting->fld_amc_description     = $request->fld_amc_description;
        $domainhosting->fld_createdon           = date('Y-m-d');
        $domainhosting->fld_updatedon           = date('Y-m-d');
        $domainhosting->fld_status              = 1;

        if ($domainhosting->save()) 
        {
            if($request->addmore)     
            {
                foreach($request->addmore as $key => $admm)
                {
                    if($admm['images']){
                        $emailList                        = New PaymentList;
                        $emailList->fld_amc_id            = $domainhosting->id;
                        $emailList->fld_amc_payment_input = 0;
                        $emailList->fld_amc_payment_amount = $admm['images'];
                        $emailList->fld_amc_payment_date  = $admm['price'];
                        $emailList->fld_amc_payment_desc  = $admm['stock'];
                        $emailList->fld_status            = 1;
                        $emailList->save();
                    }
                }
            }
            return redirect()->route('sub_admin.amc.view')->with('success', 'AMC Created Successfully');
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
            'fld_amc_dh_details' => 'required',
            'fld_amc_tax_rate' => 'required',
            'fld_amc_amount' => 'required',
            'fld_amc_total_amount' => 'required'
        ]);

        $domainhosting                          = AdminAmc::find($id);
        $domainhosting->fld_branch_id           = $request->fld_branch_id;
        $domainhosting->fld_cust_id             = $request->fld_cust_id;
        $domainhosting->fld_amc_dh_details      = $request->fld_amc_dh_details;
        $domainhosting->fld_amc_reg_date        = $request->fld_amc_reg_date;
        $domainhosting->fld_amc_dh_tenure       = $request->fld_amc_dh_tenure;
        $domainhosting->fld_amc_end_date        = $request->fld_amc_end_date;
        $domainhosting->fld_amc_tax_rate        = $request->fld_amc_tax_rate;
        $domainhosting->fld_amc_amount          = $request->fld_amc_amount;
        $domainhosting->fld_amc_total_amount    = $request->fld_amc_total_amount;
        $domainhosting->fld_amc_description     = $request->fld_amc_description;
        $domainhosting->fld_createdon           = date('Y-m-d');
        $domainhosting->fld_updatedon           = date('Y-m-d');
        $domainhosting->fld_status              = 1;
        
        if ($domainhosting->save()) 
        {
            PaymentList::Where('fld_amc_id',$domainhosting->id)->forceDelete();
            
            if($request->addmore)     
            {
                foreach($request->addmore as $key => $admm)
                {
                    if($admm['images']){
                        $emailList                        = New PaymentList;
                        $emailList->fld_amc_id            = $domainhosting->id;
                        $emailList->fld_amc_payment_input = 0;
                        $emailList->fld_amc_payment_amount = $admm['images'];
                        $emailList->fld_amc_payment_date  = $admm['price'];
                        $emailList->fld_amc_payment_desc  = $admm['stock'];
                        $emailList->fld_status            = 1;
                        $emailList->save();
                    }
                }
            }
            return redirect()->route('sub_admin.amc.view')->with('success', 'AMC Updated Successfully');
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
        $domainhosting = AdminAmc::where('fld_status', 0)->paginate(100);
        // dd($domainhosting);
        return view('sub_admin.amc.not-view', compact('domainhosting'));
    }

    public function invoice($id)
    {
        $domainhosting = AdminAmc::where('id', $id)->first();

        // $product_description = $domainhosting->client_name ?? '';
        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        $data = [
                'domainhosting' => $domainhosting,
            ];
        
        // Render the PDF view into a variable
        $dompdf = new Dompdf();
        $html = view('sub_admin.amc.amc_invoice', $data)->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Get the PDF content
        // $pdfContent = $dompdf->output();
        // $pdf = PDF::loadView('admin.amc.amc_invoice', compact('domainhosting'));
        // $pdfname = 'tetss.pdf';

        return view('sub_admin.amc.amc_invoice', compact('domainhosting'));

    }

}
