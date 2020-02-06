@extends('layouts.fontend_master')



@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 mt-5">
            <div class="card">
                <div class="card-header text-center">
                    Laravel, jQuery Ajax, yajra datatables, sweetalert2 = Crud


                    <!-- Button trigger modal -->
                    <button id="add_customer" name="add_customer" type="button" class="btn btn-primary float-right"
                        data-toggle="modal">
                        Add Customer
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="FormModal" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog " role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Add Customer</h5>

                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>

                                </div>
                                <div class="modal-body">
                                    <span id="form_result"></span>
                                    <form id="customer_form" enctype="multipart/form-data">
                                        <input id="hidden_method" type="hidden" name="_method">
                                        @csrf
                                        <div class="form-group">
                                            <label for="" class="float-left">First Name</label>
                                            <input type="text" class="form-control" name="first_name" id="first_name"
                                                aria-describedby="helpId" placeholder="">

                                        </div>
                                        <div class="form-group">
                                            <label for="" class="float-left">Last Name</label>
                                            <input type="text" class="form-control" name="last_name" id="last_name"
                                                aria-describedby="helpId" placeholder="">

                                        </div>
                                        <div class="form-group">
                                            <label for="" class="float-left">Profile Image</label>
                                            <input type="file" class="form-control-file" name="customer_image"
                                                id="customer_image" placeholder="" aria-describedby="fileHelpId">
                                            <span id="store_image"></span>

                                        </div>


                                </div>
                                <div class="modal-footer">
                                    <input type="hidden" name="action" id="action">
                                    <input type="hidden" name="hidden_id" id="hidden_id">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <input type="submit" id="action_button" value="Add" class="btn btn-primary">
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered " id="customer_table" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection