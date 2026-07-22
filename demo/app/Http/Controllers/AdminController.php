<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Auth;
use Mail;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function view()
    {
        $users = User::where('user_type', 'admin')->get();
        return view('admin.admin.view', compact('users'));
    }

    public function create()
    {
        return view('admin.admin.add');
    }

    public function edit($id)
    {
        $sub_admin = User::where('id', $id)->first();
        return view('admin.admin.edit', compact('sub_admin'));
    }

    public function delete($id)
    {
        if ($id) {
            $user  = User::find($id);
            if ($user->delete()) {
                return redirect()->route('admin.admin.view')->with('error', 'Sub Admin Deleted Successfully');
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
            'email' => 'required|max:255|unique:users',
            'password' => 'required'
        ]);

        $password           = Hash::make($request->password);
        $user               = new User();
        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->phone        = $request->phone;
        $user->admin_id     = Auth::user()->id;
        $user->user_type    = "admin";
        $user->status       = "active";
        $user->password     = $password;
        if ($user->save()) 
        {
            
            $data['name']          = $request->name;
            $data['email']         = $request->email;
            $data['password']      = $request->password;
            $data['logo']          = "https://manchesters.in/assets/images/mainHome/logo.png";
            $data['link_url']      = url('/login');

            try{
                Mail::send('mail.user_details', $data, function($message)use($data) {
                    $message->to($data['email'], $data['name'])
                        ->from("noreplywebbitech@gmail.com", 'no-reply')
                    ->subject('Welcome to Manchesters Family');
                });
            }
            catch(\Exception $e){
                // dd($e);
            }

            return redirect()->route('admin.admin.view')->with('success', 'Sub Admin Created Successfully');
        }
        else{
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $user               = User::find($id);
        if ($request->password) {
            $request->validate([
                'name' => 'required|max:255',
                'password' => 'required'
            ]);

            $password           = Hash::make($request->password);
            $user->password     = $password;
        }
        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->phone        = $request->phone;
        $user->admin_id     = Auth::user()->id;
        $user->user_type    = "admin";
        $user->status       = "active";
        if ($user->save()) {
            return redirect()->route('admin.admin.view')->with('warning', 'Sub Admin Edited Successfully');
        }
        else{
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }
}
