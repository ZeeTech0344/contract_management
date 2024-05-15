
<style>
    #quotation-pdf {
        width: 100%;
        border:1px solid rgb(139, 138, 138);
        border-collapse: collapse;
    }

    #quotation-pdf th,
    #quotation-pdf td {
        border:1px solid rgb(139, 138, 138);
        padding: 5px;
        text-align: center;
    }

    .special{
        text-align: left !important;
    }
</style>



@php
    $total_amount = 0;

    $total_profit = 0;
@endphp

<table id="quotation-pdf">
    <thead>
        <tr>
            <th>Invoice#</th>
            <th class="special">Name</th>
            <th>Phone#</th>
            <th class="special">Address</th>
            <th>Status</th>
            <th>T_Amount</th>
            <th>Expense</th>
            <th>Contract(%)</th>
            <th>Pay_To_Contract</th>
            <th>Profit/Loss</th>
            <th>Date/Time</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $quotation_data)
            <tr>
                <td>{{ $quotation_data->invoice_no }}</td>
                <td class="special">{{ $quotation_data->client_name }}</td>
                <td>{{ $quotation_data->client_phone }}</td>
                <td class="special">{{ $quotation_data->client_address}}</td>
                <td>{{$quotation_data->get_status == 1 ? 'Approved' : 'Not Approved'}}</td>
                <td>{{ $quotation_data->get_invoice_data_sum_total}}</td> 
                <td>{{  $quotation_data->get_expense_sum_total }}</td>
                <td>{{ $quotation_data->get_percentage_sum }}</td>
                <td>{{ ($quotation_data->get_invoice_data_sum_total /100 *$quotation_data->get_percentage_sum) }}</td>
                <td>
                    @php
                        $profit_or_loss = ( $quotation_data->get_invoice_data_sum_total - ($quotation_data->get_invoice_data_sum_total /100 *$quotation_data->get_percentage_sum) - $quotation_data->get_expense_sum_total);
                    @endphp
                  {{ $profit_or_loss}}
                </td>
                <td> {{ date_format(date_create($quotation_data->get_date), "d-m-Y")}}</td>
            </tr>

            @php
                $total_amount = $total_amount + $quotation_data->total_amount;

                $total_profit = $total_profit +  $profit_or_loss;
            @endphp
        @endforeach
        <tr>
            <td colspan="11">Total Profit: {{$total_profit }} AED</td>
        </tr>
    </tbody>
</table>


