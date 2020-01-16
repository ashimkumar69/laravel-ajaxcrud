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
                $button .= '<button id="' . $customers->id . '" type="button" class="editBtn btn btn-sm btn-primary">Edit</button>';
                $button .= '<button id="' . $customers->id . '" type="button" class="deleteBtn btn btn-sm btn-danger">Delete</button>';
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
        if ($request->action == "add") {
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


        if ($request->action == "edit") {
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

                unlink(base_path("public/uploads/customer_photo/" . Customer::findOrFail($request->hidden_id)->customer_image));

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


        // delete request
        if ($request->delete == 1) {
            unlink(base_path("public/uploads/customer_photo/" . Customer::findOrFail($request->id)->customer_image));
            $customer = Customer::findOrFail($request->id);
            $customer->delete();
            return response()->json(["success" => "Data Added successfully"]);
        } else {
            return response()->json(["error" => "Bad request"]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
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

            $data = Customer::findOrFail($request->id);
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
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer, $id)
    {
        //
    }
}
