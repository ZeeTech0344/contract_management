@extends('old_design.main')

<style>
    @media print {

        /* Hide elements that shouldn't be printed */
        body * {
            visibility: hidden;
            margin: 0;
            background-color: white !important;
        }

        thead {
            background-color: #e4e4e4;
        }

        /* Display the specific div and its content */
        #capture_area,
        #capture_area * {
            visibility: visible;

        }

        #pdf-container {
            margin-top: -50px;
        }
    }
</style>

@section('content')
    <div id="pdf-container" style="background:white;">
        <div class="invoice-container text-danger " id="capture_area" style="margin-top:0px;">
            <div class="invoice-header text-danger d-flex justify-content-between">
                <div>
                    <h6 style="color:#555;">Estimate No. {{ $data[0]['invoice_no'] }}</h6>
                    <h4 style="color:#b80f00;font-weight:bolder;">RELIABLE HOMES TECHNICAL SERVICES</h4>

                    <label for="" style="color:#b80f00;"><i class="fas fa-envelope"></i> Email:
                        info@thereliablehome.com</label><br>
                    <label for="" style="color:#b80f00;"> <i class="fas fa-phone-square"></i> Phone#: +971 50 606
                        4055</label><br>

                </div>
                <div>
                    <img src="{{ url('old_design/img/companey logo.png') }}" alt="" style="width:150px;">
                </div>
            </div>


            <div class="d-flex">

                <div class="invoice-details">
                    <div class="row">
                        <h6 style="color:#b80f00;"><i class="fas fa-user"></i> Client Credentials:</h6>
                        <p style="text-transform:uppercase;font-size:13px;"><i class="fas fa-address-card"></i>
                            {{ $data[0]['get_one_record_client']['name'] }}<br>
                            <i class="fas fa-map-marker"></i> Address:
                            {{ $data[0]['get_one_record_client']['address'] }}<br>
                            <i class="fas fa-phone-square"></i> Phone: {{ $data[0]['get_one_record_client']['phone_no'] }}
                        <div style="display:flex; margin-top:-11px;">
                            <p style="font-size: 15px;"><i class="far fa-calendar-alt"></i> Estimate Date:
                                {{ date_format(date_create($data[0]['created_at']), 'd-m-Y') }}</p>

                            &nbsp;<p style="font-size: 15px;"><i class="far fa-calendar-alt"></i> Estimate Validity:
                                @php
                                    $date = new DateTime($data[0]['created_at']);
                                    // Add 10 days to the date
                                    $date->modify('+10 days');
                                    // Format the date as desired
                                    echo $newDate = $date->format('d-m-Y');
                                @endphp
                            </p>

                        </div>
                    </div>
                </div>




            </div>


            <div class="invoice-items table-responsive" style="margin-top:-20px;">

                <table class="table quotation_table" style="background:white;">
                    <thead style="background-color:#e4e4e4; vertical-align:center;">
                        <tr>
                            <th class="text-center">Sr#</th>
                            <th>Description</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-center">Unit Price</th>
                            <th class="text-center">Total</th>
                        </tr>
                    </thead>



                    <tbody style="background:white; font-size:12px;">
                        @php
                            $sr = 1;
                            $total_grand = 0;
                            $previous_scope = '';
                            $total_amount_count = 0;
                            $total_quantity_count = 0;
                    
                            // Sort the data array by scope and then by created_at
                            usort($data, function($a, $b) {
                                // First compare by scope
                                $scopeComparison = strcmp($a['scope'], $b['scope']);
                                if ($scopeComparison !== 0) {
                                    return $scopeComparison;
                                }
                                // If scope is the same, compare by created_at
                                return strcmp($a['created_at'], $b['created_at']);
                            });
                        @endphp
                    
                        @foreach ($data as $get_data)
                            @if ($previous_scope !== $get_data['scope'])
                                @php
                                    $previous_scope = $get_data['scope'];
                                @endphp
                                <tr>
                                    <td class="text-center"><b>{{ $sr++ }}</b></td>
                                    <td><b>{{ $previous_scope }}</b></td>
                                    <td class="text-center"><b>{{ $get_data['grand_quantity'] }}</b></td>
                                    <!-- Empty column for quantity -->
                                    <td class="text-center"><b>{{ $get_data['grand_amount'] }}</b></td>
                                    <!-- Empty column for amount -->
                                    <td class="text-center">
                                        <b>{{ $get_data['include_or_exclude'] == 1 ? $get_data['grand_total'] : '-' }}</b>
                                    </td> <!-- Empty column for total -->
                                </tr>
                            @endif
                    
                            @if ($get_data['head'] !== '<p><br></p>' && $get_data['head'] !== '' && $get_data['head'] !== null)
                                <tr>
                                    <td></td>
                                    <td>{!! $get_data['head'] !!}</td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                </tr>
                            @endif
                            @php
                                if ($get_data['include_or_exclude']) {
                                    $total_grand += $get_data['total']; // Accumulate subtotal for the current scope
                                }
                            @endphp
                        @endforeach
                        {{-- Display grand total for the last scope before the head --}}
                        <tr>
                            <td colspan="3" id="invoice-total" style="font-weight:bolder; font-size:13px;"></td>
                            <td class="calculations" style="text-align: right;"><b>Grand Total</b></td>
                            <td class="text-center" colspan="2"><label id="invoice_total_in_number"><b>{{ number_format($total_grand, 2) }}</label> AED</b></td>
                        </tr>
                    </tbody>

                    

                </table>
            </div>

            @foreach ($notes as $get_note)
                <p style="color:#b80f00; padding:0; margin:0;"> {!! $get_note->notes !!}</p>
            @endforeach

            <div>

            </div>

            <div class="container term_and_condition" style="text-align: justify; color:#b80f00; font-size:12px;">
                <h6 class="text-right" style="text-decoration: underline;">Terms & Conditions</h6>

                <ul style="margin:0;">
                    <li style="margin:0;">This is a computer generated quotation/Invoice , signature is not needed</li>

                    <li style="margin:0;">Reliable Home Technical Services requires 50% advance payment of the total amount
                        of the quotation/Invoice.</li>

                    <li style="margin:0;">This Quotation is valid for only 10 days from the mentioned date.</li>

                </ul>

            </div>



            @php
                $status = $data[0]['status'];
            @endphp

        </div>

        <div class="d-flex justify-content-center">
            <!-- Button to trigger screenshot capture -->
            <button id="capture" class="btn btn-sm btn-info" style="margin-right:3px;"> <i class="fas fa-camera"></i>
                Screenshot</button>
            <button class="btn btn-sm btn-warning" id="print-pdf"><i class="fas fa-print"></i> Print</button>

            @if (Auth::user()->role == 'Admin')
                <button type="button" id="quotation_button" data-id={{ $status }} style="margin-left:3px;"
                    class="btn float-right {{ $data[0]['status'] == 0 ? 'btn-danger' : 'btn-success' }}">
                    {{ $data[0]['status'] == 0 ? 'Not Approved' : 'Approved' }}
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
                else if (n < 1000000) return words(Math.floor(n / 1000), false) + " thousand " + (n % 1000 > 0 ? " " + words(
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


        const number = "<?php echo $total_grand; ?>";
        var check_number = Number.isInteger(parseFloat(number));

     
         if (check_number == true) {
            var number_to_word = numberToWords(number) + "  ONLY";
         } else if (check_number == false) {
            var number_to_word = numberToWords(number);
        }


        $("#invoice-total").text("Sum: " + number_to_word.toUpperCase());
       



    </script>
@endsection
