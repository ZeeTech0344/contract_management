@extends('old_design.main')

<style>
    @media print {
    /* Hide elements that shouldn't be printed */
    body * {
        visibility: hidden;
        margin:0;
        background-color:white !important;
    }

    thead{
        background-color:#e4e4e4;
    }

    /* Display the specific div and its content */
    #capture_area, #capture_area * {
        visibility: visible;
       
    }

    #pdf-container{
        margin-top:-50px;
    }
}
</style>

@section('content')
<div id="pdf-container" style="background:white;">
    <div class="invoice-container text-danger " id="capture_area" style="margin-top:0px;">
        <div class="invoice-header text-danger d-flex justify-content-between">
            <div >
                <h6 style="color:#555;">Invoice No. {{  $data[0]["invoice_no"]  }}</h6>
                <h4  style="color:#b80f00;font-weight:bolder;">RELIABLE HOMES TECHNICAL SERVICES</h4>
                
                <label for="" style="color:#b80f00;"><i class="fas fa-envelope"></i> Email: info@thereliablehome.com</label><br>
                <label for="" style="color:#b80f00;"> <i class="fas fa-phone-square"></i> Phone#: +971 50 606 4055</label><br>
                
            </div>
            <div>
                <img src="{{ url('old_design/img/companey logo.png') }}" alt=""
                    style="width:150px;">
            </div>
        </div>


        <div class="d-flex">

        <div class="invoice-details">
            <div class="row">
                <h6 style="color:#b80f00;"><i class="fas fa-user"></i> Client Credentials:</h6>
                <p style="text-transform:uppercase;font-size:13px;"><i class="fas fa-address-card"></i> {{$data[0]["get_one_record_client"]["name"]}}<br>
                <i class="fas fa-map-marker"></i> Address: {{ $data[0]["get_one_record_client"]["address"]}}<br>
                <i class="fas fa-phone-square"></i>  Phone: {{$data[0]["get_one_record_client"]["phone_no"]}}
                <div style="display:flex; margin-top:-11px;">
                <p style="font-size: 15px;"><i class="far fa-calendar-alt"></i> Estimate Date:
                    {{ date_format(date_create($data[0]["created_at"]), 'd-m-Y') }}</p>
                </div>
            </div>
        </div>


       

        </div>


        <div class="invoice-items table-responsive">
            <h5 class="text-center" style="color:#c01809;">Invoice Items</h5>
            <table class="table" style="background:white;">
                <thead style="background-color:#97150a;">
                    <tr>
                        <th class="text-center">Sr#</th>
                        <th>Description</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-center">Unit Price</th>
                        <th class="text-center">Total</th>
                    </tr>
                </thead>
                
                
                <tbody style="background:white;">
                    @php
                    $sr = 1;
                    $subtotal = 0;
                    $pervious_scope = "";
                    $total_amount_count = 0;
                    $total_quantity_count = 0;
                    @endphp
                    @foreach ($data as $get_data)
                    @if ($pervious_scope !== $get_data["scope"])
                    @php
                    
                    $pervious_scope = $get_data["scope"];
                   
                    @endphp
                    <tr>
                        <td class="text-center"><b>{{ $sr++ }}</b></td>
                        <td><b>{{$pervious_scope}}</b></td>
                        <td class="text-center"><b>{{$get_data["grand_quantity"]}}</b></td> <!-- Empty column for quantity -->
                        <td class="text-center"><b>{{$get_data["grand_amount"]}}</b></td> <!-- Empty column for amount -->
                        <td class="text-center"><b>{{$get_data["include_or_exclude"] == 1 ? $get_data["grand_total"] : "-"}}</b></td> <!-- Empty column for total -->
                    </tr>
                    @endif

                    @if($get_data["head"] !== "<p><br></p>" && $get_data["head"] !=="" && $get_data["head"] !== NULL )
                    <tr>
                        <td></td>
                        <td>{!! $get_data["head"] !!}</td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                    </tr>
                    @endif

                    @php
                    if($get_data["include_or_exclude"]){
                        $subtotal += $get_data["total"]; // Accumulate subtotal for the current scope
                    }
                    @endphp
                    @endforeach

                    @php

                        $recieved_payment = $data[0]["get_one_record_client"]["phone_no"];
                        //   $tax = $data[0]->getOneRecordClient->tax;

                        //   $calculation_after_tax = $total * ($tax / 100);

                        //   $total_amount =  $total +  $calculation_after_tax;

                        //   $balance = $total_amount - $recieved_payment;
                    @endphp

                    <tr>
                        <td colspan="4" class="calculations" style="text-align: right;"><b>Sub Total</b></td>
                        <td class="text-center" >{{ $subtotal }}</td>
                    </tr>

                    <tr>
                        <td colspan="4" class="calculations" style="text-align: right;"><b>Tax(%)</b></td>
                        <td class="text-center">
                            {{ $subtotal / 100 * $data[0]["get_one_record_client"]["tax"] }} ({{ $data[0]["get_one_record_client"]["tax"] }}%)
                        </td>
                        
                    </tr>
                    @php
                    $total = $subtotal /100 * $data[0]["get_one_record_client"]["tax"] + $subtotal;
                 @endphp
                    <tr>
                        <td colspan="3"><b><label for="" id="invoice-total"></label></b></td>
                        <td class="calculations" style="text-align: right;"><b>Total</b></td>
                        <td class="text-center" id="invoice_total_in_number">{{number_format($total,2)." AED"}}</td>
                    </tr>

                   
               
                </tbody>
            </table>
        </div>

        @foreach ($notes as $get_note)
                <p class="color:#b80f00; padding:0; margin:0;">{!!$get_note->notes!!}</p>
        @endforeach

        <div class="container mt-5 text-danger term_and_condition" style="text-align: justify; font-size:12px;">
            <h6 class="text-right" style="text-decoration: underline;">Terms & Conditions</h6>

            <ul>
                <li>This is a computer generated quotation/Invoice , signature is not needed</li>

                <li>Reliable Home Technical Services requires 50% advance payment of the total amount of the quotation/Invoice.</li>

                <li>This Quotation is valid for only 10 days from the mentioned date.</li>

            </ul>

        </div>



        @php
            $status = $data[0]["status"];
        @endphp

    </div>

    <div class="d-flex justify-content-center">
        <!-- Button to trigger screenshot capture -->
        <button id="capture" class="btn btn-sm btn-info" style="margin-right:3px;"> <i class="fas fa-camera"></i> Screenshot</button>
        <button class="btn btn-sm btn-warning" id="print-pdf"><i class="fas fa-print"></i> Print</button>
       
        @if(Auth::user()->role == "Admin")
        <button type="button" id="quotation_button" data-id={{ $status }} style="margin-left:3px;"
            class="btn float-right {{ $data[0]['status'] == 0 ? 'btn-danger' : 'btn-success' }}">
            {{ $data[0]["status"] == 0 ? 'Not Approved' : 'Approved' }}
        </button>
        @endif
    



    </div>
</div>
    

@endsection


@section('script')
    <script>


// script.js
$(document).ready(function() {
    // Add a click event to a button or any trigger
    $('#print-pdf').on('click', function() {
        // Open the print dialog
        window.print();
    });
});


document.getElementById('capture').addEventListener('click', function() {
      // Specify the target element to capture
      var targetElement = document.getElementById('capture_area');

      html2canvas(targetElement).then(function(canvas) {
        // Convert the canvas to an image data URL
        var imgData = canvas.toDataURL('image/png');

        // Create a temporary link element
        var link = document.createElement('a');
        link.href = imgData;
        link.download = 'screenshot.png';

        // Append the link to the document and trigger a click to download the image
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
      });
    });


        var invoice_and_client_id = "<?php echo $invoice_data_for_approval; ?>";


        $("#quotation_button").on("click", function() {
            var status = $("#quotation_button").data("id");
            var data = {
                status: status,
                data_for_update: invoice_and_client_id
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('update-quotation-status') }}",
                type: "POST",
                data: {
                    data
                },
                dataType: "json",
                success: function(data) {

                    console.log(data);


                    $("#quotation_button").data("id", data);

                    if (data == 1) {
                        $("#quotation_button").addClass("btn-success");
                        $("#quotation_button").text("Approved");
                        $("#quotation_button").removeClass("btn-danger");
                        successAlert("Quotation Approved Successfully!");
                    } else if (data == 0) {
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



        
      
        
        function numberToWords(num) {
            let parts = num.toString().split(".");
            let integerPart = parseInt(parts[0], 10);
            let decimalPart = parts.length > 1 ? parseInt(parts[1], 10) : null;

            if (decimalPart !== null) {
                // Adjust for leading zeros in decimal part to ensure accurate word representation
                decimalPart = parseFloat("0." + parts[1]) * 100;
            }

            const belowTen = ["", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine"];
            const belowTwenty = ["ten", "eleven", "twelve", "thirteen", "fourteen", "fifteen", "sixteen", "seventeen",
                "eighteen", "nineteen"
            ];
            const belowHundred = ["", "", "twenty", "thirty", "forty", "fifty", "sixty", "seventy", "eighty", "ninety"];

            function words(n, includeAnd = true) {
                if (n < 10) return belowTen[n];
                else if (n < 20) return belowTwenty[n - 10];
                else if (n < 100) return belowHundred[Math.floor(n / 10)] + (n % 10 > 0 ? " " + belowTen[n % 10] : "");
                else if (n < 1000) return belowTen[Math.floor(n / 100)] + " hundred" + (n % 100 > 0 ? (includeAnd ?
                    " and " : " ") + words(n % 100, false) : "");
                else if (n < 1000000) return words(Math.floor(n / 1000), false) + " thousand" + (n % 1000 > 0 ? " " + words(
                    n % 1000, includeAnd) : "");
                else if (n < 1000000000) return words(Math.floor(n / 1000000), false) + " million" + (n % 1000000 > 0 ?
                    " " + words(n % 1000000, includeAnd) : "");
                else return words(Math.floor(n / 1000000000), false) + " billion" + (n % 1000000000 > 0 ? " " + words(n %
                    1000000000, includeAnd) : "");
            }

            let integerWords = words(integerPart, decimalPart === null);
            let decimalWords = decimalPart !== null ? words(decimalPart, false) : "";

            return decimalPart !== null ? `${integerWords} Dirham and Fils ${decimalWords}` : `${integerWords} Dirham`;
        }


        const number = "<?php echo $total; ?>";
        var check_number = Number.isInteger(parseFloat(number));

     
        
        //var number_to_word = numberToWords(number) + "  ONLY";

        if (check_number == true) {
            var number_to_word = numberToWords(number) + "  ONLY";
         } else if (check_number == false) {
            var number_to_word = numberToWords(number);
        }
        


        $("#invoice-total").text("Sum: " + number_to_word.toUpperCase());
       


    </script>
@endsection
