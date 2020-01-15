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
            type: "GET"
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
        $(".modal-title").text("Add Customer");
        $("#action_button").val("Add");
        $("#action").val("add");
        $("#form_result").html("");
        $("#FormModal").modal("show");
    });

    $("#customer_form").on("submit", function(e) {
        e.preventDefault();

        //insert by add
        //
        //
        //
        if ($("#action").val() === "add") {
            $.ajax({
                type: "POST",
                url: "/customers",
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
                    // $("#action").val("");
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
        //  problem in route & data request
        if ($("#action").val() === "edit") {
            $.ajax({
                url: "/customers",
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
        let id = $(this).attr("id");
        $("#form_result").html("");

        $.ajax({
            url: "/customers/" + id + "/edit",
            type: "GET",
            dataType: "json",
            success: function(res) {
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
                        url: "/customers",
                        type: "POST",
                        data: { id: id },
                        dataType: "json",
                        success: function(res) {}
                    });
                    $("#customer_table")
                        .DataTable()
                        .ajax.reload();

                    swalWithBootstrapButtons.fire(
                        "Deleted!",
                        "Your file has been deleted.",
                        "success"
                    );
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
