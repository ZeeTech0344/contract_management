


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
    
        .select2-container .select2-selection--single {
                    height: 36px !important;
                }
        
                .select2-container--default .select2-selection--single .select2-selection__arrow b {
        
                    /* margin-top: 3px !important; */
                }
        
        
                
        
                body {
                    display: flex;
                    flex-direction: column;
                    min-height: 100vh;
                    background-color: #f8f9fa; /* Light Gray */
                    font-family: Verdana, Geneva, Tahoma, sans-serif;
                }
        
                main {
                    flex: 1;
                }
        
                /* Navbar Styling */
                .navbar {
                    background-color: #0878a4; /* Dark Gray */
                }
        
                .navbar-brand {
                    font-size: 1.5rem;
                }
        
                .navbar-nav .nav-link {
                    color: #ffffff; /* White */
                    margin-right: 10px;
                    transition: color 0.3s ease;
                }
        
                .navbar-nav .nav-link:hover {
                    color: #ffc107; /* Yellow */
                }
        
                .form-group{
                    margin: 10px;
                }
        
                .card-header{
                    background-color: #0878a4;
                    color:white;
                }
        
                .modal {
                width: 100% !important;
                }
        
        
                .invoice-container {
                    max-width: 800px;
                    margin: 50px auto;
                    background-color: #fff;
                    padding: 20px;
                    /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); */
                    /* border-top: 8px solid #555;
                    border-bottom: 8px solid #555; */
                    border-radius: 10px;
                }
                .invoice-header {
                    border-bottom: 2px solid #555;
                    padding-bottom: 10px;
                    margin-bottom: 20px;
                    color: #4F50;
                }
                .invoice-header h2 {
                    color: #4CAF50;
                    font-size: 28px;
                }
                .invoice-details {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 20px;
                }
                .invoice-details .col {
                    flex: 0 0 48%;
                }
                .invoice-details h5 {
                    color: #4CAF50;
                    font-size: 18px;
                }
                .invoice-details p {
                    color: #555;
                    font-size: 16px;
                }
                .invoice-items table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                }
                .invoice-items table th, .invoice-items table td {
                    border: 1px solid #555;
                    /* padding: 12px; */
                    text-align: left;
                }
                .invoice-items table th {
                    background-color: #f66f6f;
                    border:1px solid #555;
                }
                .invoice-items table td {
                    background-color: #f9f9f9;
                    border:1px solid #555;
                }
                .invoice-total {
                    margin-top: 20px;
                    font-weight: bold;
                    color: #4CAF50;
                    font-size: 20px;
                }
        
        
        footer {
            background-color: #0878a4; /* Dark Gray */
            color: #ffffff; /* White */
            text-align: center;
            padding-top: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        </style>

</head>

<body>
    



<div id="pdf-container">
    <div class="invoice-container text-danger " id="capture_area">
        <div class="invoice-header text-danger d-flex justify-content-between">
            <div>
                <h2 style="color:#555;">Invoice No. {{  $data[0]->invoice_no  }}</h2>
                <h3 class="text-danger text-bold">RELIABLE HOMES TECHNICAL SERVICES</h3>
                <p style="font-size: 18px;" class="text-danger"><i class="far fa-calendar-alt"></i> Invoice Date:
                    {{ date_format(date_create($data[0]->created_at), 'F j, Y') }}</p>
            </div>
            <div>
                
            </div>
        </div>




        <div class="invoice-details">
           
            <div class="row">
                <h5 class="text-danger"><i class="fas fa-user"></i> From:</h5>
                <p style="font-weight: bolder; text-transform:uppercase;">Reliable Home Technical Services<br>
                    TRN: {{ $data[0]->getOneRecordClient->trn_no == 1 ? '100023468900003' : '-' }}<br>
            </div>
        </div>

        <div class="invoice-details">
         
            <div class="row">
                <h5 class="text-danger"><i class="fas fa-user"></i> To:</h5>
                <p style="font-weight: bolder; text-transform:uppercase;">Name: {{ $data[0]->getOneRecordClient->name }}<br>
                    Address: {{ $data[0]->getOneRecordClient->address }}<br>
                    Phone#: {{ $data[0]->getOneRecordClient->phone_no }}</p>
            </div>
        </div>


        <div class="invoice-items table-responsive">
            <h5 class="text-center">Invoice Items</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center">Sr#</th>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th class="text-center">Total</th>
                    </tr>
                </thead>
                <tbody>

                    @php
                        $sr = 1;
                        $total = 0;
                    @endphp
                    @foreach ($data as $get_data)
                        <tr>
                            <td class="text-center">{{ $sr++ }}</td>
                            <td>{{ $get_data->head }}</td>
                            <td class="text-center">{{ $get_data->quantity }}</td>
                            <td class="text-center">{{ number_format($get_data->amount) }}</td>
                            <td class="text-center">{{ number_format($get_data->total) }}</td>
                        </tr>
                        @php
                            $total = $total + $get_data->total;

                        @endphp
                    @endforeach

                    @php

                        $recieved_payment = $data[0]->getOneRecordClient->recieved_payment;
                        
                    @endphp

                    <tr>
                        <td colspan="4" class="calculations" style="text-align: right;"><b>Grand Total</b></td>
                        <td class="text-center"><label id="invoice_total_in_number">{{ number_format($total)}}</label> AED</td>
                    </tr>
                   
                </tbody>
            </table>
        </div>

        <div>
            <p id="invoice-total" style="text-transform: uppercase; text-decoration:underline;"></p>
        </div>

        <div class="container mt-5 text-danger term_and_condition" style="text-align: justify;">
            <h5 class="text-right" style="text-decoration: underline;">Terms & Conditions</h5>

            <ul>
                <li>This is a computer generated quotation/Invoice , signature is not needed</li>

                <li>Reliable Home Technical Services requires 50% advance payment of the total amount of the quotation/Invoice.</li>

                <li>This Quotation is valid for only 10 days from the mentioned date.</li>

            </ul>

        </div>



        @php
            $status = $data[0]->status;
        @endphp

    </div>


</div>

</body>
</html>



<script src="{{url('new_design/plugins/jquery/jquery.min.js')}}"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{url('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <script src="{{ url('plugins/select2/js/select2.full.min.js') }}"></script>


<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.2/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.bootstrap5.min.js"></script>

   


    

    <script>







        function numberToWords(number) {
            const units = ['', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
            const teens = ['eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen',
                'nineteen'
            ];
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

            return 'Amount (In Words): <br>' + result + ' only';
        }


        var total_amount = $("#invoice_total_in_number").text();

        let stringWithCommas = total_amount;
        let stringWithoutCommas = stringWithCommas.replace(/,/g, '');

        amount_in_words = $("#invoice-total").html(numberToWords(stringWithoutCommas));
        $("#invoice-total").addClass("text-center");
    </script>

