@extends('old_design.main')

@section('content')
    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary text-white">Employee Form</h6>
            </div>
            <div class="card-body">
                <form id="employee-form" autocomplete="off">
                    <div class="form-group">
                        <label for="exampleFormControlInput1"> Name</label>
                        <input type="text" class="form-control" id="employee_name" name="employee_name"
                            onkeyup="removeBorder(this)">
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlInput1"> DOB</label>
                        <input type="date" class="form-control" id="dob" name="dob"
                            onkeyup="removeBorder(this)">
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlInput1">Post</label>
                        <select name="employee_post" id="employee_post" class="form-control" onkeyup="removeBorder(this)">
                            <option value="">Select Post</option>
                            <option>Accountant</option>
                            <option>Office Boy</option>
                            <option>Clerk </option>
                            <option>Owner</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlInput1">Salary</small></label>
                        <input type="number" class="form-control" id="basic_sallary" name="basic_sallary"
                            onkeyup="removeBorder(this)">
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlInput1">ID #</label>
                        <input type="text" class="form-control" id="cnic" name="cnic"
                            onkeyup="removeBorder(this)">
                    </div>


                    <div class="form-group">
                        <label for="exampleFormControlInput1"> Phone#</label>
                        <input type="text" class="form-control" id="phone_no" name="phone_no"
                            onkeyup="removeBorder(this)">
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlInput1">Father Name</label>
                        <input type="text" class="form-control" id="father_name" name="father_name"
                            onchange="removeBorder(this)">
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlInput1">Father CNIC</label>
                        <input type="text" class="form-control" id="father_cnic" name="father_cnic"
                            onchange="removeBorder(this)">
                    </div>


                    <div class="form-group">
                        <label for="exampleFormControlInput1"> Date of Joining </label>
                        <input type="date" class="form-control" id="joining" name="joining"
                            onchange="removeBorder(this)">
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlInput1"> Date of Leaving</label>
                        <input type="date" class="form-control" id="leaving" name="leaving"
                            onchange="removeBorder(this)">
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlInput1" class="col-form-label">Photo</label>
                        <div class="row">

                            <div class="col pl-0">
                                <input type="file" class="form-control" id="image" name="image"
                                    onchange="displayImage(this)">
                            </div>
                            <div class="col-auto pr-0">
                                <img id="image_name" class="d-none" width="70px" height="70px"
                                    style="margin-right: 10px;">
                            </div>
                        </div>
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
                    <input type="hidden" name="employee_hidden_id" id="employee_hidden_id">
                </form>

            </div>

        </div>
    </div>


    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold text-white">Employee List</h6>
                <div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="search_value" name="search_value"
                            placeholder="Search..........">
                    </div>
                    <table class="table table-bordered employee_front_table" id="dataTable" width="100%"
                        cellspacing="0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Salary</th>
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
                url: "{{ url('employee-and-head-list') }}" + "/" + "Employee",
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
                    data: 'phone_no',
                    name: 'phone_no'
                },
                {
                    data: 'salary',
                    name: 'salary'
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
                url: "{{ url('insert-employee-and-head') }}",
                type: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    console.log(data);
                    $('#employee-form')[0].reset();

                    $("#image_name")[0].classList.add("d-none");
                    $("#employee_hidden_id").val("");
                    successAlert("Employee Saved Successfully!");
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
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('edit-employee') }}",
                type: "GET",
                data: {
                    id: id
                },
                success: function(data) {


                    $("#employee_name").val(data[0]["employee_name"]);
                    $("#employee_post").val(data[0]["employee_post"]);
                    $("#basic_sallary").val(data[0]["basic_sallary"]);
                    $("#cnic").val(data[0]["cnic"]);
                    $("#dob").val(data[0]["dob"]);
                    $("#phone_no").val(data[0]["phone_no"]);
                    $("#joining").val(data[0]["joining"]);
                    $("#leaving").val(data[0]["leaving"]);
                    $("#name").val(data[0]["name"]);
                    $("#password").val(data[0]["password"]);
                    $("#image_name").attr("src", "{{ asset('images') }}" + "/" + data[0]["image"]);
                    $("#image_name")[0].classList.remove("d-none");
                    $("#employee_status").val(data[0]["employee_status"]);
                    $("#employee_hidden_id").val(data[0]["id"]);

                }
            })

        })






        $(document).on("click", ".view_profile", function() {

            var id = $(this).data("id");
            var url = "{{ url('view-employee-profile') }}" + "/" + id;
            mediumModal(url);

        })


        function removeBorder() {

        }
    </script>
@endsection
