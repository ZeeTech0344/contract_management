
@extends("old_design.main")


@section("content")




<div class="invoice-container">
    <div class="invoice-header text-center">
        <h2><i class="fas fa-file-invoice"></i>FinaReceipt</h2>
        <h3>All City Technical Service L.L.C</h3>
        <p style="color: #4CAF50; font-size: 18px;"><i class="far fa-calendar-alt"></i> Receipt Date: {{date_format(date_create($data[0]->created_at),"F j, Y")}}</p>
    </div>

    <div class="invoice-details">
        <div class="col">
            <h5><i class="fas fa-user"></i> Final Receipt: Ref# Quotation: {{$data[0]->invoice_no}}</h5>
        </div>
    </div>

    <div class="invoice-items table-responsive">
        <h5><i class="fas fa-tools"></i> Quotation</h5>
        <table class="table">
            <thead>
                <tr>
                    <th>Sr#</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                
                @php
                $sr=1;
                $total= 0;
            @endphp
          @foreach ($data as $get_data)
              <tr>
                <td>{{$sr++}}</td>
                <td>{{$get_data->head}}</td>
                <td class="text-center">{{$get_data->quantity}}</td>
                <td class="text-center">{{number_format($get_data->amount)}}</td>
                <td class="text-center">{{number_format($get_data->total)}}</td>
              </tr>
              @php
                  $total = $total + $get_data->total;
                  
              @endphp
          @endforeach

          @php

              $recieved_payment = $data[0]->getOneRecordClient->recieved_payment;
              $tax = $data[0]->getOneRecordClient->tax;

              $calculation_after_tax = $total * ($tax / 100);

              $total_amount =  $total +  $calculation_after_tax;

              $balance = $total_amount - $recieved_payment;



          @endphp

          <tr>
            <td colspan="4" class="calculations" style="text-align: right;"><b>Grand Total</b></td><td class="text-center">{{ number_format($total)}}</td>
          </tr>
          <tr>
            <td colspan="4"   class="calculations" style="text-align: right;"><b>Recieved Payment</b></td><td class="text-center">{{ number_format($recieved_payment)}}</td>
          </tr>
          <tr>
            <td colspan="4"  class="calculations" style="text-align: right;"><b>VAT Tax</b></td><td class="text-center">{{ number_format($tax)}} % ({{$calculation_after_tax}})</td>
          </tr>
          <tr>
            <td colspan="4"  class="calculations" style="text-align: right;"><b>Balance</b></td><td class="text-center" id="invoice_total_in_number">{{ number_format($balance )}}</td>
          </tr>
            </tbody>
        </table>
    </div>

    <div>
        <p id="invoice-total" style="text-transform: uppercase; text-decoration:underline;"></p>
    </div>



   
    
    <div class="invoice-items table-responsive">
        <h5> <i class="fas fa-receipt"></i>  Expense</h5>
        <table class="table">
            <thead>
                <tr>
                    <th>Sr#</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                
                @php
                $sr=1;
                $total_expense= 0;
            @endphp

         
          @foreach ($expense_record as $get_data)
              <tr>
                <td>{{$sr++}}</td>
                <td>{{$get_data->head}}</td>
                <td class="text-center">-</td>
                <td class="text-center">{{number_format($get_data->amount)}}</td>
                <td class="text-center">{{number_format($get_data->amount)}}</td>
              </tr>
              @php
                  $total_expense = $total_expense + $get_data->amount;
                  
              @endphp
          @endforeach

          @php

              $recieved_payment = $data[0]->getOneRecordClient->recieved_payment;
              $tax = $data[0]->getOneRecordClient->tax;

              $calculation_after_tax = $total_expense * ($tax / 100);

              $total_amount =  $total_expense +  $calculation_after_tax;

              $balance = $total_amount - $recieved_payment;

          @endphp
          <tr>
            <td colspan="4" class="calculations" style="text-align: right;"><b>Grand Total</b></td><td class="text-center">{{ number_format($total_expense)}}</td>
          </tr>

          <tr>
            <td colspan="4" class="calculations" style="text-align: right;"><b>Total Amount - Total Expense = Profit </b></td><td class="text-center">{{ number_format($total - $total_expense)}}</td>
          </tr>

          @php
              $total_percentage = 0;
              $total_pay = 0;
          @endphp
          @foreach ($contractor_info as $partnership)
          <tr>

            @php
               $total_pay_to_each_contractor =  ($total - $total_expense)/100 *$partnership->percentage;
            @endphp
            <td colspan="4" style="text-align: right;"><b>Pay To: {{$partnership->getContractor->name}} </b></td><td class="text-center"> {{$total_pay_to_each_contractor }} ({{ number_format($partnership->percentage)}}%)</td>
          </tr>
          @php
              $total_pay = $total_pay + $total_pay_to_each_contractor;
          @endphp
          @endforeach
          <tr>
            <td colspan="4"style="text-align: right;"><b>Remaining</b></td><td class="text-center">{{ number_format(($total - $total_expense)- $total_pay)}}</td>
          </tr>
            </tbody>
        </table>
    </div>

    <div>
        <p id="invoice-total" style="text-transform: uppercase; text-decoration:underline;"></p>
    </div>


    <div class="container mt-5 text-danger term_and_condition">
        <h5 class="text-right" style="text-decoration: underline;">Terms & Conditions</h5>
        
        <p class="text-justify">This is a computer-generated document. No signature required on our part.</p>
    
        <p class="text-justify">Please issue the cheque in favor of <strong>ALL CITY TECHNICAL SERVICES L.L.C</strong>.</p>
    
        <p class="text-justify">Any type of work that is not mentioned in the above quotation is excluded from the Grand Total Pricing of work mentioned above.</p>
    
        <p class="text-justify">Payments: 50% Cash/CDC Advance Payment, 50% Cash/CDC at Job Completion.</p>
    
        <p class="text-justify">This quotation is valid for <strong>15 Days</strong> only.</p>
    </div>

   
   
    @php
    $status = $data[0]->status;
  @endphp
    
</div> 

<div class="d-flex justify-content-center">
    <button onclick="printInvoice()" class="btn btn-sm btn-warning">Print Invoice</button>
    <button type="button"  id="quotation_button" data-id={{$status}} style="margin-left:3px;" class="btn float-right {{$data[0]["status"] == 0 ? 'btn-danger' : "btn-success"}}">
        {{$data[0]->status == 0 ? 'Not Approved' : "Approved"}}
      </button>

    </div>
@endsection


@section("script")

<script>




var invoice_and_client_id = "<?php echo $invoice_data_for_approval; ?>";


$("#quotation_button").on("click", function(){
  var status = $("#quotation_button").data("id");
  var data = {
    status : status,
    data_for_update: invoice_and_client_id
  }

  $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('update-quotation-status') }}",
                    type: "POST",
                    data: {data},
                    dataType: "json",
                    success: function(data) {
                      
                        console.log(data);

                        
                        $("#quotation_button").data("id", data);

                        if(data == 1){
                          $("#quotation_button").addClass("btn-success");
                          $("#quotation_button").text("Approved");
                          $("#quotation_button").removeClass("btn-danger");
                          successAlert("Quotation Approved Successfully!");
                        }else if(data == 0){
                          $("#quotation_button").removeClass("btn-success");
                          $("#quotation_button").addClass("btn-danger");
                          $("#quotation_button").text("Not Approved");
                          errorAlert("Quotation UnApproved!");
                        }
                       
                      
                    },
                    error: function(data) { 

                    }
                })

})





    function printInvoice() {
        // Create a new window for printing
        var printWindow = window.open('', '_blank');
    
        // Append the HTML content for printing to the new window's document
        printWindow.document.write('<html><head>');
        printWindow.document.write('<style>');
        printWindow.document.write('.invoice-container {max-width: 800px;margin: 50px auto;background-color: #fff;padding: 20px;box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);border-top: 8px solid #4CAF50;border-bottom: 8px solid #4CAF50;border-radius: 10px;}');
        printWindow.document.write('.invoice-header {text-align:center; border-bottom: 2px solid #4CAF50;padding-bottom: 10px;margin-bottom: 20px;color: #4CAF50;}');
        printWindow.document.write('.invoice-header h2 {color: #4CAF50;font-size: 28px;}');
        printWindow.document.write('.invoice-details {display: flex;justify-content: space-between;margin-bottom: 20px;}');
        printWindow.document.write('.invoice-details .col {flex: 0 0 48%;}');
        printWindow.document.write('.invoice-details h5 {color: #4CAF50;font-size: 18px;}');
        printWindow.document.write('.invoice-details p {color: #555;font-size: 16px;}');
        printWindow.document.write('.invoice-items table {width: 100%;border-collapse: collapse;margin-top: 20px;}');
        printWindow.document.write('.invoice-items table th, .invoice-items table td {border: 1px solid #ddd;padding: 12px;text-align: left;}');
        printWindow.document.write('.invoice-items table th {background-color: #4CAF50;color: #fff;border: 1px solid rgb(255, 255, 255);}');
        printWindow.document.write('.invoice-items table td {background-color: #f9f9f9;border: 1px solid rgb(255, 255, 255);}');
        printWindow.document.write('.invoice-total {margin-top: 20px;font-weight: bold;color: #4CAF50;font-size: 20px;}');
        printWindow.document.write('.term_and_condition {color: red;}');
        printWindow.document.write('.calculations {text-align:right;}');
        printWindow.document.write('</style>');
        printWindow.document.write('</head><body>');
    
        // Access the first element with the specified class name
        var invoiceContainer = document.getElementsByClassName('invoice-container')[0];
    
        // Append only the table to the new window's document
        printWindow.document.write(invoiceContainer.innerHTML);
    
        // Append the closing tags for the HTML and body
        printWindow.document.write('</body></html>');
    
        // Wait for the content to be loaded (you may need to adjust the timeout)
        setTimeout(function() {
            // Print the content
            printWindow.print();
    
            // Close the new window after printing
            printWindow.close();
        }, 500);
    }
    





    
    
   function numberToWords(number) {
    const units = ['', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
    const teens = ['eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];
    const tens = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];

    const convertLessThanOneThousand = (num) => {
        if (num === 0) {
            return '';
        } else if (num < 10) {
            return units[num];
        } else if (num < 20) {
            return teens[num - 11];
        } else if (num < 100) {
            return tens[Math.floor(num / 10)] + ' ' + units[num % 10];
        } else {
            return units[Math.floor(num / 100)] + ' hundred ' + convertLessThanOneThousand(num % 100);
        }
    };

    if (number === 0) {
        return 'zero only';
    }

    const billion = Math.floor(number / 1000000000);
    const million = Math.floor((number % 1000000000) / 1000000);
    const thousand = Math.floor((number % 1000000) / 1000);
    const remainder = number % 1000;

    let result = '';

    if (billion) {
        result += convertLessThanOneThousand(billion) + ' billion ';
    }

    if (million) {
        result += convertLessThanOneThousand(million) + ' million ';
    }

    if (thousand) {
        result += convertLessThanOneThousand(thousand) + ' thousand ';
    }

    if (remainder) {
        result += convertLessThanOneThousand(remainder);
    }

    return 'Amount (In Words): ' + result + ' only';
}


var total_amount = $("#invoice_total_in_number").text();

let stringWithCommas = total_amount;
let stringWithoutCommas = stringWithCommas.replace(/,/g, '');

 amount_in_words = $("#invoice-total").text(numberToWords(stringWithoutCommas));

    
    
    </script>
    
@endsection    