

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha384-GLhlTQ8iKDEr5LlOI5I9n6ZiETSZKu4/5P1C+2Ls/hu+q1i055/mcswd62FQJ2iD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha384-GLhlTQ8iKDEr5LlOI5I9n6ZiETSZKu4/5P1C+2Ls/hu+q1i055/mcswd62FQJ2iD" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <title>Attractive Invoice Template with Font Awesome</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f8ff;
            margin: 0;
            padding: 0;
        }
        .invoice-container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-top: 8px solid #4CAF50;
            border-bottom: 8px solid #4CAF50;
            border-radius: 10px;
        }
        .invoice-header {
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
            margin-bottom: 20px;
            color: #4CAF50;
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
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .invoice-items table th {
            background-color: #4CAF50;
            color: #fff;
            border:1px solid rgb(255, 255, 255);
        }
        .invoice-items table td {
            background-color: #f9f9f9;
            border:1px solid rgb(255, 255, 255);
        }
        .invoice-total {
            margin-top: 20px;
            font-weight: bold;
            color: #4CAF50;
            font-size: 20px;
        }
    </style>
</head>
<body>

<div class="invoice-container">
    <div class="invoice-header text-center">
        <h2><i class="fas fa-file-invoice"></i> Attractive Invoice</h2>
        <p style="color: #4CAF50; font-size: 18px;"><i class="far fa-calendar-alt"></i> Invoice Date: January 26, 2024</p>
    </div>

    <div class="invoice-details">
        <div class="col">
            <h5><i class="fas fa-building"></i> From:</h5>
            <p>Your Company Name<br>
                Address Line 1<br>
                Address Line 2<br>
                City, State, ZIP</p>
        </div>
        <div class="col">
            <h5><i class="fas fa-user"></i> To:</h5>
            <p>Client Name<br>
                Client Company<br>
                Client Address<br>
                City, State, ZIP</p>
        </div>
    </div>

    <div class="invoice-items table-responsive">
        <h5><i class="fas fa-shopping-cart"></i> Invoice Items</h5>
        <table class="table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Item 1</td>
                    <td>2</td>
                    <td>$50.00</td>
                    <td>$100.00</td>
                </tr>
                <!-- Add more rows for additional items -->
            </tbody>
        </table>
    </div>

    <div class="invoice-total">
        <p><i class="fas fa-dollar-sign"></i> Total Amount: $100.00</p>
    </div>
    <button onclick="printInvoice()">Print Invoice</button>
</div>

</body>
</html>

<script>



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



</script>

