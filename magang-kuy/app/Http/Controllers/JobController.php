<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index()
    {
        $data = Job::with(['qualifications', 'responsibilities'])->get();

        return response()->json($data);
    }

    public function create(Request $request)
    {
        try {
            $job = new Job;
            $job->name = $request->name;
            $job->category = $request->category;
            $job->company_name = $request->company_name;
            // $job->company_logo = $request->company_logo;
            $job->location = $request->location;

            if ($job->save()) {
                $data = Job::where('name', $request->name)->orderBy('created_at', 'desc')->first();
                return response()->json(['massage' => 'Success', 'data' => $data]);
            }
        } catch (\Exception $e) {
            return response()->json(['massage' => 'Failed', 'data' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $job = Job::findOrFail($id);
            $gambar = $request->file('company_logo')->getClientOriginalName();

            $newName = "$request->name.$gambar";

            $request->file('company_logo')->move('storage/job', $newName);

            $job->name = $request->name;

            $job->category = $request->category;
            $job->company_name = $request->company_name;
            $job->company_logo = 'storage/job/' . $newName;
            $job->location = $request->location;

            if ($job->save()) {
                $data = Job::where('name', $request->name)->orderBy('created_at', 'desc')->first();
                return response()->json(['massage' => 'Success', 'data' => $data]);
            }
        } catch (\Exception $e) {
            return response()->json(['massage' => 'Failed', 'data' => $e->getMessage()], 400);
        }
    }

    public function show(Request $request)
    {
        $id = $request->input('id');
        $category = $request->input('category');

        if ($id) {
            $data = Job::with(['about', 'qualification', 'responsibility'])->find($id);

            if ($data) {
                return response()->json(['message' => 'Success', 'data' => $data]);
            } else {
                return response()->json(['message' => 'Failed', 'data' => $data], 404);
            }
        }


        if ($category) {
            $data = Job::where('category', 'like', '%' . $category . '%')->with(['about', 'qualification', 'responsibility'])->get();

            return response()->json($data);
        }
    }

    public function destroy($id)
    {
        try {
            $job = Job::findOrFail($id);

            if ($job->delete()) {
                return response()->json(['massage' => 'Success', 'data' => null]);
            }
        } catch (\Throwable $e) {
            return response()->json(['massage' => 'Failed', 'data' => $e->getMessage()], 400);
        }
    }
}
