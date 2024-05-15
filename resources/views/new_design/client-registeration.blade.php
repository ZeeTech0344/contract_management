@extends("new_design.layout.layout")


@section('content')
    <div class="row">
        <div class="col-md-6">
            <!-- Horizontal Form -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Client Information Form</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form class="form-horizontal" id="buyer_purchaser_detail">
                    <div class="card-body" style="padding-bottom:0;">
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="name" id="name"
                                    oninput="convertToUpperCase(this)" placeholder="Full Name.......">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="phone_no" class="col-sm-2 col-form-label">Phone#</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" oninput="convertToUpperCase(this)"
                                    name="phone_no" id="phone_no" placeholder="Phone#......." >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="address" class="col-sm-2 col-form-label">A/C&nbsp;#</label>
                            <div class="col-sm-10">
                                <input type="text" value="0" class="form-control" name="account_no" id="account_no"
                                    placeholder="Acount#.......">
                            </div>
                        </div>

                        <!--<div class="form-group row">-->
                        <!--    <label for="CNIC" class="col-sm-2 col-form-label">CNIC</label>-->
                        <!--    <div class="col-sm-10">-->
                        <!--        <input type="text" class="form-control" name="cnic" id="cnic"-->
                        <!--            placeholder="CNIC.......">-->
                        <!--    </div>-->
                        <!--</div>-->
                        <div class="form-group row">
                            <label for="address" class="col-sm-2 col-form-label">Address</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="address" id="address"
                                    oninput="convertToUpperCase(this)" placeholder="Address.......">
                            </div>
                        </div>

                        <div class="form-group row d-none">
                            <label for="address" class="col-sm-2 col-form-label">Opening</label>
                            <div class="col-sm-10">
                                <input type="hidden" class="form-control" name="opening_amount" id="opening_amount">
                            </div>
                        </div>

                        <div class="form-group row">
                            
                            <div class="col">
                                <button type="submit" class="btn btn-info">Save</button>
                            </div>
                        </div>


                        <input type="hidden" class="form-control" name="hidden_buyer_purchaser_id"
                            id="hidden_buyer_purchaser_id">

                    </div>
                    <!-- /.card-body -->
                    
                    <!-- /.card-footer -->
                </form>
            </div>
            <!-- /.card -->
        </div>


        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Client List</h2>
                    <label id="sum" style="margin-left:180px;"> </label>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="table_search" id="search_value" class="form-control float-right"
                                placeholder="Search">

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-2">
                    <table class="table table-head-fixed text-nowrap" id="supplier_table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Phone#</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection



@section('script')
    <script>
        var buyer_purchaser_table = $('#supplier_table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            ajax: {
                url: "{{ url('get-supplier-list') }}",
                data: function(d) {
                    d.search = $("#search_value").val();
                }
            },
            columns: [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'phone_no',
                    name: 'phone_no'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ]
        });


        $('#search_value').keypress(function(event) {
            // Check if the pressed key is Enter (keyCode 13)
            if (event.which === 13) {
                buyer_purchaser_table.draw();
            }
        });



        // It has the name attribute "registration"

        
    $("#buyer_purchaser_detail").on("submit", function (e) {
        e.preventDefault(); // Prevent the default form submission

        var formData = new FormData(this);

        $.ajax({
            headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
            url: "{{ url('insert-buyer-purchaser-record') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Optionally handle success response
                console.log("Success:", response);
                
                // Reset the form and update the datatable
                $("#buyer_purchaser_detail")[0].reset();
                buyer_purchaser_table.draw();

                $("#hidden_buyer_purchaser_id").val("");
                
                // Example toastr notification
                successAlert('Client Record Successfully!');
            },
            error: function(error) {
                // Handle any errors here
                console.error("Error:", error);
            }
        });
    });


      

        // convert small letter to capital
        function convertToUpperCase(input) {
            input.value = input.value.toUpperCase();
        }

        $(document).on("click", ".edit_buyer_purchaser_detail", function() {

            var id = $(this).data('id');

            $.ajax({
                headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                url: "{{ url('edit-buyer-purchaser-detail') }}",
                type: "POST",
                data: {
                    id
                },
                success: function(data) {
                    $("#name").val(data["name"]);
                    $("#phone_no").val(data["phone_no"]);
                    $("#account_no").val(data["account_no"]);
                    $("#cnic").val(data["cnic"]);
                    $("#address").val(data["address"]);
                    $("#opening_amount").val(data["opening_amount"]);
                    $("#hidden_buyer_purchaser_id").val(data["id"]);

                }
            })

        });


        $(document).on("click", ".update_status_buyer_purchaser_detail", function() {

            var id = $(this).data('id');

            $.ajax({
                headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                url: "{{ url('update-status-buyer-purchaser-detail') }}",
                type: "POST",
                data: {
                    id: id
                },
                success: function(data) {

                    buyer_purchaser_table.draw();

                }
            })

        });



        $(document).on("click", ".view_buyer_purchaser_detail", function() {

            var id = $(this).data('id');

            var url = "{{ url('supplier-info-view') }}" + "/" + id;
            viewModal(url);

        });


        



        $(document).on("click", ".delete_buyer_purchaser_detail", function() {

            var confirm_delete = confirm("Are you sure you want to delete supplier and its all record! You data will not restored");
            if (confirm_delete) {
                var id = $(this).data('id');
                $.ajax({
                    headers: {
                        'X-CSRF-Token': csrfToken
                    },
                    url: "{{ url('delete-supplier-record') }}",
                    type: "POST",
                    data: {
                        id: id
                    },
                    success: function(data) {

                        buyer_purchaser_table.draw();

                    }
                })

            }


        });
    </script>
@endsection