{{-- @php

    echo '<pre>';
    print_r($contractors);
    echo '</pre>';

@endphp --}}


<style>
    #contractor_info_table {
        border: 1px solid rgb(195, 195, 195);
        width: 100%;
    }

    #contractor_info_table td,
    #contractor_info_table th {
        border: 1px solid rgb(195, 195, 195);
        padding: 10px !important;
    }
</style>

<div class="table-responsive">
<table id="contractor_info_table">
    <thead>
        <tr>
            <th>ID</th>
            <th>C_Name</th>
            <th>Phone#</th>
            <th class="text-center">Total Amount + (Tax %)</th>
            <th class="text-center">Total Expense</th>
            <th class="text-center">Percentage</th>
            <th class="text-center">Pay Amount</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>

    @php
        $sr = 1;
        $total_amount = 0;
        $total_contractor_amount = 0;

        $owner_amount = 0 ;
    @endphp
    <tbody>
        @foreach ($contractors as $contractor_data)
            <tr>
                <td class="text-center">{{$sr++}}</td>
                <td>{{$contractor_data->getClientData->name}}</td>
                <td>{{$contractor_data->getClientData->phone_no}}</td>
                @php
                    $dataArray = json_decode($contractor_data->getClientData->getInvoiceData, true);
                    $filteredArray = array_filter($dataArray, function($item) {
                        // Return true if the condition matches, otherwise false
                        return $item['include_or_exclude'] === 1; // Filtering for type A
                    });
                    // Extract 'amount' column from the filtered array
                    $amounts = array_column($filteredArray, 'amount');
                    $totalSum = array_sum($amounts);

                    $tax_amount = $totalSum/ 100 *$contractor_data->getClientData->tax;

                    $total_amount = $totalSum + $tax_amount;

                    $owner_amount = $owner_amount + $total_amount;

                    $total_payed_amount = $total_amount/100*$contractor_data->percentage;

                    //expense detail
                    $expense = json_decode($contractor_data->getClientData->getExpense, true);
                    
                    $expense_amounts = array_column($expense, 'amount');
                    $total_expense_sum = array_sum($expense_amounts);
                @endphp
                <td class="text-center">{{ $total_amount}}</td>
                <td class="text-center">{{ $total_expense_sum}}</td>
                <td class="text-center">{{$contractor_data->percentage."%"}}</td>
                <td class="text-center">{{$total_payed_amount}}</td>
                <td class="text-center"><button data-id={{$contractor_data->getClientData->id.",".$contractor_data->getClientData->getInvoiceData[0]->invoice_no}} class="btn btn-sm btn-danger view-contractor-detail">View</button></td>
            </tr>

            @php
                $total_contractor_amount =  $total_contractor_amount  + $total_payed_amount;

                
            @endphp
 
        @endforeach
        <tr>
            <td colspan="7" class="text-end"><b>Total Payed Amount: </b></td><td><b>{{$total_contractor_amount}} AED</b></td>
        </tr>

        <tr>
        <td colspan="7" class="text-end"><b>Total Owner Profit: </b></td><td><b>{{ $owner_amount - $total_contractor_amount - $total_expense_sum }} AED</b></td>
        </tr>
    </tbody>
</table>
</div>

<script>


$(".view-contractor-detail").on("click", function(){

    var contractor_particulars = $(this).data("id");
    var array = contractor_particulars.split(",");
    var client_id = array[0];
    var invoice_no = array[1];

    var url = "{{url('view-contractor-final-receipt')}}" + "/" + client_id + "/" + invoice_no;

    window.location.href="{{url('view-contractor-final-receipt')}}" + "/" + client_id + "/" + invoice_no;
    // mediumModal(url);

})

</script>
