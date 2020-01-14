<?php

namespace App\Http\Controllers;

use App\Customer;
use DataTables;
use Validator;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $customers = Customer::latest()->get();
            return DataTables::of($customers)->addColumn('action', function ($customers) {
                $button = '<div class="btn-group" role="group" aria-label="Basic example">';
                $button .= '<button id="' . $customers->id . '" type="button" class="btn btn-sm btn-primary editBtn">Edit</button>';
                $button .= '<button id="' . $customers->id . '" type="button" class="btn btn-sm btn-danger">Delete</button>';
                $button .= '</div>';
                return $button;
            })->make(true);
        }
        return view("customers.index");
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
        $rule = [
            "first_name" => "required",
            "last_name" => "required",
            "customer_image" => "required|image|max:2048",
        ];

        $error = Validator::make($request->all(), $rule);

        if ($error->fails()) {
            return response()->json(["errors" => $error->errors()->all()]);
        }

        $image = $request->file("customer_image");
        $new_name = rand() . "." . $image->getClientOriginalExtension();
        $image->move(public_path("uploads/customer_photo"), $new_name);


        $form_data = [
            "first_name" => $request->first_name,
            "last_name" => $request->last_name,
            "customer_image" => $new_name,
        ];

        Customer::create($form_data);

        return response()->json(["success" => "Data Added successfully"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if ($request->ajax()) {

            $data = Customer::findOrFail($id);
            return response()->json(["data" => $data]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {


        $image_name = $request->hidden_image;
        $image = $request->file("customer_image");

        if ($image != "") {
            $rule = [
                "first_name" => "required",
                "last_name" => "required",
                "customer_image" => "required|image|max:2048",
            ];

            $error = Validator::make($request->all(), $rule);

            if ($error->fails()) {
                return response()->json(["errors" => $error->errors()->all()]);
            }

            $image_name  = rand() . "." . $image->getClientOriginalExtension();
            $image->move(public_path("uploads/customer_photo"), $image_name);
        } else {

            $rule = [
                "first_name" => "required",
                "last_name" => "required",
            ];

            $error = Validator::make($request->all(), $rule);

            if ($error->fails()) {
                return response()->json(["errors" => $error->errors()->all()]);
            }
        }

        $form_data = [
            "first_name" => $request->first_name,
            "last_name" => $request->last_name,
            "customer_image" => $image_name,
        ];

        Customer::whereId($request->hidden_id)->update($form_data);

        return response()->json(["success" => "Data Added successfully"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
