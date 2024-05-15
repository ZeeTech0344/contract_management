@extends('old_design.main')

@section('content')

    <div class="col-md-6">
        <!-- Horizontal Form -->
        <div class="card card-info">
            <div class="card-header d-flex justify-content-between">
                <h6 class="card-title">Expense Form</h6>
                <div><a href="#" class="btn btn-sm btn-warning" style="margin-right: 23px;" onclick="AddFinalPercentage()">Percentage</a></div>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form class="form-horizontal" id="buyer_purchaser_detail">


                <div class="card-body" style="padding-bottom:0;">


                    {{-- <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Date</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" name="date" id="date">
                    </div>
                </div> --}}

                    <div class="form-group row d-none">
                        <label for="type" class="col-sm-2 col-form-label">Type</label>
                        <div class="col-sm-10">
                            <select class="form-control"
                                {{ isset($type) && ($type == 'Suppliers' || $type == 'Expense') ? 'disabled' : '' }}
                                name="type" onchange="selectOption()" id="type">
                                <option value="">Select Type</option>
                                <option {{ isset($type) && $type == 'Suppliers' ? 'selected' : '' }} selected>Suppliers
                                </option>
                                {{-- <option {{ isset($type) && ($type == 'Expense') ? 'selected' : '' }}>Expense</option> --}}
                            </select>


                        </div>
                    </div>


                    <div class="form-group row d-none">
                        <label for="type" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text"  name="name" id="name" class="form-control">
                        </div>
                        <input type="hidden" id="hidden_supplier_id" name="hidden_supplier_id">
                    </div>

                    <div class="form-group row d-none">
                        <label for="type" class="col-sm-2 col-form-label">Phone#</label>
                        <div class="col-sm-10">
                            <input type="text" name="phone_no" id="phone_no" class="form-control">
                        </div>
                    </div>


                    <div class="form-group row d-none">
                        <label for="type" class="col-sm-2 col-form-label">Address</label>
                        <div class="col-sm-10">
                            <input type="text" name="address" id="address"  class="form-control">
                        </div>
                    </div>


                    <div class="form-group row d-none">
                        <label for="type" class="col-sm-2 col-form-label">Recieved</label>
                        <div class="col-sm-10">
                            <input type="text"  name="recieved_payment" id="recieved_payment" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row d-none">
                        <label for="type" class="col-sm-2 col-form-label">Tax</label>
                        <div class="col-sm-10">
                            <input type="text"  name="tax" id="tax" class="form-control">
                        </div>
                    </div>


                    <div class="form-group row d-none">
                        <div class="col-md-2"></div>
                        <div class="col-sm-10 d-flex">
                            <input type="radio" name="trn_no"  checked id="trn_no" value="1">TRN Show
                            &nbsp; &nbsp;<input type="radio" name="trn_no" id="trn_no" value="0">TRN Not Show
                        </div>

                    </div>


                


                    <div class="form-group row d-none ">
                        <label for="type" class="col-sm-2 col-form-label">Type</label>
                        <div class="col-sm-10">
                            <select class="form-control"
                                {{ isset($type) && ($type == 'Suppliers' || $type == 'Expense') ? 'disabled' : '' }}
                                name="type" onchange="selectOption()" id="type">
                                <option value="">Select Type</option>
                                <option {{ isset($type) && $type == 'Suppliers' ? 'selected' : '' }} selected>Suppliers
                                </option>
                                {{-- <option {{ isset($type) && ($type == 'Expense') ? 'selected' : '' }}>Expense</option> --}}
                            </select>


                        </div>
                    </div>



                    <div class="form-group row supplier_field" style="display: none;">
                        <label for="name" class="col-sm-2 col-form-label ">Name</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="buyer_purchaser_id" id="buyer_purchaser_id">
                            </select>
                        </div>
                    </div>

                    <input type="hidden" name="buyer_purchaser_hidden_id" id="buyer_purchaser_hidden_id" value="0">

                    <div class="form-group row supplier_field" style="display: none;">
                        <label for="amount_status" class="col-sm-2 col-form-label">Status</label>
                        <div class="col-sm-10">
                            <select name="amount_status" id="amount_status" class="form-control">
                                <option value="">Select Amount Status</option>
                                <option selected>Bill</option>
                                <option>Supplier Amount Recieved</option>
                            </select>
                        </div>
                    </div>





                    <input type="hidden" name="amount_status_hidden_value" id="amount_status_hidden_value">

                    <div class="form-group row " id="head_row">
                        <label for="head" class="col-sm-2 col-form-label">Description</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="head" id="head"
                                placeholder="Description......."
                                value="{{ isset($data) && isset($type) && $type == 'Expense' ? $data->head : '' }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="total" class="col-sm-2 col-form-label">Quantity</label>
                        <div class="col-sm-10 d-flex ">
                            <div style="margin-right: 5px;"> <input type="number" class="form-control" 
                                    name="quantity" id="quantity" placeholder="Quantity" onkeyup="calculate(this)">
                            </div>
                            <div style="margin-right: 5px;"> <input type="number" onkeyup="calculate(this)" class="form-control" 
                                name="amount" id="amount" placeholder="Amount">
                        </div>
                        <div > <input type="number" class="form-control"
                            name="total" id="total" placeholder="Total">
                    </div>
                           
                        </div>
                    </div>

                    <div class="form-group row d-none" id="remarks_row">
                        <label for="remarks" class="col-sm-2 col-form-label">Remarks</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="remarks" id="remarks"
                                placeholder="Remarks.......">
                        </div>

                    </div>

                    <div class="form-group row">
                        <div class="d-flex justify-content-between pb-2">
                            
                            <input type="button" class="btn btn-warning" value="Save"
                        onclick="saveData()">
                        <button type="submit" class="btn btn-success">Add</button>
                        </div>
                    </div>





                    {{-- <input type="hidden" class="form-control" name="hidden_id" id="hidden_id"> --}}


                    <input type="hidden" name="hidden_id" id="hidden_id"
                        value="{{ isset($data) && isset($type) && $type == 'Expense' ? $data->id : '' }}">
                    <input type="hidden" id="hidden_type" value="{{ isset($type) && $type == 'Expense' ? $type : '' }}">
                </div>
                <!-- /.card-body -->
                    <input type="hidden" value="{{ isset($invoice_data) ? $invoice_data[0]->invoice_no : '' }}" id="invoice_no"  name="invoice_no">
                <!-- /.card-footer -->
            </form>
        </div>
        <!-- /.card -->

    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h6 class="card-title">Expense View &nbsp;&nbsp;&nbsp; Sum(<label id="sum"> </label>)</h6>


            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-head-fixed text-nowrap text-center" id="bill_table">
                    <thead>
                        <tr>
                            {{-- <th class="d-none">Date</th> --}}
                            <th>Head</th>
                            <th>Qty</th>
                            <th>Amount</th>
                            <th>Total</th>
                            <th class="text-center">Edit</th>
                            <th class="text-center">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($expense))
                        @foreach ($expense as $data)
                            <tr>
                                <td>{{$data->head}}</td>
                                <td>{{$data->quantity}}</td>
                                <td>{{$data->amount}}</td>
                                <td>{{$data->total}}</td>
                                <td class="d-none">{{$data->id}}</td>
                                <td class="text-center"><button class='edit-btn btn btn-sm btn-success'>Edit</button></td>
                                <td class="text-center"><button class='delete-btn  btn btn-sm btn-danger'>Delete</button></td>
                                <td class="d-none">{{$data->id}}</td>
                            </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
@endsection



@section('script')
    <script>

        function calculate(e){
            var amount = $("#amount").val();
            var quantity = $("#quantity").val();
            $("#total").val(quantity * amount);
        }

        var client_id = "<?php echo $client_id ?>";
        var invoice_no = "<?php echo $invoice_no ?>";
        function AddFinalPercentage() {

            var url = "{{ url('add-contractor-percentage') }}" + "/" + client_id + "/" + invoice_no;
            viewModal(url);

        }




        @if (isset($type) && !empty($type))
            // Call the selectOption function automatically

            selectOption();
        @endif


        var csrfToken = $('meta[name="csrf-token"]').attr('content');




        $("#table_search").keyup(function() {

            var value = this.value.toLowerCase().trim();

            $("#bill_table tr").each(function(index) {
                if (!index) return;
                $(this).find("td").each(function() {
                    var id = $(this).text().toLowerCase().trim();
                    var not_found = (id.indexOf(value) == -1);
                    $(this).closest('tr').toggle(!not_found);
                    return not_found;

                });
            });

        });


        function tableToArray() {
            var dataArray = [];

            // Iterate through each row in the table
            $('#bill_table tbody tr').each(function(rowIndex, row) {
                var rowData = [];

                // Iterate through each cell in the row
                $(row).find('td').each(function(colIndex, cell) {
                    rowData.push($(cell).text());
                });

                dataArray.push(rowData);
            });

            return dataArray;
        }

        // Example usage
        function saveData() {

            var send_data_to_server = [];

            var tableDataArray = tableToArray();
            $.each(tableDataArray, function(index, value) {
               
                var slice_array = tableDataArray[index].slice(0, 5);

                console.log(slice_array);

                send_data_to_server.push(slice_array);
            });

            var data_length = tableDataArray.length;

            var indexNames = ["head", "quantity", "amount", "total" ,  "hidden"];

            var result = [];

            send_data_to_server.forEach(function(row) {
                var rowData = {};

                row.forEach(function(value, colIndex) {
                    var indexName = indexNames[colIndex];
                    
                        if(value == "Edit"){
                            rowData[indexName] = "";
                        }else{
                            rowData[indexName] = value;
                        }
                    
                });

                result.push(rowData);
            });

            if (data_length > 0) {

                var client_id = "<?php echo $client_id ?>";
                var jsonData = JSON.stringify({ 
                    supplier_data: result,
                    client_id : client_id
                });


                $.ajax({
                    headers: {
                        'X-CSRF-Token': csrfToken
                    },
                    url: "{{ url('insert-last-receipt') }}",
                    type: "POST",
                    data: jsonData,
                    contentType: "application/json", // Set content type to JSON
                    dataType: "json", // Expect JSON response from the server
                    success: function(data) {
                        $("#bill_table tbody").html("");
                        $("#buyer_purchaser_detail")[0].reset();
                        $("#invoice_no").val("");
                        successAlert("Quotation Successfully Created!");
                    }
                })

            }

        }






        var editedRow = null;

        $("#buyer_purchaser_detail").submit(function(event) {



            event.preventDefault();


            if ($("#type").val() == "Suppliers" && $("#amount_status").val() == "Bill") {


                // Serialize the form data
                var formData = $(this).serializeArray();

                // Extract values from the serialized form data
                var values = formData.map(function(obj) {
                    return obj.value;
                });

                if (editedRow) {    
                    console.log(values);                
                    const newArray = values.slice(13, 17);
                    // Update the values of the edited row
                    editedRow.find("td").each(function(index) {
                        var fieldName = formData[index] ? formData[index].name : null;
                        if (fieldName) {
                            $(this).text(newArray[index]);
                        }
                    });
                    editedRow = null; // Reset editedRow after updating
                    calculateSum();
                } else {
                    // Create a new row with td elements containing the form values and action buttons
                    var newRow = "<tr>";
                    values.forEach(function(value, index) {
                        var fieldName = formData[index] ? formData[index].name : null;

                        if (
                            fieldName &&
                            fieldName !== 'trn_no' &&
                            fieldName !== 'phone_no' &&
                            fieldName !== 'name' &&
                            fieldName !== 'address' &&
                            fieldName !== 'recieved_payment' &&
                            fieldName !== 'tax' &&
                            fieldName !== 'buyer_purchaser_id' &&
                            fieldName !== 'buyer_purchaser_hidden_id' &&
                            fieldName !== 'amount_status' &&
                            fieldName !== 'amount_status_hidden_value' &&
                            fieldName !== 'type' &&
                            fieldName !== 'hidden_id' &&
                            fieldName !== 'invoice_no' &&
                            fieldName !== 'hidden_supplier_id' &&
                            fieldName !== 'remarks'
                        ) {

                            if (fieldName == "total") {
                                newRow += "<td class='total'>" + value + "</td>";
                            } else {
                                newRow += "<td>" + value + "</td>";
                            }

                        }
                    });

                    // Add action buttons (edit and delete)
                    newRow += "<td class='d-none'></td><td class='text-center'><button class='edit-btn btn btn-sm btn-success'>Edit</button></td>";
                    newRow += "<td class='text-center'><button class='delete-btn  btn btn-sm btn-danger'>Delete</button></td>";

                    newRow += "</tr>";

                    // Append the new row to the tbody of the table
                    $("#bill_table tbody").append(newRow);
                }

                // Clear the form fields after saving

                calculateSum();

                // $("#amount_status").val("");
                $("#head").val("");
                $("#quantity").val("");
                $("#amount").val("");
                $("#total").val("");
                $("#remarks").val("");

            } else if ($("#type").val() == "Suppliers" && $("#amount_status").val() == "Supplier Amount Recieved") {

                var formData = new FormData(this);

                $.ajax({
                    headers: {
                        'X-CSRF-Token': csrfToken
                    },
                    url: "{{ url('supplier-amount-recieved') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {

                        $("#amount").val("");
                        $("#total").val("");
                        $("#remarks").val("");


                        toastr.success('Supplier Amount Recieved Successfully!');


                    }
                })


            } else if ($("#type").val() == "Expense") {

                var formData = new FormData(this);

                $.ajax({
                    headers: {
                        'X-CSRF-Token': csrfToken
                    },
                    url: "{{ url('insert-expense') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {

                        $("#head").val("");
                        $("#amount").val("");
                        $("#total").val("");
                        $("#hidden_id").val("");
                        $("#hidden_type").val("");

                        toastr.success('Expense Inserted Successfully!');

                    }
                })


            }


        });

        // Add event handlers for edit and delete buttons
        $(document).on("click", ".edit-btn", function() {
            // Handle edit button click
            editedRow = $(this).closest("tr");
            var rowData = editedRow.find("td").map(function() {
                return $(this).text();
            }).get();

            // Populate the form fields with the row data

            // this is hidden id value

            // $("#date").val(rowData[0]); 
            $("#head").val(rowData[0]); // Replace 'field2' with the actual ID or name of your form field
            $("#quantity").val(rowData[1]); 
            $("#amount").val(rowData[2]);// Replace 'field2' with the actual ID or name of your form field
            $("#total").val(rowData[3]);
            // Repeat for other form fields
        });

        $(document).on("click", ".delete-btn", function() {
            // Handle delete button click
            $(this).closest("tr").remove(); // Remove the closest tr
            calculateSum();
        });





        function calculateSum() {
            var sum = 0;
            // Iterate through each element with class "myClass"
            $('.total').each(function() {

                console.log($(this)[0].innerText);
                // Parse the value as a float and add it to the sum
                sum += parseFloat($(this)[0].innerText) || 0;
            });
            // Display the sum in the designated element (e.g., a div with id "sum")
            $('#sum').text(sum);
        }




        // function disableInputTage(e) {

        //     if (e.value == "Recieved") {
        //         $("#head").prop("disabled", true);
        //         $("#quantity").prop("disabled", true);
        //     } else {
        //         $("#head").prop("disabled", false);
        //         $("#quantity").prop("disabled", false);
        //     }

        //     // $("#buyer_purchaser_detail")[0].reset();

        // }



        function selectOption() {
            var get_type_value = $("#type").val();
            if (get_type_value == "Suppliers") {
                getNames();

                $(".supplier_field").removeClass("d-none");
                $("#calculation_row").removeClass("d-none");

                $("#quantity").removeClass("d-none");

                if ($("#amount_status").val() == "Supplier Amount Recieved") {
                    $("#head_row").addClass("d-none");

                }


            } else if (get_type_value == "Expense") {

                $(".supplier_field").addClass("d-none");
                $("#remarks_row").addClass("d-none");
                $("#head_row").removeClass("d-none");
                $("#calculation_row").removeClass("d-none");

                $("#quantity").addClass("d-none");
                $("#bill_table tbody").html("");
                calculateSum();

            } else {
                $(".supplier_field").removeClass("d-none");
                $("#remarks_row").addClass("d-none");
                $("#head_row").addClass("d-none");
                $("#calculation_row").addClass("d-none");
                $("#quantity").addClass("d-none");
                calculateSum();
            }

            // $("#buyer_purchaser_detail")[0].reset();
        }

        selectOption();


        $("#amount_status").on("change", function() {

            if (this.value !== "Bill") {
                // $("#remarks_row").removeClass("d-none");

                var check = confirm("Are you sure! You dont want to create a bill");
                if (check) {

                    $("#remarks_row").removeClass("d-none");
                    $("#bill_table tbody").html("");
                    $("#head_row").addClass("d-none");
                    $("#calculation_row").removeClass("d-none");
                    $("#quantity").addClass("d-none");
                } else {
                    // $("#remarks_row").addClass("d-none");
                }

            } else {

                // $("#remarks_row").addClass("d-none");
                $("#head_row").removeClass("d-none");
                $("#calculation_row").removeClass("d-none");

                $("#quantity").removeClass("d-none");
            }


        })



        function getNames() {
            //buyer_and_purchaser_name
            $("#buyer_purchaser_id").html("");
            $.ajax({
                url: "{{ url('buyer-purchaser-list') }}",
                type: "GET",
                cache: true,
                success: function(data) {

                    const selectElement = $('#buyer_purchaser_id');
                    selectElement.append('<option value="">Select Client</option>');
                    $.each(data, function(index, option) {
                        selectElement.append('<option value="' + option["id"] + '">' + option["name"] +
                            '</option>');

                    });



                }
            })

            // $.each(options, function(index, option) {
            //     selectElement.append('<option value="' + option + '">' + option + '</option>');
            // });

        }



        if ($("#head").prop("readonly")) {
            $("#buyer_purchaser_detail").rules("remove", "head");
        }

        if ($("#quantity").prop("readonly")) {
            $("#buyer_purchaser_detail").rules("remove", "quantity");
        }



        // convert small letter to capital
        function convertToUpperCase(input) {
            input.value = input.value.toUpperCase();
        }

        $(document).on("click", ".edit_buyer_purchaser_detail", function() {

            var id = $(this).data('id');

            $.ajax({
                headers: {
                    'X-CSRF-Token': csrfToken
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
                    $("#hidden_buyer_purchaser_id").val(data["id"]);

                }
            })

        });


        $(document).on("click", ".update_status_buyer_purchaser_detail", function() {

            var id = $(this).data('id');

            $.ajax({
                headers: {
                    'X-CSRF-Token': csrfToken
                },
                url: "{{ url('update-status-buyer-purchaser-detail') }}",
                type: "POST",
                data: {
                    id
                },
                success: function(data) {

                    buyer_purchaser_table.draw();

                }
            })

        });


        $("#supplier-ledger").on("click", function() {

            var url = "{{ url('select-supplier-for-ledger') }}";
            viewModal(url);

        })

        $(".toselect-tag").select2();
    </script>
@endsection
