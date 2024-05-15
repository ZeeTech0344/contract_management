@extends('old_design.main')


@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">Quotation List</h6>
        </div>

        <div class="p-2 d-flex justify-content-center align-items-center">
            <div class="d-flex ml-auto">
                <label for="" class="p-2 text-info">Search: </label>
                <input type="text" id="search_data_value" name="search_data_value" class="form-control mr-2 text-info">
                <label for="" class="p-2 text-info">From:</label>
                <input type="date" id="from_date" name="from_date" class="form-control mr-2 text-info"
                    onchange="searchData(this)">
                <label for="" class="p-2 text-info">To:</label>
                <input type="date" id="to_date" name="to_date" class="form-control mr-2 text-info"
                    onchange="searchData(this)">
                <label for="" class="p-2 text-info">Status: </label>
                <select name="status" id="status" class="form-control text-info" onchange="searchData(this)">
                    <option value="">Select Status</option>
                    <option value="0">Not Approved</option>
                    <option value="1">Approved</option>
                </select>
                <input type="button" class="btn btn-sm btn-warning" id="search_data" name="search_data" value="Search"
                    style="margin-left:5px;">
                <input type="button" class="btn btn-sm btn-primary" id="get_view" name="get_view"
                    onclick="getViewOrPdf(this)" value="View" style="margin-left:5px;">

                <input type="button" class="btn btn-sm btn-danger" id="get_pdf" name="get_pdf"
                    onclick="getViewOrPdf(this)" value="PDF" style="margin-left:5px;">

                <input type="button" class="btn btn-sm btn-secondary" id="get_profit_and_loss" name="get_profit_and_loss"  value="Profit & Loss Report" style="margin-left:5px;">
            </div>
        </div>



        <div class="card-body">
            {{-- here we set table responsive --}}
            <div class="">

                <table class="table get-list-of-quotation" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th> Invoice# </th>
                            <th> Name </th>
                            <th> Phone# </th>
                            <th> Address </th>
                            <th> Date/Time </th>
                            <th class="text-center"> Status </th>
                            <th> Action </th>
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
        function getViewOrPdf(e) {

            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();
            var search_data_value = $("#search_data_value").val();
            var status = $("#status").val();

            if (e.id == "get_pdf") {
                var url_get = "{{ url('get-quotation-pdf') }}" + "/pdf";
            } else if (e.id == "get_view") {
                var url_get = "{{ url('get-quotation-pdf') }}" + "/view";
            }

            $.ajax({
                url: url_get,
                type: 'GET',
                data: {
                    from_date: from_date,
                    to_date: to_date,
                    search_data_value: search_data_value,
                    status: status
                },
                success: function(response) {

                    if (e.id == "get_pdf") {
                        // Create a temporary link element
                        var link = document.createElement('a');
                        link.href = 'data:application/pdf;base64,' + response.pdf_data;
                        link.download = 'profile.pdf';

                        // Trigger the download
                        document.body.appendChild(link);
                        link.click();
                        // Cleanup
                        document.body.removeChild(link);
                    } else if (e.id == "get_view") {
                        $('#mediumModal').modal('show');
                        $('#mediumModalLabel').html(response["title"]);
                        $('#mediumModalview').html(response["view"]);
                    }
                }
            });

        }






        $("#get_profit_and_loss").on("click", function() {

            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();

            console.log("yes");
           
            var url = "{{ url('get-profit-and-loss-report') }}" + "/" + from_date + "/" + to_date;
            fullScreenModal(url);

        })



        $(document).on("click", ".daily-work", function() {

            var client_id = $(this).data("id");

            var url = "{{ url('daily-work') }}" + "/" + client_id;
            fullScreenModal(url);

        })


        $(document).on("click", ".delete_invoice", function() {

            var invoice_no = $(this).data("id");

            var element = this;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('delete-invoice') }}",
                type: "GET",
                data: {
                    invoice_no: invoice_no
                },
                success: function(data) {

                    $(element).parent().parent().parent().parent().fadeOut();


                }
            })

        })


        $(document).on("click", ".final-notes", function() {

            var get_client_id = $(this).data("id");
            var url = "{{ url('final-notes') }}" + "/" + get_client_id;
            viewModal(url);

        })



        $('#extraLargeModal').on('shown.bs.modal', function() {
            initQuill();
        });

        // Code to destroy Quill when modal is hidden
        $('#extraLargeModal').on('hidden.bs.modal', function() {
            destroyQuill();
        });


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
                url: "{{ url('get-list-of-quotation') }}",
                data: function(d) {
                    d.search_data_value = $("#search_data_value").val();
                    d.status = $("#status").val();
                    d.from_date = $("#from_date").val();
                    d.to_date = $("#to_date").val();
                }
            },

            columns: [

                {
                    data: 'invoice_no',
                    name: 'invoice_no'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'phone_no',
                    name: 'phone_no'
                },
                {
                    data: 'address',
                    name: 'address'
                },
                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'status',
                    name: 'status'
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



        $("#search_data").on("click", function() {

            quotation_list.draw();

        })
    </script>
@endsection
