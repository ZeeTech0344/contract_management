<style>
    .salary_grand_table {
        width: 100%;
        border: 1px solid rgb(214, 212, 212);
        border-collapse: collapse;
    }

    .salary_grand_table td,
    .salary_grand_table th {
        border: 1px solid rgb(214, 212, 212);
        padding: 5px;

    }
</style>


@php

    $grand_advance_total = 0;
    $total_salary = 0;
    $total_deduction_day_of_work = 0;

    $total_addition_in_salary = 0;

    $total_paid_salary = 0;

@endphp

<h5 style="text-align: center;">Salary Detail {{ date('M-Y', strtotime($month)) }}</h5>
<table class="salary_grand_table">
    <tr>
        <th>No</th>
        <th>Name</th>
        <th>Post</th>
        <th>Joining</th>
        <th>B_Salary</th>
        <th>Advance</th>
        <th>Pending</th>
        <th>Deduct</th>
        <th>Add</th>
        <th>DOW</th>
        <th>Remarks</th>
        <th>Paid Salary</th>

    </tr>

    @foreach ($salary_detail as $salary)
        @php
            $total_advance = 0;
        @endphp
        <tr>




            @php
                foreach ($salary->bank as $get_amount_easypaisa) {
                    $total_advance = $total_advance + $get_amount_easypaisa->amount;
                }

            @endphp
            @php

                $total_salary = $total_salary + $salary->basic_sallary;
            @endphp
            <td>{{ $salary->employee_no }}</td>
            <td>{{ $salary->employee_name }}</td>
            <td>{{ $salary->employee_post }}</td>
            <td>{{ date_format(date_create($salary->joining), 'd-m-Y') }}</td>
            <td>{{ $salary->basic_sallary }}</td>
            <td>
                {{ $total_advance }}
                @php
                    $grand_advance_total = $grand_advance_total + $total_advance;
                @endphp
            </td>
            @foreach ($salary->salary as $get_salary_detail)
                @php
                    $total_deduction_day_of_work =
                        $total_deduction_day_of_work + $get_salary_detail->day_of_work_deduction;
                    $total_addition_in_salary = $total_addition_in_salary + $get_salary_detail->addition;
                    $total_paid_salary = $total_paid_salary + $get_salary_detail->amount;
                @endphp
                <td>{{ $get_salary_detail->pendings ? $get_salary_detail->pendings : '-' }}</td>
                <td>{{ $get_salary_detail->day_of_work_deduction ? $get_salary_detail->day_of_work_deduction : '-' }}
                </td>
                <td>{{ $get_salary_detail->addition ? $get_salary_detail->addition : '-' }}</td>
                <td>{{ $get_salary_detail->day_of_work ? $get_salary_detail->day_of_work : '-' }}</td>
                <td>{{ $get_salary_detail->remarks ? $get_salary_detail->remarks : '-' }}</td>
                <td>{{ $get_salary_detail->amount ? $get_salary_detail->amount : '-' }}</td>
            @endforeach
        </tr>
    @endforeach


    <tr>
        <td colspan="12">
            <b>Total Salary (Basic): {{ number_format($total_salary, 2) }}</b>
        </td>
    </tr>
    <tr>
        <td colspan="12">
            <b> Total Salary (Paid) : {{ number_format($total_paid_salary, 2) }}</b>
        </td>
    </tr>
    {{-- <tr>
        <td colspan="12">
            <b>Total Salary (Unpaid): {{ number_format($total_salary - $total_paid_salary) }}</b>
        </td>
    </tr> --}}



</table>
