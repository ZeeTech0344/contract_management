

 <div class="row">
    <div class="col-md-6">
        <!-- Horizontal Form -->
        <div class="card card-info">
            <div class="card-header d-flex justify-content-between">
                Contractor Percentage (%) 
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form class="form-horizontal" id="client_register_form">
                <div class="card-body" style="padding-bottom:0;">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Name</label>
                        <div class="col-sm-9">
                            <select name="contractor_id" id="contractor_id" class="form-control">
                                <option value="">Select Contractor</option>
                                @foreach ($contractors as  $contractor_name)
                                    <option value="{{$contractor_name->id}}">{{$contractor_name->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Percent</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" oninput="convertToUpperCase(this)"
                                name="percentage" id="percentage" max="100" placeholder="Percentage (%)......." >
                        </div>
                    </div>
    
                    <div class="form-group row">
                        
                        <div class="col">
                            <button type="submit" class="btn btn-warning">Save</button>
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
               Contractor Percentage List
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-2">
                <table class="table table-head-fixed text-nowrap w-100" id="client_table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Percentage</th>
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
 

        <script>
    
            var client_id = "<?php echo $client_id ?>";
            var invoice_no = "<?php echo $invoice_no ?>";

            console.log(client_id);

            var buyer_purchaser_table = $('#client_table').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: "{{ url('get-contractor-percentage-list') }}" + "/" + client_id + "/" + invoice_no,
                    data: function(d) {
                        d.search = $("#search_value").val();
                    }
                },
                columns: [{
                        data: 'contractor',
                        name: 'contractor'
                    },
                    {
                        data: 'percentage',
                        name: 'percentage'
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
    
            
        $("#client_register_form").on("submit", function (e) {
            e.preventDefault(); // Prevent the default form submission
    
            var formData = new FormData(this);
            formData.append('client_id', client_id);
            formData.append('invoice_no', invoice_no);
            $.ajax({
                headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                url: "{{ url('insert-contractor-percentage') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // Optionally handle success response
                    console.log("Success:", response);
                    
                    // Reset the form and update the datatable
                    $("#client_register_form")[0].reset();
                    buyer_purchaser_table.draw();
    
                    $("#hidden_buyer_purchaser_id").val("");
                    // Example toastr notification
                    successAlert('Contractor Record Successfully!');
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
    
            $(document).on("click", ".edit_partnership_detail", function() {
    
                var id = $(this).data('id');
    
                $.ajax({
                    headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                    url: "{{ url('edit-partnership-detail') }}",
                    type: "POST",
                    data: {
                        id
                    },
                    success: function(data) {
                        $("#contractor_id").val(data["contractor_id"]);
                        $("#percentage").val(data["percentage"]);
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
                    url: "{{ url('update-contractor-status') }}",
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
    
                var url = "{{ url('contractor-info-view') }}" + "/" + id;
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



            $("#add-contractor-info").on("click", function(){
                var url = "{{url('contractor-info')}}";
                mediumModal(url);
            })

        </script>
