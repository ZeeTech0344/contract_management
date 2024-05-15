<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    <link rel="stylesheet" href="{{url('old_design/nprogress/nprogress.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">

    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.0-rc.2/dist/quill.snow.css" rel="stylesheet" />

<!-- Bootstrap DateTimePicker CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet">


    <style>


       
/* CSS to make modal fullscreen */
.modal-fullscreen {
  width: 100%;
  margin: 0;
}

.modal-dialog.modal-fullscreen {
  margin: 0;
}

.modal-content {
  
  border-radius: 0;
}

.modal-body {
  overflow-y: auto;
}


.select2-container .select2-selection--single {
            height: 36px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {

            /* margin-top: 3px !important; */
        }


        

        body {
            display: flex;
            flex-direction: column;
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
            /* margin-bottom: 20px; */
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

        .invoice-items table th{
            padding:10px !important;
        }

        .invoice-items table th {
            background-color: #e4e4e4;
            border:1px solid #555;
        }
        .invoice-items table td {
            /* background-color: #f9f9f9; */
            border:1px solid #555;
            vertical-align: middle;
        }
        .invoice-total {
            margin-top: 20px;
            font-weight: bold;
            color: #4CAF50;
            font-size: 20px;
        }

        .invoice-items td, .invoice-items th{
            padding:3 !important;
            font-size:12px;
        }

        .error_set {
            border: 2px solid red;
        }

        

        /* Card Styling */
        /* .card {
            border: none;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: scale(1.02);
        }

        
        .card:nth-child(1) {
            background-color: #007bff;
            color: #ffffff; 
        }

        .card:nth-child(2) {
            background-color: #28a745;
            color: #ffffff; 
        }

        .card:nth-child(3) {
            background-color: #dc3545; 
            color: #ffffff;
        } */

        
/* Footer Styling */
footer {
    background-color: #0878a4; /* Dark Gray */
    color: #ffffff; /* White */
    text-align: center;
    padding-top: 10px;
    position: fixed;
    bottom: 0;
    width: 100%;
}


.quotation_table td, .quotation_table th, .quotation_table ul{
        margin:4px !important;
        padding:4px !important;
}

.quotation_table ul{
    padding-left:10px !important;
}

    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            {{-- <a class="navbar-brand" href="#">Dashboard</a> --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    {{-- <li class="nav-item">
                        <a class="nav-link" href="{{ url('client-registeration-old') }}"><i class="fas fa-user"></i> Client Registeration</a>
                    </li> --}}

                    {{-- <li class="nav-item">
                        <a class="nav-link" href="{{ url('/old-home') }}"><i class="fas fa-home"></i> Home</a>
                    </li> --}}

                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-info-circle"></i> Quotation
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ url('quotation-old') }}">Generate Quotation</a>
                            <a class="dropdown-item" href="{{ url('quotation-list') }}">List of Quotation</a>
                        </div>
                    </li>


                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-hammer"></i> Contractor
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ url('contractor-info') }}"> Contractor Register</a>
                            <a class="dropdown-item" href="{{ url('list-of-contractor-for-detail-view') }}"> Detail of Contractor</a>
                        </div>
                    </li>


                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-briefcase"></i> Accounts
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{url('expense-head')}}">Expense Head</a>
                            <a class="dropdown-item" href="{{url('view-bank-form')}}">Add Expense</a>
                            
                        </div>
                    </li>




                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-id-card"></i> Employee
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{url('employee-and-head-view')}}">Add Employee</a>
                            <a class="dropdown-item" href="{{url('salary')}}"> Salary</a>
                            <a class="dropdown-item" href="{{ url('pending-form') }}">Pending</a>
                        </div>
                    </li>



                    @if(Auth::user()->role == "Admin")
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('users-list-view') }}"><i class="fas fa-user-shield"></i> Users List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('register') }}"><i class="fas fa-circle"></i> Registeration</a>
                    </li>
                    @endif

                    
                    
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('logout') }}"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid mt-2">
        <div class="row pb-5" style="background-color:white;">
            {{-- <div class="col-lg-4 mb-4">
                <!-- Card 1 -->
                <div class="card bg-success">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-chart-bar"></i> Card 1</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of
                            the card's content.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <!-- Card 2 -->
                <div class="card bg-danger">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-cogs"></i> Card 2</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of
                            the card's content.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <!-- Card 3 -->
                <div class="card bg-warning">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-users"></i> Card 3</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of
                            the card's content.</p>
                    </div>
                </div>
            </div> --}}
          


