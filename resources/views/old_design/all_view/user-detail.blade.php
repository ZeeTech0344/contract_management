@extends('old_design.main')


@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">Users List</h6>
        </div>

        <div class="p-2 d-flex justify-content-end">
            <div class="d-flex">
                <input type="text" name="search_user" placeholder="Search ............" id="search_user" class="form-control"
                    style="margin-right:10px;">
                {{-- <input type="button" class="btn btn-sm btn-info" id="search_button" value="Search"> --}}
                <button class="btn btn-sm btn-info" id="search_button">Search</button>
            </div>
        </div>



        <div class="card-body">
            <div class="table-responsive">

                <table class="table get-list-of-quotation" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th> Name </th>
                            <th> Email</th>
                            <th class="text-center"> Date/Time </th>
                            <th class="text-center"> Role </th>
                            <th class="text-center"> Action </th>
                        </tr>
                    </thead>

                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script>
        $(document).on("change", ".role_id", function() {

            // Get the value of the checked radio button
            var selectedValue = $("input[name='" + $(this).attr('name') + "']:checked").val();
            var splitArray = selectedValue.split(',');
            var role = splitArray[0];
            var user_id = splitArray[1];

            var confirm_role = confirm("Are you your to change role???");

            if (confirm_role) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('update-user-role') }}",
                    type: "POST",
                    data: {
                        user_id: user_id,
                        role: role
                    },
                    success: function(data) {

                        successAlert("User Role Updated!");


                    }
                })
            }

        })




        var quotation_list = $('.get-list-of-quotation').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            // paging: false,
            // "info": false,
            "language": {
                "infoFiltered": ""
            },

            ajax: {
                url: "{{ url('users-list') }}",
                data: function(d) {
                    d.status = $("#status").val();
                    d.from_date = $("#from_date").val();
                    d.to_date = $("#to_date").val();
                }
            },

            columns: [

                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'role',
                    name: 'role'
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

        function searchData(e) {
            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();
            var status = $("#status").val();

            console.log(from_date);
            if ((from_date !== "" && to_date !== "") || status !== "") {
                quotation_list.draw();
            }

        }
    </script>
@endsection
