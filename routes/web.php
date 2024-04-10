<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\AttendanceRegisterController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HumanResourceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RequisitionController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
Route::get('/', function () {
    return redirect('login');
})->name('site.index');

/*--------------------------------------
| Authentication Routes
|-------------------------------------*/

Auth::routes(['register' => False]);
Route::middleware(['preventBackHistory'])->group(function () {
    Route::middleware(['auth', 'view.share'])->group(function () {
        Route::group(['middleware' => ['auth']], function () {
            /**
             * Logout Route
             */
            Route::get('/logout', 'MonthController@perform')->name('logout.perform');
            Route::get('/logout', [\App\Http\Controllers\Auth\LogoutController::class, 'perform'])->name('logout.perform');

        });
        Route::get('/home', [HomeController::class, 'index'])->name('home');
        Route::get('members/merge', [\App\Http\Controllers\MemberController::class, 'memberMerge'])->name('member.merge');
        Route::post('members/merging', [\App\Http\Controllers\MemberController::class, 'merge'])->name('member.edit-merge');
        Route::get('assets/liabilities', [\App\Http\Controllers\AssetController::class, 'liabilities'])->name('liabilities');
        Route::get('assets/current', [\App\Http\Controllers\AssetController::class, 'currentAssets'])->name('current-assets');
        Route::get('assets/capital', [\App\Http\Controllers\AssetController::class, 'capital'])->name('capital');
        Route::resource('churches', \App\Http\Controllers\ChurchController::class);
        Route::resource('home-attendances', \App\Http\Controllers\HomeAttendanceController::class);
        Route::resource('payments', \App\Http\Controllers\PaymentController::class);
        Route::resource('purchases', \App\Http\Controllers\PurchaseController::class);
        Route::resource('ministries', \App\Http\Controllers\MinistryController::class);
        Route::resource('months', \App\Http\Controllers\MonthController::class);
        Route::resource('members', \App\Http\Controllers\MemberController::class);
        Route::resource('banks', \App\Http\Controllers\BankController::class);
        Route::resource('announcements', \App\Http\Controllers\AnnouncementController::class);
        Route::resource('accounts', \App\Http\Controllers\AccountController::class);
        Route::resource('finances', \App\Http\Controllers\AccountController::class);
        Route::resource('suppliers', \App\Http\Controllers\SupplierController::class);
        Route::resource('labourers', \App\Http\Controllers\LabourerController::class);
        Route::resource('prices', \App\Http\Controllers\PriceController::class);
        Route::resource('materials', \App\Http\Controllers\MaterialController::class);
        Route::resource('human-resources', \App\Http\Controllers\HumanResourceController::class);
        Route::get('employees/index', [\App\Http\Controllers\LabourerController::class, 'employeeIndex'])->name('labourers.employees');
        Route::resource('labours', \App\Http\Controllers\LabourController::class);
        Route::resource('departments', \App\Http\Controllers\DepartmentController::class);
        Route::resource('contracts', \App\Http\Controllers\ContractController::class);
        Route::resource('leaves', \App\Http\Controllers\LeaveController::class);
        Route::resource('attendances', \App\Http\Controllers\AttendanceController::class);
        Route::resource('receipts', \App\Http\Controllers\ReceiptController::class);
        Route::get('church/reports/', [\App\Http\Controllers\ReceiptController::class, 'churchReports'])->name('church.reports');
        Route::post('church/report/generate', [\App\Http\Controllers\ReceiptController::class, 'churchReportGenerate'])->name('church-report.generate');
        Route::get('church/report/generate', [\App\Http\Controllers\ReceiptController::class, 'churchReportGenerate'])->name('church-report.generate');
        Route::resource('zones', \App\Http\Controllers\ZoneController::class);
        Route::get('receipt/unverified', [\App\Http\Controllers\ReceiptController::class, 'unverified'])->name('receipt.unverified');

        Route::resource('leave-settings', \App\Http\Controllers\LeaveSettingController::class);
        Route::resource('requisitions', RequisitionController::class)->except(['store', 'show', 'destroy']);
//    Route::get('receipt/generate/', [\App\Http\Controllers\DeliveryController::class, 'generateDeliveryNote'])->name('receipt.generate');
        Route::get('receipt/ministry/generate/', [\App\Http\Controllers\DeliveryController::class, 'generateReceipt'])->name('ministry-receipt.generate');
        Route::get('receipt/church/generate/', [\App\Http\Controllers\DeliveryController::class, 'generateChurchReceipt'])->name('church-receipt.generate');
//    Route::post('receipt/generate/', [\App\Http\Controllers\DeliveryController::class, 'generateDeliveryNote'])->name('receipt.generate');
        Route::post('receipt/report', [\App\Http\Controllers\ReceiptController::class, 'generateReceipt'])->name('receipt.produce');
        Route::get('receipt/generate/', [\App\Http\Controllers\OrderController::class, 'generateReceipt'])->name('request.generate');
        Route::get('receipt/member/generate/', [\App\Http\Controllers\DeliveryController::class, 'generateMemberReceiptById'])->name('member-receipt.generate');
        Route::post('payment/report', [\App\Http\Controllers\PaymentController::class, 'generateReceipt'])->name('receipt.generate');
        Route::resource('pledges', \App\Http\Controllers\PledgeController::class);
        Route::get('bank/statements', [\App\Http\Controllers\BankController::class, 'statements'])->name('bank.statements');
        Route::post('bank-statements/report', [\App\Http\Controllers\BankController::class, 'generateReport'])->name('bank-statement.produce');


        Route::post('pledge/report', [\App\Http\Controllers\PledgeController::class, 'generatePledge'])->name('pledge.generate');

        Route::post('leave/summary', [\App\Http\Controllers\LeaveController::class, 'leaveSummary'])->name('leave.summary');
        Route::get('leave/summary', [\App\Http\Controllers\LeaveController::class, 'leaveSummary'])->name('leave.summary');
        Route::post('material/movements', [\App\Http\Controllers\StockFlowController::class, 'materialMovement'])->name('material.movements');
        Route::get('material/movements', [\App\Http\Controllers\StockFlowController::class, 'materialMovement'])->name('material.movements');
        Route::delete('request/remove/{requisition_item}', [RequisitionController::class, "removeDepartmentRequisitionItem"])->name('request.trash');
        Route::delete('request/{requisition}', [RequisitionController::class, "removeDepartmentRequisition"])->name('request.cancel');
        Route::delete('requisitions/{requisition}', [RequisitionController::class, "removeRequisition"])->name('requisitions.destroy');
        Route::delete('requisitions/remove/{requisition_item}', [RequisitionController::class, "removeRequisitionItem"])->name('requisitions.items.destroy');
        Route::delete('message/{message}', [\App\Http\Controllers\MessageController::class, "destroy"])->name('messages.delete');
        Route::get('requisitions/next', [RequisitionController::class, 'determineNext'])->name('requisitions.next');
        Route::get('requisitions/determine', [RequisitionController::class, 'determine'])->name('requisitions.determine');
        Route::get('requisitions/open/{requisition}', [RequisitionController::class, "showRequisition"])->name('requisitions.show');
        Route::get('requisitions/check/{requisition}', [RequisitionController::class, "showDepartmentRequisition"])->name('requisitions.check');
        Route::get('member/allocate', [\App\Http\Controllers\MemberController::class, "allocateMinistry"])->name('member.allocate');
        // Route::get('labourer/change', [\App\Http\Controllers\LabourerController::class, "allocateLabourToProject"])->name('labourer.change');
        Route::get('reports/financial/generate', [\App\Http\Controllers\FinanceController::class, 'generateFinancialStatement'])->name('financial.generate');
        Route::get('reports/financial-statements/', [\App\Http\Controllers\FinanceController::class, 'financialStatement'])->name('financial.statements');
        Route::post('reports/financial/generate', [\App\Http\Controllers\FinanceController::class, 'generateFinancialStatement'])->name('financial.generate');
        Route::post('reports/financial-statements/', [\App\Http\Controllers\FinanceController::class, 'financialStatement'])->name('financial.statements');

        Route::get('requisitions/project/{project}', [RequisitionController::class, 'projectRequisitions'])->name('requisitions.projects.index');
        Route::post('requisitions/list/enlist', [RequisitionController::class, 'addItemToList'])->name('requisitions.enlist');
        Route::get('requisitions/list/delist/{item_id}', [RequisitionController::class, 'removeItemFromList'])->name('requisitions.delist');
        Route::get('requisitions/save', [RequisitionController::class, 'store'])->name('requisitions.store');
        Route::post('request/update', [RequisitionController::class, 'updateRequest'])->name('request.update');
        Route::post('request/approve', [RequisitionController::class, 'approveRequest'])->name('request.approve');
        Route::post('request/approval', [RequisitionController::class, 'approveProjectRequest'])->name('request.approval');
        Route::get('requisitions/archive', [RequisitionController::class, 'archive'])->name('request.archive');
        Route::get('users/reset/password', [UserController::class, 'settingsPage'])->name('user.reset');
        Route::get('users/audit-trail', [UserController::class, 'logActivity'])->name('user.audit');
        Route::get('requisitions/pending', [RequisitionController::class, 'pending'])->name('request.pending');
        Route::post('members/report', [\App\Http\Controllers\MemberController::class, 'generateReport'])->name('member.reports');
        Route::get('Attendance/report', [\App\Http\Controllers\AttendanceController::class, 'generateReport'])->name('member-attendance.reports');

        Route::get('reports/out-tray', [\App\Http\Controllers\ReportController::class, 'archive'])->name('reports.archive');

        Route::get('stock/next', [\App\Http\Controllers\StockFlowController::class, 'determine'])->name('stock.next');
        Route::get('stock/add/items', [\App\Http\Controllers\StockFlowController::class, 'addItem'])->name('stock.add');
        Route::post('stock/list/enlist', [\App\Http\Controllers\StockFlowController::class, 'addItemToList'])->name('stock.enlist');
        Route::get('stock/list/delist/{item_id}', [\App\Http\Controllers\StockFlowController::class, 'removeItemFromList'])->name('stock.delist');
        Route::get('stock/save', [\App\Http\Controllers\StockFlowController::class, 'store'])->name('stock.store');
        Route::get('reports/determine', [\App\Http\Controllers\ReportController::class, 'determine'])->name('reports.determine');
        Route::get('reports/next', [\App\Http\Controllers\ReportController::class, 'determineNext'])->name('reports.next');

        Route::get('requisitions/next', [RequisitionController::class, 'determineNext'])->name('requisitions.next');
        Route::get('requisitions/determine', [RequisitionController::class, 'determine'])->name('requisitions.determine');
        Route::get('requisitions/open/{requisition}', [RequisitionController::class, "showRequisition"])->name('requisitions.show');
        Route::get('requisitions/check/{requisition}', [RequisitionController::class, "showDepartmentRequisition"])->name('requisitions.check');
        Route::get('labourer/allocate', [\App\Http\Controllers\LabourerController::class, "allocateLabour"])->name('labourer.allocate');
        // Route::get('labourer/change', [\App\Http\Controllers\LabourerController::class, "allocateLabourToProject"])->name('labourer.change');
        Route::get('reports/financial/generate', [\App\Http\Controllers\FinanceController::class, 'generateFinancialStatement'])->name('financial.generate');
        Route::get('reports/financial-statements/', [\App\Http\Controllers\FinanceController::class, 'financialStatement'])->name('financial.statements');
        Route::post('reports/financial/generate', [\App\Http\Controllers\FinanceController::class, 'generateFinancialStatement'])->name('financial.generate');
        Route::post('reports/financial-statements/', [\App\Http\Controllers\FinanceController::class, 'financialStatement'])->name('financial.statements');

        Route::get('asset/service/due', [\App\Http\Controllers\AssetServiceController::class, 'serviceDue'])->name('service.due');
        Route::get('asset/service/archive', [\App\Http\Controllers\AssetServiceController::class, 'serviceArchive'])->name('service.archive');

        Route::delete('request/remove/{requisition_item}', [RequisitionController::class, "removeDepartmentRequisitionItem"])->name('request.trash');
        Route::delete('request/{requisition}', [RequisitionController::class, "removeDepartmentRequisition"])->name('request.cancel');
        Route::delete('requisitions/{requisition}', [RequisitionController::class, "removeRequisition"])->name('requisitions.destroy');
        Route::delete('requisitions/remove/{requisition_item}', [RequisitionController::class, "removeRequisitionItem"])->name('requisitions.items.destroy');
        Route::delete('message/{message}', [\App\Http\Controllers\MessageController::class, "destroy"])->name('messages.delete');

        /**
         * Requisition routes
         */
        Route::resource('requisitions', RequisitionController::class)->except(['store', 'show', 'destroy']);
        Route::resource('stock', RequisitionController::class)->except(['store', 'show', 'destroy']);

        /**
         * Users routes
         */
        Route::resource('users', UserController::class);
        /**
         * Human-Resource Routes
         */
        Route::resource('human-resources', \App\Http\Controllers\HumanResourceController::class);
        Route::resource('contracts', \App\Http\Controllers\ContractController::class);
        Route::resource('contract-types', \App\Http\Controllers\ContractTypeController::class);

        /**
         * Finance Routes
         */
        Route::get('finances', [FinanceController::class, 'index'])->name('finances.index');
        /**
         * Human Resource Routes
         */
        Route::resource('setting', \App\Http\Controllers\SettingController::class);

        Route::get('analytics', [HomeController::class, 'analytics'])->name('analytics');
        Route::resource('accounts', \App\Http\Controllers\AccountController::class);
        Route::resource('payments', \App\Http\Controllers\PaymentController::class);
        Route::resource('stocks', \App\Http\Controllers\StockFlowController::class);
        /**
         *  Banks Routes
         */
        Route::resource('banks', \App\Http\Controllers\BankController::class);
        /**
         * Expenses Routes
         */
        Route::resource('expenses', \App\Http\Controllers\ExpenseController::class);
        /**
         * Income Routes
         */
        Route::resource('incomes', \App\Http\Controllers\IncomeController::class);
        /**
         * Suppliers Routes
         */
        Route::resource('transfers', \App\Http\Controllers\TransferController::class);

        /**
         * Prices Routes
         */

        Route::resource('prices', \App\Http\Controllers\PriceController::class);
        Route::resource('report-items', \App\Http\Controllers\ReportItemController::class);

        Route::resource('financial-years', \App\Http\Controllers\FinancialYearController::class);

        /**
         * Budgets Routes
         */

        Route::resource('budgets', \App\Http\Controllers\BudgetController::class);

        Route::resource('suppliers', SupplierController::class);


        /**
         * labourers Routes
         */

        Route::resource('labourers', \App\Http\Controllers\LabourerController::class);

        /**
         * Transfers Routes
         */


        /**
         * Allocation Routes
         */

        Route::resource('member_ministries', \App\Http\Controllers\MemberMinistryController::class);

        /**
         * financial year Routes
         */


        /**
         * Allocation Routes
         */

        Route::resource('selections', \App\Http\Controllers\SelectionController::class);
        Route::resource('months', \App\Http\Controllers\MonthController::class);

        /**
         * Purchase Routes
         */

        Route::resource('purchases', \App\Http\Controllers\PurchaseController::class);

        /**
         * stock flow Routes
         */
        Route::resource('reports', \App\Http\Controllers\ReportController::class);
        Route::resource('stock-flows', \App\Http\Controllers\StockFlowController::class);
        Route::resource('services', \App\Http\Controllers\ServiceController::class);
        Route::resource('asset-services', \App\Http\Controllers\AssetServiceController::class);
        Route::resource('assets', \App\Http\Controllers\AssetController::class);
        Route::resource('categories', \App\Http\Controllers\CategoryController::class);
        Route::resource('material-budgets', \App\Http\Controllers\MaterialBudgetController::class);
        Route::resource('notes', \App\Http\Controllers\NoteController::class);
        /**
         * Stores Routes
         */
        Route::resource('stores', \App\Http\Controllers\StoreController::class);
        Route::resource('leave-settings', \App\Http\Controllers\LeaveSettingController::class);
        Route::resource('leaves', \App\Http\Controllers\LeaveController::class);

        /**
         * Payments Routes
         */


        /**
         * departments Routes
         */
        Route::resource('departments', \App\Http\Controllers\DepartmentController::class);


        /**
         * labours Routes
         */
        Route::resource('drivers', \App\Http\Controllers\DriverController::class);

        Route::resource('labours', \App\Http\Controllers\LabourController::class);
        /**
         * Project Routes
         */
        Route::resource('projects', ProjectController::class);
        /**
         *  accounts Routes
         */
        /**
         * Material  Routes
         */
        Route::resource('materials', \App\Http\Controllers\MaterialController::class);
        Route::get('labourer/show/{labourer}', [\App\Http\Controllers\LabourerController::class, 'showLeaveByEmployee'])->name('labourer.leave');

        /**
         * Clients Routes
         */
        Route::resource('clients', ClientController::class);

        /**
         * Asset Routes
         */
        Route::get('assets', [AssetController::class, 'index'])->name('assets.index');

        /**
         * Settings routes
         */
        Route::get('settings', [App\Http\Controllers\UserController::class, 'settingsPage'])->name('settings');
        Route::patch('settings', [App\Http\Controllers\UserController::class, 'settingsUpdate'])->name('settings.update');

        /**
         * Analytics routes
         */


        /**
         * Attendance management routes
         */
        Route::post('labourers/subcontractors', [\App\Http\Controllers\LabourerController::class, 'store'])->name('subcontractor.form');
        Route::post('labourers/subcontractors/store', [\App\Http\Controllers\LabourerController::class, 'subcontractorsStore'])->name('transactions.store');
        Route::post('register/{department}/produce', [AttendanceRegisterController::class, 'produceAttendance'])->name('register.produce');
        Route::post('suppliers/store', [\App\Http\Controllers\SupplierController::class, 'supplierStore'])->name('transaction.store');
        Route::get('attendance/{department}/record', [AttendanceRegisterController::class, 'showAttendanceForm'])->name('attendance.record');
        Route::get('attendance/{department}/report', [AttendanceRegisterController::class, 'showAttendanceReport'])->name('attendance.report');
        Route::post('attendance/{department}/save', [AttendanceRegisterController::class, 'saveAttendanceData'])->name('attendance.save');
        Route::get('attendance/{department}/view', [AttendanceRegisterController::class, 'showAttendance'])->name('attendance.view');
        Route::get('attendance/index', [AttendanceRegisterController::class, 'index'])->name('attendance.index');
        Route::post('attendance/report', [AttendanceRegisterController::class, 'generateAttendanceReport'])->name('attendance.generate');
        Route::get('attendance/{labourer}/show', [AttendanceRegisterController::class, 'showAttendanceByEmployee'])->name('attendance.show');
        Route::get('attendance/{attendance}/edit', [AttendanceRegisterController::class, 'editAttendanceByEmployee'])->name('attendance.edit');
        Route::post('attendance/update', [AttendanceRegisterController::class, 'update'])->name('attendance.update');
        Route::get('register/index', [AttendanceRegisterController::class, 'employeeRegister'])->name('register.index');
        Route::post('attendance/store', [AttendanceRegisterController::class, 'store'])->name('attendance.store');

        Route::post('attendance/register', [AttendanceRegisterController::class, 'employeeRegisterByMonth'])->name('register.generate');

        Route::post('home-attendance/report', [\App\Http\Controllers\HomeAttendanceController::class, 'generateAttendanceReport'])->name('home-attendance.generate');
        Route::resource('programs', \App\Http\Controllers\ProgramController::class);
        Route::post('program/report', [\App\Http\Controllers\ProgramController::class, 'generateAttendanceReport'])->name('program.generate');

        Route::resource('ministries', \App\Http\Controllers\MinistryController::class);
        route::get("notification/unread", [NotificationController::class, 'unreadNotifications'])->name('notifications.unread');
        route::get("message/unread", [\App\Http\Controllers\MessageController::class, 'unreadNotifications'])->name('messages.unread');
        Route::get('settings', [App\Http\Controllers\UserController::class, 'settingsPage'])->name('settings');

        Route::resource('users', UserController::class);
        Route::get('settings', [App\Http\Controllers\UserController::class, 'settingsPage'])->name('settings');
        Route::patch('settings', [App\Http\Controllers\UserController::class, 'settingsUpdate'])->name('settings.update');
        Route::get('payment/member/transactions', [\App\Http\Controllers\PaymentController::class, "memberPayments"])->name('member.transaction');
        Route::get('payment/ministry/transactions', [\App\Http\Controllers\PaymentController::class, "ministryPayments"])->name('ministry.transaction');
        Route::get('payment/church/transactions', [\App\Http\Controllers\PaymentController::class, "homePayments"])->name('home.transactions');
        Route::get('payment/all/transactions', [\App\Http\Controllers\PaymentController::class, "allTransaction"])->name('receipt.all');

        /**
         * Notification routes
         */
        route::get("notification", [NotificationController::class, 'index'])->name('notifications.index');
        route::get("notification/unread", [NotificationController::class, 'unreadNotifications'])->name('notifications.unread');
        route::get("notification/mark-all-read", [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        route::get("notification/read/{notification}", [NotificationController::class, 'readNotification'])->name('notifications.read');


        route::get("message", [\App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
        route::get("message/unread", [\App\Http\Controllers\MessageController::class, 'unreadNotifications'])->name('messages.unread');
        route::get("message/mark-all-read", [\App\Http\Controllers\MessageController::class, 'markAllAsRead'])->name('messages.mark-all-read');
        route::get("message/read/{notification}", [\App\Http\Controllers\MessageController::class, 'readNotification'])->name('messages.read');


    });
});
