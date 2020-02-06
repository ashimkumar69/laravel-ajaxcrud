"use strict";
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
            url: "/customers",
            method: "GET"
        },
        columns: [
            { data: "id", name: "id" },
            {
                data: "customer_image",
                name: "customer_image",
                render: function(data) {
                    return (
                        "<img src='/uploads/customer_photo/" +
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

    // data insert to server
    //
    //
    //
    $("#add_customer").click(function() {
        $("#hidden_method").val("post");
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
                method: "post",
                url: "/customers",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json"
            })
                .done(function(res) {
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
                })
                .fail(function() {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Something went wrong!"
                    });
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
            let id = $("#hidden_id").val();

            $.ajax({
                method: "post",
                url: "/customers/" + id,
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json"
            })
                .done(function(res) {
                    let html = "";
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
                })
                .fail(function(res) {
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
                    $("#form_result").html(html);
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
        $("#hidden_method").val("put");
        $("#form_result").html("");
        let id = $(this).attr("id");

        $.ajax({
            method: "get",
            url: "/customers/" + id,
            dataType: "json"
        })
            .done(function(res) {
                $("#first_name").val(res.data.first_name);
                $("#last_name").val(res.data.last_name);
                $("#store_image").html(
                    "<img src='/uploads/customer_photo/" +
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
            })
            .fail(function() {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Something went wrong!"
                });
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
                        method: "delete",
                        url: "/customers/" + id,
                        dataType: "json"
                    })
                        .done(function(res) {
                            $("#customer_table")
                                .DataTable()
                                .ajax.reload();

                            swalWithBootstrapButtons.fire(
                                "Deleted!",
                                `${res.success}`,
                                "success"
                            );
                        })
                        .fail(function() {
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Something went wrong!"
                            });
                        });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
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

    // end
});
