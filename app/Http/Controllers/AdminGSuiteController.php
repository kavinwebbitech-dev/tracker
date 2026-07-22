<?php

namespace App\Http\Controllers;

use App\Models\Branches;
use App\Models\Customers;
use App\Models\GSuite;
use App\Models\GSuide;
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
        $month = '';

        if (request('start_date') && request('start_date') != '') {
            $start_date = request('start_date');
        }
        if (request('month') && request('month') != '') {
            $month = request('month');
        }

        if ($start_date) {

            $date = \Carbon\Carbon::now()->addDays($start_date);
            $to_date = $date->format('Y-m-d');
            $domainhosting = GSuite::where('fld_status', 1)->where("fld_gsuite_end_date", '<', $to_date)->paginate(100);
        } elseif ($month) {
            $domainhosting = GSuite::where('fld_status', 1)->whereMonth('fld_gsuite_end_date', $month)->paginate(100);
        } else {
            $domainhosting = GSuite::where('fld_status', 1)->paginate(100);
        }

        return view('admin.gsuide.view', compact('domainhosting', 'start_date', 'month'));
    }

    public function create()
    {
        $branches = Branches::where('fld_status', 1)->get();
        $customers = Customers::where('fld_status', 1)->orderBy('id', 'desc')->latest()->get();
        return view('admin.gsuide.add', compact('branches', 'customers'));
    }

    public function edit($id)
    {
        $branches = Branches::where('fld_status', 1)->get();
        $customers = Customers::where('fld_status', 1)->orderBy('id', 'desc')->latest()->get();

        $domainhosting = GSuite::where('id', $id)->first();
        $customer_details = Customers::where('id', $domainhosting->fld_cust_id)->first();
        return view('admin.gsuide.edit', compact('branches', 'customers', 'domainhosting', 'customer_details'));
    }

    public function NotInterest($id)
    {
        $domainhosting                          = GSuite::find($id);
        $domainhosting->fld_status              = 0;

        if ($domainhosting->save()) {
            return redirect()->route('admin.gsuide.view')->with('success', 'GSuite Updated Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function Interest($id)
    {
        $domainhosting                          = GSuite::find($id);
        $domainhosting->fld_status              = 1;

        if ($domainhosting->save()) {
            return redirect()->back()->with('success', 'GSuite Updated Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function delete($id)
    {
        if ($id) {
            $user  = GSuite::find($id);
            // dd($user);
            if ($user->delete()) {
                return redirect()->route('admin.gsuide.view')->with('error', 'GSuite Deleted Successfully');
            } else {
                return redirect()->back()->with('error', 'Something Wrong');
            }
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function DetailsGet(Request $request)
    {
        // dd($request->all());

        $number_of_mail = $request->number_of_mail;
        $guside_id = $request->guside_id;
        $fld_gsuite_end_date = $request->fld_gsuite_end_date;
        $total_amount = 0;
        $total_amount1 = 0;
        $gsuide_details = GSuide::where('email_type', $request->gsuide_type)->get();
        $end_amount = [];



        if ($guside_id) {

            $email_list = EmailList::where('fld_gsuite_id', $guside_id)->get();
            $html = "";
            for ($i = 1; $i <= $number_of_mail; $i++) {
                $email_name = $email_list[$i - 1]->fld_email ?? '';

                if (count($gsuide_details) > 0) {

                    foreach ($gsuide_details as $key1 => $value1) {
                        // dd($i);
                        if ($i >= $value1->start_email && $i <= $value1->end_email) {
                            $total_amount += $value1->actual_price;
                            $total_amount1 += $value1->amount;
                            $html .= '<div class="row mb-3">
                                <div class="col-md-5">
                                    <input type="text" name="addmore[' . $i . '][images]" value="' . $email_name . '" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <input type="date" name="addmore[' . $i . '][price]" value="' . $fld_gsuite_end_date . '" class="form-control" />
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="addmore[' . $i . '][stock]" value="' . $value1->actual_price . '" class="form-control inputdecimal" />
                                </div>
                            </div>';
                        }
                    }
                }
            }
        } else {
            $html = "";

            for ($i = 1; $i <= $number_of_mail; $i++) {

                if (count($gsuide_details) > 0) {

                    foreach ($gsuide_details as $key1 => $value1) {
                        // dd($i);
                        if ($i >= $value1->start_email && $i <= $value1->end_email) {
                            $total_amount += $value1->actual_price;
                            $total_amount1 += $value1->amount;
                            $html .= '<div class="row mb-3">
                                <div class="col-md-5">
                                    <input type="text" name="addmore[' . $i . '][images]" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <input type="date" name="addmore[' . $i . '][price]" value="' . $fld_gsuite_end_date . '" class="form-control" />
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="addmore[' . $i . '][stock]" value="' . $value1->actual_price . '" class="form-control inputdecimal" />
                                </div>
                            </div>';
                        }
                    }
                }
            }
        }


        $data['number_of_field'] = $html;
        $data['amount'] = $total_amount1;
        $data['actual_price'] = $total_amount;

        return $data;
    }

    public function store(Request $request)
    {
        // dd($request->all());
    // dd($request->addmore);

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
        // $domainhosting->number_of_mail          = $request->number_of_mail;
        // $domainhosting->gsuide_type             = $request->gsuide_type;
        // $domainhosting->fld_actual_amount       = $request->fld_actual_amount;
        // $domainhosting->fld_autual_total_amount = $request->fld_autual_total_amount;
        $domainhosting->fld_email_count         = $request->fld_email_count;
        $domainhosting->fld_createdon           = date('Y-m-d');
        $domainhosting->fld_updatedon           = date('Y-m-d');
        $domainhosting->fld_status              = 1;

        $customer = Customers::find($request->fld_cust_id);

        if ($customer) {
            $customer->fld_name = $request->cname;
            $customer->fld_email = $request->cemail;
            $customer->fld_company_name = $request->ccname;
            $customer->save();
        }

            
        if ($domainhosting->save()) {

            if ($request->addmore) {
                foreach ($request->addmore as $key => $admm) {
                    if ($admm['images']) {
                        $emailList                  = new EmailList;
                        $emailList->fld_gsuite_id   = $domainhosting->id;
                        $emailList->fld_email_input = 0;
                        $emailList->fld_email       = $admm['images'];
                        $emailList->fld_email_date  = $admm['price'];
                        $emailList->fld_email_desc  = $admm['stock'];
                        $emailList->fld_status      = 1;
                        $emailList->save();
                    }
                }
            }
            return redirect()->route('admin.gsuide.view')->with('success', 'GSuite Created Successfully');
        } else {
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
            'fld_total_amount' => 'required',
            'fld_gsuite_end_date' => 'required',
            // Exactly one of these two should be present depending on which
            // tenure select is active client-side; neither is unconditionally required.
            'fld_gsuite_tenure' => 'nullable',
            'fld_expiry_gsuite_tenure' => 'nullable',
        ]);

        $domainhosting                          = GSuite::find($id);
        $domainhosting->fld_branch_id           = $request->fld_branch_id;
        $domainhosting->fld_cust_id             = $request->fld_cust_id;
        $domainhosting->fld_domain_name         = $request->fld_domain_name;
        $domainhosting->fld_gsuite_start_date   = $request->fld_gsuite_start_date;

        // Only overwrite each tenure field if it was actually submitted.
        // Disabled selects don't post, so a missing field means "leave as-is",
        // not "clear it out".
        $domainhosting->fld_gsuite_tenure        = $request->fld_gsuite_tenure;
        $domainhosting->fld_expiry_gsuite_tenure = $request->fld_expiry_gsuite_tenure;

        $domainhosting->fld_gsuite_end_date     = $request->fld_gsuite_end_date;
        $domainhosting->fld_tax_percentage      = $request->fld_tax_percentage;
        $domainhosting->fld_amount              = $request->fld_amount;
        $domainhosting->fld_total_amount        = $request->fld_total_amount;
        $domainhosting->fld_description         = $request->fld_description;
        // $domainhosting->fld_actual_amount       = $request->fld_actual_amount;
        // $domainhosting->fld_autual_total_amount = $request->fld_autual_total_amount;
        $domainhosting->fld_email_count         = $request->fld_email_count;
        $domainhosting->fld_createdon           = date('Y-m-d');
        $domainhosting->fld_updatedon           = date('Y-m-d');
        $domainhosting->fld_status              = 1;

        $customer = Customers::find($request->fld_cust_id);

        if ($customer) {
            $customer->fld_name = $request->cname;
            $customer->fld_email = $request->cemail;
            $customer->fld_company_name = $request->ccname;
            $customer->save();
        }

        if ($domainhosting->save()) {
            EmailList::Where('fld_gsuite_id', $domainhosting->id)->forceDelete();

            if ($request->addmore) {
                foreach ($request->addmore as $key => $admm) {
                    if ($admm['images']) {
                        $emailList                  = new EmailList;
                        $emailList->fld_gsuite_id   = $domainhosting->id;
                        $emailList->fld_email_input = 0;
                        $emailList->fld_email       = $admm['images'];
                        $emailList->fld_email_date  = $admm['price'];
                        $emailList->fld_email_desc  = $admm['stock'];
                        $emailList->fld_status      = 1;
                        $emailList->save();
                    }
                }
            }
            return redirect()->route('admin.gsuide.view')->with('success', 'GSuite Updated Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function UserDetails(Request $request)
    {
        $UserDetails = Customers::where('id', $request->id)->first();
        $view =  view('admin.amc.customer', compact('UserDetails'))->render();
        return response()->json(['html' => $view]);
    }

    public function notview()
    {
        $domainhosting = GSuite::where('fld_status', 0)->paginate(100);
        return view('admin.gsuide.not-view', compact('domainhosting'));
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
        $html = view('admin.gsuide.gsuite_invoice', $data)->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Get the PDF content
        // $pdfContent = $dompdf->output();
        // $pdf = PDF::loadView('admin.domainhosting.gsuite_invoice', compact('domainhosting'));
        // $pdfname = 'tetss.pdf';

        return view('admin.gsuide.gsuite_invoice', compact('domainhosting'));
    }

    public function search(Request $request)
    {

        $start_date = $request->start_date;
        $text_value_search = $request->text_value_search;

        $domainhosting = GSuite::where('fld_status', 1);

        if ($start_date) {

            $date = \Carbon\Carbon::now()->addDays($start_date);
            $to_date = $date->format('Y-m-d');
            $domainhosting = $domainhosting->where('fld_status', 1)->where("fld_domain_end_date", '<', $to_date);
        }

        $domainhosting = $domainhosting->where(function ($q) use ($text_value_search) {
            $q->where('fld_domain_name', 'LIKE', "%" . $text_value_search . "%");
        });

        $domainhosting = $domainhosting->get();

        $view =  view('admin.gsuide.search', compact('domainhosting'))->render();

        if ($view) {
            $data['project'] = $view;
            $data['status'] = "success";
        } else {
            $data['status'] = "error";
        }

        return $data;
    }



    // Staff Details
    public function Staffview()
    {
        $start_date = '';

        if (request('start_date') && request('start_date') != '') {
            $start_date = request('start_date');
        }
        if ($start_date) {
            $date = \Carbon\Carbon::now()->addDays($start_date);
            $to_date = $date->format('Y-m-d');
            $domainhosting = GSuite::where('fld_status', 1)->where("fld_gsuite_end_date", '<', $to_date)->get();
        } else {
            $domainhosting = GSuite::where('fld_status', 1)->get();
        }

        return view('staff.gsuide.view', compact('domainhosting', 'start_date'));
    }

    public function Staffcreate()
    {
        $branches = Branches::where('fld_status', 1)->get();
        $customers = Customers::where('fld_status', 1)->orderBy('id', 'desc')->get();
        return view('staff.gsuide.add', compact('branches', 'customers'));
    }

    public function Staffedit($id)
    {
        $branches = Branches::where('fld_status', 1)->get();
        $customers = Customers::where('fld_status', 1)->orderBy('id', 'desc')->get();

        $domainhosting = GSuite::where('id', $id)->first();
        $customer_details = Customers::where('id', $domainhosting->fld_cust_id)->first();
        return view('staff.gsuide.edit', compact('branches', 'customers', 'domainhosting', 'customer_details'));
    }

    public function StaffNotInterest($id)
    {
        $domainhosting                          = GSuite::find($id);
        $domainhosting->fld_status              = 0;

        if ($domainhosting->save()) {
            return redirect()->route('staff.gsuide.view')->with('success', 'GSuite Updated Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function StaffInterest($id)
    {
        $domainhosting                          = GSuite::find($id);
        $domainhosting->fld_status              = 1;

        if ($domainhosting->save()) {
            return redirect()->back()->with('success', 'GSuite Updated Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function Staffdelete($id)
    {
        if ($id) {
            $user  = GSuite::find($id);
            // dd($user);
            if ($user->delete()) {
                return redirect()->route('staff.gsuide.view')->with('error', 'GSuite Deleted Successfully');
            } else {
                return redirect()->back()->with('error', 'Something Wrong');
            }
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function Staffstore(Request $request)
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
        $domainhosting->fld_email_count         = count($request->addmore);
        $domainhosting->fld_createdon           = date('Y-m-d');
        $domainhosting->fld_updatedon           = date('Y-m-d');
        $domainhosting->fld_status              = 1;

        if ($domainhosting->save()) {
            if ($request->addmore) {
                foreach ($request->addmore as $key => $admm) {
                    if ($admm['images']) {
                        $emailList                  = new EmailList;
                        $emailList->fld_gsuite_id   = $domainhosting->id;
                        $emailList->fld_email_input = 0;
                        $emailList->fld_email       = $admm['images'];
                        $emailList->fld_email_date  = $admm['price'];
                        $emailList->fld_email_desc  = $admm['stock'];
                        $emailList->fld_status      = 1;
                        $emailList->save();
                    }
                }
            }
            return redirect()->route('staff.gsuide.view')->with('success', 'GSuite Created Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function Staffupdate(Request $request, $id)
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
        $domainhosting->fld_email_count         = count($request->addmore);
        $domainhosting->fld_createdon           = date('Y-m-d');
        $domainhosting->fld_updatedon           = date('Y-m-d');
        $domainhosting->fld_status              = 1;

        if ($domainhosting->save()) {
            EmailList::Where('fld_gsuite_id', $domainhosting->id)->forceDelete();

            if ($request->addmore) {
                foreach ($request->addmore as $key => $admm) {
                    if ($admm['images']) {
                        $emailList                  = new EmailList;
                        $emailList->fld_gsuite_id   = $domainhosting->id;
                        $emailList->fld_email_input = 0;
                        $emailList->fld_email       = $admm['images'];
                        $emailList->fld_email_date  = $admm['price'];
                        $emailList->fld_email_desc  = $admm['stock'];
                        $emailList->fld_status      = 1;
                        $emailList->save();
                    }
                }
            }
            return redirect()->route('staff.gsuide.view')->with('success', 'GSuite Updated Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function StaffUserDetails(Request $request)
    {
        $UserDetails = Customers::where('id', $request->id)->first();
        $view =  view('staff.amc.customer', compact('UserDetails'))->render();
        return response()->json(['html' => $view]);
    }

    public function Staffnotview()
    {
        $domainhosting = GSuite::where('fld_status', 0)->get();
        return view('staff.gsuide.not-view', compact('domainhosting'));
    }

    public function Staffinvoice($id)
    {
        $domainhosting = GSuite::where('id', $id)->first();

        // $product_description = $domainhosting->client_name ?? '';
        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        $data = [
            'domainhosting' => $domainhosting,
        ];

        // Render the PDF view into a variable
        $dompdf = new Dompdf();
        $html = view('staff.gsuide.gsuite_invoice', $data)->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Get the PDF content
        // $pdfContent = $dompdf->output();
        // $pdf = PDF::loadView('admin.domainhosting.gsuite_invoice', compact('domainhosting'));
        // $pdfname = 'tetss.pdf';

        return view('staff.gsuide.gsuite_invoice', compact('domainhosting'));
    }
}
