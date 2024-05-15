@extends('old_design.main')


@section('content')
    <div>
        <div class="col-12 d-flex justify-content-center">

            {{-- <div class="col-lg-6 col-sm-12"> --}}

            <div class="col-lg-12 col-sm-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between">
                        <h6 class="m-0 font-weight-bold text-white" style="text-align: left;">Salary</h6>
                        <div>
                            <a href="#" class="d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-2" id="get-paid-pdf">
                                Print</a>
                            <a href="#" class="d-sm-inline-block btn btn-sm btn-secondary shadow-sm mr-2"
                                id="get-unpaid-report">Unpaid Salary</a>

                            <a href="#" class="d-sm-inline-block btn btn-sm btn-success shadow-sm mr-2"
                                id="get-paid-report">Paid Salary</a>

                            <a href="#" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"
                                id="get-salary-detail"> Salary Report</a>



                        </div>
                    </div>
                    <div class="card-body">
                        <div>
                            <div class="row p-2 d-flex justify-content-center">
                                <div class="col col-4">
                                    <input type="month" id="month" name="month" class="form-control">
                                </div>
                                <div class="col-auto"> <!-- Wrap the buttons in a col-auto -->
                                    <input type="button" value="Generate" class="btn btn-primary" id="generate-salary">
                                    <input type="button" value="Reset" class="btn btn-secondary ml-2" onclick="reset()">
                                </div>
                            </div>
                            
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="pt-0 pb-0 d-flex justify-content-end" style="padding:1.5rem;">

        <input type="text" id="search" name="search" placeholder="Search Employee......."
            onchange="checkValues(this)" class="form-control w-25">

    </div>
    {{-- <div> --}}

    <div class="table-responsive" style="padding:22px;">
        <table class="table table-bordered table_employee_other" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Employee#</th>
                    <th>Name</th>
                    <th>Post</th>
                    <th>Salary</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
            </tbody>
        </table>
    </div>
@endsection


@section('script')
    <script>
        var employee_salary_table = $('.table_employee_other').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            paging: false,
            order: false,
            // "info": false,
            "language": {
                "infoFiltered": ""
            },

            ajax: {
                url: "{{ url('get-data-of-employee-salary') }}",
                data: function(d) {
                    d.month = $("#month").val()
                }
            },
            columns: [{
                    data: 'employee_no',
                    name: 'employee_no'
                },

                {
                    data: 'name',
                    name: 'name'
                },

                {
                    data: 'post',
                    name: 'post'
                },


                {
                    data: 'salary',
                    name: 'salary'
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


















        $("#generate-salary").click(function() {

            employee_salary_table.draw();

        })

        function reset() {
            $("#month").val('');
            employee_salary_table.draw();
        }


        $("#get-paid-report").click(function() {

            var month = $("#month")[0].value;
            if (month !== "") {
                console.log("yes");
                var url = "{{ url('get-paid-salary') }}" + "/" + month;
                viewModal(url);
            }

        })


        $("#get-unpaid-report").click(function() {

            var month = $("#month")[0].value;
            if (month !== "") {
                var url = "{{ url('get-salary-upaid-detail') }}" + "/" + month;
                viewModal(url);
            }

        })



        $("#get-salary-detail").click(function() {
            var month = $("#month")[0].value;
            if (month !== "") {
                console.log("yes");
                var url = "{{ url('get-salary-detail') }}" + "/" + month;
                viewModal(url);
            }
        })



        $(document).on("click", "#get-paid-pdf", function() {

            var salary_month = $("#month")[0].value;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('get-salary-pdf') }}",
                type: "POST",
                data: {
                    month: salary_month,
                },
                success: function(response) {

                    var printWindow = window.open('', '_blank');
                    printWindow.document.write(
                        '<html><head><style>@page { size: landscape; }</style></head><body>' +
                        response + '</body></html>');

                    setTimeout(function() {
                        printWindow.print();
                        printWindow.close();
                    }, 500);


                }
            });

        })


        $(document).on("click", ".pay_now_salary", function() {

            var get_data = $(this).data("id").split(",");
            if (get_data[6] !== "") {
                var url = "{{ url('pay-now-salary') }}" + "/" + get_data[0] + "/" + get_data[1] + "/" + get_data[
                    2] + "/" + get_data[3] + "/" + get_data[4] + "/" + get_data[5];
                mediumModal(url);
            } else {
                alert("Please updated joining date of this employee!");
            }


        })

        $("#search").keyup(function() {

            var value = this.value.toLowerCase().trim();

            $(".table_employee_other tr").each(function(index) {
                if (!index) return;
                $(this).find("td").each(function() {
                    var id = $(this).text().toLowerCase().trim();
                    var not_found = (id.indexOf(value) == -1);
                    $(this).closest('tr').toggle(!not_found);
                    return not_found;
                });
            });
        });
    </script>
@endsection
