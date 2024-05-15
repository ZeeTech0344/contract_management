<div class="row">
    <div class="col-md-6">
        <!-- Horizontal Form -->
        <div class="card card-info">
            <div class="card-header d-flex justify-content-between">
                Final Notes
                {{-- <div><a href="#" class="btn btn-sm btn-secondary" onclick="getClientList()">Client Form</a></div> --}}
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form class="form-horizontal" id="buyer_purchaser_detail">


                <div class="card-body" style="padding-bottom:0;">



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


                    <div class="form-group row supplier_field" style="display: none;">
                        <label for="name" class="col-sm-2 col-form-label ">Name</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="buyer_purchaser_id" id="buyer_purchaser_id">
                            </select>
                        </div>
                    </div>

                    <input type="hidden" name="buyer_purchaser_hidden_id" id="buyer_purchaser_hidden_id"
                        value="0">

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



                    <div class="form-group row mb-5" id="head_row">

                        <div class="col-sm-12">
                            <div id="head">

                            </div>
                        </div>
                    </div>


                    <div class="form-group row">
                        <div class="d-flex justify-content-between pb-2">
                            <button type="submit" class="btn btn-success">Save</button>

                        </div>
                    </div>

                    <input type="hidden" name="hidden_id" id="hidden_id"
                        value="{{ isset($data) && isset($type) && $type == 'Expense' ? $data->id : '' }}">
                    <input type="hidden" id="hidden_type"
                        value="{{ isset($type) && $type == 'Expense' ? $type : '' }}">
                </div>
                <!-- /.card-body -->
                <input type="hidden"
                    value="{{ isset($invoice_data) && isset($invoice_data[0]) && $invoice_data[0]->invoice_no ? $invoice_data[0]->invoice_no : '' }}"
                    id="invoice_no" name="invoice_no">

                <input type="hidden" id="client_id" name="client_id" value="{{ $client_id }}">

                <input type="hidden" name="hidden_note_id" id="hidden_note_id">
            </form>
        </div>
        <!-- /.card -->

    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div>Notes</div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">
                <table class="table table-head-fixed text-nowrap w-100" id="list-of-notes">
                    <thead class="text-center">
                        <tr>
                            <th>Client</th>
                            <th>Note</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">

                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>
<script>
    var client_id = "<?php echo $client_id; ?>";
    var list_of_notes = $('#list-of-notes').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        // paging: false,
        // "info": false,
        "language": {
            "infoFiltered": ""
        },

        ajax: {
            url: "{{ url('list-of-notes') }}" + "/" + client_id,
            data: function(d) {
                d.search_data_value = $("#search_data_value").val();
            }
        },

        columns: [

            {
                data: 'name',
                name: 'name'
            },

            {
                data: 'notes',
                name: 'notes'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ],

        success: function(data) {
            console.log(data);
        }
    });





    // Function to initialize Quill
    function initQuill() {


        quill = new Quill('#head', {
                theme: 'snow',
                placeholder: 'Write Notes Here'
            }


        );

        // Get the hidden textarea
        const editorInput = document.createElement('textarea');
        editorInput.style.display = "none";
        editorInput.setAttribute('name', 'head'); // Set the name attribute to send data through form
        editorInput.setAttribute('id', 'head');
        document.getElementById('head').appendChild(editorInput);

        // Update the hidden textarea with Quill content
        quill.on('text-change', function() {
            editorInput.value = quill.root.innerHTML;
        });





    }

    // Function to destroy Quill instance
    function destroyQuill() {
        if (quill !== null) {
            quill = null;
            $('#head').html(''); // Clear the HTML content inside the Quill container
        }
    }









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






    // convert small letter to capital
    function convertToUpperCase(input) {
        input.value = input.value.toUpperCase();
    }

    $(document).on("click", ".edit_note", function() {

        var id = $(this).data('id');

        console.log(id);

        $.ajax({
            headers: {
                'X-CSRF-Token': csrfToken
            },
            url: "{{ url('edit-final-note') }}",
            type: "POST",
            data: {
                id
            },
            success: function(data) {
                quill.root.innerHTML = data["notes"];
                $("#hidden_id").val(data["id"]);
            }
        })

    });






    $(document).on("click", ".delete_note", function() {
        var btn = $(this);
        var id = $(this).data('id');

       var element = this;

        $.ajax({
            headers: {
                'X-CSRF-Token': csrfToken
            },
            url: "{{ url('delete-final-note') }}",
            type: "POST",
            data: {
                id
            },
            success: function(data) {
                btn.closest('tr').fadeOut(400, function() {
                    // Optionally remove the <tr> after fadeOut completes
                    $(this).remove();
                });
                toastr.error('Deleted Note Successfully!');
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






    $("#buyer_purchaser_detail").submit(function(event) {

        event.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            headers: {
                'X-CSRF-Token': csrfToken
            },
            url: "{{ url('insert-final-note') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                quill.root.innerHTML = "";
                list_of_notes.draw();
                toastr.success('Notes Saved Successfully!');
            }
        })




    });
</script>
