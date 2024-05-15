<div class="row">
    <div class="col-md-4">
        <!-- Horizontal Form -->
        <div class="card card-info">
            <div class="card-header d-flex justify-content-between">
                Daily Work
            </div>
            <form class="form-horizontal" id="daily_work_form">


                <div class="card-body" style="padding-bottom:0;">

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label ">Scope</label>
                        <div class="col-sm-9">
                            <Select class="form-control" name="scope_id" id="scope_id">
                                <option value="">Select Scope</option>
                                @foreach ($scopes as $scope)
                                    <option value="{{ $scope->id }}">{{ $scope->scope }}</option>
                                @endforeach
                            </Select>
                        </div>
                    </div>


                    {{-- <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label ">Value</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="value" id="value">
                        </div>
                    </div> --}}




                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label ">Time</label>
                        <div class="col-sm-9">
                            <input type="time" class="form-control" name="time_of_work" id="time_of_work">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label ">Team</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="team" id="team">
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label ">Amount</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="amount" id="amount">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label ">Amt. Type</label>
                        <div class="col-sm-9">
                            <select name="amount_type" id="amount_type" class="form-control">
                                <option value="">Amount Type</option>
                                <option>Cash</option>
                                <option>Online</option>
                            </select>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label ">Recieved By</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="recieved_by" id="recieved_by">
                        </div>
                    </div>




                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label ">Remarks</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="remarks" id="remarks">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label "></label>
                        <div class="col-sm-9 d-flex justify-content-end">
                            <input type="submit" class="btn btn-sm btn-success" value="Submit">
                        </div>
                    </div>


                    <input type="hidden" id="daily_work_hidden_id" name="daily_work_hidden_id">
                </div>

            </form>
        </div>
        <!-- /.card -->

    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div>Daily Works List</div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">
                <table class="table table-head-fixed text-nowrap w-100" id="list-of-notes">
                    <thead class="text-center">
                        <tr>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Scope</th>
                            <th>Time</th>
                            <th>Team</th>
                            <th>Amount</th>
                            <th>Amt_Type</th>
                            <th>Rec. dBy</th>
                            <th>Remarks</th>
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

    $("#daily_work_form").submit(function(event) {

        event.preventDefault();

        var formData = new FormData(this);
        formData.append('client_id', client_id);

        $.ajax({
            headers: {
                'X-CSRF-Token': csrfToken
            },
            url: "{{ url('insert-daily-work') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                toastr.success('Daily Work Saved Successfully!');
                $("#daily_work_hidden_id").val("");
                list_of_daily_work.draw();

            }
        })




    });


    var list_of_daily_work = $('#list-of-notes').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        // paging: false,
        // "info": false,
        "language": {
            "infoFiltered": ""
        },

        ajax: {
            url: "{{ url('daily-work-list') }}" + "/" + client_id,
            data: function(d) {
                d.search_data_value = $("#search_data_value").val();
            }
        },

        columns: [

            {
                data: 'created_at',
                name: 'created_at'
            },
            {
                data: 'client',
                name: 'client'
            },

            {
                data: 'scope',
                name: 'scope'
            },

            {
                data: 'time_of_work',
                name: 'time_of_work'
            },

            {
                data: 'team',
                name: 'team'
            },

            {
                data: 'amount',
                name: 'amount'
            },

            {
                data: 'amount_type',
                name: 'amount_type'
            },

            {
                data: 'recieved_by',
                name: 'recieved_by'
            },

            {
                data: 'remarks',
                name: 'remarks'
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

    $(document).on("click", ".edit_daily_work", function() {

        var id = $(this).data('id');

        $.ajax({
            url: "{{ url('edit-daily-work') }}",
            type: "GET",
            data: {
                id
            },
            success: function(data) {
                $("#scope_id").val(data["scope_id"]);
                $("#time_of_work").val(data["time_of_work"]);
                $("#team").val(data["team"]);
                $("#amount").val(data["amount"]);
                $("#amount_type").val(data["amount_type"]);
                $("#recieved_by").val(data["recieved_by"]);
                $("#remarks").val(data["remarks"]);
                $("#daily_work_hidden_id").val(data["id"]);
            }
        })

    });






    $(document).on("click", ".delete_daily_work", function() {
        var btn = $(this);
        var id = $(this).data('id');

        var element = this;

        $.ajax({
            headers: {
                'X-CSRF-Token': csrfToken
            },
            url: "{{ url('delete-daily-work') }}",
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
                // quill.root.innerHTML = "";
                list_of_notes.draw();
                toastr.success('Notes Saved Successfully!');
            }
        })




    });

    function initQuill() {
        // Your function implementation here
    }

    function destroyQuill() {
        // Your function implementation here
    }
</script>
