<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::all();
        return view('jobs.index', compact('jobs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required', 
            'date' => 'nullable|date'
        ]);

        Job::create($request->only('title', 'date'));
        return response()->json(['success' => true]);
    }

    public function destroy($id)
{
    $job = Job::find($id);
    
    if ($job) {
        $job->delete();
        return response()->json(['success' => true]);
    }
    
    return response()->json(['success' => false, 'message' => 'Job not found']);
}

    public function toggleCompleted(Request $request, Job $job)
    {
        $job->status = !$job->status; 
        $job->save();
        return response()->json(['success' => true, 'status' => $job->status]);
    }
}
