

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


    <div id="pdf-container">
        <div class="invoice-container text-danger " id="capture_area">
            <div class="invoice-header text-danger d-flex justify-content-between">
                <div>
                    <h6 style="color:#555;">Invoice No. {{ $data[0]['invoice_no'] }}</h6>
                    <h4 style="color:#c01809;font-weight:bolder;">RELIABLE HOMES TECHNICAL SERVICES</h4>
                    <label for="" style="color:#b80f00;"><i class="fas fa-envelope"></i> Email: info@thereliablehome.com</label><br>
                    <label for="" style="color:#b80f00;"> <i class="fas fa-phone-square"></i> Phone#: +971 50 606 4055</label>
                    <p style="color:#b80f00;"><i class="far fa-calendar-alt"></i> Invoice Date:
                        {{ date_format(date_create($data[0]['created_at']), 'd-m-Y') }}</p>


                </div>
                <div>
                    <img src="{{ url('old_design/img/companey logo.png') }}" alt="" style="width:150px;">
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
                            $pervious_scope = '';
                            $total_amount_count = 0;
                            $total_quantity_count = 0;
                        @endphp
                        @foreach ($data as $get_data)
                            @if ($pervious_scope !== $get_data['scope'])
                                @php

                                    $pervious_scope = $get_data['scope'];

                                @endphp
                                <tr>
                                    <td class="text-center"><b>{{ $sr++ }}</b></td>
                                    <td><b>{{ $pervious_scope }}</b></td>
                                    <td class="text-center"><b>{{ $get_data['grand_quantity'] }}</b></td>
                                    <!-- Empty column for quantity -->
                                    <td class="text-center"><b>{{ $get_data['grand_amount'] }}</b></td>
                                    <!-- Empty column for amount -->
                                    <td class="text-center"><b>{{ $get_data['grand_total'] }}</b></td>
                                    <!-- Empty column for total -->
                                </tr>
                            @endif
                            <tr>
                                <td></td>
                                <td>{!! $get_data['head'] !!}</td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                            </tr>
                            @php
                            if($get_data["include_or_exclude"]){
                                $subtotal += $get_data['total']; // Accumulate subtotal for the current scope
                            }
                            @endphp
                        @endforeach

                        @php

                            $recieved_payment = $data[0]['get_one_record_client']['phone_no'];
                            //   $tax = $data[0]->getOneRecordClient->tax;

                            //   $calculation_after_tax = $total * ($tax / 100);

                            //   $total_amount =  $total +  $calculation_after_tax;

                            //   $balance = $total_amount - $recieved_payment;
                        @endphp

                        <tr>
                            <td colspan="4" class="calculations" style="text-align: right;"><b>Sub Total</b></td>
                            <td class="text-center">{{ number_format($subtotal) }}</td>
                        </tr>

                        <tr>
                            <td colspan="4" class="calculations" style="text-align: right;"><b>Tax(%)</b></td>
                            <td class="text-center">
                                {{ ($subtotal / 100) * $data[0]['get_one_record_client']['tax'] }}
                                ({{ $data[0]['get_one_record_client']['tax'] }}%)
                            </td>

                        </tr>

                        <tr>
                            <td colspan="3"><b>Sum: <label id="amount_in_words_total" for=""></label></b></td>
                            <td class="calculations" style="text-align: right;"><b>Total</b></td>
                            <td class="text-center" id="invoice_total_in_number">
                                {{ ($subtotal / 100) * $data[0]['get_one_record_client']['tax'] + $subtotal }}<label>
                                    AED</label></td>
                        </tr>

                        @php
                            $total = ($subtotal / 100) * $data[0]['get_one_record_client']['tax'] + $subtotal;
                        @endphp

                    </tbody>
                </table>
            </div>

            
            <br>
            <br>
            

            <div class="invoice-items table-responsive">
                <h5> <i class="fas fa-receipt"></i> Expense</h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-center">Sr#</th>
                            <th>Description</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-center">Unit Price</th>
                            <th class="text-center">Total</th>
                        </tr>
                    </thead>
                    <tbody>

                        @php
                            $sr = 1;
                            $total_expense = 0;
                        @endphp


                        @foreach ($expense_record as $get_data)
                            <tr>
                                <td>{{ $sr++ }}</td>
                                <td>{{ $get_data->head }}</td>
                                <td class="text-center">-</td>
                                <td class="text-center">{{ number_format($get_data->amount) }}</td>
                                <td class="text-center">{{ number_format($get_data->amount) }}</td>
                            </tr>
                            @php
                                $total_expense = $total_expense + $get_data->amount;

                            @endphp
                        @endforeach

                        @php

                            $recieved_payment = $data[0]['get_one_record_client']['recieved_payment'];
                            $tax = $data[0]['get_one_record_client']['tax'];

                            $calculation_after_tax = $total_expense * ($tax / 100);

                            $total_amount = $total_expense + $calculation_after_tax;

                            $balance = $total_amount - $recieved_payment;

                        @endphp
                        <tr>
                            <td colspan="4" class="calculations" style="text-align: right;"><b>Grand Total</b></td>
                            <td class="text-center">{{ number_format($total_expense) }}</td>
                        </tr>

                        <tr>
                            <td colspan="4" class="calculations" style="text-align: right;"><b>Total Amount - Total
                                    Expense = Profit </b></td>
                            <td class="text-center">{{ $total - $total_expense }}</td>
                        </tr>

                        @php
                            $total_percentage = 0;
                            $total_pay = 0;
                        @endphp
                        @foreach ($contractor_info as $partnership)
                            <tr>

                                @php
                                    $total_pay_to_each_contractor = (($total - $total_expense) / 100) * $partnership->percentage;
                                @endphp
                                <td colspan="4" style="text-align: right;"><b>Pay To:
                                        {{ $partnership->getContractor->name }} </b></td>
                                <td class="text-center"> {{ $total_pay_to_each_contractor }}
                                    ({{ $partnership->percentage }}%)</td>
                            </tr>
                            @php
                                $total_pay = $total_pay + $total_pay_to_each_contractor;
                            @endphp
                        @endforeach
                        <tr>
                            <td colspan="3"><b>Sum: <label for="" id="total-remaining" ></label></b></td>
                            <td style="text-align: right;"><b>Remaining</b></td>
                            <td class="text-center" id="remaing_amount_get">{{ $total - $total_expense - $total_pay}}<label> AED</label></td>
                        </tr>
                    </tbody>
                </table>
            </div>


            <div class="container mt-5 text-danger term_and_condition" style="text-align: justify; font-size:12px;">
                <h6 class="text-right" style="text-decoration: underline;">Terms & Conditions</h6>

                <ul>
                    <li>This is a computer generated quotation/Invoice , signature is not needed</li>

                    <li>Reliable Home Technical Services requires 50% advance payment of the total amount of the
                        quotation/Invoice.</li>

                    <li>This Quotation is valid for only 10 days from the mentioned date.</li>

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
            {{-- <button class="btn btn-sm btn-warning" id="print-pdf"><i class="fas fa-print"></i> Print</button> --}}

            {{-- @if (Auth::user()->role == 'Admin')
                <button type="button" id="quotation_button" data-id={{ $status }} style="margin-left:3px;"
                    class="btn float-right {{ $data[0]['status'] == 0 ? 'btn-danger' : 'btn-success' }}">
                    {{ $data[0]['status'] == 0 ? 'Not Approved' : 'Approved' }}
                </button>
            @endif --}}




        </div>
    </div>

    <script>

        
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





        // var invoice_no = "<?php echo $get_invoice; ?>";
        // var client_id = "<?php echo $client_id; ?>";

        // $(document).on("click", "#print-pdf", function() {


        //     $.ajax({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         url: "{{ url('invoice-pdf') }}" + "/" + invoice_no + "/" + client_id,
        //         type: "GET",
        //         success: function(data) {

        //             const pdfData = data[0];
        //             // Create a blob object from the base64-encoded data
        //             const byteCharacters = atob(pdfData);
        //             const byteNumbers = new Array(byteCharacters.length);
        //             for (let i = 0; i < byteCharacters.length; i++) {
        //                 byteNumbers[i] = byteCharacters.charCodeAt(i);
        //             }
        //             const byteArray = new Uint8Array(byteNumbers);
        //             const blob = new Blob([byteArray], {
        //                 type: 'application/pdf'
        //             });


        //             // Create a URL for the blob object
        //             const url = URL.createObjectURL(blob);

        //             // Create a link element with the URL and click on it to download the PDF file
        //             const link = document.createElement('a');
        //             link.href = url;
        //             link.download = 'supplier_data.pdf';
        //             document.body.appendChild(link);
        //             link.click();
        //         }
        //     })



        // })



        $(document).ready(function() {
    // Add a click event to a button or any trigger
    $('#print-pdf').on('click', function() {
        // Open the print dialog
        window.print();
    });
});









        function numberToWords(number) {
            const units = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
            const teens = ['Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen',
                'Nineteen'
            ];
            const tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

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
                    return units[Math.floor(num / 100)] + ' Hundred ' + convertLessThanOneThousand(num % 100);
                }
            };

            if (number === 0) {
                return 'Zero';
            }

            const billion = Math.floor(number / 1000000000);
            const million = Math.floor((number % 1000000000) / 1000000);
            const thousand = Math.floor((number % 1000000) / 1000);
            const remainder = number % 1000;

            let result = '';

            if (billion) {
                result += convertLessThanOneThousand(billion) + ' Billion ';
            }

            if (million) {
                result += convertLessThanOneThousand(million) + ' Million ';
            }

            if (thousand) {
                result += convertLessThanOneThousand(thousand) + ' Thousand ';
            }

            if (remainder) {
                result += convertLessThanOneThousand(remainder);
            }

            return result.trim();
        }

        function convertAmountToWords(amount) {
            const [dollars, cents] = amount.toString().split('.');
            let words = numberToWords(parseInt(dollars)) + ' Dirham';

            if (cents) {
                words += ' and Fils ' + numberToWords(parseInt(cents)) + ' only';
            } else {
                words += ' only';
            }

            return words;
        }



        var total_amount = $("#invoice_total_in_number").text();

        var remaing_amount_get = $("#remaing_amount_get").text();

        console.log(total_amount);

        let stringWithCommas = total_amount;
        let stringWithoutCommas = stringWithCommas.replace(/,/g, '');


        amount_in_words = $("#amount_in_words_total").html(convertAmountToWords(stringWithoutCommas));
        $("#amount_in_words_total").addClass("text-center");

        $("#total-remaining").html(convertAmountToWords(remaing_amount_get));

    </script>

