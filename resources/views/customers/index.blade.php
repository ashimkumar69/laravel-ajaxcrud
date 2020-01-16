@extends('layouts.fontend_master')



@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 mt-5">
            <div class="card">
                <div class="card-header text-center">
                    Laravel Ajax Crud


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
                                    <form id="customer_form" method="POST" enctype="multipart/form-data">

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

@section("fontend_js")
<script>
    $(document).ready(function() {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });

    //get data into server
    //
    //
    //
    $("#customer_table").DataTable({
        lengthMenu: [
            [5, 10, 15, -1],
            [5, 10, 15, "All"]
        ],
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('customers.index') }}",
            method: "GET"
        },
        columns: [
            { data: "id", name: "id" },
            {
                data: "customer_image",
                name: "customer_image",
                render: function(data) {
                    return (
                        "<img src='{{ URL::to("/")}}/uploads/customer_photo/" +
                        data +
                        "' width='100px' class='img-fluid img-thumbnail' alt='customer img'>"
                    );
                }
            },
            { data: "first_name", name: "first_name" },
            { data: "last_name", name: "last_name" },
            { data: "action", name: "action", orderable: false }
        ]
    });

    //  end get data into server
    //
    //
    //

    // data insert to server
    //
    //
    //
    $("#add_customer").click(function() {
        $("#first_name").val("");
        $("#last_name").val("");
        $("#customer_image").val("");
        $("#store_image").html("");
        $("#store_image").append("");
        $("#hidden_id").val("");
        $("#form_result").html("");
        $(".modal-title").text("Add Customer");
        $("#action_button").val("Add");
        $("#action").val("add");
        $("#FormModal").modal("show");
    });

    $("#customer_form").on("submit", function(e) {
        e.preventDefault();

        //insert by add
        //
        //
        //
        if ($("#action").val() == "add") {
            $.ajax({
                url: "{{ route('customers.store') }}",
                method: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json",
                success: function(res) {
                    let html = "";
                    if (res.errors) {
                        html = '<div class="alert alert-danger">';
                        for (
                            let count = 0;
                            count < res.errors.length;
                            count++
                        ) {
                            html += "<p>" + res.errors[count] + "</p>";
                        }
                        html += "</div>";
                    }
                    if (res.success) {
                        html =
                            '<div class="alert alert-success">' +
                            res.success +
                            "</div>";
                        $("#customer_form")[0].reset();
                        $("#customer_table")
                            .DataTable()
                            .ajax.reload();
                    }
                    $("#form_result").html(html);

                }
            });
        }

        //end insert by add
        //
        //
        //

        //insert by edit
        //
        //
        //
        //
        if ($("#action").val() == "edit") {
            $.ajax({
                url: "{{ route('customers.store') }}",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json",
                success: function(res) {
                    let html = "";
                    if (res.errors) {
                        html = '<div class="alert alert-danger">';
                        for (
                            let count = 0;
                            count < res.errors.length;
                            count++
                        ) {
                            html += "<p>" + res.errors[count] + "</p>";
                        }
                        html += "</div>";
                    }
                    if (res.success) {
                        html =
                            '<div class="alert alert-success">' +
                            res.success +
                            "</div>";
                        $("#customer_form")[0].reset();
                        $("#store_image").html("");
                        $("#customer_table")
                            .DataTable()
                            .ajax.reload();
                    }
                    $("#form_result").html(html);
                }
            });
        }

        //end insert by edit
        //
        //
        //
        //

        // end submit
    });

    // end data insert to server
    //
    //
    //











    //get data to server for edit
    //
    //
    //
    //



    $(document).on("click", ".editBtn", function() {

        $("#form_result").html("");
        let id = $(this).attr("id");

        $.ajax({
            url: "/customers/" + id + "/edit",
            type: "GET",
            data:{
                "id":id
            },
            dataType: "json",
            success: function(res) {
                $("#first_name").val(res.data.first_name);
                $("#last_name").val(res.data.last_name);
                $("#store_image").html(
                    "<img src='{{ URL::to("/")}}/uploads/customer_photo/" +
                        res.data.customer_image +
                        "'  width='70px' class='img-fluid img-thumbnail' alt='person img'>"
                );
                $("#store_image").append(
                    "<input type='hidden' id='hidden_image' name='hidden_image' value='" +
                        res.data.customer_image +
                        "'>"
                );
                $("#hidden_id").val(res.data.id);
                $(".modal-title").text("Edit Customer Data");
                $("#action_button").val("Edit");
                $("#action").val("edit");
                $("#FormModal").modal("show");
            }
        });
    });

    //end get data to server for edit
    //
    //
    //
    //

    // delete customer
    //
    //
    //
    //
    $(document).on("click", ".deleteBtn", function() {

        let id = $(this).attr("id");

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
            },
            buttonsStyling: false
        });

        swalWithBootstrapButtons
            .fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                reverseButtons: true
            })
            .then(result => {
                if (result.value) {
                    $.ajax({
                        url: "{{ route('customers.store') }}",
                        type: "POST",
                        data: {
                        id:id,
                        delete: 1,
                        },
                        success: function(res) {
                            if(res.success){
                            $("#customer_table")
                        .DataTable()
                        .ajax.reload();

                    swalWithBootstrapButtons.fire(
                        "Deleted!",
                        "Your file has been deleted.",
                        "success"
                    );
                        }
                        if(res.error){

                        Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                        })
                        }
                        }
                    });

                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                        "Cancelled",
                        "Your imaginary file is safe :)",
                        "error"
                    );
                }
            });
    });
    //end delete customer
    //
    //
    //
    //

    // end on load
});



</script>
@endsection
