<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Queue;
use Validator;

class QueueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $queues = Queue::all();
        return $queues;
        // return view("queue.index", compact('queues'));
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:32|min:2|unique:queues,name',
            'description' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()]);
        }

        $queue = new Queue;
        $queue->name = $request->get("name");
        $queue->description = $request->get("description");
        $queue->save();
        return ["item" => $queue];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $queue = Queue::findOrFail($id);
        return $queue;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:32|min:2|unique:queues,name,' . $id,
            'description' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()]);
        }

        $queue = Queue::findOrFail($id);
        $queue->name = $request->get("name");
        $queue->description = $request->get("description");
        $queue->save();
        return ["item" => $queue];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $queue = Queue::findOrFail($id);
        $queue->delete();
        return ["success" => $id];
    }
}
