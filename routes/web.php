<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {


    // return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

Route::post('/insert-supplier-data', [HomeController::class, 'insertSupplierData']);

//contractor
Route::get('/new_home', [HomeController::class, 'NewHome']);

Route::get('/buyer-purchaser-list', [HomeController::class, 'buyerPurchaserList']);

Route::get('/quotation', [HomeController::class, 'quotation']);

//it return both pdf + view
Route::get('/get-quotation-pdf/{type}', [HomeController::class, 'getQotationPdf']);

Route::get('/get-profit-and-loss-report/{from_date}/{to_date}', [HomeController::class, 'getProfitAndLossReport']);

Route::get('/get-expense-report/{from_date}/{to_date}', [HomeController::class, 'getExpenseReport']);

Route::get('/view-invoice/{invoice_no}/{client_id}', [HomeController::class, 'viewInvoice']);

Route::get('/invoice-pdf/{invoice_no}/{client_id}', [HomeController::class, 'invoicePDF']);

Route::get('/quotation-list', [HomeController::class, 'quotationList']);

Route::get('/final-notes/{client_id}', [HomeController::class, 'finalNotes']);

Route::post('/insert-final-note', [HomeController::class, 'insertFinalNote']);

Route::get('/daily-work/{client_id}', [HomeController::class, 'dailayWork']);

Route::post('/insert-daily-work', [HomeController::class, 'insertDailyWork']);

Route::get('/daily-work-list/{client_id}', [HomeController::class, 'dailyWorkList']);

Route::get('/edit-daily-work', [HomeController::class, 'editDailyWork']);

Route::get('/get-employees', [HomeController::class, 'getEmployees']);

Route::post('/insert-bank-amount', [HomeController::class, 'insertBankAmount']);

Route::post('/delete-daily-work', [HomeController::class, 'deleteDailyWork']);

Route::get('/salary', [HomeController::class, 'salary']);

Route::post('/get-salary-pdf', [HomeController::class, 'getSalaryPdf']);

Route::get('/get-paid-salary/{month}', [HomeController::class, 'getPaidSalary']);

Route::post('/delete-salary-record', [HomeController::class, 'deleteSalaryRecord']);

Route::get('/get-salary-detail/{month}', [HomeController::class, 'getSalaryDetail']);

Route::get('/get-salary-upaid-detail/{month}', [HomeController::class, 'getSalaryUnpaidDetail']);

Route::get('/get-data-of-employee-salary', [HomeController::class, 'getDataOfEmployeeSalary']);

Route::get('/view-bank-form', [HomeController::class, 'viewBankForm']);

Route::get('/get-bank-expense-list', [HomeController::class, 'getBankExpenseList']);

Route::get('/edit-bank-amount', [HomeController::class, 'editBankAmount']);


Route::get('/expense-head', [HomeController::class, 'expenseHead']);

Route::post('/insert-expense-head', [HomeController::class, 'insertExpenseHead']);

Route::get('/edit-expense-or-employee', [HomeController::class, 'editExpenseOrEmployee']);

Route::post('/delete-expense-or-employee', [HomeController::class, 'deleteExpenseOrEmployee']);

Route::get('/pay-now-salary/{id}/{date}/{salary}/{name}/{joining}/{employee_post}', [HomeController::class, 'payNowSalary']);

Route::get('/check-pendings', [HomeController::class, 'checkPendings']);

Route::get('/pending-form', [HomeController::class, 'pendingForm']);

Route::get('/edit-pending-amount', [HomeController::class, 'editPendingAmount']);

Route::get('/delete-pending-amount', [HomeController::class, 'deletePendingAmount']);

Route::post('/insert-pending', [HomeController::class, 'insertPending']);

Route::get('/get-pending-list', [HomeController::class, 'getPendingList']);

Route::get('/check-advance-salary', [HomeController::class, 'checkAdvanceSalary']);

Route::post('/final-salary-insert', [HomeController::class, 'finalSalaryInsert']);

Route::get('/employee-and-head-view', [HomeController::class, 'employeeAndHeadView']);

Route::get('/edit-employee', [HomeController::class, 'editEmployee']);

Route::get('/view-employee-profile/{id}', [HomeController::class, 'viewEmployeeProfile']);

Route::post('/insert-employee-and-head', [HomeController::class, 'insertEmployeeAndHead']);

Route::get('/employee-and-head-list/{type}', [HomeController::class, 'employeeAndHeadList']);

Route::get('/users-list', [HomeController::class, 'usersList']);

Route::get('/users-list-view', [HomeController::class, 'usersListView']);

Route::post('/update-user-role', [HomeController::class, 'updateUserRole']);

Route::get('/get-list-of-quotation', [HomeController::class, 'getListOfQuotation']);

Route::get('/client-registeration', [HomeController::class, 'clientRegisteration']);

Route::get('/get-supplier-list', [HomeController::class, 'getSupplierList']);

Route::post('/insert-buyer-purchaser-record', [HomeController::class, 'insertBuyerPurchaserRecord']);

Route::post('/update-status-buyer-purchaser-detail', [HomeController::class, 'updateStatusBuyerPurchaserDetail']);

Route::post('/edit-buyer-purchaser-detail', [HomeController::class, 'buyerPurchaserRecordStatusUpdate']);

Route::post('/update-quotation-status', [HomeController::class, 'updateQuotationStatus']);

Route::get('/supplier-info-view/{id}', [HomeController::class, 'supplierInfoView']);

Route::get('/contractor-info-view/{id}', [HomeController::class, 'contractorInfoView']);

Route::get('/old-home', [HomeController::class, 'oldHome']);

Route::get('/client-registeration-old', [HomeController::class, 'clientRegisterationOld']);

Route::get('/edit-quotation/{invoice_no}', [HomeController::class, 'editQuotation']);

Route::get('/delete-invoice', [HomeController::class, 'deleteInvoice']);

Route::get('/quotation-old', [HomeController::class, 'quotationOld']);

Route::get('/contractor-info', [HomeController::class, 'contractorInfo']);

Route::get('/list-of-contractor-for-detail', [HomeController::class, 'listOfContractorForDetail']);

Route::get('/view-contractor-detail/{contractor_id}/{contractor_name}', [HomeController::class, 'viewContractorDetail']);

Route::get('/view-contractor-final-receipt/{client_id}/{invoice_no}', [HomeController::class, 'viewContractorFinalReceipt']);

Route::get('/list-of-contractor-for-detail-view', [HomeController::class, 'listOfContractorForDetailView']);

Route::post('/insert-contractor-info', [HomeController::class, 'insertContractorInfo']);

Route::get('/get-contractor-list', [HomeController::class, 'getContractorList']);

Route::post('/update-contractor-status', [HomeController::class, 'updateContractorStatus']);

Route::get('/last-receipt/{client_id}/{invoice_no}', [HomeController::class, 'lastReceipt']);

Route::get('/add-contractor-percentage/{client_id}/{invoice_no}', [HomeController::class, 'addContractorPercentage']);

Route::get('/get-contractor-percentage-list/{client_id}/{invoice_no}', [HomeController::class, 'getContractorPercentageList']);

Route::post('/insert-contractor-percentage', [HomeController::class, 'insertContractorPercentage']);

Route::post('/edit-partnership-detail', [HomeController::class, 'editPartnershipDetail']);

Route::get('/final-receipt/{client_id}/{invoice_no}', [HomeController::class, 'finalReceipt']);

Route::get('/final-receipt-for-client/{client_id}/{invoice_no}', [HomeController::class, 'finalReceiptForClient']);

Route::get('/test', [HomeController::class, 'test']);

Route::get('/list-of-notes/{client_id}', [HomeController::class, 'listOfNotes']);

Route::post('/edit-final-note', [HomeController::class, 'editFinalNote']);

Route::post('/delete-final-note', [HomeController::class, 'deleteFinalNote']);

Route::get('/delete-item', [HomeController::class, 'deleteItem']);

Route::get('/logout', [HomeController::class, 'logout']);

Route::post('/insert-last-receipt', [HomeController::class, 'insertLastReceipt']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
