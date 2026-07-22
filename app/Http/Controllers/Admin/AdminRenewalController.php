<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branches;
use App\Models\Customers;
use App\Models\DomainHosting;
use App\Models\HostingServer;
 use App\Models\DomainServer;
use Illuminate\Http\Request;
use PDF;
use Dompdf\Dompdf;

class AdminRenewalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function view()
    {
        $start_date = '';

        if (request('start_date') && request('start_date') != '') {
            $start_date = request('start_date');
        }
        if ($start_date) {
            $date = \Carbon\Carbon::now()->addDays($start_date);
            $to_date = $date->format('Y-m-d');
            $domainhosting = DomainHosting::where('fld_status', 1)->where("fld_domain_end_date", '<', $to_date)->paginate(100);
        } else {
            $domainhosting = DomainHosting::where('fld_status', 1)->paginate(100);
        }

        return view('sub_admin.domainhosting.view', compact('domainhosting', 'start_date'));
    }

    public function create()
    {
        $branches = Branches::where('fld_status', 1)->get();
        $customers = Customers::where('fld_status', 1)->get();
        $hostingServers = HostingServer::where('fld_status', 1)->get();
        $domains = DomainServer::select('fld_domain_server_name')->get();
        return view('sub_admin.domainhosting.add', compact('branches', 'customers', 'hostingServers','domains'));
    }

    public function edit($id)
    {
        $branches = Branches::where('fld_status', 1)->get();
        $customers = Customers::where('fld_status', 1)->get();
        $hostingServers = HostingServer::where('fld_status', 1)->get();
        $domains = DomainServer::select('fld_domain_server_name')->get();
        $domainhosting = DomainHosting::where('id', $id)->first();
        $customer_details = Customers::where('id', $domainhosting->fld_cust_id)->first();
        return view('sub_admin.domainhosting.edit', compact('branches', 'customers', 'domainhosting', 'customer_details', 'hostingServers','domains'));
    }

    public function NotInterest($id)
    {
        $domainhosting              = DomainHosting::find($id);
        $domainhosting->fld_status  = 0;

        if ($domainhosting->save()) {
            return redirect()->route('sub_admin.domain.hosting.view')->with('success', 'Domain and Hosting Updated Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function Interest($id)
    {
        $domainhosting              = DomainHosting::find($id);
        $domainhosting->fld_status  = 1;

        if ($domainhosting->save()) {
            return redirect()->back()->with('success', 'Domain and Hosting Updated Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function delete($id)
    {
        if ($id) {
            $user = DomainHosting::find($id);
            if ($user->delete()) {
                return redirect()->route('sub_admin.domain.hosting.view')->with('error', 'Domain and Hosting Deleted Successfully');
            } else {
                return redirect()->back()->with('error', 'Something Wrong');
            }
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'fld_branch_id'       => 'required|max:255',
            'fld_cust_id'         => 'required|max:255',
            'fld_domain_name'     => 'required',
            'fld_tax_percentage'  => 'required',
            'fld_amount'          => 'required',
            'fld_total_amount'    => 'required',
        ]);

        $domainhosting                          = new DomainHosting();
        $domainhosting->fld_branch_id           = $request->fld_branch_id;
        $domainhosting->fld_cust_id             = $request->fld_cust_id;
        $domainhosting->fld_domain_name         = $request->fld_domain_name;
        $domainhosting->fld_domain_start_date   = $request->fld_domain_start_date;
        $domainhosting->fld_domain_tenure       = $request->fld_domain_tenure;
        $domainhosting->fld_domain_end_date     = $request->fld_domain_end_date;
        $domainhosting->fld_hosting_name        = $request->fld_hosting_name;
        $domainhosting->fld_hosting_start_date  = $request->fld_hosting_start_date;
        $domainhosting->fld_hosting_tenure      = $request->fld_hosting_tenure;
        $domainhosting->fld_hosting_end_date    = $request->fld_hosting_end_date;
        $domainhosting->fld_tax_percentage      = $request->fld_tax_percentage;
        $domainhosting->fld_amount              = $request->fld_amount;
        $domainhosting->fld_total_amount        = $request->fld_total_amount;
        $domainhosting->fld_description         = $request->fld_description;
        $domainhosting->fld_createdon           = date('Y-m-d');
        $domainhosting->fld_updatedon           = date('Y-m-d');
        $domainhosting->fld_status              = 1;

        $customer = Customers::find($request->fld_cust_id);

        if ($customer) {
            $customer->fld_name         = $request->cname;
            $customer->fld_email        = $request->cemail;
            $customer->fld_company_name = $request->ccname;
            $customer->save();
        }

        if ($domainhosting->save()) {
            return redirect()->route('sub_admin.domain.hosting.view')->with('success', 'Domain and Hosting Created Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'fld_branch_id'       => 'required|max:255',
            'fld_cust_id'         => 'required|max:255',
            'fld_domain_name'     => 'required',
            'fld_tax_percentage'  => 'required',
            'fld_amount'          => 'required',
            'fld_total_amount'    => 'required',
        ]);

        $domainhosting                            = DomainHosting::find($id);
        $domainhosting->fld_branch_id             = $request->fld_branch_id;
        $domainhosting->fld_cust_id               = $request->fld_cust_id;
        $domainhosting->fld_domain_name           = $request->fld_domain_name;
        $domainhosting->fld_domain_start_date     = $request->fld_domain_start_date;
        $domainhosting->fld_domain_tenure         = $request->fld_domain_tenure;
        $domainhosting->fld_expiry_domain_tenure  = $request->fld_expiry_domain_tenure;
        $domainhosting->fld_domain_end_date       = $request->fld_domain_end_date;
        $domainhosting->fld_hosting_name          = $request->fld_hosting_name;
        $domainhosting->fld_hosting_start_date    = $request->fld_hosting_start_date;
        $domainhosting->fld_hosting_tenure        = $request->fld_hosting_tenure;
        $domainhosting->fld_expiry_hosting_tenure = $request->fld_expiry_hosting_tenure;
        $domainhosting->fld_hosting_end_date      = $request->fld_hosting_end_date;
        $domainhosting->fld_tax_percentage        = $request->fld_tax_percentage;
        $domainhosting->fld_amount                = $request->fld_amount;
        $domainhosting->fld_total_amount          = $request->fld_total_amount;
        $domainhosting->fld_description           = $request->fld_description;
        $domainhosting->fld_status                = 1;

        $customer = Customers::find($request->fld_cust_id);

        if ($customer) {
            $customer->fld_name         = $request->cname;
            $customer->fld_email        = $request->cemail;
            $customer->fld_company_name = $request->ccname;
            $customer->save();
        }

        if ($domainhosting->save()) {
            return redirect()->route('sub_admin.domain.hosting.view')->with('success', 'Domain and Hosting Updated Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function UserDetails(Request $request)
    {
        $UserDetails = Customers::where('id', $request->id)->first();
        $view = view('sub_admin.amc.customer', compact('UserDetails'))->render();
        return response()->json(['html' => $view]);
    }

    public function notview()
    {
        $domainhosting = DomainHosting::where('fld_status', 0)->paginate(100);
        return view('sub_admin.domainhosting.not-view', compact('domainhosting'));
    }

    public function search(Request $request)
    {
        $start_date = $request->start_date;
        $text_value_search = trim($request->text_value_search);

        $domainhosting = DomainHosting::where('fld_status', 1);

        if (!empty($start_date)) {
            $date = \Carbon\Carbon::now()->addDays($start_date);
            $to_date = $date->format('Y-m-d');

            $domainhosting->where('fld_domain_end_date', '<', $to_date);
        }

        if (!empty($text_value_search)) {
            $domainhosting->where(function ($q) use ($text_value_search) {
                $q->where('fld_domain_name', 'LIKE', "%{$text_value_search}%")
                    ->orWhere('fld_hosting_name', 'LIKE', "%{$text_value_search}%");
            });
        }

        $domainhosting = $domainhosting->get();

        $view = view('sub_admin.domainhosting.search', compact('domainhosting'))->render();

        return response()->json([
            'status'  => true,
            'project' => $view,
        ]);
    }

    public function invoice($id)
    {
        $domainhosting = DomainHosting::where('id', $id)->first();

        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        $data = [
            'domainhosting' => $domainhosting,
        ];

        $dompdf = new Dompdf();
        $html = view('sub_admin.domainhosting.dh_invoice', $data)->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdfContent = $dompdf->output();
        $pdf = PDF::loadView('sub_admin.domainhosting.dh_invoice', compact('domainhosting'));
        $pdfname = 'tetss.pdf';

        return view('sub_admin.domainhosting.dh_invoice', compact('domainhosting'));
    }
}