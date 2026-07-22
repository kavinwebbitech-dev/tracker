<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use App\Models\ProposalDetails;
use Illuminate\Http\Request;
use Auth;

class AdminProposalController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function view()
    {
        $users = Proposal::get();
        return view('admin.proposal.view', compact('users'));
    }

    public function create()
    {
        return view('admin.proposal.add');
    }

    public function edit($id)
    {
        $sub_admin = Proposal::where('id', $id)->first();
        return view('admin.proposal.edit', compact('sub_admin'));
    }

    public function status($id)
    {
        $sub_admin = Proposal::where('id', $id)->first();
        return view('admin.proposal.status', compact('sub_admin'));
    }

    public function delete($id)
    {
        if ($id) {
            $user  = Proposal::find($id);
            if ($user->delete()) {
                return redirect()->route('admin.proposal.view')->with('error', 'Proposal Deleted Successfully');
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
            'name' => 'required|max:255',
            'proposal_date' => 'required'
        ]);

        $document_list  = $request->title_list;
        $imageName1 = "";
        $user                   = new Proposal();
        $user->name             = $request->name;
        $user->uploaded_by      = Auth::user()->id;
        $user->proposal_date    = $request->proposal_date;
        if ($user->save()) 
        {
            if (count($document_list) > 0) {
                foreach ($document_list as $key => $value) {
                    $bill_details              = new ProposalDetails();
                    if (isset($value['proposal_documents']) && $value['proposal_documents']) {
                        // dd($value['proposal_documents']);
                        $imageName1 = time().$key.'.'.$value['proposal_documents']->extension();
                        $value['proposal_documents']->move(public_path('proposal_documents'), $imageName1);
                    }
                    $bill_details->proposal_id     = $user->id;
                    $bill_details->documents       = $imageName1 ?? '';
                    $bill_details->status          = $value['proposal_type'];
                    $bill_details->amount          = $value['amount'];
                    $bill_details->save();
                }
            }
            return redirect()->route('admin.proposal.view')->with('success', 'Proposal Created Successfully');
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
            'name' => 'required|max:255',
            'proposal_date' => 'required'
        ]);

        $document_list  = $request->title_list;

        $user                   = Proposal::find($id);
        $user->name             = $request->name;
        $user->uploaded_by      = Auth::user()->id;
        $user->amount           = $request->amount;
        $user->proposal_date    = $request->proposal_date;
        if ($user->save()) 
        {
            if (count($document_list) > 0) {
                ProposalDetails::Where('proposal_id',$user->id)->forceDelete();
                foreach ($document_list as $key => $value) {
                    // dd($value);
                    $bill_details              = new ProposalDetails();
                    if (isset($value['proposal_documents']) && $value['proposal_documents']) {
                        // dd($value['proposal_documents']);
                        $imageName1 = time().$key.'.'.$value['proposal_documents']->extension();
                        $value['proposal_documents']->move(public_path('proposal_documents'), $imageName1);
                    }
                    else
                    {
                        $imageName1 = $value['hidden_file'];
                    }
                    $bill_details->proposal_id     = $user->id;
                    $bill_details->documents       = $imageName1;
                    $bill_details->status          = $value['proposal_type'];
                    $bill_details->amount          = $value['amount'];
                    $bill_details->save();
                }
            }
            return redirect()->route('admin.proposal.view')->with('success', 'Proposal Edited Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }

    }

}
