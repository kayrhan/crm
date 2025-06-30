<?php

use App\Http\Controllers\AccountingController;
use App\Http\Controllers\AccountingTrController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ContractsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\DiscussionLogController;
use App\Http\Controllers\DocumentTemplatesController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\ExternalPartnerController;
use App\Http\Controllers\FileAttachmentController;
use App\Http\Controllers\FreelancerController;
use App\Http\Controllers\HospitalityController;
use App\Http\Controllers\PackageTrackingController;
use App\Http\Controllers\PostBoxController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TicketAttachmentController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\VipOrganizationsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\ImportantDecisionController;
use App\Http\Controllers\OffersController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\SummaryOrderController;
use App\Http\Controllers\TagAndSearchController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\MailController;
use App\Http\Middleware\EnsureUserIsNotFreelancer;

Route::get("/", [LoginController::class, "loginIndex"])->name("login");
Route::get("/error/{code}", [ErrorController::class, "index"])->name("error-page");
Route::get("/forgot-password", [ForgotPasswordController::class, "index"])->name("forgot.password");
Route::get("/forgot-password/reset/{token}/{email}", [ForgotPasswordController::class, "getResetPassword"])->name("forgot.password.reset");
Route::post("/forgot-password", [ForgotPasswordController::class, "sendMail"])->name("forgot.password.sendmail");
Route::post("/forgot-password/update", [ForgotPasswordController::class, "updatePassword"])->name("forgot.password.update");

Auth::routes(["register" => false]);

Route::middleware("auth")->group(function() {

    //Dashboard Controller Routes
    Route::get("/dashboard", [DashboardController::class, "getDashboard"])->name("dashboard");
    Route::get("/dashboard/proofed", [DashboardController::class, "getDashboardProofed"]);
    Route::get("/getUsersForDashboard", [DashboardController::class, "getUsersForDashboard"]);
    Route::get("/getOrganizationsForDashboard", [DashboardController::class, "getOrganizationsForDashboard"]);

    //Users Controller Routes
    Route::post("/logout", [LoginController::class, "logout"]);
    Route::get("/resetPassword/{id}", [AdminUserController::class, "resetPassword"]);
    Route::get("/users", [AdminUserController::class, "index"]);
    Route::get("/add-user", [AdminUserController::class, "addUserIndex"]);
    Route::get("/add-user/{organization_id}", [AdminUserController::class, "addUserIndex"]);
    Route::get("/user/{id}", [AdminUserController::class, "userIndex"]);
    Route::get("/update-user/{id}", [AdminUserController::class, "updateUserIndex"]);
    Route::get("/update-user/{id}/{organization_id}", [AdminUserController::class, "updateUserIndex"]);
    Route::post("/add-user/get-roles", [AdminUserController::class, "getRoles"])->name("add-user.getRoles");
    Route::get("/getUsers", [AdminUserController::class, "getUsers"]);
    Route::get("/getUser/{id}", [AdminUserController::class, "getUser"]);
    Route::post("/getUser/cc", [AdminUserController::class, "getCcUsers"]);
    Route::get("/getOrganizationUsers/{organizationId}", [AdminUserController::class, "getOrganizationUsers"]);
    Route::get("/getOrganizationUsersRawData/{organizationId}", [AdminUserController::class, "getOrganizationUsersRawData"]);
    Route::get("/getOrganizationContract/{organizationId}", [OrganizationController::class, "getOrganizationContractData"]);
    Route::get("/getPersonnelRawData", [AdminUserController::class, "getPersonnelRawData"]);
    Route::get("/updateUserStatus/{userId}", [AdminUserController::class, "updateUserStatus"]);
    Route::get("/updateEmailStatus/{id}", [AdminUserController::class, "updateEmailStatus"]);
    Route::get("/login-from-user/{id}", [AdminUserController::class, "loginFromUser"]);
    Route::post("/create-user", [AdminUserController::class, "createUser"]);
    Route::post("/edit-user/{id}", [AdminUserController::class, "editUser"]);
    Route::post("/delete-user/{id}", [AdminUserController::class, "deleteUser"]);
    Route::post("/reset-password/{id}", [AdminUserController::class, "resetUserPassword"]);

    // Freelancers Routes
    Route::get("/freelancers", [FreelancerController::class, "index"]);
    Route::get("/freelancers/list", [FreelancerController::class, "list"]);

    //Organization Controller Routes
    Route::resource("organizations", OrganizationController::class);
    Route::get("/getOrganizationsRawData", [OrganizationController::class, "getOrganizationsRawData"]);
    Route::get("/getOrganizations", [OrganizationController::class, "getOrganizations"]);
    Route::get("/getOrganization/{id}", [OrganizationController::class, "getOrganization"]);
    Route::get("/updateOrganizationStatus/{id}", [OrganizationController::class, "updateOrganizationStatus"]);

    //VIP Organizations
    Route::get("/vip-organizations", [VipOrganizationsController::class, "index"])->name("vip.organizations");
    Route::get("/vip-organizations/get", [VipOrganizationsController::class, "getVipOrganizations"])->name("vip.organizations.get");
    Route::post("/vip-organizations/setvip", [VipOrganizationsController::class, "setVipOrganization"])->name("vip.organizations.setvip");
    Route::get("/vip-organizations/remove/{id}", [VipOrganizationsController::class, "removeVipOrganization"])->name("vip.organizations.remove");

    //Role Controller Routes
    Route::get("/roles", [RoleController::class, "index"]);
    Route::get("/add-role", [RoleController::class, "addRoleIndex"]);
    Route::get("/update-role/{id}", [RoleController::class, "updateRoleIndex"]);
    Route::get("/role/{id}", [RoleController::class, "roleIndex"]);
    Route::get("/getRoles", [RoleController::class, "getRoles"]);
    Route::get("/getRole/{id}", [RoleController::class, "getRole"]);
    Route::post("/create-role", [RoleController::class, "createRole"]);
    Route::post("/edit-role/{id}", [RoleController::class, "editRole"]);
    Route::post("/delete-role/{id}", [RoleController::class, "deleteRole"]);


    //Ticket Controller Routes
    Route::get("/tickets", [TicketsController::class, "index"]);
    Route::get("/add-ticket/{reference?}/{copy?}", [TicketsController::class, "addTicketIndex"])->middleware(EnsureUserIsNotFreelancer::class);
    Route::get("/update-ticket/{ticketId}", [TicketsController::class, "updatedTicketIndex"]);
    Route::get("/getTickets", [TicketsController::class, "getTickets"]);
    Route::get("/getWithDueDateTickets", [TicketsController::class, "getWithDueDateTickets"]);
    Route::get("/removeAttachment/{attachmentId}", [TicketsController::class, "removeAttachment"]);
    Route::get("/getTicket/{id}", [TicketsController::class, "getTicket"]);
    Route::post("/create-ticket", [TicketsController::class, "createTicket"])->name("create-ticket");
    Route::post("/edit-ticket/{editTicket}", [TicketsController::class, "editTicket"]);
    Route::post("/delete-ticket/{editTicket}", [TicketsController::class, "deleteTicket"]);
    Route::get("/ticket/getEffort/{id}", [TicketsController::class, "getEffort"]);
    Route::post("/ticket/updateEffort", [TicketsController::class, "updateEffort"]);
    Route::post("/ticket/addEffort", [TicketsController::class, "addEffort"]);
    Route::post("/ticket/proof", [TicketsController::class, "proofTicket"]);
    Route::post("/ticket/discount", [TicketsController::class, "discountEffort"]);
    Route::get("/ticket/deleteEffort/{id}", [TicketsController::class, "deleteEffort"]);
    Route::get("/ticket/update-status-counter", [TicketsController::class, "updateStatusCounter"]);
    Route::get("/ticket/updateStatus", [TicketsController::class, "updateStatus"]);
    Route::get("/tickets/reference/get/{id}", [TicketsController::class, "getPossibleReferenceTickets"]);
    Route::post("/tickets/reference/remove", [TicketsController::class, "removeReference"]);
    Route::post("/tickets/privacy/update", [TicketsController::class, "changePrivacyStatus"]);
    Route::post("/tickets/take-comment", [TicketsController::class, "takeComment"]);

    //Ticket Attachment Controller Routes
    Route::get("/ticket-attachment", [TicketAttachmentController::class, "index"]);
    Route::get("/getTicketAttachments", [TicketAttachmentController::class, "getTicketAttachments"]);
    Route::get("/ticket-attachment/{ticketId}", [TicketAttachmentController::class, "getTicketAttachment"]);
    Route::get("/deleteAttachment/{attachmentID}", [TicketAttachmentController::class, "deleteAttachment"]);
    Route::get("/addAttachment/{ticketId}", [TicketAttachmentController::class, "addAttachment"]);
    Route::get("/tickets/change-private-status", [TicketsController::class, "change_private_status"]);

    //Ticket Important Decisions Routes
    Route::post("important/add", [ImportantDecisionController::class, "add"]);
    Route::post("important/update", [ImportantDecisionController::class, "update"]);
    Route::post("important/delete", [ImportantDecisionController::class, "delete"]);
    Route::get("important/get/{id}", [ImportantDecisionController::class, "get"]);

    //Reports Controller Routes
    Route::get("/reports", [ReportsController::class, "index"]);
    Route::get("/getTicketSummary", [ReportsController::class, "getTicketSummary"]);
    Route::get("/getExcelSummaryAll", [ReportsController::class, "getExcelSummaryAll"]);
    Route::post("/getReportSummary/{id}", [ReportsController::class, "getReportSummary"]);
    Route::post("/reportSummary/get-file-name/{id}", [ReportsController::class, "getFileName"]);
    Route::get("/getUsersRawData", [ReportsController::class, "getUsersRawData"]);
    Route::get("/tickets/ticketsRaw", [ReportsController::class, "get_tickets_raw"]);
    Route::get("/getFreelancerUsersRawData", [AdminUserController::class, "getFreelancerUsersRawData"]);

    // Mail Section
    Route::get("/send-update/{ticket_id}", [MailController::class, "sendUpdate"]);
    Route::post("/send-update/{ticket_id}/{discussion_id}", [MailController::class, "sendUpdate"]);

    // File Attachment Controller Routes
    Route::post("/attachFiles", [FileAttachmentController::class, "uploadFile"]);
    Route::get("/uploads/{file}", [FileAttachmentController::class, "access_control"])->name("uploads");
    Route::get("/uploads/{file}/{rename}", [FileAttachmentController::class, "access_control"])->name("uploads-rename");
    Route::get("/tempfiles/{file}/{rename?}", [FileAttachmentController::class, "tempFiles"]);


    //Discussion Controller Routes
    Route::post("/create-discussion/{ticketId}", [DiscussionController::class, "createDiscussion"]);
    Route::get("/changeMessageStatus/{messageId}", [DiscussionController::class, "changeMessageStatus"]);
    Route::get("/delete-discussion/{id}", [DiscussionController::class, "deleteComment"]);
    Route::get("/discussion/add-attachment/{id}", [DiscussionController::class, "add_attachment"]);
    Route::post("discussion/comment/last-receivers", [DiscussionController::class, "get_last_receivers"]);

    // Attachment Component Routes
    Route::post("/attachment/uploadFiles", [AttachmentController::class, "uploadFiles"]);
    Route::post("/attachment/delete", [AttachmentController::class, "delete"]);
    Route::post("/attachment/togglePrivate", [AttachmentController::class, "togglePrivate"]);
    Route::post("/attachment/commentAttachmentDownloadAll", [AttachmentController::class, "commentAttachmentDownloadAll"]);
    Route::post("/attachment/attachmentDownloadAll", [AttachmentController::class, "attachmentDownloadAll"]);

    Route::middleware("superAdmin")->group(function() {
        Route::get("/contracts/{owner_company}/{contractType?}", [ContractsController::class, "index"]);
        Route::get("/add-contract/{owner_company}", [ContractsController::class, "addContract"]);
        Route::post("/add-contract/{owner_company}", [ContractsController::class, "addContractData"]);
        Route::get("/getContracts/{owner_company}", [ContractsController::class, "list"]);
        Route::get("/update-contract/{owner_company}/{id}", [ContractsController::class, "show"]);
        Route::post("/update-contract/{owner_company}", [ContractsController::class, "update"]);
        Route::get("/delete-contract/{id}", [ContractsController::class, "delete"]);
        Route::get("/quest-contract/{contractId}", [ContractsController::class, "questContractId"]);
        Route::get("/contract/delete-attachment/{id}", [ContractsController::class, "removeAttachment"]);
        Route::get("contracts/file/{company}/{file_name}/{file_type}", [ContractsController::class, "getFile"]);

        Route::middleware("accounting")->group(function() {
            Route::get("/bills", [BillController::class, "index"]);
            Route::get("/getBills", [BillController::class, "getBills"]);
            Route::post("/exportAll", [BillController::class, "exportAll"]);
            Route::post("/updateInvoiced", [BillController::class, "updateInvoiced"]);
            Route::post("/updateClosed", [BillController::class, "updateClosed"]);

            Route::get("/getucon/accounting/{type}", [AccountingController::class, "index"]);
            Route::get("getucon/accounting/get-data/{type}", [AccountingController::class, "list"]);
            Route::get("/getucon/accounting/add/{type}", [AccountingController::class, "add"]);
            Route::get("/getucon/accounting/update/{type}/{id}", [AccountingController::class, "update"]);
            Route::post("/getucon/accounting/add/{type}", [AccountingController::class, "add_post"]);
            Route::post("/getucon/accounting/update/{type}/{id}", [AccountingController::class, "update_post"]);
            Route::post("/getucon/accounting/cancel-invoice", [AccountingController::class, "cancel_invoice"]);
            Route::post("/getucon/accounting/get-requested-accounting", [AccountingController::class, "getRequestedAccounting"]);
            Route::post("/getucon/accounting/send-mail", [AccountingController::class, "send_email"]);
            Route::post("/getucon/accounting/retrieve-invoice-details", [AccountingController::class, "retrieveInvoiceDetails"]);
            Route::post("/getucon/accounting/set-reminder", [AccountingController::class, "setReminder"]);
            Route::post("/getucon/accounting/receive-payment", [AccountingController::class, "receivePayment"]);
            Route::post("/getucon/accounting/delete-payment", [AccountingController::class, "deletePayment"]);
            Route::get("/getucon/accounting/payment-history/{invoice_number}", [AccountingController::class, "paymentHistory"]);
            Route::post("/getucon/accounting/delete-attachment", [AccountingController::class, "deleteAttachment"]);
            Route::get("/getucon/accounting/get-ticket/{ticket_id}", [AccountingController::class, "get_ticket"]);
            Route::post("/getucon/accounting/change-ticket-status", [AccountingController::class, "changeTicketStatus"]);
            Route::post("/getucon/accounting/payment-monitoring", [AccountingController::class, "paymentMonitoring"]);
            Route::post("/getucon/accounting/delete-ticket", [AccountingController::class, "deleteTicket"]);
            Route::post("/getucon/accounting/delete-main-ticket", [AccountingController::class, "deleteMainTicket"]);
            Route::post("/getucon/accounting/update-ticket", [AccountingController::class, "updateTicket"]);

            //Accounting TR Controller Routes
            Route::post("/accounting-tr/check-official-invoice", [AccountingTrController::class, "checkOfficialInvoice"]);
            Route::post("/accounting-tr/set-reminder", [AccountingTrController::class, "setReminder"]);
            Route::post("/accounting-tr/delete-attachment", [AccountingTrController::class, "deleteAttachment"]);
            Route::get("/accounting-tr/payment-history/{invoice_number}", [AccountingTrController::class, "paymentHistory"]); // Yukarıda olması gerekiyor.
            Route::post("/accounting-tr/retrieve-invoice-details", [AccountingTrController::class, "retrieveInvoiceDetails"]);// Yukarıda olması gerekiyor.
            Route::get("/accounting-tr/{ref_company}/{type}", [AccountingTrController::class, "index"]);
            Route::get("accounting-tr/get-data/{ref_company}/{type}", [AccountingTrController::class, "list"]);
            Route::get("/accounting-tr/add/{ref_company}/{type}", [AccountingTrController::class, "add"]);
            Route::get("/accounting-tr/update/{ref_company}/{type}/{id}", [AccountingTrController::class, "update"]);
            Route::post("/accounting-tr/add/{ref_company}/{type}", [AccountingTrController::class, "add_post"]);
            Route::post("/accounting-tr/update/{ref_company}/{type}/{id}", [AccountingTrController::class, "update_post"]);
            Route::post("/accounting-tr/get-requested-accounting/{ref_company}", [AccountingTrController::class, "getRequestedAccounting"]);
            Route::post("/accounting-tr/send-mail/{ref_company}", [AccountingTrController::class, "send_email"]);
            Route::post("/accounting-tr/delete-payment", [AccountingTrController::class, "deletePayment"]);
            Route::post("/accounting-tr/receive-payment", [AccountingTrController::class, "receivePayment"]);
            Route::post("/accounting-tr/cancel-invoice", [AccountingTrController::class, "cancelInvoice"]);
            Route::get("/get-ticket/{ticket_id}", [AccountingTrController::class, "get_ticket"]);
            Route::get("/accounting-tr/quest-no/{ref_company}/{no}",[AccountingTrController::class,"quest_no"]);
            Route::post("/accounting-tr/change-ticket-status", [AccountingTrController::class, "changeTicketStatus"]);
            Route::post("/getucon/accounting-tr/{ref_company}/payment-monitoring", [AccountingTrController::class, "paymentMonitoring"]);
            Route::post("/accounting-tr/delete-ticket", [AccountingTrController::class, "deleteTicket"]);
            Route::post("/accounting-tr/delete-main-ticket", [AccountingTrController::class, "deleteMainTicket"]);
            Route::post("/accounting-tr/update-ticket", [AccountingTrController::class, "updateTicket"]);
            //Transactions
            Route::get("/transactions", [TransactionController::class, "index"]);
            Route::get("/transactions/list/{company_id}", [TransactionController::class, "list"]);
            Route::post("/transactions/add", [TransactionController::class, "add"]);
            Route::get("/transactions/get-data/{id}", [TransactionController::class, "get_data"]);
            Route::post("/transactions/update/{id}", [TransactionController::class, "update"]);
            Route::post("/transactions/get-totals/{company_id}", [TransactionController::class, "get_totals"]);
            Route::get("/transactions/get-categories/{company_id}", [TransactionController::class, "get_categories"]);
            Route::get("/transactions/get-categories-raw", [TransactionController::class, "get_categories_raw"]);
            Route::get("/transactions/add-category", [TransactionController::class, "add_category"]);
            Route::get("/transactions/update-category", [TransactionController::class, "update_category"]);
            Route::get("/transactions/get-transaction-count/{category_id}", [TransactionController::class, "get_transaction_count"]);
            Route::get("/transactions/move-category", [TransactionController::class, "move_category"]);
            Route::get("/transactions/delete-category", [TransactionController::class, "delete_category"]);

            // Hospitality Receipt(Bewirtungsbeleg) Section
            Route::get("/hospitality-receipt/get-table-data", [HospitalityController::class, "getTableData"]);
            Route::get("/hospitality-receipt", [HospitalityController::class, "index"]);
            Route::get("/hospitality-receipt/create", [HospitalityController::class, "create"]);
            Route::get("/hospitality-receipt/edit/{id}", [HospitalityController::class, "edit"]);
            Route::post("/hospitality-receipt/store", [HospitalityController::class, "store"]);
            Route::post("/hospitality-receipt/update", [HospitalityController::class, "update"]);
            Route::post("/hospitality-receipt/delete", [HospitalityController::class, "destroy"]);
            Route::post("/hospitality-receipt/delete-visitor", [HospitalityController::class, "deleteVisitor"]);
        });

        // Document Templates
        Route::get("/document-templates", [DocumentTemplatesController::class, "index"]);
        Route::post("/document-templates/upload", [DocumentTemplatesController::class, "upload"]);
        Route::post("/document-templates/delete", [DocumentTemplatesController::class, "delete"]);
    });

    // calendar
    Route::get("/calendar", [CalendarController::class, "index"]);
    Route::get("/calendar/{user_id}", [CalendarController::class, "indexUser"]);
    Route::get("/calendar/get/{id}", [CalendarController::class, "show"]);
    Route::post("/calendar", [CalendarController::class, "insertNewData"]);
    Route::post("/tickettocalendar", [CalendarController::class, "ticketToCalendar"]);
    Route::post("/calendar/update", [CalendarController::class, "updateData"]);
    Route::post("/calendar/updatedate", [CalendarController::class, "updateDate"]);
    Route::get("/calendar/delete/{id}", [CalendarController::class, "delete"]);
    Route::get("/calendar/getdata/{id}/{start}/{end}/{org}/{status}/{timezone}", [CalendarController::class, "getData"]);
    Route::get("/calendar/copy/{id}/{side}", [CalendarController::class, "copy"]);

    // Summary Order
    Route::post("/summaryOrder/updateOrder", [SummaryOrderController::class, "updateOrder"]);

    //External partner
    Route::get("/external-partners", [ExternalPartnerController::class, "index"]);
    Route::get("/external-partners/add", [ExternalPartnerController::class, "add_partner"]);
    Route::post("/external-partners/add", [ExternalPartnerController::class, "add_partner_post"]);
    Route::get("/external-partners/get-partners", [ExternalPartnerController::class, "get_partners"]);
    Route::get("/external-partners/update/{id}", [ExternalPartnerController::class, "update_partner"]);
    Route::post("/external-partners/update-post", [ExternalPartnerController::class, "update_partner_post"]);
    Route::get("/external-partners/get-raw-data", [ExternalPartnerController::class, "get_raw_data"]);
    Route::get("/external-partners/delete/{id}", [ExternalPartnerController::class, "delete_partner"]);
    Route::get("/external-partners/attachement/delete/{id}", [ExternalPartnerController::class, "delete_attachment"]);
    Route::post("/external-partners/add-contact", [ExternalPartnerController::class, "add_partner_contact"]);
    Route::post("/external-partners/update-contact/{user_id}", [ExternalPartnerController::class, "update_partner_contact"]);
    Route::get("/external-partners/get-partner-contacts/{partner_id}", [ExternalPartnerController::class, "get_partner_contacts"]);
    Route::get("/external-partners/get-user-info/{user_id}", [ExternalPartnerController::class, "get_partner_contact_info"]);
    Route::post("/external-partners/delete-contact", [ExternalPartnerController::class, "delete_contact"]);
    Route::get("/external-partners/get-partner-users-raw", [ExternalPartnerController::class, "get_partner_users_raw"]);

    Route::get("/services", [ServiceController::class, "index"]);
    Route::get("/services/create", [ServiceController::class, "create"]);
    Route::post("/services", [ServiceController::class, "store"]);
    Route::get("/services/list", [ServiceController::class, "list"]);
    Route::get("/services/{service}", [ServiceController::class, "edit"]);
    Route::post("/services/delete/attachments/{id}", [ServiceController::class, "deleteAttachment"]);
    Route::post("/services/delete/{service}", [ServiceController::class, "delete"]);
    Route::post("/services/{service}", [ServiceController::class, "update"]);

    // Tag And Select
    Route::get("/tagAndSearch/getOptions", [TagAndSearchController::class, "getOptions"]);

    //Package Tracking Routes
    Route::middleware("packageTracking")->group(function() {
        Route::get("/package-tracking", [PackageTrackingController::class, "index"])->name("package-tracking.index");
        Route::get("/package-tracking/list", [PackageTrackingController::class, "list"])->name("package-tracking.list");
        Route::get("/add-package", [PackageTrackingController::class, "addPackage"]);
        Route::post("/add-package", [PackageTrackingController::class, "addPackagePost"]);
        Route::get("/delete-package/{id}", [PackageTrackingController::class, "deletePackage"])->name("package-tracking.delete");
        Route::get("/update-package/{id}", [PackageTrackingController::class, "updatePackage"])->name("package-tracking.update");
        Route::post("/update-package", [PackageTrackingController::class, "updatePackagePost"])->name("package-tracking.update.post");
    });

    //Todos and post-box
    Route::middleware("todos")->group(function () {
        Route::get("/todos", [TodoController::class, "index"])->name("todo.index");
        Route::get("/todos/list", [TodoController::class, "list"])->name("todo.list");
        Route::get("/add-todo", [TodoController::class, "addTodo"])->name("todo.addTodo");
        Route::post("/add-todo", [TodoController::class, "addTodoPost"])->name("todo.addtodo.post");
        Route::get("/update-todo/{todo_number}", [TodoController::class, "updateTodo"])->name("todo.updatetodo");
        Route::post("/update-todo", [TodoController::class, "updateTodoPost"])->name("todo.updatetodo.post");
        Route::get("/delete-todo/{todo_number}", [TodoController::class, "deleteTodo"])->name("todo.delete.todo");
        Route::get("/delete-todo-attachment/{id}", [TodoController::class, "deleteTodoAttachment"]);

        Route::get("/post-box", [PostBoxController::class, "index"])->name("post-box.index");
        Route::get("/post-box/list", [PostBoxController::class, "show"])->name("post-box.index.list");
        Route::get("/post-box/add-post-box", [PostBoxController::class, "create"])->name("post-box.add-post-box");
        Route::post("/post-box/update-post-box", [PostBoxController::class, "update_post"])->name("post-box.update-post-box.post");
        Route::get("/post-box/update-post-box/{id}", [PostBoxController::class, "update"])->name("post-box.update-post-box");
        Route::post("/post-box/add-post-box", [PostBoxController::class, "create_post"])->name("post-box.add-post-box.post");
        Route::get("/post-box/delete", [PostBoxController::class, "delete"])->name("post-box.delete");
        Route::get("/post-box/deleteAttachment/{id}", [PostBoxController::class, "deleteAttachment"])->name("post-box.delete.attachment");

        Route::get("/offers", [OffersController::class, "index"])->name("offers.index");
        Route::get("/add-offer", [OffersController::class, "create_offer"])->name("offers.add");
        Route::post("/add-offer", [OffersController::class, "create_offer_post"])->name("offers.add.post");
        Route::get("/update-offer/{id}", [OffersController::class, "update_offer"])->name("offers.update");
        Route::post("/update-offer/", [OffersController::class, "update_offer_post"])->name("offers.update.post");
        Route::get("/offer/list", [OffersController::class, "list"])->name("offers.list");
        Route::get("/offer/delete/{id}", [OffersController::class, "delete_offer"])->name("offers.delete");
        Route::get("/offer/isOfferNumberUnique/{offer_no}", [OffersController::class, "is_offer_number_unique"]);
        Route::post("/offer/offerDataDelete", [OffersController::class, "offer_data_delete"]);
        Route::get("/offer/getOfferData/{id}", [OffersController::class, "get_offer_data"]);
        Route::post("/offer/updateOfferData", [OffersController::class, "update_offer_data"]);

        //discussion log
        Route::get("/getDiscussionLog/{id}/{batchCount}", [DiscussionLogController::class, "getDiscussionLogs"]);
        Route::get("/get-discussion-data/{id}", [DiscussionController::class, "get_discussion_data"]);
        Route::post("/discussion/update-comment/{id}", [DiscussionController::class, "set_discussion_data"]);

        // Warehouse
        // Office
        Route::get("/offices", [OfficeController::class, "index"]);
        Route::get("/office/list", [OfficeController::class, "list"]);
        Route::get("/add-office-page", [OfficeController::class, "addPage"]);
        Route::get("/update-office/{id}", [OfficeController::class, "updatePage"]);
        Route::post("/add-office", [OfficeController::class, "addOffice"])->name("offices.add.office");
        Route::post("/office/update", [OfficeController::class, "updateOffice"])->name("offices.update.office");
        //Stock
        Route::get("/stocks", [WarehouseController::class, "index"]);
        Route::get("/add-stock", [WarehouseController::class, "addPage"]);
        Route::get("/stocks/list", [WarehouseController::class, "list"]);
        Route::post("/stocks/add", [WarehouseController::class, "addStock"])->name("stocks.add");
        Route::post("/stocks/update", [WarehouseController::class, "updateStock"])->name("stocks.update");
        Route::get("/update-stock/{id}", [WarehouseController::class, "updatePage"]);
        Route::post("/stocks/delete/", [WarehouseController::class, "deleteStock"]);

        // Countries and Cities
        Route::get("/countries", [OfficeController::class, "getCountries"]);
        Route::get("/getCities/{country_name}", [OfficeController::class, "getCities"]);

        // Asset Management
        Route::get("assets/{company}", [AssetController::class, "index"])->name("assets.index");
        Route::get("assets/create/{company_name}", [AssetController::class, "create"])->name("assets.create");
        Route::post("assets/add", [AssetController::class, "store"])->name("assets.add");
        Route::get("assets/{asset}/edit/{company_name}", [AssetController::class, "edit"])->name("assets.edit");
        Route::post("assets/{asset}", [AssetController::class, "update"])->name("assets.update");
        Route::delete("assets/{asset}", [AssetController::class, "destroy"])->name("assets.destroy");
        Route::get("assets/list/{company_name}", [AssetController::class, "list"])->name("assets.list");
        Route::post("assets/deleteFile/{asset}/{type}", [AssetController::class, "deleteFile"])->name("assets.deleteFile");
    });
});