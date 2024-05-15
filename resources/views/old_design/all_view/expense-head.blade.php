@extends('old_design.main')

@section('content')
    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary text-white">Expense Head Form</h6>
            </div>
            <div class="card-body">
                <form id="employee-form" autocomplete="off">
                    <div class="form-group">
                        <label for="exampleFormControlInput1"> Name</label>
                        <input type="text" class="form-control" id="employee_name" name="employee_name"
                            onkeyup="removeBorder(this)">
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlInput1">Status</label>
                        <select name="employee_status" id="employee_status" onchange="validate(this)" class="form-control">
                            <option>On</option>
                            <option>Off</option>
                        </select>
                    </div>

                    <div class="form-group d-flex justify-content-end">
                        <input type="submit" value="Add" class="btn btn-primary">
                    </div>
                    <input type="hidden" name="hidden_id" id="hidden_id">
                </form>

            </div>

        </div>
    </div>


    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold text-white">Expense Head List</h6>
                <div>
                    {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="generate_employee_other_report"><i
                    class="fas fa-download fa-sm text-white-50"></i>Generate Full Report</a> --}}

                    {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                        id="employee_other_reports"><i class="fas fa-download fa-sm text-white-50"></i>Generate Full
                        Report</a> --}}
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="search_value" name="search_value"
                            placeholder="Search..........">
                    </div>
                    <table class="table table-bordered employee_front_table" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Status</th>
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

    {{-- </div> --}}
    </div>
@endsection



@section('script')
    <script>
        var salary_list = $('.employee_front_table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            // paging: false,
            // "info": false,
            "language": {
                "infoFiltered": ""
            },

            ajax: {
                url: "{{ url('employee-and-head-list') }}" + "/" + "Others",
                data: function(d) {
                    d.search_value = $("#search_value").val();
                    d.session = $("#session").val();
                }
            },

            columns: [{
                    data: 'employee_name',
                    name: 'employee_name'
                },

                {
                    data: 'employee_status',
                    name: 'employee_status'
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



        $('#employee-form').submit(function(event) {
            event.preventDefault(); // Prevent the default form submission
            var formData = new FormData(this);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('insert-expense-head') }}",
                type: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    console.log(data);
                    $('#employee-form')[0].reset();
                    $("#employee_hidden_id").val("");
                    successAlert("Head Saved Successfully!");
                    salary_list.draw();
                },
                error: function(data) {
                    // Handle error if needed
                }
            });
        });



        $("#search_value").on('keyup', function(e) {

            if (e.key === 'Enter' || e.keyCode === 13) {
                salary_list.draw();
            }
        });



        $(document).on("click", ".edit", function() {
            var id = $(this).data("id");

            $.ajax({
                url: "{{ url('edit-expense-or-employee') }}",
                type: "get",
                data: {
                    id: id
                },
                success: function(data) {
                    $("#employee_name").val(data["employee_name"]);
                    $("#employee_status").val(data["employee_status"]);
                    $("#hidden_id").val(data["id"]);
                }
            })
        })



        
    $(document).on("click", ".delete", function() {
        var btn = $(this);
        var id = $(this).data('id');
       var element = this;

        $.ajax({
            headers: {
                'X-CSRF-Token': csrfToken
            },
            url: "{{ url('delete-expense-or-employee') }}",
            type: "POST",
            data: {
                id
            },
            success: function(data) {
                btn.closest('tr').fadeOut(400, function() {
                    // Optionally remove the <tr> after fadeOut completes
                    $(this).remove();
                });
                toastr.error('Deleted Expense Successfully!');
            }
        })

    });




        function removeBorder() {

        }
    </script>
@endsection
