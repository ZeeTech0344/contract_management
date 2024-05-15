@extends("old_design.main")


@section("content")
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">Quotation List</h6>
    </div>


    <div class="p-2 d-flex justify-content-end">
       <input type="text" name="search_data_value" id="search_data_value" class="form-control w-25" placeholder="Search Contractor...........">
       <button class="btn btn-sm btn-warning" style="margin-left:5px;" id="search_data">Search</button>
    </div>
    
    <div class="card-body" style="padding:0; padding-left:5px; padding-right:5px;">
        <div class="table-responsive">
           
            <table class="table get-list-of-quotation" id="dataTable" width="100%"
                cellspacing="0">
                <thead>
                    <tr>
                        <th> Name </th>
                        <th> Phone# </th>
                        <th> Account# </th>
                        <th> Address </th>
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


  @section("script")
<script>





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
                    url: "{{ url('list-of-contractor-for-detail') }}",
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
                        data: 'phone_no',
                        name: 'phone_no'
                    },
                    {
                        data: 'account_no',
                        name: 'account_no'
                    },
                    {
                        data: 'address',
                        name: 'address'
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

           


    $(document).on("click",".contractor_grand_detail", function(){

        var contractor_data = $(this).data("id");
        var createArray = contractor_data.split(',');
        var contractor_id = createArray[0];
        var contractor_name = createArray[1];
        
        var url = "{{url('view-contractor-detail')}}" + "/" + contractor_id + "/" + contractor_name;
        viewModal(url);

    })


    $("#search_data").on("click", function(){

        quotation_list.draw();
       
        
    })

</script>

  @endsection

  