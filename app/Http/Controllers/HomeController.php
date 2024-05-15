<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\BuyerPurchaserDetail;
use App\Models\contractor_information;
use App\Models\DailyWork;
use App\Models\EmployeeAndHead;
use App\Models\EmployeeAttendance;
use App\Models\ExpenseDetail;
use App\Models\FinalNote;
use App\Models\partnership_detail;
use App\Models\Pending;
use App\Models\Salary;
use App\Models\supplierData;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use PDF;

class HomeController extends Controller
{
    function getExpenseReport(Request $req, $from_date, $to_date)
    {
        $bank_expense = Bank::with("getEmployee")->whereDate("created_at", ">=" ,$from_date)->whereDate("created_at", "<=" ,$to_date)->get();
        $html = [];
        $html["title"] = "Expense Detail (" . date_format(date_create($from_date), "d-m-Y") . " to " . date_format(date_create($to_date), "d-m-Y") . ")";
        $html["view"] = view("old_design.all_view.bank-expense-detail", compact("bank_expense"))->render();
        return response()->json($html, 200);
        //return view("old_design.all_view.bank-expense-detail", compact("bank_expense"));
    }

    function getProfitAndLossReport(Request $req)
    {


        $from_date = $req->from_date;
        $to_date = $req->to_date;

        if ($req->from_date && $req->to_date) {

            $from_date = $req->from_date;
            $to_date = $req->to_date;

            $data = BuyerPurchaserDetail::selectRaw('
            buyer_purchaser_details.id as client_id_get,
            buyer_purchaser_details.name as client_name,
            buyer_purchaser_details.phone_no as client_phone,
            buyer_purchaser_details.address as client_address,
            (SELECT COALESCE(SUM(total), 0) FROM supplier_data WHERE buyer_purchaser_details.id = supplier_data.supplier_id AND include_or_exclude = 1) AS get_invoice_data_sum_total,
            (SELECT COALESCE(SUM(total), 0) FROM expense_details WHERE buyer_purchaser_details.id = expense_details.client_id) AS get_expense_sum_total,
            (SELECT COALESCE(SUM(percentage), 0) FROM partnership_details WHERE buyer_purchaser_details.id = partnership_details.client_id) AS get_percentage_sum,
            (SELECT invoice_no FROM supplier_data WHERE buyer_purchaser_details.id = supplier_data.supplier_id LIMIT 1) AS invoice_no,
            (SELECT status FROM supplier_data WHERE buyer_purchaser_details.id = supplier_data.supplier_id LIMIT 1) AS get_status,
            (SELECT created_at FROM supplier_data WHERE buyer_purchaser_details.id = supplier_data.supplier_id LIMIT 1) AS get_date
            ')
                ->whereHas('getInvoiceData', function ($query) use ($from_date, $to_date) {
                    $query->whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date);
                })
                ->get();


            $html = [];
            $html["title"] = "Quotation Profit & Loss (" . date_format(date_create($from_date), "d-m-Y") . " to " . date_format(date_create($to_date), "d-m-Y") . ")";
            $html["view"] = view('old_design.all_view.get-profit-and-loss-report', compact("data", "from_date", "to_date"))->render();
            return response()->json($html, 200);
        }

        // $pdf = PDF::loadView('old_design.all_view.get-profit-and-loss-report', compact("data", "from_date", "to_date"));
        // $pdfData = $pdf->output();
        // $base64Pdf = base64_encode($pdfData);
        // return response()->json(['pdf_data' => $base64Pdf]);
        //return view('old_design.all_view.get-profit-and-loss-report', compact("data", "from_date", "to_date"));
    }



    function getQotationPdf(Request $req, $type)
    {

        $from_date = $req->from_date;
        $to_date = $req->to_date;

        if (($req->status == "0" || $req->status == "1") && $req->from_date && $req->to_date && $req->search_data_value) {

            $search = $req->search_data_value;

            $data = supplierData::leftJoin('buyer_purchaser_details', 'buyer_purchaser_details.id', '=', 'supplier_data.supplier_id')
                ->where('supplier_data.status', $req->status)
                ->whereDate('supplier_data.created_at', ">=", $req->from_date)
                ->whereDate('supplier_data.created_at', "<=", $req->to_date)
                ->where('buyer_purchaser_details.name', 'like', '%' . $search . '%')
                ->orwhere('buyer_purchaser_details.phone_no', 'like', '%' . $search . '%')
                ->orwhere('buyer_purchaser_details.address', 'like', '%' . $search . '%')
                ->select('supplier_data.invoice_no', 'buyer_purchaser_details.name', 'buyer_purchaser_details.phone_no', 'buyer_purchaser_details.address', 'supplier_data.status', 'buyer_purchaser_details.id')
                ->selectRaw('SUM(CASE WHEN supplier_data.include_or_exclude = 1 THEN supplier_data.total ELSE 0 END) as total_amount') // Total amount where status is 1
                ->groupBy('supplier_data.invoice_no', 'buyer_purchaser_details.name', 'buyer_purchaser_details.phone_no', 'buyer_purchaser_details.address', 'supplier_data.status', 'buyer_purchaser_details.id')
                ->orderBy("supplier_data.id", "desc")->get();
        } elseif ($req->search_data_value) {

            $search = $req->search_data_value;

            $data = supplierData::leftJoin('buyer_purchaser_details', 'buyer_purchaser_details.id', '=', 'supplier_data.supplier_id')
                ->where('buyer_purchaser_details.name', 'like', '%' . $search . '%')
                ->orwhere('buyer_purchaser_details.phone_no', 'like', '%' . $search . '%')
                ->orwhere('buyer_purchaser_details.address', 'like', '%' . $search . '%')
                ->select('supplier_data.invoice_no', 'buyer_purchaser_details.name', 'buyer_purchaser_details.phone_no', 'buyer_purchaser_details.address', 'supplier_data.status', 'buyer_purchaser_details.id')
                ->selectRaw('SUM(CASE WHEN supplier_data.include_or_exclude = 1 THEN supplier_data.total ELSE 0 END) as total_amount') // Total amount where status is 1
                ->groupBy('supplier_data.invoice_no', 'buyer_purchaser_details.name', 'buyer_purchaser_details.phone_no', 'buyer_purchaser_details.address', 'supplier_data.status', 'buyer_purchaser_details.id')
                ->orderBy("supplier_data.id", "desc")->get();
        } elseif (($req->status == "0" || $req->status == "1") && $req->from_date && $req->to_date) {

            $data = supplierData::leftJoin('buyer_purchaser_details', 'buyer_purchaser_details.id', '=', 'supplier_data.supplier_id')
                ->where('supplier_data.status', $req->status)
                ->whereDate('supplier_data.created_at', ">=", $req->from_date)
                ->whereDate('supplier_data.created_at', "<=", $req->to_date)
                ->select('supplier_data.invoice_no', 'buyer_purchaser_details.name', 'buyer_purchaser_details.phone_no', 'buyer_purchaser_details.address', 'supplier_data.status', 'buyer_purchaser_details.id')
                ->selectRaw('SUM(CASE WHEN supplier_data.include_or_exclude = 1 THEN supplier_data.total ELSE 0 END) as total_amount') // Total amount where status is 1
                ->groupBy('supplier_data.invoice_no', 'buyer_purchaser_details.name', 'buyer_purchaser_details.phone_no', 'buyer_purchaser_details.address', 'supplier_data.status', 'buyer_purchaser_details.id')
                ->orderBy("supplier_data.id", "desc")->get();
        } elseif ($req->status == "0" || $req->status == "1") {

            $data = supplierData::leftJoin('buyer_purchaser_details', 'buyer_purchaser_details.id', '=', 'supplier_data.supplier_id')
                ->where('supplier_data.status', $req->status)
                ->select('supplier_data.invoice_no', 'buyer_purchaser_details.name', 'buyer_purchaser_details.phone_no', 'buyer_purchaser_details.address', 'supplier_data.status', 'buyer_purchaser_details.id')
                ->selectRaw('SUM(CASE WHEN supplier_data.include_or_exclude = 1 THEN supplier_data.total ELSE 0 END) as total_amount') // Total amount where status is 1
                ->groupBy('supplier_data.invoice_no', 'buyer_purchaser_details.name', 'buyer_purchaser_details.phone_no', 'buyer_purchaser_details.address', 'supplier_data.status', 'buyer_purchaser_details.id')
                ->orderBy("supplier_data.id", "desc")->get();
        } elseif ($req->from_date && $req->to_date && $req->status) {

            $data = supplierData::leftJoin('buyer_purchaser_details', 'buyer_purchaser_details.id', '=', 'supplier_data.supplier_id')
                ->where('supplier_data.status', $req->status)
                ->whereDate('supplier_data.created_at', ">=", $req->from_date)
                ->whereDate('supplier_data.created_at', "<=", $req->to_date)
                ->select('buyer_purchaser_details.id as client_id', 'supplier_data.invoice_no', 'buyer_purchaser_details.name', 'buyer_purchaser_details.phone_no', 'buyer_purchaser_details.address', 'supplier_data.status', 'buyer_purchaser_details.id')
                ->selectRaw('SUM(CASE WHEN supplier_data.include_or_exclude = 1 THEN supplier_data.total ELSE 0 END) as total_amount') // Total amount where status is 1
                ->groupBy('buyer_purchaser_details.id', 'supplier_data.invoice_no', 'buyer_purchaser_details.name', 'buyer_purchaser_details.phone_no', 'buyer_purchaser_details.address', 'supplier_data.status', 'buyer_purchaser_details.id')
                ->orderBy("supplier_data.id", "desc")
                ->get();
        } elseif ($req->from_date && $req->to_date) {

            $data = supplierData::leftJoin('buyer_purchaser_details', 'buyer_purchaser_details.id', '=', 'supplier_data.supplier_id')
                ->whereDate('supplier_data.created_at', ">=", $req->from_date)
                ->whereDate('supplier_data.created_at', "<=", $req->to_date)
                ->select('buyer_purchaser_details.id as client_id', 'supplier_data.invoice_no', 'buyer_purchaser_details.name', 'buyer_purchaser_details.phone_no', 'buyer_purchaser_details.address', 'supplier_data.status', 'buyer_purchaser_details.id')
                ->selectRaw('SUM(CASE WHEN supplier_data.include_or_exclude = 1 THEN supplier_data.total ELSE 0 END) as total_amount') // Total amount where status is 1
                ->groupBy('buyer_purchaser_details.id', 'supplier_data.invoice_no', 'buyer_purchaser_details.name', 'buyer_purchaser_details.phone_no', 'buyer_purchaser_details.address', 'supplier_data.status', 'buyer_purchaser_details.id')
                ->orderBy("supplier_data.id", "desc")
                ->get();
        }

        if ($type == "pdf") {
            $pdf = PDF::loadView('old_design.all_view.quotation-list-pdf', compact("data", "from_date", "to_date"));
            $pdfData = $pdf->output();
            $base64Pdf = base64_encode($pdfData);
            return response()->json(['pdf_data' => $base64Pdf]);
        }

        $html = [];
        $html["title"] = "Quotation (" . date_format(date_create($from_date), "d-m-Y") . " to " . date_format(date_create($to_date), "d-m-Y") . ")";
        $html["view"] =  view('old_design.all_view.quotation-list-pdf', compact("data", "from_date", "to_date", "type"))->render();
        return response()->json($html, 200);
    }


    function deletePendingAmount(Request $req)
    {
        $pending = Pending::find($req->id);
        $pending->delete();
        return response()->json("deleted", 200);
    }

    function editPendingAmount(Request $req)
    {

        $pending = Pending::find($req->id);
        return response()->json($pending, 200);
    }


    function viewEmployeeProfile(Request $req, $id)
    {
        if ($req->ajax()) {
            $employees = EmployeeAndHead::where("account_for", "Employee")
                ->where("id", $id)->get()->first();

            $html = [];
            $html["title"] = $employees->employee_name;
            $html["view"] =  view("old_design.all_view.employee-profile", compact("employees"))->render();
            return response()->json($html, 200);
        }
    }



    function editEmployee(Request $req)
    {

        $employee =  EmployeeAndHead::where("id", $req->id)->get();
        return response()->json($employee, 200);
    }

    function getSalaryDetail(Request $req, $month_get)
    {

        if ($req->ajax()) {
            $month = $req->month . "-" . "01";
            $last_date = date("Y-m-t", strtotime($month));

            $salary_detail = EmployeeAndHead::with(['bank' => function ($query) use ($month) {
                $query->where('paid_for_month_date', $month)
                    ->where("purpose", "Advance");
            }])
                ->with(['salary' => function ($query) use ($month) {
                    $query->where('salary_month', $month);
                }])
                ->whereDate('joining', '<=', $last_date)
                ->where("account_for", "Employee")
                ->where("employee_status", "On")
                ->get();

            $html = [];
            $html["title"] = "Salary Report (" . date_format(date_create($month_get), "M-Y") . ")";
            $html["view"] = view("old_design.all_view.get-salary-pdf", compact("salary_detail", "month"))->render();
            return response()->json($html, 200);
        }
    }

    function deleteSalaryRecord(Request $req)
    {

        $easypaisa_paid_amount = Bank::find($req->data[0]);
        $easypaisa_paid_amount->delete();

        DB::table('pendings')
            ->where("account_id", $req->data[0])
            ->where("account_name", $req->data[1])
            ->update(['status' => 'Pending', 'account_id' => null, 'account_name' => null]);

        DB::table('salaries')
            ->where("account_id", $req->data[0])
            ->where("account_name", $req->data[1])
            ->delete();
    }


    function getPaidSalary(Request $req, $get_month)
    {

        if ($req->ajax()) {
            $month  = $get_month . "-" . "01";
            $data = salary::with("employee")
                ->where("salary_month", $month)
                ->orderBy("id", "asc")->get();

            $html = [];
            $html["title"] = "ُPaid Salary (" . date_format(date_create($month), "M-Y") . ")";
            $html["view"] = view("old_design.all_view.salary-paid-view", compact("data", "month"))->render();
            return response()->json($html, 200);
        }
    }

    function getSalaryUnpaidDetail(Request $req, $get_month)
    {
        if ($req->ajax()) {

            $month = $get_month . "-01";
            $last_date = date("Y-m-t", strtotime($month));

            $salary_detail = EmployeeAndHead::with(['bank' => function ($query) use ($month) {
                $query->where('paid_for_month_date', $month)
                    ->where("purpose", "Advance");
            }])
                ->with(['pendings' => function ($query) use ($month, $last_date) {
                    $query->whereDate('created_at', ">=", $month)
                        ->whereDate('created_at', "<=", $last_date)
                        ->where("status", "Pending");
                }])
                ->whereDoesntHave('salary', function ($query) use ($month) {
                    $query->where('salary_month', $month);
                })
                ->whereDate('joining', '<=', $last_date)
                ->where("account_for", "Employee")
                ->where("employee_status", "On")
                ->get();

            $html = [];
            $html["title"] = "Salary Report (" . date_format(date_create($month), "M-Y") . ")";
            $html["view"] = view("old_design.all_view.salary-unpaid-view", compact("salary_detail", "month"))->render();
            return response()->json($html, 200);
        }
    }


    function getSalaryPdf(Request $req)
    {

        if ($req->ajax()) {
            $month = $req->month . "-" . "01";
            $last_date = date("Y-m-t", strtotime($month));

            $salary_detail = EmployeeAndHead::with(['bank' => function ($query) use ($month) {
                $query->where('paid_for_month_date', $month)
                    ->where("purpose", "Advance");
            }])
                ->with(['salary' => function ($query) use ($month) {
                    $query->where('salary_month', $month);
                }])
                ->whereDate('joining', '<=', $last_date)
                ->where("account_for", "Employee")
                ->where("employee_status", "On")
                ->get();



            return view("old_design.all_view.get-salary-pdf", compact("salary_detail", "month"));

            // return view("accounts.get-salary-pdf", compact("salary_detail", "month"));
        }
    }

    function insertPending(Request $req)
    {


        if ($req->ajax()) {

            $validation = [
                'date' =>  'required',
                'employee_id' =>  'required',
                'amount' =>  'required',
                'remarks' =>  'required',
            ];

            $validator = Validator::make($req->all(), $validation);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->all()], 400);
            }

            if ($req->hidden_id) {
                $pending = Pending::find($req->hidden_id);
            } else {
                $pending = new Pending();
            }

            $pending->date = $req->date;
            $pending->employee_id = $req->employee_id;
            $pending->amount = $req->amount;
            $pending->status = "Pending";
            $pending->remarks = $req->remarks;
            $pending->save();
            return response()->json(['saved'], 200);
        }
    }

    function getPendingList(Request $req)
    {
        if ($req->ajax()) {

            if ($req->search_value && $req->from_date_pending && $req->to_date_pending) {

                $search_value = $req->search_value;

                $count_data =  Pending::with("getStaff:id,employee_name,employee_post")
                    ->whereHas('getStaff', function ($query) use ($search_value) {
                        $query->where("employee_name", "like", '%' . $search_value . '%');
                    })
                    ->whereDate("date", ">=", $req->from_date_pending)
                    ->whereDate("date", "<=", $req->to_date_pending)
                    ->count();

                $data = Pending::with("getStaff:id,employee_name,employee_post")
                    ->whereHas('getStaff', function ($query) use ($search_value) {
                        $query->where("employee_name", "like", '%' . $search_value . '%');
                    })
                    ->whereDate("date", ">=", $req->from_date_pending)
                    ->whereDate("date", "<=", $req->to_date_pending)
                    ->offset($req->start)->limit(10)->orderBy("id", "desc");
            } elseif ($req->search_value) {

                $search_value = $req->search_value;

                $count_data =  Pending::with("getStaff:id,employee_name,employee_post")
                    ->whereHas('getStaff', function ($query) use ($search_value) {
                        $query->where("employee_name", "like", '%' . $search_value . '%');
                    })->count();

                $data = Pending::with("getStaff:id,employee_name,employee_post")
                    ->whereHas('getStaff', function ($query) use ($search_value) {
                        $query->where("employee_name", "like", '%' . $search_value . '%');
                    })
                    ->offset($req->start)->limit(10)->orderBy("id", "desc");
            } elseif ($req->from_date_pending && $req->to_date_pending && $req->pending_status && $req->pending_employee_id) {


                $count_data = Pending::where("employee_id", $req->pending_employee_id)
                    ->where("status", $req->pending_status)
                    ->whereDate("date", ">=", $req->from_date_pending)
                    ->whereDate("date", "<=", $req->to_date_pending)
                    ->count();

                $data = Pending::with("getStaff:id,employee_name,employee_post")
                    ->where("employee_id", $req->pending_employee_id)
                    ->where("status", $req->pending_status)
                    ->whereDate("date", ">=", $req->from_date_pending)
                    ->whereDate("date", "<=", $req->to_date_pending)
                    ->offset($req->start)->limit(10)->orderBy("id", "desc");
            } elseif ($req->from_date_pending && $req->to_date_pending && $req->pending_employee_id) {


                $count_data = Pending::where("employee_id", $req->pending_employee_id)
                    ->whereDate("date", ">=", $req->from_date_pending)
                    ->whereDate("date", "<=", $req->to_date_pending)
                    ->count();

                $data = Pending::with("getStaff:id,employee_name,employee_post")
                    ->where("employee_id", $req->pending_employee_id)
                    ->whereDate("date", ">=", $req->from_date_pending)
                    ->whereDate("date", "<=", $req->to_date_pending)
                    ->offset($req->start)->limit(10)->orderBy("id", "desc");
            } elseif ($req->from_date_pending && $req->to_date_pending && $req->pending_status) {


                $count_data = Pending::where("status", $req->pending_status)
                    ->whereDate("date", ">=", $req->from_date_pending)
                    ->whereDate("date", "<=", $req->to_date_pending)
                    ->count();


                $data = Pending::with("getStaff:id,employee_name,employee_post")
                    ->where("status", $req->pending_status)
                    ->whereDate("date", ">=", $req->from_date_pending)
                    ->whereDate("date", "<=", $req->to_date_pending)
                    ->offset($req->start)->limit(10)->orderBy("id", "desc");
            } elseif ($req->from_date_pending && $req->to_date_pending) {

                $count_data = Pending::whereDate("date", ">=", $req->from_date_pending)
                    ->whereDate("date", "<=", $req->to_date_pending)
                    ->count();

                $data = Pending::with("getStaff:id,employee_name,employee_post")
                    ->whereDate("date", ">=", $req->from_date_pending)
                    ->whereDate("date", "<=", $req->to_date_pending)
                    ->offset($req->start)->limit(10)->orderBy("id", "desc");
            } else {



                $count_data = Pending::count();
                $data = Pending::with("getStaff:id,employee_name,employee_post")->offset($req->start)->limit(10)->orderBy("id", "desc");
            }


            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('remarks', function ($row) {
                    return $row->remarks;
                })

                ->addColumn('status', function ($row) {
                    return $row->status;
                })
                ->addColumn('amount', function ($row) {
                    return number_format($row->amount);
                })
                ->addColumn('employee_id', function ($row) {
                    return $row->getStaff->employee_post . "-" . $row->getStaff->employee_name;
                })
                ->addColumn('date', function ($row) {
                    return $row->date;
                })

                ->addColumn('action', function ($row) {

                    if ($row->status == "Pending") {
                        $btn = '<div class="btn-group btn-sm">
                        <button type="button" class="btn btn-sm btn-info dropdown-toggle" ' . (Auth::User()->user_type == "User" ? "disabled" : "") . ' data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                        </button>
                        <div class="dropdown-menu">';
                        $btn .=  '<a href="javascript:void(0)" class="dropdown-item  edit-pending-amount"  data-id="' . $row->id . '">Edit</a>';
                        $btn .= '<a  href="javascript:void(0)" class="dropdown-item delete-pending-amount" data-id="' . $row->id . '">Delete</a>';
                        $btn .= '</div>
                        </div>';
                    } else {
                        $btn = '<div class="btn-group btn-sm">
                        <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>
                        Action
                        </button>
                        <div class="dropdown-menu">
                        <a href="javascript:void(0)" class="dropdown-item  edit-pending-amount"  data-id="' . $row->id . '">Edit</a>';
                        $btn .= '<a  href="javascript:void(0)" class="dropdown-item delete-pending-amount" data-id="' . $row->id . '">Delete</a>';
                        $btn .= '</div>
                        </div>';
                    }

                    return $btn;
                })
                ->setFilteredRecords($data->count())
                ->setTotalRecords($count_data)
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    function pendingForm(Request $req)
    {
        return view("old_design.all_view.pending-form");
    }

    function checkPendings(Request $req)
    {
        $current_month = $req->date;
        $date = new DateTime($current_month);
        // $date->modify('first day of last month');
        $firstDayOfPreviousMonth = $date->format('Y-m-01');
        $lastDayOfPreviousMonth = $date->format('Y-m-t');


        $check_pendings = DB::table('pendings')
            ->select(DB::raw('sum(amount) as sum'))
            ->where("employee_id", $req->id)
            ->where("status", "Pending")
            ->whereDate("created_at", ">=", $firstDayOfPreviousMonth)
            ->whereDate("created_at", "<=", $lastDayOfPreviousMonth)
            ->get();

        return response()->json([(isset($check_pendings[0]) ? $check_pendings[0]->sum : 0)], 200);
    }

    function editBankAmount(Request $req)
    {
        $paid_amount = bank::find($req->id);
        $get_detail_employee = EmployeeAndHead::find($paid_amount->employee_id);
        return response()->json([$paid_amount, $get_detail_employee], 200);
    }

    function getEmployees(Request $req)
    {
        $staff = EmployeeAndHead::where("account_for", $req->employee_type)->where("employee_status", "On")->get();
        return response()->json($staff, 200);
    }


    function deleteExpenseOrEmployee(Request $req)
    {
        $expense_or_employee = EmployeeAndHead::find($req->id);
        $expense_or_employee->delete();
        return response()->json("deleted", 200);
    }


    function editExpenseOrEmployee(Request $req)
    {
        $expense_or_employee = EmployeeAndHead::find($req->id);
        return response()->json($expense_or_employee, 200);
    }


    function insertExpenseHead(Request $req)
    {


        $validation = [];

        if ($req->hidden_id) {
            // If there's a hidden ID (indicating an update operation), append to the validation rules
            $validation['employee_name'] = 'unique:employee_and_heads,employee_name,' . $req->hidden_id;
        } else {
            // If there's no hidden ID (indicating a new insertion), define the validation rules
            $validation = [
                'employee_name' => 'required|unique:employee_and_heads,employee_name'
            ];
        }

        $validator = Validator::make($req->all(), $validation);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }

        if ($req->hidden_id) {
            $employee = EmployeeAndHead::find($req->hidden_id);
        } else {
            $employee = new EmployeeAndHead();
        }

        $employee->employee_name = $req->employee_name;
        $employee->employee_status = $req->employee_status;
        $employee->account_for = "Others";
        $employee->save();
        return response()->json("saved", 200);
    }



    function expenseHead()
    {

        return view("old_design.all_view.expense-head");
    }


    function getEmployeeOrHead(Request $req)
    {

        if ($req->employee_type) {
            $data = EmployeeAttendance::where("account_for", $req->employee_type)
                ->where("employee_status", "On")->get();
        }

        return response()->json($data);
    }

    function viewBankForm(Request $req)
    {

        return view("old_design.all_view.view-bank-form");
    }


    function insertBankAmount(Request $req)
    {

        if ($req->ajax()) {

            $validation = [
                'employee_id' =>  'required',
                'purpose' =>  'required',
                'amount' =>  'required',
                'bank_name' =>  'required'
            ];

            $validator = Validator::make($req->all(), $validation);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->all()], 400);
            }

            if ($req->hidden_id) {
                $bank_paid =  Bank::find($req->hidden_id);
            } else {

                $bank_amount = Bank::latest()->first();
                $bank_paid = new Bank();

                if ($bank_amount) {
                    $invoice_no = $bank_amount->invoice_no + 1;
                } else {
                    $invoice_no = 100000;
                }
                $bank_paid->invoice_no = $invoice_no;
            }


            $bank_paid->employee_id = $req->employee_id;
            $bank_paid->purpose = $req->purpose;
            if (isset($req->advance_payment_month)) {
                $bank_paid->paid_for_month_date = $req->advance_payment_month . "-01";
            }

            $bank_paid->given_by = Auth::user()->id;

            $bank_paid->status = "Paid";
            $bank_paid->amount = $req->amount;
            $bank_paid->bank_name = $req->bank_name;
            $bank_paid->remarks = $req->remarks;
            // $easypaisa_paid->paid_date = $req->paid_date;
            $bank_paid->save();
        }
    }




    function getBankExpenseList(Request $req)
    {

        if ($req->search_value &&  $req->from_date && $req->to_date) {

            $search_value = $req->search_value;

            $total_count = Bank::with("getEmployee:id,employee_name,employee_post")
                ->whereHas('getEmployee', function ($query) use ($search_value) {
                    $query->where("employee_name", "like", '%' . $search_value . '%');
                })->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->count();

            $data = Bank::with("getEmployee:id,employee_name,employee_post")
                ->whereHas('getEmployee', function ($query) use ($search_value) {
                    $query->where("employee_name", "like", '%' . $search_value . '%');
                })
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");
        } elseif ($req->search_value) {


            $search_value = $req->search_value;

            $total_count = Bank::with("getEmployee:id,employee_name,employee_post")
                ->whereHas('getEmployee', function ($query) use ($search_value) {
                    $query->where("employee_name", "like", '%' . $search_value . '%');
                })
                ->count();

            $data = Bank::with("getEmployee:id,employee_name,employee_post")
                ->whereHas('getEmployee', function ($query) use ($search_value) {
                    $query->where("employee_name", "like", '%' . $search_value . '%');
                })
                ->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");
        } elseif ($req->from_date && $req->to_date) {

            $total_count = Bank::with("getEmployee:id,employee_name,employee_post")
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->count();

            $data = Bank::with("getEmployee:id,employee_name,employee_post")
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");
        } else {

            $total_count = Bank::with("getEmployee:id,employee_name,employee_post")->count();
            $data = Bank::with("getEmployee:id,employee_name,employee_post")->offset($req->start)->limit(10)->orderBy("id", "desc");
        }


        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('bank_name', function ($row) {
                return  $row->bank_name;
            })
            ->addColumn('paid_date', function ($row) {
                return  date_format(date_create($row->created_at), "d-m-Y");
            })
            ->addColumn('employee', function ($row) {
                if ($row->getEmployee->employee_post !== null) {
                    $advance_date = date_format(date_create($row->paid_for_month_date), "d-M-Y");
                    return $row->getEmployee->employee_name . " (" . $row->getEmployee->employee_post . ")-" . $advance_date;
                } else {
                    return $row->getEmployee->employee_name;
                }
            })
            ->addColumn('purpose', function ($row) {
                return $row->purpose;
            })
            ->addColumn('status', function ($row) {
                return $row->status;
            })
            ->addColumn('amount', function ($row) {
                return number_format($row->amount);
            })
            ->addColumn('remarks', function ($row) {
                return $row->remarks;
            })

            ->addColumn('action', function ($row) {

                if ($row->purpose == "Others" ||  $row->purpose == "Advance") {

                    $btn = '<div class="btn-group btn-sm">
                    <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Action
                    </button>
                    <div class="dropdown-menu">';
                    $btn .= '<a href="javascript:void(0)" class="dropdown-item  edit-bank-amount text-dark"  data-id="' . $row->id . '">Edit</a>';
                    $btn .= '</div></div>';
                } else {

                    $btn = '<div class="btn-group btn-sm">
                    <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Action
                    </button>
                    <div class="dropdown-menu">';
                    $btn .= '<a href="javascript:void(0)" class="dropdown-item  edit-bank-amount text-dark"  data-id="' . $row->id . '">Edit</a>';
                    $btn .= '</div></div>';
                }
                // $btn .= '<a  href="javascript:void(0)" class="dropdown-item return-easypaisa-amount" data-id="' . $row->id . '">Return</a>';

                // $btn .= '</div>
                // </div>';


                return $btn;
            })
            ->setFilteredRecords($total_count)
            ->setTotalRecords($data->count())
            ->rawColumns(['action'])
            ->make(true);
    }



    function finalSalaryInsert(Request $req)
    {


        if ($req->ajax()) {

            $validation = [
                'bank_name' =>  'required',

            ];

            $validator = Validator::make($req->all(), $validation);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->all()], 400);
            }

            $firstDayOfMonth = $req->paid_for_month;;
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfMonth));

            $latestEntry = Bank::latest()->first();

            if ($latestEntry) {
                $invoice = $latestEntry->invoice_no +  1;
            } else {
                $invoice = 1000;
            }

            $paid_amount = new Bank();
            $paid_amount->invoice_no =  $invoice;
            $paid_amount->employee_id = $req->employee_id;
            $paid_amount->purpose = "Salary";
            $paid_amount->paid_for_month_date = $req->paid_for_month;
            $paid_amount->status = "Paid";
            $paid_amount->amount = $req->salary;
            $paid_amount->bank_name = $req->bank_name;
            $paid_amount->given_by = Auth::user()->id;
            $paid_amount->save();

            $salary = new Salary();
            $salary->employee_id = $req->employee_id;

            $salary->basic_salary = $req->basic_salary;
            $salary->advance = $req->get_advance;
            $salary->pendings = $req->pendings;
            // $salary->fuel_amount = $req->fuel_amount;
            $salary->day_of_work_deduction = $req->day_of_work_deduction;
            $salary->addition = $req->addition;
            $salary->remarks = $req->remarks;
            $salary->day_of_work = $req->day_of_work;

            $salary->amount = $req->salary;
            $salary->salary_month = $req->paid_for_month;
            $salary->status = "Paid";
            $salary->account_id  = $paid_amount->id;
            $salary->account_name  = $req->bank_name;
            $salary->save();

            DB::table('pendings')
                ->where("employee_id", $req->employee_id)
                ->where("status", "Pending")
                ->whereDate("created_at", ">=", $firstDayOfMonth)
                ->whereDate("created_at", "<=", $lastDayOfMonth)
                ->update(['status' => 'Paid', 'account_id' => $paid_amount->id, 'account_name' => $req->bank_name]);
        }
    }




    function checkAdvanceSalary(Request $req)
    {


        $current_month = $req->date;
        $date = new DateTime($current_month);
        // $date->modify('first day of last month');
        $firstDayOfPreviousMonth = $date->format('Y-m-01');
        $lastDayOfPreviousMonth = $date->format('Y-m-t');

        $check_advance = DB::table('banks')
            ->select(DB::raw('sum(amount) as sum'))
            ->where("employee_id", $req->id)
            ->where("purpose", "Advance")
            ->whereDate("paid_for_month_date", ">=", $firstDayOfPreviousMonth)
            ->whereDate("paid_for_month_date", "<=", $lastDayOfPreviousMonth)
            ->get();

        return response()->json($check_advance, 200);
    }


    function payNowSalary(Request $req, $id, $date, $salary, $name, $joining, $employee_post)
    {

        $firstDayOfMonth = $date;
        $lastDayOfMonth = date('Y-m-t', strtotime($date));

        $get_attendance = EmployeeAttendance::where("employee_id", $id)
            ->whereDate("date", ">=", $firstDayOfMonth)
            ->whereDate("date", "<=", $lastDayOfMonth)
            ->where("attendance_type", "present")
            ->count();

        if ($req->ajax()) {
            $html = [];
            $html["title"] = "Pay Salary (" . date_format(date_create($date), "M-Y") . ")";
            $html["view"] = view("old_design.all_view.pay-now-salary", compact("get_attendance", "id", "date", "salary", "name", "joining", "employee_post"))->render();

            return response()->json($html, 200);
        }
    }



    function getDataOfEmployeeSalary(Request $req)
    {

        if ($req->ajax()) {
            if ($req->month) {
                $count_employee = count(DB::table('employee_and_heads')->where("account_for", "Employee")
                    ->where("employee_status", "On")
                    ->get());
                $month  = $req->month . "-" . "01";
                $last_date = date("Y-m-t", strtotime($month));
                $data = EmployeeAndHead::whereDoesntHave('salary', function ($query) use ($month) {
                    $query->where('salary_month', $month);
                })->where("employee_status", "On")
                    ->whereDate('joining', '<=', $last_date)
                    ->where("account_for", "Employee")->orderBy("id", "asc");
            } else {
                $count_employee = count(DB::table('employee_and_heads')
                    ->where("account_for", "Employee")
                    ->where("employee_status", "On")->get());
                $month  = date("Y-m") . "-" . "01";
                $last_date = date("Y-m-t", strtotime($month));
                $data = EmployeeAndHead::whereDoesntHave('salary', function ($query) use ($month) {
                    $query->where('salary_month', $month);
                })
                    ->where("employee_status", "On")
                    ->whereDate('joining', '<=', $last_date)
                    ->where("account_for", "Employee")->orderBy("id", "asc");
            }
            // })->where("plot_area", $req->block)->where("status", "On")->offset($req->start)->limit(10)->orderBy("id", "DESC");
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('employee_no', function ($row) {
                    return $row->employee_no;
                })
                ->addColumn('name', function ($row) {
                    return $row->employee_name;
                })
                ->addColumn('post', function ($row) {
                    return $row->employee_post;
                })

                ->addColumn('salary', function ($row) {
                    return number_format($row->basic_sallary);
                })

                ->addColumn('action', function ($row) use ($month) {
                    $btn = '<div class="btn-group btn-sm">
                <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Action
                </button>
                <div class="dropdown-menu">
                <a href="javascript:void(0)" class="dropdown-item  pay_now_salary"  data-id="' . $row->id . "," . $month . "," . $row->basic_sallary . "," . $row->employee_name . "," . $row->joining . "," . $row->employee_post . '">View Salary</a>';
                    $btn .= '</div>
                </div>';
                    return $btn;
                })
                ->setFilteredRecords($count_employee)
                ->setTotalRecords($data->count())
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    function employeeAndHeadList(Request $req, $type)
    {

        if ($req->ajax()) {

            if ($req->search_value) {

                $total_count = EmployeeAndHead::where("employee_name", "like", '%' . $req->search_value . '%')
                    ->where("account_for", $type)
                    // ->where("employee_status", "On")
                    ->count();

                $data = EmployeeAndHead::where("employee_name", "like", '%' . $req->search_value . '%')
                    ->where("account_for", $type)
                    // ->where("employee_status", "On")
                    ->orderBy("id", "desc")
                    ->get();
            } else {
                $total_count = EmployeeAndHead::where("account_for", $type)
                    ->count();
                $data = EmployeeAndHead::where("account_for", $type)
                    ->orderBy("id", "desc");
            }



            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('employee_name', function ($row) {
                    return $row->employee_name;
                })

                ->addColumn('phone_no', function ($row) {
                    return $row->phone_no;
                })

                ->addColumn('salary', function ($row) {
                    return $row->basic_sallary;
                })

                ->addColumn('employee_status', function ($row) {
                    return $row->employee_status;
                })

                ->addColumn('view_only', function ($row) {
                    return '<a href="javascript:void(0)" class="view_profile btn  btn-success"  data-id="' . $row->id . '">View</a>';
                })

                ->addColumn('action', function ($row) use ($type) {

                    $btn = '<div class="btn-group btn-sm">
                    <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Action
                    </button>
                    <div class="dropdown-menu">
                    <a href="javascript:void(0)" class="dropdown-item edit"  data-id="' . $row->id . '">Edit</a>';
                    if ($type == "Employee") {
                        $btn .= '<a href="javascript:void(0)" class="dropdown-item view_profile"  data-id="' . $row->id . '">View</a>';
                    }
                    $btn .= '</div>
                    </div>';
                    return $btn;
                })
                ->setFilteredRecords($total_count)
                ->setTotalRecords($data->count())
                ->rawColumns(['action', 'check_box', 'view_only'])
                ->make(true);
        }
    }



    function insertEmployeeAndHead(Request $req)
    {


        $validation = [
            'employee_name' =>  'required',
            'employee_post' => 'required',
            'basic_sallary' => 'required',
            'dob' => 'required',
            'cnic' => 'required',
            'basic_sallary' => 'required',
            'employee_status' =>  'required',
            'cnic' =>  'unique:employee_and_heads,cnic'
        ];


        if ($req->employee_hidden_id) {
            $validation["cnic"] = 'unique:employee_and_heads,cnic,' . $req->employee_hidden_id;
        }

        $validator = Validator::make($req->all(), $validation);


        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }

        if ($req->employee_hidden_id) {
            $employee = EmployeeAndHead::find($req->employee_hidden_id);
        } else {
            $get_employee_no = EmployeeAndHead::where("account_for", "Employee")->first();
            $employee = new EmployeeAndHead();
            if ($get_employee_no == "") {
                $employee->employee_no = 10000;
            } else {
                $employee->employee_no = $get_employee_no->employee_no + 1;
            }
        }

        $employee->employee_name = $req->employee_name;
        $employee->employee_post = $req->employee_post;
        $employee->dob = $req->dob;
        $employee->basic_sallary = $req->basic_sallary;
        $employee->cnic =  $req->cnic;
        $employee->phone_no =  $req->phone_no;
        $employee->father_name =  $req->father_name;
        $employee->father_cnic =  $req->father_cnic;
        $employee->father_phone_no =  $req->father_phone_no;
        $employee->employee_status = $req->employee_status;
        $employee->account_for = "Employee";
        $employee->joining = $req->joining;
        $employee->leaving = $req->leaving;
        if (isset($req->image)) {
            $imageName = time() . '.' . $req->image->extension();
            $req->image->move(public_path('images'), $imageName);
            $employee->image = $imageName;
        }

        $employee->save();
        return response()->json("saved", 200);
    }
    function employeeAndHeadView()
    {

        return view("old_design.all_view.add-employee-and-head");
    }


    function salary(Request $req)
    {

        return view("new_design.new_design_view.salary");
    }
    function deleteDailyWork(Request $req)
    {

        $daily_work = DailyWork::find($req->id);
        $daily_work->delete();
        return response()->json("deleted", 200);
    }


    function editDailyWork(Request $req)
    {

        $daily_work = DailyWork::find($req->id);
        return response()->json($daily_work, 200);
    }
    function dailyWorkList(Request $req, $client_id)
    {


        if ($req->ajax()) {

            $all_data_count = DailyWork::where("client_id", $client_id)->count();
            $data = DailyWork::with("getClientData")->with("getScope")->where("client_id", $client_id)->offset($req->start)->limit(10)->orderBy("id", "desc");

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    return date_format(date_create($row->created_at), "d-m-Y");
                })
                ->addColumn('client', function ($row) {
                    return $row->getClientData->name;
                })
                ->addColumn('scope', function ($row) {
                    return $row->getScope->scope;
                })
                ->addColumn('time_of_work', function ($row) {
                    return $row->time_of_work;
                })
                ->addColumn('team', function ($row) {
                    return $row->team;
                })
                ->addColumn('amount', function ($row) {
                    return $row->amount;
                })
                ->addColumn('amount_type', function ($row) {
                    return $row->amount_type;
                })
                ->addColumn('recieved_by', function ($row) {
                    return $row->recieved_by;
                })
                ->addColumn('remarks', function ($row) {
                    return $row->remarks;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="dropdown">
                    <button class="btn btn-sm btn-block btn-danger dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                         <a class="edit_daily_work dropdown-item" data-id="' . $row->id . '" href="#">Edit</a>
                         <a class="delete_daily_work dropdown-item" data-id="' . $row->id . '" href="#">Delete</a>
                    </div>
                    </div>';
                    return $btn;
                })

                ->setFilteredRecords($all_data_count)
                ->setTotalRecords($data->count())
                ->rawColumns(['action', 'scope'])
                ->make(true);
        }
    }

    function insertDailyWork(Request $req)
    {

        if ($req->daily_work_hidden_id) {
            $daily_work = DailyWork::find($req->daily_work_hidden_id);
        } else {
            $daily_work = new DailyWork();
        }
        $daily_work->client_id = $req->client_id;
        $daily_work->scope_id = $req->scope_id;
        $daily_work->time_of_work = $req->time_of_work;
        $daily_work->team = $req->team;
        $daily_work->amount = $req->amount;
        $daily_work->amount_type = $req->amount_type;
        $daily_work->recieved_by = $req->recieved_by;
        $daily_work->remarks = $req->remarks;
        $daily_work->save();
        return response()->json("saved", 200);
    }

    function dailayWork(Request $req, $client_id)
    {

        $scopes = supplierData::where("supplier_id", $client_id)->get();
        $html = [];
        $html["title"] = "Daily Work";
        $html["view"] = view("new_design.new_design_view.daily-work", compact("client_id", "scopes"))->render();
        return response()->json($html, 200);
    }


    function deleteFinalNote(Request $req)
    {
        $note = FinalNote::find($req->id);
        $note->delete();
        return response()->json("deleted", 200);
    }

    function editFinalNote(Request $req)
    {

        $note = FinalNote::find($req->id);
        return response()->json($note, 200);
    }

    function listOfNotes(Request $req, $client_id)
    {


        if ($req->ajax()) {

            $all_data_count = FinalNote::where("client_id", $client_id)->count();
            $data = FinalNote::with("getClientData")->where("client_id", $client_id)->offset($req->start)->limit(10)->orderBy("id", "desc");

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->getClientData->name;
                })
                ->addColumn('notes', function ($row) {
                    return $row->notes;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="dropdown">
                    <button class="btn btn-sm btn-block btn-danger dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                         <a class="edit_note dropdown-item" data-id="' . $row->id . '" href="#">Edit</a>
                         <a class="delete_note dropdown-item" data-id="' . $row->id . '" href="#">Delete</a>
                    </div>
                    </div>';
                    return $btn;
                })

                ->setFilteredRecords($all_data_count)
                ->setTotalRecords($data->count())
                ->rawColumns(['action', 'notes'])
                ->make(true);
        }
    }


    function insertFinalNote(Request $req)
    {

        if ($req->hidden_id) {
            $final_note = FinalNote::find($req->hidden_id);
        } else {
            $final_note = new FinalNote();
        }
        $final_note->notes = $req->head;
        $final_note->client_id = $req->client_id;
        $final_note->save();
        return response()->json("saved", 200);
    }

    function finalNotes(Request $req, $client_id)
    {


        $html = [];
        $html["title"] = "Final Notes";
        $html["view"] = view("old_design.all_view.final-notes", compact("client_id"))->render();
        return response()->json($html, 200);
    }


    function viewContractorFinalReceipt(Request $req, $client_id, $invoice_no)
    {


        $client_id = $client_id;
        $get_invoice = $invoice_no;

        $firstArray = supplierData::with("getOneRecordClient")->where("invoice_no", $invoice_no)->where("supplier_id", $client_id)->get()->toArray();
        $secondArray = supplierData::select(DB::raw('SUM(total) as grand_total, SUM(quantity) as grand_quantity , SUM(amount) as grand_amount , scope, invoice_no, supplier_id'))
            ->where("invoice_no", $invoice_no)
            ->where("supplier_id", $client_id)
            ->groupBy('scope', 'invoice_no', 'supplier_id') // Group by the 'scope' column
            ->get()->toArray();


        $data = [];
        foreach ($firstArray as $firstItem) {
            foreach ($secondArray as $secondItem) {
                if (
                    $firstItem['scope'] === $secondItem['scope'] &&
                    $firstItem['invoice_no'] === $secondItem['invoice_no'] &&
                    $firstItem['supplier_id'] === $secondItem['supplier_id']
                ) {
                    $data[] = array_merge($firstItem, $secondItem);
                }
            }
        }



        $expense_record = ExpenseDetail::with("getClientData")->where("client_id", $client_id)->get();

        $contractor_info = partnership_detail::with("getContractor")->where("client_id", $client_id)->where("invoice_no", $invoice_no)->get();

        $invoice_data_for_approval = $client_id . "," . $get_invoice;



        // $html = [];
        // $html["title"] = "Contractor Detail <button class='btn btn-sm btn-warning' id='back_to_contractor' >Back</button>";
        // $html["view"] = view("old_design.all_view.view-contractor-final-receipt", compact("contractor_info", "expense_record", "data", "client_id", "get_invoice", "invoice_data_for_approval"))->render();
        // return response()->json($html, 200);


        return view("old_design.all_view.final-receipt-new", compact("contractor_info", "expense_record", "data", "client_id", "get_invoice", "invoice_data_for_approval"));
    }


    function viewContractorDetail(Request $req, $contractor_id, $contractor_name)
    {

        if ($req->ajax()) {

            $contractors = partnership_detail::with([
                'getClientData.getInvoiceData',
                'getClientData.getExpense'
            ])
                ->where("contractor_id", $contractor_id)
                ->get();

            $html = [];
            $html["title"] = "Contractor Detail (" . $contractor_name . ")";
            $html["view"] = view("old_design.all_view.view-contractor-detail", compact("contractors"))->render();
            return response()->json($html, 200);
        }

        // $contractor_id = 3;

        // return view("old_design.all_view.view-contractor-detail", compact("contractors"));
    }

    function listOfContractorForDetailView(Request $req)
    {

        return view("old_design.all_view.list-of-contractor-for-detail");
    }

    function listOfContractorForDetail(Request $req)
    {

        if ($req->ajax()) {


            if ($req->search_data_value) {

                $search = $req->search_data_value;

                $all_data_count = contractor_information::where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('phone_no', 'like', '%' . $search . '%')
                        ->orWhere('address', 'like', '%' . $search . '%');
                })->count();

                $data = contractor_information::where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('phone_no', 'like', '%' . $search . '%')
                        ->orWhere('address', 'like', '%' . $search . '%');
                })->limit(10)->orderBy("id", "desc");
            } else {
                $all_data_count = contractor_information::count();
                $data = contractor_information::offset($req->start)->limit(10)->orderBy("id", "desc");
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('phone_no', function ($row) {
                    return $row->phone_no;
                })
                ->addColumn('account_no', function ($row) {
                    return $row->account_no;
                })
                ->addColumn('address', function ($row) {
                    return $row->address;
                })
                ->addColumn('status', function ($row) {
                    return "<label class='text-center d-block'>" . $row->status . "</label>";
                })

                ->addColumn('action', function ($row) {
                    $btn = '<div class="dropdown">
                    <button class="btn btn-sm btn-block btn-danger dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                         <a class="contractor_grand_detail dropdown-item" data-id="' . $row->id . "," . $row->name . '" href="#">View</a>
                    </div>
                    </div>';
                    return $btn;
                })

                ->setFilteredRecords($all_data_count)
                ->setTotalRecords($data->count())
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
    }



    function finalReceiptForClient(Request $req, $client_id, $invoice_no)
    {


        $client_id = $client_id;
        $get_invoice = $invoice_no;

        $firstArray = supplierData::with("getOneRecordClient")->where("invoice_no", $invoice_no)->where("supplier_id", $client_id)->get()->toArray();
        $secondArray = supplierData::select(DB::raw('SUM(total) as grand_total, SUM(quantity) as grand_quantity , SUM(amount) as grand_amount , scope, invoice_no, supplier_id'))
            ->where("invoice_no", $invoice_no)
            ->where("supplier_id", $client_id)
            ->groupBy('scope', 'invoice_no', 'supplier_id') // Group by the 'scope' column
            ->orderBy('created_at', 'desc')
            ->get()->toArray();


        $data = [];
        foreach ($firstArray as $firstItem) {
            foreach ($secondArray as $secondItem) {
                if (
                    $firstItem['scope'] === $secondItem['scope'] &&
                    $firstItem['invoice_no'] === $secondItem['invoice_no'] &&
                    $firstItem['supplier_id'] === $secondItem['supplier_id']
                ) {
                    $data[] = array_merge($firstItem, $secondItem);
                }
            }
        }




        $expense_record = ExpenseDetail::with("getClientData")->where("client_id", $client_id)->get();

        $contractor_info = partnership_detail::with("getContractor")->where("client_id", $client_id)->where("invoice_no", $invoice_no)->get();

        $invoice_data_for_approval = $client_id . "," . $get_invoice;

        $notes = FinalNote::where("client_id", $client_id)->get();

        return view("old_design.all_view.final-receipt-for-client", compact("notes", "contractor_info", "expense_record", "data", "client_id", "get_invoice", "invoice_data_for_approval"));
    }

    function updateUserRole(Request $req)
    {

        $user_id = $req->user_id;
        $role = $req->role;

        $user = User::find($user_id);
        $user->role = $role;
        $user->save();
        return response()->json("saved", 200);
    }


    function usersListView()
    {

        return view("old_design.all_view.user-detail");
    }

    function usersList(Request $req)
    {


        if ($req->ajax()) {



            $all_data_count = User::count();


            $data = User::offset($req->start)->limit(10)->orderBy("id", "desc");



            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('email', function ($row) {
                    return $row->email;
                })
                ->addColumn('date', function ($row) {
                    return "<label class='text-center d-block'>" . date_format(date_create($row->created_at), "d-m-Y h:m:i") . "</label>";
                })
                ->addColumn('role', function ($row) {
                    return '<label class="text-center d-block">' . $row->role . '</label>';
                })

                ->addColumn('action', function ($row) {
                    $btn = '<div class="text-center">
                <input type="radio" ' . ($row->role == "Admin" ? "checked" : "") . ' name="role_id' . $row->id . '" class="role_id" value="Admin,' . $row->id . '">Admin
                <input type="radio" ' . ($row->role == "User" ? "checked" : "") . ' name="role_id' . $row->id . '" class="role_id" value="User,' . $row->id . '">User
            </div>';
                    $btn .= '</div></div>';
                    return $btn;
                })

                ->setFilteredRecords($all_data_count)
                ->setTotalRecords($data->count())
                ->rawColumns(['action', 'date', 'role'])
                ->make(true);
        }
    }

    function logout()
    {

        Auth::logout();
        return redirect('/login');
    }

    function invoicePDF(Request $req, $invoice_no, $client_id)
    {

        // set_time_limit(120);

        $client_id = $client_id;
        $get_invoice = $invoice_no;
        $data = supplierData::with("getOneRecordClient")->where("invoice_no", $invoice_no)->where("supplier_id", $client_id)->get();


        $invoice_data_for_approval = $client_id . "," . $get_invoice;



        $pdf = PDF::loadView("old_design.all_view.quotation-view-pdf", compact("data", "client_id", "get_invoice", "invoice_data_for_approval"));
        $file = $pdf->download('supplier_pdf.pdf');

        // // Return the file with appropriate headers
        return response()->json([base64_encode($file)], 200);

        // return view("old_design.all_view.quotation-view-pdf", compact("data", "client_id", "get_invoice", "invoice_data_for_approval"));

    }

    function deleteItem(Request $req)
    {

        if ($req->ajax()) {
            $quotation = supplierData::find($req->id);
            $quotation->delete();
            return response()->json("success", 200);
        }
    }
    function finalReceipt(Request $req, $client_id, $invoice_no)
    {

        $client_id = $client_id;
        $get_invoice = $invoice_no;

        $firstArray = supplierData::with("getOneRecordClient")->where("invoice_no", $invoice_no)->where("supplier_id", $client_id)->get()->toArray();
        $secondArray = supplierData::select(DB::raw('SUM(total) as grand_total, SUM(quantity) as grand_quantity , SUM(amount) as grand_amount , scope, invoice_no, supplier_id'))
            ->where("invoice_no", $invoice_no)
            ->where("supplier_id", $client_id)
            ->groupBy('scope', 'invoice_no', 'supplier_id') // Group by the 'scope' column
            ->get()->toArray();


        $data = [];
        foreach ($firstArray as $firstItem) {
            foreach ($secondArray as $secondItem) {
                if (
                    $firstItem['scope'] === $secondItem['scope'] &&
                    $firstItem['invoice_no'] === $secondItem['invoice_no'] &&
                    $firstItem['supplier_id'] === $secondItem['supplier_id']
                ) {
                    $data[] = array_merge($firstItem, $secondItem);
                }
            }
        }




        $expense_record = ExpenseDetail::with("getClientData")->where("client_id", $client_id)->get();

        $contractor_info = partnership_detail::with("getContractor")->where("client_id", $client_id)->where("invoice_no", $invoice_no)->get();

        $invoice_data_for_approval = $client_id . "," . $get_invoice;

        $notes = FinalNote::where("client_id", $client_id)->get();

        return view("old_design.all_view.final-receipt-new", compact("notes", "contractor_info", "expense_record", "data", "client_id", "get_invoice", "invoice_data_for_approval"));
    }

    function editPartnershipDetail(Request $req)
    {

        return partnership_detail::find($req->id);
    }


    function insertContractorPercentage(Request $req)
    {

        // Define validation rules
        $validationRules = [
            'client_id' => [
                'required',
                'numeric',
                Rule::unique('partnership_details')
                    ->where('contractor_id', $req->contractor_id)
                    ->ignore($req->hidden_buyer_purchaser_id),
            ],
            'contractor_id' => [
                'required',
                'numeric',
                Rule::unique('partnership_details')
                    ->where('client_id', $req->client_id)
                    ->ignore($req->hidden_buyer_purchaser_id),
            ],
            'invoice_no' => 'required|string',
            'percentage' => 'required|numeric',
        ];

        // Validate the request
        $validator = Validator::make($req->all(), $validationRules);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }


        // Proceed with insert or update
        if ($req->hidden_buyer_purchaser_id) {
            // Update existing record
            $percentage = partnership_detail::find($req->hidden_buyer_purchaser_id);
        } else {
            // Insert new record
            $percentage = new partnership_detail();
        }

        $percentage->client_id = $req->client_id;
        $percentage->contractor_id = $req->contractor_id;
        $percentage->invoice_no = $req->invoice_no;
        $percentage->percentage = $req->percentage;
        $percentage->save();

        return response()->json("Saved", 200);
    }


    function getContractorPercentageList(Request $req, $client_id, $invoice_no)
    {


        if ($req->ajax()) {

            if ($req->search) {
                $query = partnership_detail::with('getContractor')
                    ->where("name", "like", '%' . $req->search . '%');
            } else {
                $query = partnership_detail::with('getContractor')
                    ->where('client_id', $client_id)
                    ->where('invoice_no', $invoice_no);
            }

            $total_count = $query->count();

            $data = $query->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");

            return DataTables::of($data)
                ->addColumn('contractor', function ($row) {
                    return $row->getContractor->name;
                })
                ->addColumn('percentage', function ($row) {
                    return $row->percentage;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="dropdown">
                    <button class="btn btn-sm btn-block btn-danger dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                         <a class="edit_partnership_detail dropdown-item" data-id="' . $row->id . '" href="#">Edit</a>
                    </div>
                    </div>';
                    return $btn;
                })
                ->setFilteredRecords($total_count)
                ->setTotalRecords($total_count) // Use the total count directly instead of counting again
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
    }
    function addContractorPercentage(Request $req, $client_id, $invoice_no)
    {


        if ($req->ajax()) {

            $contractors = contractor_information::all();
            $html = [];
            $html["title"] = "Add Contractor Percentage";
            $html["view"] = view("old_design.all_view.add-contractor-percentaga", compact("contractors", "client_id", "invoice_no"))->render();
            return response()->json($html, 200);
        }

        // return view("old_design.all_view.add-contractor-percentaga");

    }


    function contractorInfoView(Request $req, $id)
    {

        if ($req->ajax()) {

            $supplier = contractor_information::find($id);
            $html = [];
            $html["title"] = "Contractor Information";
            $html["view"] = view("new_design.new_design_view.supplier-info-view", compact("supplier"))->render();
            return response()->json($html, 200);
        }
    }

    function updateContractorStatus(Request $req)
    {

        $id = $req->id;
        $buyer_purchaser_record = contractor_information::find($id);
        $buyer_purchaser_record->status == "On" ? $buyer_purchaser_record->status = "Off" : $buyer_purchaser_record->status = "On";
        $buyer_purchaser_record->save();
        return response()->json("update", 200);
    }

    function getContractorList(Request $req)
    {


        if ($req->ajax()) {

            if ($req->search) {
                $query = contractor_information::where("name", "like", '%' . $req->search . '%');
            } else {
                $query = contractor_information::query();
            }

            $total_count = $query->count();

            $data = $query->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");

            return DataTables::of($data)
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('phone_no', function ($row) {
                    return $row->phone_no;
                })
                ->addColumn('status', function ($row) {
                    $statusClass = $row->status == "On" ? 'btn-success' : 'btn-danger';
                    $btn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="update_status_buyer_purchaser_detail btn-block btn btn-sm ' . $statusClass . '">' . $row->status . '</a>';
                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="dropdown">
                    <button class="btn btn-sm btn-block btn-danger dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                         <a class="edit_buyer_purchaser_detail dropdown-item" data-id="' . $row->id . '" href="#">Edit</a>
                        <a class="view_buyer_purchaser_detail dropdown-item" data-id="' . $row->id . '" href="#">View</a>
                        
                    </div>
                    </div>';
                    return $btn;
                })
                ->setFilteredRecords($total_count)
                ->setTotalRecords($total_count) // Use the total count directly instead of counting again
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
    }


    function insertContractorInfo(Request $req)
    {

        $validation = [
            'name' => 'required',
        ];

        // if ($req->has('hidden_buyer_purchaser_id')) {
        //     $validation['phone_no'] = [
        //         'required',
        //         Rule::unique('buyer_purchaser_details', 'phone_no')->ignore($req->hidden_buyer_purchaser_id),
        //     ];
        // } else {
        //     $validation['phone_no'] = 'required|unique:buyer_purchaser_details,phone_no';
        // }


        $validator = Validator::make($req->all(), $validation);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        if ($req->hidden_buyer_purchaser_id) {
            $buyer_purchaser = contractor_information::find($req->hidden_buyer_purchaser_id);
        } else {
            $buyer_purchaser = new contractor_information();
        }
        $buyer_purchaser->name = $req->name;
        $buyer_purchaser->phone_no = $req->phone_no;
        $buyer_purchaser->account_no = $req->account_no;
        $buyer_purchaser->cnic = $req->cnic;
        $buyer_purchaser->address = $req->address;
        $buyer_purchaser->save();
        return response()->json("saved", 200);
    }



    function test()
    {
        return view("old_design.all_view.test");
    }

    function insertLastReceipt(Request $req)
    {

        $data = $req->all();
        $withHidden = [];
        $withoutHidden = [];

        $created_at = Carbon::now();
        $updated_at = Carbon::now();




        foreach ($data["supplier_data"] as $item) {
            if ($item['hidden'] == "") {
                unset($item["hidden"]);
                $item['client_id'] = $req->client_id;
                $item['created_at'] = $created_at;
                $item['updated_at'] = $updated_at;
                $withoutHidden[] = $item;
            } else {
                $withHidden[] = $item;
            }
        }


        $update_array = $withHidden;
        if (count($withHidden) > 0) {

            $updateQuery = 'UPDATE expense_details SET ' . implode(', ', array_map(function ($data) {
                return 'head = CASE WHEN id = ' . $data['hidden'] . ' THEN "' . $data['head'] . '" ELSE head END, ' .
                    'quantity = CASE WHEN id = ' . $data['hidden'] . ' THEN "' . $data['quantity'] . '" ELSE quantity END, ' .
                    'amount = CASE WHEN id = ' . $data['hidden'] . ' THEN "' . $data['amount'] . '" ELSE amount END, ' .
                    'total = CASE WHEN id = ' . $data['hidden'] . ' THEN "' . $data['total'] . '" ELSE total END';
            }, $update_array));

            $updateQuery .= ' WHERE id IN (' . implode(',', array_column($update_array, 'hidden')) . ')';

            DB::statement($updateQuery);
        }


        ExpenseDetail::insert($withoutHidden);
        return response()->json("saved");

        return false;
    }

    function lastReceipt(Request $req, $client_id, $invoice_no)
    {
        $expense = ExpenseDetail::where("client_id", $client_id)->get();

        return view("old_design.all_view.last-receipt", compact("client_id", "invoice_no", "expense"));
    }

    function contractorInfo()
    {

        // $html = [];
        // $html["title"] = "Contractor Information Form";
        // $html["view"] = view("old_design.all_view.contractor-infor")->render();
        // return response()->json($html, 200);
        return  view("old_design.all_view.contractor-infor");
    }

    function deleteInvoice(Request $req)
    {

        if ($req->ajax()) {
            $invoices = SupplierData::where('invoice_no', $req->invoice_no)->get(); // Retrieve all invoices matching the criteria
            foreach ($invoices as $invoice) {
                // Delete related records (assuming you have defined the relationships)
                $invoice->getMultipleClient()->delete();
                // Then delete the invoice itself
                $invoice->delete();
            }
            return response()->json("deleted", 200);
        }
    }

    function editQuotation(Request $req, $invoice_no)
    {

        $invoice_data =  supplierData::with("getOneRecordOfClient")->where("invoice_no", $invoice_no)->get();
        return view("old_design.all_view.quotation-old", compact("invoice_data", "invoice_no"));
    }

    function quotationOld()
    {

        return view("old_design.all_view.quotation-old");
    }


    function clientRegisterationOld()
    {


        $html = [];
        $html["title"] = "Client Information Form";
        $html["view"] = view("old_design.all_view.client-registeration")->render();
        return response()->json($html, 200);

        // return view("old_design.all_view.client-registeration");
    }


    function oldHome()
    {

        return view("old_design.all_view.old-home");
    }


    function supplierInfoView(Request $req, $id)
    {

        if ($req->ajax()) {

            $supplier = BuyerPurchaserDetail::find($id);
            $html = [];
            $html["title"] = "Client Information";
            $html["view"] = view("new_design.new_design_view.supplier-info-view", compact("supplier"))->render();
            return response()->json($html, 200);
        }
    }

    function buyerPurchaserRecordStatusUpdate(Request $req)
    {

        $id = $req->id;
        $buyer_purchaser_record = BuyerPurchaserDetail::find($id);
        return response()->json($buyer_purchaser_record, 200);
    }


    function updateStatusBuyerPurchaserDetail(Request $req)
    {

        $id = $req->id;
        $buyer_purchaser_record = BuyerPurchaserDetail::find($id);
        $buyer_purchaser_record->status == "On" ? $buyer_purchaser_record->status = "Off" : $buyer_purchaser_record->status = "On";
        $buyer_purchaser_record->save();
        return response()->json("update", 200);
    }


    function insertBuyerPurchaserRecord(Request $req)
    {

        $validation = [
            'name' => 'required',
        ];

        // if ($req->has('hidden_buyer_purchaser_id')) {
        //     $validation['phone_no'] = [
        //         'required',
        //         Rule::unique('buyer_purchaser_details', 'phone_no')->ignore($req->hidden_buyer_purchaser_id),
        //     ];
        // } else {
        //     $validation['phone_no'] = 'required|unique:buyer_purchaser_details,phone_no';
        // }


        $validator = Validator::make($req->all(), $validation);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        if ($req->hidden_buyer_purchaser_id) {
            $buyer_purchaser = BuyerPurchaserDetail::find($req->hidden_buyer_purchaser_id);
        } else {
            $buyer_purchaser = new BuyerPurchaserDetail();
        }

        $buyer_purchaser->name = $req->name;
        $buyer_purchaser->phone_no = $req->phone_no;
        $buyer_purchaser->account_no = $req->account_no;
        $buyer_purchaser->cnic = $req->cnic;
        $buyer_purchaser->address = $req->address;
        $buyer_purchaser->opening_amount = $req->opening_amount;
        $buyer_purchaser->save();
        return response()->json("saved", 200);
    }

    function getSupplierList(Request $req)
    {

        if ($req->ajax()) {

            if ($req->search) {
                $query = BuyerPurchaserDetail::where("name", "like", '%' . $req->search . '%');
            } else {
                $query = BuyerPurchaserDetail::query();
            }

            $total_count = $query->count();

            $data = $query->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");

            return DataTables::of($data)
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('phone_no', function ($row) {
                    return $row->phone_no;
                })
                ->addColumn('status', function ($row) {
                    $statusClass = $row->status == "On" ? 'btn-success' : 'btn-danger';
                    $btn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="update_status_buyer_purchaser_detail btn-block btn btn-sm ' . $statusClass . '">' . $row->status . '</a>';
                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="dropdown">
                    <button class="btn btn-sm btn-block btn-danger dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                         <a class="edit_buyer_purchaser_detail dropdown-item" data-id="' . $row->id . '" href="#">Edit</a>
                        <a class="view_buyer_purchaser_detail dropdown-item" data-id="' . $row->id . '" href="#">View</a>
                        
                    </div>
                    </div>';
                    return $btn;
                })
                ->setFilteredRecords($total_count)
                ->setTotalRecords($total_count) // Use the total count directly instead of counting again
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
    }


    function clientRegisteration()
    {


        return view("new_design.client-registeration");
        $html = [];
        $html["title"] =  "Client Registeration";
        $html["view"] = view("academic.view-qurbani-data", compact("get_qurbani_data"))->render();
        return response()->json($html, 200);
    }



    function updateQuotationStatus(Request $req)
    {

        $string = $req->data["data_for_update"];

        $array_for_approval = explode(",", $string);

        //supplier id is actually client id
        $supplier_id = $array_for_approval[0];
        $invoice_no = $array_for_approval[1];
        $status = $req->data["status"];

        if ($status == 0) {
            $status = 1;
        } elseif ($status == 1) {
            $status = 0;
        }
        supplierData::where('supplier_id', '=', $supplier_id)
            ->where('invoice_no', '=', $invoice_no)
            ->update(['status' => $status]);

        return response()->json($status, "200");
    }


    function insertSupplierData(Request $req)
    {



        $data = $req->all();

        $created_at = Carbon::now();
        $updated_at = Carbon::now();

        if ($req->invoice_no) {

            $withHidden = [];
            $withoutHidden = [];
            $check = 0;

            // echo "<pre>";
            // print_r($data["supplier_data"]);
            // echo "</pre>";
            // return false;

            foreach ($data["supplier_data"] as $item) {

                if ($item['hidden'] == "") {

                    unset($item["hidden"]);
                    $item['invoice_no'] = $req->invoice_no;
                    $item['created_at'] = $created_at;
                    $item['updated_at'] = $updated_at;
                    $item['supplier_id'] = $data["hidden_supplier_id"];

                    $check++;
                    if ($check == 1) {
                        $check_status = supplierData::where("invoice_no", $data["invoice_no"])->latest()->first();

                        if ($check_status) {
                            $status = $check_status->status;
                        } else {
                            $status = 0;
                        }
                    }
                    $item['status'] = $status;
                    $withoutHidden[] = $item;
                } else {

                    $withHidden[] = $item;
                }
            }




            // return false;


            $update_array = $withHidden;
            if (count($withHidden) > 0) {
                //    echo "<pre>";
                //     print_r($withHidden);
                //     echo "</pre>";
                //     return false;


                // echo "<pre>";
                // print_r($withHidden);
                // echo "</pre>";

                // return false;
                $updateQuery = 'UPDATE supplier_data SET ' . implode(', ', array_map(function ($update_array) {
                    $escapedHead = addslashes($update_array['head']);
                    return 'scope = CASE WHEN id = ' . $update_array['hidden'] . ' THEN "' . $update_array['scope'] . '" ELSE scope END, ' .
                        'head = CASE WHEN id = ' . $update_array['hidden'] . ' THEN "' . $escapedHead . '" ELSE head END, ' .
                        'quantity = CASE WHEN id = ' . $update_array['hidden'] . ' THEN "' . $update_array['quantity'] . '" ELSE quantity END, ' .
                        'amount = CASE WHEN id = ' . $update_array['hidden'] . ' THEN "' . $update_array['amount'] . '" ELSE amount END, ' .
                        'include_or_exclude = CASE WHEN id = ' . $update_array['hidden'] . ' THEN "' . $update_array['include_or_exclude'] . '" ELSE include_or_exclude END, ' .
                        'total = CASE WHEN id = ' . $update_array['hidden'] . ' THEN "' . $update_array['total'] . '" ELSE total END';
                }, $update_array));

                $updateQuery .= ' WHERE id IN (' . implode(',', array_column($update_array, 'hidden')) . ')';
                DB::statement($updateQuery);
            }

            if (count($withoutHidden) > 0) {
                supplierData::insert($withoutHidden);
                // return response()->json("saved", 200);
            }


            $data["buyer_purchaser_data"]["name"];
            $hidden_client_id =  $data["hidden_supplier_id"];
            $client_search = BuyerPurchaserDetail::find($hidden_client_id);
            $client_search->name = $data["buyer_purchaser_data"]["name"];
            $client_search->phone_no = $data["buyer_purchaser_data"]["phone_no"];
            $client_search->address = $data["buyer_purchaser_data"]["address"];
            $client_search->trn_no = $data["buyer_purchaser_data"]["trn_no"];
            if (isset($data["buyer_purchaser_data"]["tax"])) {
                $client_search->tax = $data["buyer_purchaser_data"]["tax"];
            }
            if (isset($data["buyer_purchaser_data"]["recieved_payment"])) {
                $client_search->recieved_payment = $data["buyer_purchaser_data"]["recieved_payment"];
            }
            $client_search->save();

            return response()->json("saved");
        }

        if (count($data["supplier_data"]) > 0) {
            $client_data = new BuyerPurchaserDetail();
            $client_data->name = $data["buyer_purchaser_data"]["name"];
            $client_data->phone_no = $data["buyer_purchaser_data"]["phone_no"];
            $client_data->address = $data["buyer_purchaser_data"]["address"];
            $client_data->trn_no = $data["buyer_purchaser_data"]["trn_no"];
            if (isset($data["buyer_purchaser_data"]["tax"])) {
                $client_data->tax = $data["buyer_purchaser_data"]["tax"];
            }
            if (isset($data["buyer_purchaser_data"]["recieved_payment"])) {
                $client_data->recieved_payment = $data["buyer_purchaser_data"]["recieved_payment"];
            }
            $client_data->save();




            $last_invoice_no = supplierData::latest()->value('invoice_no');

            if (!$last_invoice_no) {
                $last_invoice_no = 1000;
            } else {
                $last_invoice_no = $last_invoice_no + 1;
            }


            foreach ($data["supplier_data"] as $key => $get_data) {

                // $data["supplier_data"][$key]["date"] = $date;
                $data["supplier_data"][$key]["invoice_no"] = $last_invoice_no;
                $data["supplier_data"][$key]["supplier_id"] = $client_data->id;
                $data["supplier_data"][$key]["head"] = $get_data["head"];
                $data["supplier_data"][$key]["quantity"] = $get_data["quantity"];
                $data["supplier_data"][$key]["amount"] = $get_data["amount"];
                $data["supplier_data"][$key]["total"] = $get_data["total"];
                $data["supplier_data"][$key]["created_at"] = $created_at;
                $data["supplier_data"][$key]["updated_at"] = $updated_at;
                unset($data["supplier_data"][$key]["hidden"]);
            }

            $final_array_insert =  $data["supplier_data"];


            supplierData::insert($final_array_insert);
            return response()->json("saved");
        }
    }


    function buyerPurchaserList(Request $req)
    {

        $data = BuyerPurchaserDetail::where("status", "On")->orderBy("id", "DESC")->get();

        return response()->json($data, 200);
    }

    function getListofQuotation(Request $req)
    {

        if ($req->ajax()) {

            if (($req->status == "0" || $req->status == "1") && $req->from_date && $req->to_date && $req->search_data_value) {

                $search = $req->search_data_value;

                $all_data_count = supplierData::leftJoin('buyer_purchaser_details', 'buyer_purchaser_details.id', '=', 'supplier_data.supplier_id')
                    ->where('supplier_data.status', $req->status)
                    ->whereDate('supplier_data.created_at', ">=", $req->from_date)
                    ->whereDate('supplier_data.created_at', "<=", $req->to_date)
                    ->where('buyer_purchaser_details.name', 'like', '%' . $search . '%')
                    ->orwhere('buyer_purchaser_details.phone_no', 'like', '%' . $search . '%')
                    ->orwhere('buyer_purchaser_details.address', 'like', '%' . $search . '%')
                    ->select('supplier_data.invoice_no', 'buyer_purchaser_details.name', 'buyer_purchaser_details.phone_no', 'buyer_purchaser_details.address', 'supplier_data.status', 'buyer_purchaser_details.id')
                    ->groupBy('supplier_data.invoice_no', 'buyer_purchaser_details.name', 'buyer_purchaser_details.phone_no', 'buyer_purchaser_details.address', 'supplier_data.status', 'buyer_purchaser_details.id')
                    ->get()->count();

                $data = supplierData::leftJoin('buyer_purchaser_details', 'buyer_purchaser_details.id', '=', 'supplier_data.supplier_id')
                    ->where('supplier_data.status', $req->status)
                    ->whereDate('supplier_data.created_at', ">=", $req->from_date)
                    ->whereDate('supplier_data.created_at', "<=", $req->to_date)
                    ->where('buyer_purchaser_details.name', 'like', '%' . $search . '%')
                    ->orwhere('buyer_purchaser_details.phone_no', 'like', '%' . $search . '%')
                    ->orwhere('buyer_purchaser_details.address', 'like', '%' . $search . '%')
                    ->select('supplier_data.invoice_no', 'buyer_purchaser_details.name', 'buyer_purchaser_details.phone_no', 'buyer_purchaser_details.address', 'supplier_data.status', 'buyer_purchaser_details.id')
                    ->groupBy('supplier_data.invoice_no', 'buyer_purchaser_details.name', 'buyer_purchaser_details.phone_no', 'buyer_purchaser_details.address', 'supplier_data.status', 'buyer_purchaser_details.id')
                    ->offset($req->start)->limit(10)->orderBy("supplier_data.id", "desc");
            } elseif ($req->search_data_value) {

                $search = $req->search_data_value;

                $all_data_count = supplierData::leftJoin('buyer_purchaser_details', 'buyer_purchaser_details.id', '=', 'supplier_data.supplier_id')
                    ->where('buyer_purchaser_details.name', 'like', '%' . $search . '%')
                    ->orwhere('buyer_purchaser_details.phone_no', 'like', '%' . $search . '%')
                    ->orwhere('buyer_purchaser_details.address', 'like', '%' . $search . '%')
                    ->select('supplier_data.invoice_no', 'buyer_purchaser_details.name', 'buyer_purchaser_details.phone_no', 'buyer_purchaser_details.address', 'supplier_data.status', 'buyer_purchaser_details.id')
                    ->groupBy('supplier_data.invoice_no', 'buyer_purchaser_details.name', 'buyer_purchaser_details.phone_no', 'buyer_purchaser_details.address', 'supplier_data.status', 'buyer_purchaser_details.id')
                    ->get()->count();

                $data = supplierData::leftJoin('buyer_purchaser_details', 'buyer_purchaser_details.id', '=', 'supplier_data.supplier_id')
                    ->where('buyer_purchaser_details.name', 'like', '%' . $search . '%')
                    ->orwhere('buyer_purchaser_details.phone_no', 'like', '%' . $search . '%')
                    ->orwhere('buyer_purchaser_details.address', 'like', '%' . $search . '%')
                    ->select('supplier_data.invoice_no', 'buyer_purchaser_details.name', 'buyer_purchaser_details.phone_no', 'buyer_purchaser_details.address', 'supplier_data.status', 'buyer_purchaser_details.id')
                    ->groupBy('supplier_data.invoice_no', 'buyer_purchaser_details.name', 'buyer_purchaser_details.phone_no', 'buyer_purchaser_details.address', 'supplier_data.status', 'buyer_purchaser_details.id')
                    ->offset($req->start)->limit(10)->orderBy("supplier_data.id", "desc");
            } elseif (($req->status == "0" || $req->status == "1") && $req->from_date && $req->to_date) {


                $all_data_count = supplierData::where('supplier_data.status', $req->status)
                    ->whereDate('supplier_data.created_at', ">=", $req->from_date)
                    ->whereDate('supplier_data.created_at', "<=", $req->to_date)
                    ->select('supplier_data.supplier_id')
                    ->groupBy('supplier_data.supplier_id')
                    ->get()->count();

                $data = supplierData::leftJoin('buyer_purchaser_details', 'buyer_purchaser_details.id', '=', 'supplier_data.supplier_id')
                    ->where('supplier_data.status', $req->status)
                    ->whereDate('supplier_data.created_at', ">=", $req->from_date)
                    ->whereDate('supplier_data.created_at', "<=", $req->to_date)
                    ->select('supplier_data.invoice_no', 'buyer_purchaser_details.name', 'buyer_purchaser_details.phone_no', 'buyer_purchaser_details.address', 'supplier_data.status', 'buyer_purchaser_details.id')
                    ->groupBy('supplier_data.invoice_no', 'buyer_purchaser_details.name', 'buyer_purchaser_details.phone_no', 'buyer_purchaser_details.address', 'supplier_data.status', 'buyer_purchaser_details.id')
                    ->offset($req->start)->limit(10)->orderBy("supplier_data.id", "desc");
            } elseif ($req->status == "0" || $req->status == "1") {

                $all_data_count = supplierData::where('supplier_data.status', $req->status)
                    ->select('supplier_data.invoice_no')
                    ->groupBy('supplier_data.invoice_no')
                    ->get()->count();

                $data = supplierData::leftJoin('buyer_purchaser_details', 'buyer_purchaser_details.id', '=', 'supplier_data.supplier_id')
                    ->where('supplier_data.status', $req->status)
                    ->select('supplier_data.invoice_no', 'buyer_purchaser_details.name', 'buyer_purchaser_details.phone_no', 'buyer_purchaser_details.address', 'supplier_data.status', 'buyer_purchaser_details.id')
                    ->groupBy('supplier_data.invoice_no', 'buyer_purchaser_details.name', 'buyer_purchaser_details.phone_no', 'buyer_purchaser_details.address', 'supplier_data.status', 'buyer_purchaser_details.id')
                    ->offset($req->start)->limit(10)->orderBy("supplier_data.id", "desc");
            } else {

                $all_data_count = BuyerPurchaserDetail::count();

                $data = supplierData::leftJoin('buyer_purchaser_details', 'buyer_purchaser_details.id', '=', 'supplier_data.supplier_id')
                    ->select('buyer_purchaser_details.id as client_id', 'supplier_data.invoice_no', 'buyer_purchaser_details.name', 'buyer_purchaser_details.phone_no', 'buyer_purchaser_details.address', 'supplier_data.status', 'buyer_purchaser_details.id')
                    ->groupBy('buyer_purchaser_details.id', 'supplier_data.invoice_no', 'buyer_purchaser_details.name', 'buyer_purchaser_details.phone_no', 'buyer_purchaser_details.address', 'supplier_data.status', 'buyer_purchaser_details.id')
                    ->offset($req->start)->limit(10)->orderBy("supplier_data.id", "desc");
            }


            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('invoice_no', function ($row) {
                    return $row->invoice_no;
                })
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('phone_no', function ($row) {
                    return $row->phone_no;
                })
                ->addColumn('address', function ($row) {
                    return $row->address;
                })
                ->addColumn('date', function ($row) {
                    return date_format(date_create($row->created_at), "d-m-Y h:m:i");
                })

                ->addColumn('status', function ($row) {
                    return $row->status == 0 ? "<label style='color:red; text-align:center; display:block;'>Not Approved</label>" : "<label style='color:green;text-align:center;display:block;'>Approved</label>";
                })

                ->addColumn('action', function ($row) {

                    $btn = '<div class="btn-group btn-sm">
                    <button type="button" class="btn btn-sm btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                    </button>
                    <div class="dropdown-menu">
                        <a href="' . url('view-invoice') . '/' . $row->invoice_no . '/' . $row->id . '" class="dropdown-item"><i class="fas fa-eye"></i> View</a>
                        <a href="javascript:void(0)" data-id = "' . $row->client_id . '"  class="dropdown-item final-notes" > <i class="fas fa-regular fa-clipboard"></i> Final Notes</a>
                        <a href="' . url('edit-quotation') . '/' . $row->invoice_no . '"  class="dropdown-item" ><i class="fas fa-pencil-alt"></i> Edit</a>
                        <a href="javascript:void(0)" class="dropdown-item delete_invoice" data-id="' . $row->invoice_no . '"><i class="fas fa-trash-alt"></i>
                         Delete</a>';

                    if ($row->status == 1) {

                        $btn .= '<a href="' . url('last-receipt') . '/' . $row->id . '/' . $row->invoice_no . '" class="dropdown-item"><i class="fas fa-coins"></i> Add Expense</a>';
                        $btn .= '<a href="' . url('final-receipt-for-client/') . '/' . $row->id . '/' . $row->invoice_no . '" class="dropdown-item"><i class="fas fa-receipt"></i> Client Receipt</a>';
                        $btn .= '<a href="' . url('final-receipt') . '/' . $row->id . '/' . $row->invoice_no . '" class="dropdown-item"><i class="fas fa-receipt"></i> Final Receipt</a>';
                        $btn .= ' <a href="javascript:void(0)" data-id = "' . $row->client_id . '"  class="dropdown-item daily-work" > <i class="far fa-calendar"></i> Daily Work</a>';
                    }

                    $btn .= '</div>
                    </div></div>
                    </div>';
                    return $btn;
                })
                ->setFilteredRecords($all_data_count)
                ->setTotalRecords($data->count())
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
    }


    function quotationList(Request $req)
    {
        return view("new_design.new_design_view.quotation-list");
    }


    function viewInvoice(Request $req, $invoice_no = null, $client_id = null)
    {



        $client_id = $client_id;
        $get_invoice = $invoice_no;
        $firstArray = supplierData::with("getOneRecordClient")->where("invoice_no", $invoice_no)->where("supplier_id", $client_id)
            ->orderBy("created_at", "desc") // Then sorting by created_at
            ->get()->toArray();



        $secondArray = supplierData::select(
            DB::raw('SUM(CASE WHEN include_or_exclude = 1 THEN total ELSE total END) as grand_total'),
            DB::raw('SUM(CASE WHEN include_or_exclude = 1 THEN quantity ELSE quantity END) as grand_quantity'),
            DB::raw('SUM(CASE WHEN include_or_exclude = 1 THEN amount ELSE amount END) as grand_amount'),
            'scope',
            'invoice_no',
            'supplier_id'
        )
            ->where("invoice_no", $invoice_no)
            ->where("supplier_id", $client_id)
            ->groupBy('scope', 'invoice_no', 'supplier_id') // Group by the 'scope' column
            ->orderBy("created_at", "desc") // Then sorting by created_at
            ->get()
            ->toArray();



        $data = [];
        foreach ($firstArray as $firstItem) {
            foreach ($secondArray as $secondItem) {
                if (
                    $firstItem['scope'] === $secondItem['scope'] &&
                    $firstItem['invoice_no'] === $secondItem['invoice_no'] &&
                    $firstItem['supplier_id'] === $secondItem['supplier_id']
                ) {
                    $data[] = array_merge($firstItem, $secondItem);
                }
            }
        }


        $notes = FinalNote::where("client_id", $client_id)->get();
        $invoice_data_for_approval = $client_id . "," . $get_invoice;

        return view("old_design.all_view.quotation-view", compact("data", "client_id", "get_invoice", "invoice_data_for_approval", "notes"));

        // return view("new_design.new_design_view.invoice", compact("data", "client_id", "get_invoice", "invoice_data_for_approval"));
    }

    function quotation(Request $req)
    {

        return view("new_design.new_design_view.quotation");
    }




    function NewHome()
    {

        return view("new_design.home");
    }
}
