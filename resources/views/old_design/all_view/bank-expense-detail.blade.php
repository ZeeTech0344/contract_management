<style>
#expense_detail{
    border:1px solid black;
    width: 100%;
}

#expense_detail td, #expense_detail th{
    border:1px solid black;
    padding:5px;
}


</style>



@php
    $total_amount = 0;
@endphp

<table id="expense_detail">
    <thead>
        <tr>
            <th>Invoice#</th>
            <th class="special">Name</th>
            <th>Bank Name</th>
            <th class="special">Purpose</th>
            <th>(Month-Advance)</th>
            <th>Amount</th>
            <th>Remarks</th>
            <th>Date/Time</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($bank_expense as $expense)
            <tr>
                <td>
                    {{ $expense->invoice_no }}
                </td>
                <td>
                    {{ $expense->getEmployee->employee_name }}
                </td>
                <td>
                    {{ $expense->bank_name }}
                </td>
                <td>
                    {{ $expense->purpose  }}
                </td>
                <td>
                    {{ $expense->paid_for_month_date ? $expense->paid_for_month_date : "-" }}
                </td>
                <td>
                    {{ $expense->amount }}
                </td>
                <td>
                    {{ $expense->remarks ? $expense->remarks : "-" }}
                </td>
                <td>
                    {{ date_format(date_create($expense->created_at), "d-m-Y") }}
                </td>
            </tr>

            @php
                 $total_amount =  $total_amount + $expense->amount;
            @endphp

        @endforeach
        <tr>
            <td colspan="11">Total Expense: {{$total_amount}} AED</td>
        </tr>
    </tbody>
</table>
