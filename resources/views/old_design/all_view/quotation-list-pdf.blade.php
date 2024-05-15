

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

@endphp
@if($type !== "view")
    <h4 style="text-align: center;">Quotation List ({{date_format(date_create($from_date), "d-m-Y")}} to {{date_format(date_create($to_date), "d-m-Y")}})</h4>
@endif
<table id="quotation-pdf">
    <thead>
        <tr>
            <th>Invoice#</th>
            <th class="special">Name</th>
            <th>Phone#</th>
            <th class="special">Address</th>
            <th>T_Amount</th>
            <th>Status</th>
            <th>Date/Time</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $quotation_data)
            <tr>
                <td>{{ $quotation_data->invoice_no }}</td>
                <td class="special">{{ $quotation_data->name }}</td>
                <td>{{ $quotation_data->phone_no }}</td>
                <td class="special">{{ $quotation_data->address }}</td>
                <td>{{ $quotation_data->total_amount}}</td>
                <td>{{ $quotation_data->status == 1 ? 'Approved' : 'Not Approved' }}</td> 
                <td>{{ date_format(date_create($quotation_data->created_at), 'd-m-Y') }}</td>
            </tr>

            @php
                $total_amount = $total_amount + $quotation_data->total_amount;
            @endphp
        @endforeach
        <tr>
            <td colspan="7">Total Amount: {{$total_amount }} AED</td>
        </tr>
    </tbody>
</table>


