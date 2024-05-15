

<style>
    tr,td{
        border:1px solid #dbdbdb;
        padding: 10px;
    }
    table{
        border:1px solid #dbdbdb;
        border-collapse: collapse;
        width: 100%;
    }
    img{
        border:1px solid #dbdbdb;
        padding: 3px;
    }
</style>

<table>
    <tr>
        <td colspan="2" style="text-align: center;">
            <img src="{{ asset('images/'.$employees->image) }}" style="width: 150px;">
        </td>
    </tr>
    <tr>
        <td>Name</td>
        <td>{{ $employees->employee_name }}</td>
    </tr>

    <tr>
        <td>DOB</td>
        <td>{{ $employees->dob }}</td>
    </tr>

    <tr>
        <td>Phone#</td>
        <td>{{ $employees->phone_no }}</td>
    </tr>
    <tr>
        <td>ID Card#</td>
        <td>{{ $employees->cnic }}</td>
    </tr>
    <tr>
        <td>Father Name</td>
        <td>{{ $employees->father_name }}</td>
    </tr>
    <tr>
        <td>Father CNIC#</td>
        <td>{{ $employees->father_cnic }}</td>
    </tr>

    <tr>
        <td>Salary</td>
        <td>{{ $employees->basic_sallary }}</td>
    </tr>

    <tr>
        <td>Joining</td>
        <td>{{ $employees->joining }}</td>
    </tr>

    <tr>
        <td>Leaving</td>
        <td>{{ $employees->leaving }}</td>
    </tr>
    <tr>
        <td>Status</td>
        <td>{{ $employees->employee_status }}</td>
    </tr>

</table>
