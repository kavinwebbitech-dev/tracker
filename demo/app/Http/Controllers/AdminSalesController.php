<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\SalesPerson;
use Auth;
use Mail;

class AdminSalesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function view()
    {
        $users = SalesPerson::get();
        return view('admin.sales.view', compact('users'));
    }

    public function create()
    {
        return view('admin.sales.add');
    }

    public function edit($id)
    {
        $sub_admin = SalesPerson::where('id', $id)->first();
        return view('admin.sales.edit', compact('sub_admin'));
    }

    public function delete($id)
    {
        if ($id) {
            $user  = SalesPerson::find($id);
            if ($user->delete()) {
                return redirect()->route('admin.sales.view')->with('error', 'Sales Person Deleted Successfully');
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
            'firstname' => 'required',
            'email' => 'required',
            'phone' => 'required'
        ]);

        $user               = new SalesPerson();
        $user->firstname    = $request->firstname;
        $user->lastname     = $request->lastname;
        $user->email        = $request->email;
        $user->phone        = $request->phone;
        $user->color_code   = $request->color_code;
        $user->type         = $request->user_role;

        if ($request->hasFile('profile')) {
            $image = $request->file('profile');
            $filename = time().'.'.$image->extension();
            $destinationPath = public_path('/uploads');
            
            $image->move($destinationPath, $filename);
            $user->profile           = $filename;
        }

        if ($user->save()) 
        {
            return redirect()->route('admin.sales.view')->with('success', 'Sales Person Created Successfully');
        }
        else{
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $user               = SalesPerson::find($id);
        $user->firstname    = $request->firstname;
        $user->lastname     = $request->lastname;
        $user->email        = $request->email;
        $user->phone        = $request->phone;
        $user->color_code   = $request->color_code;
        $user->type         = $request->user_role;

        if ($request->hasFile('profile')) {
            $image = $request->file('profile');
            $filename = time().'.'.$image->extension();
            $destinationPath = public_path('/uploads');
            
            $image->move($destinationPath, $filename);
            $user->profile           = $filename;
        }
        
        if ($user->save()) {
            return redirect()->route('admin.sales.view')->with('warning', 'Sales Person Edited Successfully');
        }
        else{
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }


    // Staff Details
    public function Staffview()
    {
        $users = SalesPerson::get();
        return view('staff.sales.view', compact('users'));
    }

    public function Staffcreate()
    {
        return view('staff.sales.add');
    }

    public function Staffedit($id)
    {
        $sub_admin = SalesPerson::where('id', $id)->first();
        return view('staff.sales.edit', compact('sub_admin'));
    }

    public function Staffdelete($id)
    {
        if ($id) {
            $user  = SalesPerson::find($id);
            if ($user->delete()) {
                return redirect()->route('staff.sales.view')->with('error', 'Sales Person Deleted Successfully');
            }
            else{
                return redirect()->back()->with('error', 'Something Wrong');
            }
        }
        else{
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function Staffstore(Request $request)
    {
        // dd($request->all());

        
        $request->validate([
            'firstname' => 'required',
            'email' => 'required',
            'phone' => 'required'
        ]);

        $user               = new SalesPerson();
        $user->firstname    = $request->firstname;
        $user->lastname     = $request->lastname;
        $user->email        = $request->email;
        $user->phone        = $request->phone;
        $user->type         = $request->user_role;

        if ($request->hasFile('profile')) {
            $image = $request->file('profile');
            $filename = time().'.'.$image->extension();
            $destinationPath = public_path('/uploads');
            
            $image->move($destinationPath, $filename);
            $user->profile           = $filename;
        }

        if ($user->save()) 
        {
            return redirect()->route('staff.sales.view')->with('success', 'Sales Person Created Successfully');
        }
        else{
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function Staffupdate(Request $request, $id)
    {
        // dd($request->all());

        $user               = SalesPerson::find($id);
        $user->firstname    = $request->firstname;
        $user->lastname     = $request->lastname;
        $user->email        = $request->email;
        $user->phone        = $request->phone;
        $user->type         = $request->user_role;

        if ($request->hasFile('profile')) {
            $image = $request->file('profile');
            $filename = time().'.'.$image->extension();
            $destinationPath = public_path('/uploads');
            
            $image->move($destinationPath, $filename);
            $user->profile           = $filename;
        }
        
        if ($user->save()) {
            return redirect()->route('staff.sales.view')->with('warning', 'Sales Person Edited Successfully');
        }
        else{
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }
}
