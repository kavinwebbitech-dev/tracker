<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DomainServer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DomainServerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * GET domain_server/index
     */
    public function index()
    {
        $domain_servers = DomainServer::orderBy('id', 'desc')->get();

        return view('admin.domain_server', compact('domain_servers'));
    }

    /**
     * GET domain_server/create
     * Not used by the modal-driven UI (the modal handles "add" in place),
     * kept for REST completeness.
     */
    public function create()
    {
        return response()->json([
            'id'                      => '',
            'fld_domain_server_name'  => '',
            'fld_status'              => 1,
        ]);
    }

    /**
     * POST domain_server/store
     */
    public function store(Request $request)
    {
        $request->validate([
            'fld_domain_server_name' => 'required|string|max:255',
            'fld_status'             => 'required|in:0,1',
        ]);

        try {
            DomainServer::create([
                'fld_domain_server_name' => $request->fld_domain_server_name,
                'fld_status'             => $request->fld_status,
            ]);

            return redirect()->back()->with('success', 'Domain Server added successfully.');
        } catch (\Throwable $e) {
            Log::error('DomainServer store error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    /**
     * GET domain_server/edit/{id}
     * AJAX: fetch a single record to populate the edit modal
     */
    public function edit($id)
    {
        $domain_server = DomainServer::find($id);

        if (!$domain_server) {
            return response()->json(['error' => 'Record not found'], 404);
        }

        return response()->json($domain_server);
    }

    /**
     * PUT domain_server/update/{id}
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'fld_domain_server_name' => 'required|string|max:255',
            'fld_status'             => 'required|in:0,1',
        ]);

        try {
            $domain_server = DomainServer::find($id);

            if (!$domain_server) {
                return redirect()->back()->with('error', 'Domain Server not found.');
            }

            $domain_server->update([
                'fld_domain_server_name' => $request->fld_domain_server_name,
                'fld_status'             => $request->fld_status,
            ]);

            return redirect()->back()->with('success', 'Domain Server updated successfully.');
        } catch (\Throwable $e) {
            Log::error('DomainServer update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    /**
     * GET domain_server/delete/{id}
     */
    public function destroy($id)
    {
        try {
            $domain_server = DomainServer::find($id);

            if (!$domain_server) {
                return redirect()->back()->with('error', 'Domain Server not found.');
            }

            $domain_server->delete();

            return redirect()->back()->with('success', 'Domain Server deleted successfully.');
        } catch (\Throwable $e) {
            Log::error('DomainServer destroy error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }
}