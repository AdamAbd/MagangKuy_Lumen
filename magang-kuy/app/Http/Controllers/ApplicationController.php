<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Application::where('user_id', 'LIKE', '%' . $request->user_id . '%')
            ->where('job_id', 'LIKE', '%' . $request->job_id . '%')->first();

        if (!$data) {
            return response()->json([[
                'id' => 1, 'user_id' => 1, 'job_id' => 1, 'apply' => 'false',
                "created_at" => "2021-05-29T08:17:52.000000Z", "updated_at" => "2021-05-30T08:15:22.000000Z"
            ]]);
        }
        // else {
        //     # code...
        // }

        if ($data->apply == 0) {
            $data->apply = 'false';

            return response()->json([$data]);
        } elseif ($data->apply == 1) {
            $data->apply = 'true';

            return response()->json([$data]);
        } else {
            return response()->json(['Error']);
        }
        // return response()->json([$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = Application::where('user_id', 'LIKE', '%' . $request->user_id . '%')
            ->where('job_id', 'LIKE', '%' . $request->job_id . '%')->first();

        if (!$data) {
            // return response()->json('Fuck No');
            try {
                $apply = new Application;
                $apply->user_id = $request->user_id;
                $apply->job_id = $request->job_id;

                if ($apply->save()) {
                    $make = Application::where('user_id', $request->user_id)
                        ->orderBy('created_at', 'desc')->first();
                    $make->apply = 'true';
                    return response()->json([$make]);
                }
            } catch (\Exception $e) {
                return response()->json($e->getMessage(), 400);
            }
        } else {
            // return response()->json('Yes Baby');
            try {
                $apply = Application::findOrFail($data->id);
                $apply->user_id = $request->user_id;
                $apply->job_id = $request->job_id;
                $apply->apply = $request->apply;

                if ($apply->save()) {
                    $update = Application::where('id', $data->id)
                        ->orderBy('created_at', 'desc')->first();
                    // $update->apply = 'true';
                    // return response()->json($update);
                    if ($update->apply == 0) {
                        $update->apply = 'false';

                        return response()->json([$update]);
                    } elseif ($update->apply == 1) {
                        $update->apply = 'true';

                        return response()->json([$update]);
                    } else {
                        return response()->json('Error');
                    }
                }
            } catch (\Exception $e) {
                return response()->json($e->getMessage(), 400);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function show(Application $application)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function edit(Application $application)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Application $application)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function destroy(Application $application)
    {
        //
    }
}
