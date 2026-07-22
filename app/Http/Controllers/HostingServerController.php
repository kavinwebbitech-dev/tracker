<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\HostingServer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HostingServerController extends Controller
{
    
    public function index()
    {
        $hosting_servers = HostingServer::orderBy('id', 'desc')->get();

        return view('admin.server_hosting', compact('hosting_servers'));
    }

    public function create()
    {
        return response()->json([
            'id'               => '',
            'fld_hosting_name' => '',
            'fld_status'       => 1,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'fld_hosting_name' => 'required|string|max:255',
            'fld_status'       => 'required|in:0,1',
        ]);

        try {
            HostingServer::create([
                'fld_hosting_name' => $request->fld_hosting_name,
                'fld_status'       => $request->fld_status,
            ]);

            return redirect()->back()->with('success', 'Hosting Server added successfully.');
        } catch (\Throwable $e) {
            Log::error('HostingServer store error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

  
    public function edit($id)
    {
        $hosting_server = HostingServer::find($id);

        if (!$hosting_server) {
            return response()->json(['error' => 'Record not found'], 404);
        }

        return response()->json($hosting_server);
    }

  
    public function update(Request $request, $id)
    {
        $request->validate([
            'fld_hosting_name' => 'required|string|max:255',
            'fld_status'       => 'required|in:0,1',
        ]);

        try {
            $hosting_server = HostingServer::find($id);

            if (!$hosting_server) {
                return redirect()->back()->with('error', 'Hosting Server not found.');
            }

            $hosting_server->update([
                'fld_hosting_name' => $request->fld_hosting_name,
                'fld_status'       => $request->fld_status,
            ]);

            return redirect()->back()->with('success', 'Hosting Server updated successfully.');
        } catch (\Throwable $e) {
            Log::error('HostingServer update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function destroy($id)
    {
        try {
            $hosting_server = HostingServer::find($id);

            if (!$hosting_server) {
                return redirect()->back()->with('error', 'Hosting Server not found.');
            }

            $hosting_server->delete();

            return redirect()->back()->with('success', 'Hosting Server deleted successfully.');
        } catch (\Throwable $e) {
            Log::error('HostingServer destroy error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function subindex()
    {
        $hosting_servers = HostingServer::orderBy('id', 'desc')->get();

        return view('sub_admin.server_hosting', compact('hosting_servers'));
    }

    public function subcreate()
    {
        return response()->json([
            'id'               => '',
            'fld_hosting_name' => '',
            'fld_status'       => 1,
        ]);
    }

    public function substore(Request $request)
    {
        $request->validate([
            'fld_hosting_name' => 'required|string|max:255',
            'fld_status'       => 'required|in:0,1',
        ]);

        try {
            HostingServer::create([
                'fld_hosting_name' => $request->fld_hosting_name,
                'fld_status'       => $request->fld_status,
            ]);

            return redirect()->back()->with('success', 'Hosting Server added successfully.');
        } catch (\Throwable $e) {
            Log::error('HostingServer store error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

  
    public function subedit($id)
    {
        $hosting_server = HostingServer::find($id);

        if (!$hosting_server) {
            return response()->json(['error' => 'Record not found'], 404);
        }

        return response()->json($hosting_server);
    }

  
    public function subupdate(Request $request, $id)
    {
        $request->validate([
            'fld_hosting_name' => 'required|string|max:255',
            'fld_status'       => 'required|in:0,1',
        ]);

        try {
            $hosting_server = HostingServer::find($id);

            if (!$hosting_server) {
                return redirect()->back()->with('error', 'Hosting Server not found.');
            }

            $hosting_server->update([
                'fld_hosting_name' => $request->fld_hosting_name,
                'fld_status'       => $request->fld_status,
            ]);

            return redirect()->back()->with('success', 'Hosting Server updated successfully.');
        } catch (\Throwable $e) {
            Log::error('HostingServer update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function subdestroy($id)
    {
        try {
            $hosting_server = HostingServer::find($id);

            if (!$hosting_server) {
                return redirect()->back()->with('error', 'Hosting Server not found.');
            }

            $hosting_server->delete();

            return redirect()->back()->with('success', 'Hosting Server deleted successfully.');
        } catch (\Throwable $e) {
            Log::error('HostingServer destroy error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }
}