<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\Priority;
use Illuminate\Http\Request;
use Validator;
class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $people = Person::all();
        $priorities = Priority::all();
        return view("person.index", compact("people", "priorities"));

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
            'name' => 'required|max:32|min:2',
            'phone' => 'required|unique:people,phone',
            'priority_id' => 'required|numeric|gt:0,|lt:5',

        ]);

        if($validator->fails()){
            return response()->json(["errors" => $validator->errors()]);
        }

        $person = new Person();
        $person->name = $request->get("name");
        $person->phone = $request->get("phone");
        $person->priority_id = $request->get("priority_id");
        $person->save();
        $person->priority;
        return ["item" => $person];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $person = Person::findOrFail($id);
        $person->priority;
        return ["item" => $person];
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
            'name' => 'required|max:32|min:2',
            'phone' => 'required|unique:people,phone,'.$id,
            'priority_id' => 'required|numeric|gt:0,|lt:5',

        ]);

        if($validator->fails()){
            return response()->json(["errors" => $validator->errors()]);
        }

        $person = Person::findOrFail($id);
        $person->name = $request->get("name");
        $person->phone = $request->get("phone");
        $person->priority_id = $request->get("priority_id");
        $person->save();
        $person->priority;
        return ["item" => $person];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $person = Person::findOrFail($id);
        $person->delete();
        return ["success" => $id];
    }
}
