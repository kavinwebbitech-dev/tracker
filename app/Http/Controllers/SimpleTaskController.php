<?php

namespace App\Http\Controllers;

use App\Models\SimpleTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SimpleTaskController extends Controller
{
    /**
     * List all simple tasks (index page also hosts the Add/Edit modal)
     * Route name: admin.simpletask.view
     */
    public function index(Request $request)
    {
      
            $query = SimpleTask::query()->latest();

            if ($request->filled('text_value_search')) {
                $query->where('title', 'like', '%' . $request->text_value_search . '%');
            }

            if ($request->filled('start_date')) {
                $query->whereDate('start_date', '>=', $request->start_date);
            }

            if ($request->filled('end_date')) {
                $query->whereDate('end_date', '<=', $request->end_date);
            }

            $simple_tasks = $query->get();

            return view('admin.simpletask.index', compact('simple_tasks'));
        
    }

    public function create()
    {
        return redirect()->route('admin.simpletask.view');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
        ]);

        try {
            SimpleTask::create($validated);

            return redirect()
                ->route('admin.simpletask.view')
                ->with('success', 'Task created successfully.');
        } catch (\Throwable $e) {
            Log::error('SimpleTask store error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Unable to create task.');
        }
    }


    public function edit(Request $request, $id)
    {
        try {
            $task = SimpleTask::findOrFail($id);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => true,
                    'data'   => $task,
                ]);
            }

            return redirect()->route('admin.simpletask.view');
        } catch (\Throwable $e) {
            Log::error('SimpleTask edit error: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => false, 'message' => 'Task not found.'], 404);
            }

            return redirect()->route('admin.simpletask.view')->with('error', 'Task not found.');
        }
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
        ]);

        try {
            $task = SimpleTask::findOrFail($id);
            $task->update($validated);

            return redirect()
                ->route('admin.simpletask.view')
                ->with('success', 'Task updated successfully.');
        } catch (\Throwable $e) {
            Log::error('SimpleTask update error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Unable to update task.');
        }
    }



    public function destroy(Request $request, $id)
    {
        try {
            $task = SimpleTask::findOrFail($id);
            $task->delete();

            return redirect()->route('admin.simpletask.view')->with('success', 'Task deleted successfully.');
        } catch (\Throwable $e) {
            Log::error('SimpleTask destroy error: ' . $e->getMessage());
            return back()->with('error', 'Unable to delete task.');
        }
    }


    public function subindex(Request $request)
    {
       
            $query = SimpleTask::query()->latest();

            if ($request->filled('text_value_search')) {
                $query->where('title', 'like', '%' . $request->text_value_search . '%');
            }

            if ($request->filled('start_date')) {
                $query->whereDate('start_date', '>=', $request->start_date);
            }

            if ($request->filled('end_date')) {
                $query->whereDate('end_date', '<=', $request->end_date);
            }

            $simple_tasks = $query->get();

            return view('sub_admin.simpletask.index', compact('simple_tasks'));
       
    }

    public function subcreate()
    {
        return redirect()->route('sub_admin.simpletask.view');
    }

    public function substore(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
        ]);

        try {
            SimpleTask::create($validated);

            return redirect()
                ->route('sub_admin.simpletask.view')
                ->with('success', 'Task created successfully.');
        } catch (\Throwable $e) {
            Log::error('SubAdmin SimpleTask store error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Unable to create task.');
        }
    }

    public function subedit(Request $request, $id)
    {
        // Not used for JSON anymore - edit data is read from the DOM
        // (data-* attributes on the Edit link) instead of an AJAX call.
        // Kept only so the named route still resolves if visited directly.
        return redirect()->route('sub_admin.simpletask.view');
    }

    public function subupdate(Request $request, $id)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
        ]);

        try {
            $task = SimpleTask::findOrFail($id);
            $task->update($validated);

            return redirect()
                ->route('sub_admin.simpletask.view')
                ->with('success', 'Task updated successfully.');
        } catch (\Throwable $e) {
            Log::error('SubAdmin SimpleTask update error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Unable to update task.');
        }
    }

    public function subdestroy(Request $request, $id)
    {
        try {
            $task = SimpleTask::findOrFail($id);
            $task->delete();

            return redirect()
                ->route('sub_admin.simpletask.view')
                ->with('success', 'Task deleted successfully.');
        } catch (\Throwable $e) {
            Log::error('SubAdmin SimpleTask destroy error: ' . $e->getMessage());

            return redirect()
                ->route('sub_admin.simpletask.view')
                ->with('error', 'Unable to delete task.');
        }
    }
}
