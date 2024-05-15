

<style>
    #suppler_table_view th{
        text-align: left;
        border:1px solid rgb(203, 200, 200);
        padding:10px;
    }
    #suppler_table_view td{ 
        border:1px solid rgb(203, 200, 200);
        padding:10px;
    }
    #suppler_table_view{
        border-collapse: collapse;
        width: 100%;
    }
   

</style>

<table id="suppler_table_view">
    <tbody>
            <tr>
                <th>Name</th><td>{{ $supplier->name }}</td>
            </tr>
            <tr>
                <th>Phone#</th><td>{{ $supplier->phone_no }}</td>
            </tr>
            <tr>
                <th>A/C#</th><td>{{ $supplier->account_no }}</td>
            </tr>
            {{-- <tr>
                <th>CNIC</th><td>{{ $supplier->cnic }}</td>
            </tr> --}}
            <tr>
                <th>Address</th><td>{{ $supplier->address }}</td>
            </tr>
            
            
       
    </tbody>
</table>
