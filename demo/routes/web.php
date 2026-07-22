<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\SubAdminController;
use App\Http\Controllers\SubAdminTaskController;
use App\Http\Controllers\StaffTaskController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminProposalController;
use App\Http\Controllers\SubAdminProposalController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SubClientController;
use App\Http\Controllers\AdminProjectController;
use App\Http\Controllers\AdminSalesController;
use App\Http\Controllers\AdminCustomersController;
use App\Http\Controllers\AdminBranchesController;
use App\Http\Controllers\AdminRenewalController;
use App\Http\Controllers\AdminGSuiteController;
use App\Http\Controllers\AdminAmcController;
use App\Http\Controllers\LeadsFromController;
use App\Http\Controllers\LeadStatusController;
use App\Http\Controllers\IncomeAmountController;
use App\Http\Controllers\ExpensiveAmountController;
use App\Http\Controllers\CredentialController;
use App\Http\Controllers\FollowUpController;
use App\Http\Controllers\EventDetailController;
use App\Http\Controllers\ClientProjectController;
use App\Http\Controllers\FreeLancerController;
use App\Http\Controllers\FreelancerEstimateController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\MyBillController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('auth.login');
// });

Route::get('/', [App\Http\Controllers\IndexController::class, 'index'])->name('user.home');
Route::post('/user_login', [App\Http\Controllers\IndexController::class, 'UserLogin'])->name('user.login');
Route::get('/offline', function () {
    return view('vendor.laravelpwa.offline');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('fetch-staff', [App\Http\Controllers\TaskController::class, 'fetchStaff'])->name('task.staff.fetch');
Route::post('admin-fetch-staff', [App\Http\Controllers\Admin\TaskController::class, 'fetchStaff'])->name('admin.task.staff.fetch');

// Route::post('user-logout', [App\Http\Controllers\HomeController::class, 'UserLogout'])->name('user.login');


Route::group(['prefix'=>'client'],function (){
    Route::middleware(['client', 'auth'])->group(function() {
        Route::get('', [HomeController::class, 'client_index'])->name('client.index');

        Route::get('/profile', [App\Http\Controllers\HomeController::class, 'ClientProfile'])->name('client.profile');

        Route::post('/profile/upload/{id}', [App\Http\Controllers\HomeController::class, 'ClientProfileUpload'])->name('client.profile.upload');

        //Project Details
        Route::group(['prefix'=>'projects'],function (){
            Route::controller(ClientProjectController::class)->group(function () {
                Route::get('/view', 'view')->name('client.projects.view');
                
                Route::get('/edit/{id}', 'edit')->name('client.projects.edit');
                Route::get('/delete/{id}', 'delete')->name('client.projects.delete');
                Route::get('/status/{id}', 'status')->name('client.projects.status');
                Route::get('/renewal-delete/{id}', 'RenewalDelete')->name('client.projects.renewal.delete');
                Route::get('/download-project', 'DownloadProject')->name('client.download.projects.view');
                Route::get('/payment-delete/{id}', 'PaymentDelete')->name('client.projects.payment.delete');

                Route::post('/store', 'store')->name('client.projects.store');
                Route::post('/follow-store', 'follow_store')->name('client.projects.follow.store');
                Route::post('/payment_update', 'payment_update')->name('client.projects.payment.update');
                Route::post('/payment_details', 'payment_details')->name('client.projects.payment.details');
                Route::post('/payment-edit', 'payment_edit_details')->name('client.projects.payment.edit.details');
                Route::post('/payment-update', 'payment_edit_update')->name('client.projects.payment.edit.update');
                Route::post('/update/{id}', 'update')->name('client.projects.update');
                Route::post('/filter', 'filter')->name('client.projects.filter');
                Route::get('/task-details/{id}', 'TaskStatusUpdate')->name('client.task.status');
                Route::post('/time/update', 'TaskDetailsTimeUpdate')->name('client.task.details.time.update');

                Route::get('/on-going-project', 'OnGoingProject')->name('client.projects.going');
                Route::get('/pending-project', 'PendingProject')->name('client.projects.pending');
                Route::get('/on-hold-project', 'OnHoldProject')->name('client.projects.hold');
                Route::get('/completed-project', 'CompletedProject')->name('client.projects.completed');
                Route::get('/cancel-project', 'CancelProject')->name('client.projects.cancel');
                Route::get('/renewal-project', 'RenewalProject')->name('client.projects.renewal');
                Route::get('/completed-payment-project', 'PaymentCompleteView')->name('client.completed.payment.project');
                Route::get('/project-view/{id}', 'ViewProject')->name('client.projects.view.details');

                Route::get('/renewal-project-view', 'RenewalProjectView')->name('client.projects.renewal.view');

                Route::post('/search-view', 'SearchView')->name('client.projects.search.view');
            });
        });
        //Project Details
        Route::group(['prefix'=>'task-estimate'],function (){
            Route::controller(ClientProjectController::class)->group(function () {
                
                Route::get('/create', 'create')->name('client.projects.create');
                Route::get('/view', 'taskview')->name('client.projects.task.view');
                Route::get('/details/{id}', 'ViewTask')->name('client.projects.task.details');
                Route::get('/details/delete/{id}', 'DeleteDetailsTask')->name('client.projects.task.details.delete');

                Route::post('/store', 'store')->name('client.projects.store');
                Route::post('/extra-point-store', 'PointStore')->name('client.projects.extra.store');
                Route::post('/task-request-update', 'TaskRequestUpdate')->name('client.task.request.update');
                Route::post('/task-request-comment-update', 'TaskRequestCommentUpdate')->name('client.task.request.comment.update');

            });
        });

    });
});

Route::group(['prefix'=>'freelancer'],function (){
    Route::middleware(['freelancer', 'auth'])->group(function() {
        Route::get('', [HomeController::class, 'freelancer_index'])->name('freelancer.index');

        Route::get('/profile', [App\Http\Controllers\HomeController::class, 'FreelancerProfile'])->name('freelancer.profile');

        Route::post('/profile/upload/{id}', [App\Http\Controllers\HomeController::class, 'FreelancerProfileUpload'])->name('freelancer.profile.upload');

        //Project Details
        Route::group(['prefix'=>'task-estimate'],function (){
            Route::controller(FreelancerEstimateController::class)->group(function () {
                
                Route::get('/create', 'create')->name('freelancer.projects.create');
                Route::get('/view', 'taskview')->name('freelancer.projects.task.view');
                Route::get('/details/{id}', 'ViewTask')->name('freelancer.projects.task.details');
                Route::get('/details/delete/{id}', 'DeleteDetailsTask')->name('freelancer.projects.task.details.delete');

                Route::post('/store', 'store')->name('freelancer.projects.store');
                Route::post('/extra-point-store', 'PointStore')->name('freelancer.projects.extra.store');
                Route::post('/task-request-update', 'TaskRequestUpdate')->name('freelancer.task.request.update');
                Route::post('/task-request-comment-update', 'TaskRequestCommentUpdate')->name('freelancer.task.request.comment.update');

            });
        });

    });
});

Route::group(['prefix'=>'staff'],function (){
    Route::middleware(['staff', 'auth'])->group(function() {

        Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'staff_index'])->name('staff.index');

        Route::get('/profile', [App\Http\Controllers\HomeController::class, 'StaffProfile'])->name('staff.profile');

        Route::get('/incentive', [App\Http\Controllers\HomeController::class, 'Incentive'])->name('staff.incentive');

        Route::post('/profile/upload/{id}', [App\Http\Controllers\HomeController::class, 'StaffProfileUpload'])->name('staff.profile.upload');

        // Route::post('crop-image-upload', [App\Http\Controllers\HomeController::class, 'uploadCropImage']);

        //Staff
        Route::group(['prefix'=>'task'],function (){
            Route::controller(StaffTaskController::class)->group(function () {
                Route::get('/create', 'TaskCreate')->name('staff.task.create');
                Route::post('/Create-task', 'TaskStore')->name('staff.task.create.store');

                Route::get('/pending', 'Pending')->name('staff.pending');
                Route::get('/progress', 'Progress')->name('staff.progress');
                Route::get('/closed', 'Closed')->name('staff.closed');
                Route::get('/over-due', 'OverDue')->name('staff.over_due');
                Route::get('/completed', 'Completed')->name('staff.completed');
                Route::get('/canceled', 'Rejected')->name('staff.rejected');
                Route::get('/recurring', 'Recurring')->name('staff.recurring');
                Route::get('/view/{id}', 'ViewTask')->name('staff.view.task');
                Route::get('/recurring-view/{id}', 'ViewRecurTask')->name('staff.recurring.view.task');

                Route::get('/comment-delete/{id}', 'CommentDelete')->name('staff.domment.delete');

                Route::post('/render-model', 'StaffModel')->name('staff.model.render');

                Route::post('/status', 'TaskStatusUpdate')->name('staff.task.status');
                Route::get('/task_details/{id}', 'TaskDetailsGet')->name('staff.task.details.get');
                Route::post('/task_details/update', 'TaskDetailsUpdate')->name('staff.task.details.update');
                Route::post('/time/update', 'TaskDetailsTimeUpdate')->name('staff.task.details.time.update');
                Route::get('/change-status/{id}', 'ChangeStatusTask')->name('staff.change.status.task');
                Route::post('/task-comments', 'AddTaskStatus')->name('staff.task.comment.update');

                // Follow up Project
                Route::get('/follow-up-task', 'FollowUpProject')->name('staff.follow.up.project');
                Route::get('/follow-up-task-details/{id}', 'FollowUpProjectDetails')->name('staff.follow.up.project.details');
                Route::post('/follow-up-task/comments', 'FollowUpProjectComments')->name('staff.follow.up.comments.project');
                Route::get('/coordinar-status/{id}', 'ChangeCoordinarTask')->name('staff.change.coordinar.task');
                Route::get('/payment-status/{id}', 'ChangePaymentTask')->name('staff.change.payment.task');

                // Follow up Payment
                Route::post('/follow-up-payment', 'FollowUpProjectPayment')->name('staff.follow.up.project.payment');

            });
        });

        Route::group(['prefix'=>'proposal'],function (){
            Route::controller(SubAdminProposalController::class)->group(function () {
                Route::get('/create', 'Staffcreate')->name('staff.proposal.create');
                Route::get('/view', 'Staffview')->name('staff.proposal.view');
                Route::get('/edit/{id}', 'Staffedit')->name('staff.proposal.edit');
                Route::get('/delete/{id}', 'Staffdelete')->name('staff.proposal.delete');
                Route::get('/status/{id}', 'Staffstatus')->name('staff.proposal.status');

                Route::post('/store', 'Staffstore')->name('staff.proposal.store');
                Route::post('/update/{id}', 'Staffupdate')->name('staff.proposal.update');
            });
        });

        Route::group(['prefix'=>'projects'],function (){
            Route::controller(App\Http\Controllers\StaffProjectController::class)->group(function () {
                Route::get('/create', 'create')->name('staff.projects.create');
                Route::get('/view', 'view')->name('staff.projects.view');
                Route::get('/edit/{id}', 'edit')->name('staff.projects.edit');
                Route::get('/delete/{id}', 'delete')->name('staff.projects.delete');
                Route::get('/status/{id}', 'status')->name('staff.projects.status');

                Route::post('/store', 'store')->name('staff.projects.store');
                Route::post('/payment_update', 'payment_update')->name('staff.projects.payment.update');
                Route::post('/payment_details', 'payment_details')->name('staff.projects.payment.details');
                Route::post('/update/{id}', 'update')->name('staff.projects.update');
                Route::post('/filter', 'filter')->name('staff.projects.filter');

                Route::get('/on-going-project', 'OnGoingProject')->name('staff.projects.going');
                Route::get('/pending-project', 'PendingProject')->name('staff.projects.pending');
                Route::get('/on-hold-project', 'OnHoldProject')->name('staff.projects.hold');
                Route::get('/completed-project', 'CompletedProject')->name('staff.projects.completed');
                Route::get('/renewal-project', 'RenewalProject')->name('staff.projects.renewal');
                Route::get('/project-view/{id}', 'ViewProject')->name('staff.projects.view.details');

                Route::get('/renewal-project-view', 'RenewalProjectView')->name('staff.projects.renewal.view');
            });
        });

        Route::group(['prefix'=>'services'],function (){
            Route::controller(ClientController::class)->group(function () {

                Route::get('/', 'Staffindex')->name('staff.service.view');
                Route::get('/services-delete/{id}', 'Staffdelete')->name('staff.service.delete');

                Route::post('/services-edit', 'Staffedit')->name('staff.service.edit');
                Route::post('/services-store', 'Staffstore')->name('staff.service.store');
            });
        });

        Route::group(['prefix'=>'customers'],function (){
            Route::controller(AdminCustomersController::class)->group(function () {
                Route::get('/view', 'Staffview')->name('staff.customers.view');
                Route::get('/delete/{id}', 'Staffdelete')->name('staff.customers.delete');

                Route::post('/edit', 'Staffedit')->name('staff.customers.edit');
                Route::post('/store', 'Staffstore')->name('staff.customers.store');
            });
        });

        Route::group(['prefix'=>'branches'],function (){
            Route::controller(AdminBranchesController::class)->group(function () {
                Route::get('/view', 'Staffview')->name('staff.branches.view');
                Route::get('/delete/{id}', 'Staffdelete')->name('staff.branches.delete');

                Route::post('/edit', 'Staffedit')->name('staff.branches.edit');
                Route::post('/store', 'Staffstore')->name('staff.branches.store');
            });
        });
        
        Route::group(['prefix'=>'sales'],function (){
            Route::controller(AdminSalesController::class)->group(function () {
                Route::get('/create', 'Staffcreate')->name('staff.sales.create');
                Route::get('/view', 'Staffview')->name('staff.sales.view');
                Route::get('/edit/{id}', 'Staffedit')->name('staff.sales.edit');
                Route::get('/delete/{id}', 'Staffdelete')->name('staff.sales.delete');

                Route::post('/store', 'Staffstore')->name('staff.sales.store');
                Route::post('/update/{id}', 'Staffupdate')->name('staff.sales.update');
            });
        });

        Route::group(['prefix'=>'domain-and-hosting'],function (){
            Route::controller(AdminRenewalController::class)->group(function () {
                Route::get('/create', 'Staffcreate')->name('staff.domain.hosting.create');
                Route::get('/view', 'Staffview')->name('staff.domain.hosting.view');
                Route::get('/not-interest-list', 'Staffnotview')->name('staff.domain.hosting.view.interest');
                Route::get('/edit/{id}', 'Staffedit')->name('staff.domain.hosting.edit1');
                Route::get('/invoice/{id}', 'Staffinvoice')->name('staff.domain.hosting.invoice');
                Route::get('/not-interest/{id}', 'StaffNotInterest')->name('staff.domain.hosting.not.interest');
                Route::get('/interest/{id}', 'StaffInterest')->name('staff.domain.hosting.interest');
                Route::get('/delete/{id}', 'Staffdelete')->name('staff.domain.hosting.delete');

                Route::post('/store', 'Staffstore')->name('staff.domain.hosting.store');
                Route::post('/update/{id}', 'Staffupdate')->name('staff.domain.hosting.update');
                Route::post('/user-details', 'StaffUserDetails')->name('staff.domain.hosting.user.details');
            });
        });

        Route::group(['prefix'=>'gsuide'],function (){
            Route::controller(AdminGSuiteController::class)->group(function () {
                Route::get('/create', 'Staffcreate')->name('staff.gsuide.create');
                Route::get('/view', 'Staffview')->name('staff.gsuide.view');
                Route::get('/not-interest-list', 'Staffnotview')->name('staff.gsuide.view.interest');
                Route::get('/edit/{id}', 'Staffedit')->name('staff.gsuide.edit1');
                Route::get('/invoice/{id}', 'Staffinvoice')->name('staff.gsuide.invoice');
                Route::get('/not-interest/{id}', 'StaffNotInterest')->name('staff.gsuide.not.interest');
                Route::get('/interest/{id}', 'StaffInterest')->name('staff.gsuide.interest');
                Route::get('/delete/{id}', 'Staffdelete')->name('staff.gsuide.delete');

                Route::post('/store', 'Staffstore')->name('staff.gsuide.store');
                Route::post('/update/{id}', 'Staffupdate')->name('staff.gsuide.update');
                Route::post('/user-details', 'StaffUserDetails')->name('staff.gsuide.user.details');
            });
        });

        Route::group(['prefix'=>'amc'],function (){
            Route::controller(AdminAmcController::class)->group(function () {
                Route::get('/create', 'Staffcreate')->name('staff.amc.create');
                Route::get('/view', 'Staffview')->name('staff.amc.view');
                Route::get('/not-interest-list', 'Staffnotview')->name('staff.amc.view.interest');
                Route::get('/edit/{id}', 'Staffedit')->name('staff.amc.edit1');
                Route::get('/invoice/{id}', 'Staffinvoice')->name('staff.amc.invoice');
                Route::get('/not-interest/{id}', 'StaffNotInterest')->name('staff.amc.not.interest');
                Route::get('/interest/{id}', 'StaffInterest')->name('staff.amc.interest');
                Route::get('/delete/{id}', 'Staffdelete')->name('staff.amc.delete');

                Route::post('/store', 'Staffstore')->name('staff.amc.store');
                Route::post('/update/{id}', 'Staffupdate')->name('staff.amc.update');
                Route::post('/user-details', 'StaffUserDetails')->name('staff.amc.user.details');
            });
        });

        Route::group(['prefix'=>'leads-from'],function (){
            Route::controller(LeadsFromController::class)->group(function () {

                Route::get('/', 'Staffview')->name('staff.leads.from.view');
                Route::get('/leads-from-delete/{id}', 'Staffdelete')->name('staff.leads.from.delete');

                Route::post('/leads-from-edit', 'Staffedit')->name('staff.leads.from.edit');
                Route::post('/leads-from-store', 'Staffstore')->name('staff.leads.from.store');

                Route::post('/leads-from-bulk-upload', 'Staffbulk_upload')->name('staff.leads.from.bulk.upload');
                Route::post('/leads-from-report', 'StaffReport')->name('staff.leads.from.report');
            });
        });


        Route::group(['prefix'=>'notification'],function (){
            Route::controller(NotificationController::class)->group(function () {
                Route::get('', 'ViewNotification')->name('notification.view');
                Route::post('/status', 'NotificationUpdate')->name('notification.update');
            });
        });

    });
});

Route::group(['prefix'=>'sub-admin'],function (){
    Route::middleware(['sub_admin', 'auth'])->group(function() {

        Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'sub_admin_index'])->name('sub.admin.index');

        Route::get('/profile', [App\Http\Controllers\HomeController::class, 'SubAdminProfile'])->name('sub.admin.profile');
        Route::post('/profile/upload/{id}', [App\Http\Controllers\HomeController::class, 'SubAdminProfileUpload'])->name('sub.admin.profile.upload');

        Route::post('/project-details', [App\Http\Controllers\HomeController::class, 'SubProjectDetails'])->name('sub_admin.project.details');

        Route::get('/this-month-projects/{month}/{year}', [App\Http\Controllers\HomeController::class, 'SubThisMonthProjects'])->name('sub_admin.this.month.projects');


        Route::group(['prefix'=>'proposal'],function (){
            Route::controller(SubAdminProposalController::class)->group(function () {
                Route::get('/create', 'create')->name('sub_admin.proposal.create');
                Route::get('/view', 'view')->name('sub_admin.proposal.view');
                Route::get('/edit/{id}', 'edit')->name('sub_admin.proposal.edit');
                Route::get('/delete/{id}', 'delete')->name('sub_admin.proposal.delete');
                Route::get('/status/{id}', 'status')->name('sub_admin.proposal.status');

                Route::post('/store', 'store')->name('sub_admin.proposal.store');
                Route::post('/update/{id}', 'update')->name('sub_admin.proposal.update');
            });
        });

        Route::group(['prefix'=>'services'],function (){
            Route::controller(ClientController::class)->group(function () {
                Route::get('/', 'SubAdminindex')->name('sub_admin.service.view');
                Route::get('/services-delete/{id}', 'SubAdmindelete')->name('sub_admin.service.delete');

                Route::post('/services-edit', 'SubAdminedit')->name('sub_admin.service.edit');
                Route::post('/services-store', 'SubAdminstore')->name('sub_admin.service.store');
            });
        });

        Route::group(['prefix'=>'projects'],function (){
            Route::controller(App\Http\Controllers\Admin\AdminProjectController::class)->group(function () {

                Route::get('/create', 'create')->name('sub_admin.projects.create');
                Route::get('/view', 'view')->name('sub_admin.projects.view');
                Route::get('/edit/{id}', 'edit')->name('sub_admin.projects.edit');
                Route::get('/delete/{id}', 'delete')->name('sub_admin.projects.delete');
                Route::get('/status/{id}', 'status')->name('sub_admin.projects.status');
                Route::get('/renewal-delete/{id}', 'RenewalDelete')->name('sub_admin.projects.renewal.delete');
                Route::get('/payment-delete/{id}', 'PaymentDelete')->name('sub_admin.projects.payment.delete');

                Route::post('/store', 'store')->name('sub_admin.projects.store');
                Route::post('/follow-store', 'follow_store')->name('sub_admin.projects.follow.store');
                Route::post('/payment_update', 'payment_update')->name('sub_admin.projects.payment.update');
                Route::post('/payment_details', 'payment_details')->name('sub_admin.projects.payment.details');
                Route::post('/payment-edit', 'payment_edit_details')->name('sub_admin.projects.payment.edit.details');
                Route::post('/payment-update', 'payment_edit_update')->name('sub_admin.projects.payment.edit.update');
                Route::post('/update/{id}', 'update')->name('sub_admin.projects.update');
                Route::post('/filter', 'filter')->name('sub_admin.projects.filter');

                Route::get('/on-going-project', 'OnGoingProject')->name('sub_admin.projects.going');
                Route::get('/pending-project', 'PendingProject')->name('sub_admin.projects.pending');
                Route::get('/on-hold-project', 'OnHoldProject')->name('sub_admin.projects.hold');
                Route::get('/completed-project', 'CompletedProject')->name('sub_admin.projects.completed');
                Route::get('/cancel-project', 'CancelProject')->name('sub_admin.projects.cancel');
                Route::get('/renewal-project', 'RenewalProject')->name('sub_admin.projects.renewal');
                Route::get('/completed-payment-project', 'PaymentCompleteView')->name('sub_admin.completed.payment.project');
                Route::get('/project-view/{id}', 'ViewProject')->name('sub_admin.projects.view.details');

                Route::get('/renewal-project-view', 'RenewalProjectView')->name('sub_admin.projects.renewal.view');

                Route::post('/search-view', 'SearchView')->name('sub_admin.projects.search.view');

            });
        });

        Route::group(['prefix'=>'customers'],function (){
            Route::controller(App\Http\Controllers\Admin\AdminCustomersController::class)->group(function () {
                Route::get('/view', 'view')->name('sub_admin.customers.view');
                Route::get('/delete/{id}', 'delete')->name('sub_admin.customers.delete');

                Route::post('/edit', 'edit')->name('sub_admin.customers.edit');
                Route::post('/store', 'store')->name('sub_admin.customers.store');

                Route::post('/import-store', 'ImportStore')->name('sub_admin.customers.bulk.store');
            });
        });

        Route::group(['prefix'=>'branches'],function (){
            Route::controller(App\Http\Controllers\Admin\AdminBranchesController::class)->group(function () {
                Route::get('/view', 'view')->name('sub_admin.branches.view');
                Route::get('/delete/{id}', 'delete')->name('sub_admin.branches.delete');

                Route::post('/edit', 'edit')->name('sub_admin.branches.edit');
                Route::post('/store', 'store')->name('sub_admin.branches.store');
            });
        });

        Route::group(['prefix'=>'domain-and-hosting'],function (){
            Route::controller(App\Http\Controllers\Admin\AdminRenewalController::class)->group(function () {
                Route::get('/create', 'create')->name('sub_admin.domain.hosting.create');
                Route::get('/view', 'view')->name('sub_admin.domain.hosting.view');
                Route::get('/not-interest-list', 'notview')->name('sub_admin.domain.hosting.view.interest');
                Route::get('/edit/{id}', 'edit')->name('sub_admin.domain.hosting.edit1');
                Route::get('/invoice/{id}', 'invoice')->name('sub_admin.domain.hosting.invoice');
                Route::get('/not-interest/{id}', 'NotInterest')->name('sub_admin.domain.hosting.not.interest');
                Route::get('/interest/{id}', 'Interest')->name('sub_admin.domain.hosting.interest');
                Route::get('/delete/{id}', 'delete')->name('sub_admin.domain.hosting.delete');

                Route::post('/store', 'store')->name('sub_admin.domain.hosting.store');
                Route::post('/update/{id}', 'update')->name('sub_admin.domain.hosting.update');
                Route::post('/user-details', 'UserDetails')->name('sub_admin.domain.hosting.user.details');

                Route::post('/search', 'search')->name('sub_admin.domain.hosting.search');
            });
        });

        Route::group(['prefix'=>'gsuide'],function (){
            Route::controller(App\Http\Controllers\Admin\AdminGSuiteController::class)->group(function () {
                Route::get('/create', 'create')->name('sub_admin.gsuide.create');
                Route::get('/view', 'view')->name('sub_admin.gsuide.view');
                Route::get('/not-interest-list', 'notview')->name('sub_admin.gsuide.view.interest');
                Route::get('/edit/{id}', 'edit')->name('sub_admin.gsuide.edit1');
                Route::get('/invoice/{id}', 'invoice')->name('sub_admin.gsuide.invoice');
                Route::get('/not-interest/{id}', 'NotInterest')->name('sub_admin.gsuide.not.interest');
                Route::get('/interest/{id}', 'Interest')->name('sub_admin.gsuide.interest');
                Route::get('/delete/{id}', 'delete')->name('sub_admin.gsuide.delete');

                Route::post('/store', 'store')->name('sub_admin.gsuide.store');
                Route::post('/update/{id}', 'update')->name('sub_admin.gsuide.update');
                Route::post('/user-details', 'UserDetails')->name('sub_admin.gsuide.user.details');
            });
        });

        Route::group(['prefix'=>'amc'],function (){
            Route::controller(App\Http\Controllers\Admin\AdminAmcController::class)->group(function () {
                Route::get('/create', 'create')->name('sub_admin.amc.create');
                Route::get('/view', 'view')->name('sub_admin.amc.view');
                Route::get('/not-interest-list', 'notview')->name('sub_admin.amc.view.interest');
                Route::get('/edit/{id}', 'edit')->name('sub_admin.amc.edit1');
                Route::get('/invoice/{id}', 'invoice')->name('sub_admin.amc.invoice');
                Route::get('/not-interest/{id}', 'NotInterest')->name('sub_admin.amc.not.interest');
                Route::get('/interest/{id}', 'Interest')->name('sub_admin.amc.interest');
                Route::get('/delete/{id}', 'delete')->name('sub_admin.amc.delete');

                Route::post('/store', 'store')->name('sub_admin.amc.store');
                Route::post('/update/{id}', 'update')->name('sub_admin.amc.update');
                Route::post('/user-details', 'UserDetails')->name('sub_admin.amc.user.details');
            });
        });

        //Staff
        Route::group(['prefix'=>'staff'],function (){
            Route::controller(SubAdminController::class)->group(function () {
                Route::get('/create', 'SubStaffCreate')->name('staff.create');
                Route::get('/view', 'SubStaffView')->name('staff.view');
                Route::get('/edit/{id}', 'SubStaffEdit')->name('staff.edit');
                // Route::get('/task/details', 'SubStafTaskDetails')->name('staff.task.details');
                Route::get('/delete/{id}', 'SubStaffDelete')->name('staff.delete');
                Route::get('/task/details/{id}', 'StaffTaskDetails')->name('staff.task.details');
                Route::get('/staff-import', 'StaffImport')->name('sub_admin.staff.import');

                Route::post('/store', 'SubStaffStore')->name('staff.store');
                Route::post('/update/{id}', 'SubStaffUpdate')->name('staff.update');
                Route::post('/download', 'SubStaffReport')->name('sub_admin.staff.report');
                Route::get('/recurring-download/{id}', 'SubStaffRecurringReport')->name('sub_admin.staff.recurring.report');
                Route::post('/staff-import-store', 'StaffImportStore')->name('sub_admin.bulk.store');

            });
        });

        Route::group(['prefix'=>'task'],function (){
            Route::controller(SubAdminTaskController::class)->group(function () {
                Route::get('/create', 'TaskCreate')->name('sub.task.create');
                // Route::get('/recommand-task', 'RecommandTaskCreate')->name('sub.task.create');
                Route::get('/view-task', 'TaskView')->name('sub.task.view');
                Route::get('/recurring-task', 'TaskRecurringView')->name('sub.task.recurring.view');
                Route::get('/edit/{id}', 'TaskEdit')->name('sub.task.edit');
                Route::get('/status/{id}', 'TaskStatusUpdate')->name('sub.task.status');
                Route::get('/delete/{id}', 'TaskDelete')->name('sub.task.delete');
                Route::get('/single-delete/{id}', 'TaskSingleDelete')->name('sub.task.single.delete');
                Route::get('/closed-status/{id}', 'ClosedStatusTask')->name('sub_admin.closed.status.task');
                Route::get('/comment-delete/{id}', 'TaskCommentDelete')->name('sub_admin.task.comment.delete');

                Route::post('/store', 'TaskStore')->name('sub.task.store');
                Route::post('/reopen', 'Reopen')->name('sub_admin.task.reopen');
                Route::post('/status/update', 'TaskStatus')->name('sub_admin.task.status.update');
                Route::post('/recur/status/update', 'RecurTaskStatus')->name('sub_admin.recur.task.status.update');
                Route::post('/update/{id}', 'TaskUpdate')->name('sub.task.update');
                Route::post('/render-model', 'SubAdminModel')->name('sub.admin.model.render');
                Route::post('/time/update', 'TaskDetailsTimeUpdate')->name('sub_admin.task.details.time.update');
                Route::get('/task_details/{id}', 'TaskDetailsGet')->name('sub_admin.task.details.get');
                Route::post('/task_details/update', 'TaskDetailsUpdate')->name('sub_admin.task.details.update');
                Route::post('/download/report', 'SuperAdminDownload')->name('sub_admin.report.download');
                Route::post('/update_comment', 'TaskUpdateComment')->name('sub_admin.task.update.comment');
                // Super Admin to Sub Admin Task
                Route::get('/your-task', 'YourTaskView')->name('sub_admin.your.task.view');
                Route::get('/your-task-details/{id}', 'YourTaskDetailsView')->name('sub_admin.your.details.task.view');
                Route::post('/your-model-view', 'YourAdminModel')->name('sub_admin.your.model.render');
                Route::post('/admin/status/update', 'AdminTaskStatus')->name('sub_admin.admin.task.status.update');
                Route::post('/task/details/update', 'AdminTaskDetailStatus')->name('sub.admin.task.details.status.update');

                Route::post('/task-comments', 'AddTaskStatus')->name('sub_admin.task.comment.update');
                Route::post('/your-task/time/update', 'TaskDetailsTimeUpdate1')->name('your.task.details.time.update');
                Route::get('/change-status/{id}', 'ChangeStatusTask')->name('sub_admin.change.status.task');
                Route::post('/sub-admin-staff-check', 'AdminTaskCheck')->name('sub_admin.staff.task.check');

                Route::post('/admin-task-search-get', 'AdminSearchTask')->name('sub_admin.task.search');
                Route::post('/admin-task-update-time', 'AdminTaskUpdateTime')->name('sub_admin.task.hours.update');

                // Follow Up Project
                Route::get('/follow-up', 'FollowUpProject')->name('sub_admin.follow.up.project');
                Route::get('/follow-up-task-details/{id}', 'FollowUpProjectDetails')->name('sub_admin.follow.up.project.details');
                Route::post('/follow-up-task/comments', 'FollowUpProjectComments')->name('sub_admin.follow.up.comments.project');
                Route::get('/coordinar-status/{id}', 'ChangeCoordinarTask')->name('sub_admin.change.coordinar.task');
                Route::get('/payment-status/{id}', 'ChangePaymentTask')->name('sub_admin.change.payment.task');
                
                // Follow up Payment
                Route::post('/follow-up-payment', 'FollowUpProjectPayment')->name('sub_admin.follow.up.project.payment');

            });
        });

        Route::group(['prefix'=>'notification'],function (){
            Route::controller(NotificationController::class)->group(function () {
                Route::get('', 'ViewSubNotification')->name('sub_admin.notification.view');
                Route::post('/status', 'NotificationSubUpdate')->name('sub_admin.notification.update');
            });
        });

        Route::group(['prefix'=>'clients'],function (){
            Route::controller(SubClientController::class)->group(function () {

                Route::get('/', 'ClientIndex')->name('sub_admin.client.view');
                Route::get('/client-delete/{id}', 'ClientDelete')->name('sub_admin.client.delete');

                Route::post('/client-edit', 'ClientEdit')->name('sub_admin.client.edit');
                Route::post('/client-store', 'ClientStore')->name('sub_admin.client.store');

                Route::post('/client-import-store', 'clientImportStore')->name('sub_admin.client.bulk.store');
            });
        });

        Route::group(['prefix'=>'leads-from'],function (){
            Route::controller(LeadsFromController::class)->group(function () {

                Route::get('/', 'Subview')->name('sub_admin.leads.from.view');
                Route::get('/leads-from-delete/{id}', 'Subdelete')->name('sub_admin.leads.from.delete');
                Route::get('/leads-from-complete/{id}', 'SubdoneLeads')->name('sub_admin.leads.from.done');
                
                Route::post('/leads-from-edit', 'Subedit')->name('sub_admin.leads.from.edit');
                Route::post('/leads-from-store', 'Substore')->name('sub_admin.leads.from.store');

                Route::post('/leads-from-bulk-upload', 'Subbulk_upload')->name('sub_admin.leads.from.bulk.upload');
                Route::post('/leads-from-report', 'SubReport')->name('sub_admin.leads.from.report');
            });
        });

        Route::group(['prefix'=>'leads-status-list'],function (){
            Route::controller(LeadStatusController::class)->group(function () {

                Route::get('/', 'Subview')->name('sub_admin.status.list.view');
                Route::get('/delete/{id}', 'Subdelete')->name('sub_admin.status.list.delete');

                Route::post('/edit', 'Subedit')->name('sub_admin.status.list.edit');
                Route::post('/store', 'Substore')->name('sub_admin.status.list.store');

            });
        });

        Route::group(['prefix'=>'imcome-amount'],function (){
            Route::controller(IncomeAmountController::class)->group(function () {

                Route::get('/', 'SubAdminview')->name('sub_admin.imcome.amount.view');
                Route::get('/delete/{id}', 'SubAdmindelete')->name('sub_admin.imcome.amount.delete');

                Route::post('/edit', 'SubAdminedit')->name('sub_admin.imcome.amount.edit');
                Route::post('/store', 'SubAdminstore')->name('sub_admin.imcome.amount.store');

            });
        });

        Route::group(['prefix'=>'expenses-amount'],function (){
            Route::controller(ExpensiveAmountController::class)->group(function () {

                Route::get('/', 'SubAdminview')->name('sub_admin.expenses.amount.view');
                Route::get('/delete/{id}', 'SubAdmindelete')->name('sub_admin.expenses.amount.delete');

                Route::post('/edit', 'SubAdminedit')->name('sub_admin.expenses.amount.edit');
                Route::post('/store', 'SubAdminstore')->name('sub_admin.expenses.amount.store');

                Route::post('/search-data', 'SubAdminSearch')->name('sub_admin.expenses.amount.search');

            });
        });

        Route::group(['prefix'=>'credentials'],function (){
            Route::controller(CredentialController::class)->group(function () {

                Route::get('/', 'SubView')->name('sub_admin.credentials.view');
                Route::get('/delete/{id}', 'SubDelete')->name('sub_admin.credentials.delete');

                Route::post('/edit', 'SubEdit')->name('sub_admin.credentials.edit');
                Route::post('/store', 'SubStore')->name('sub_admin.credentials.store');

                Route::post('/credentials-bulk-upload', 'SubBulk_upload')->name('sub_admin.credentials.bulk.upload');

            });
        });

        Route::group(['prefix'=>'follow-up'],function (){
            Route::controller(FollowUpController::class)->group(function () {
                Route::get('/view', 'SubView')->name('sub_admin.follow.up.view');
                Route::get('/delete/{id}', 'SubSelete')->name('sub_admin.follow.up.delete');
                Route::get('/project/{id}', 'SubProject')->name('sub_admin.follow.up.add.project');
                Route::get('/export', 'SubExport')->name('sub_admin.follow.up.export');

                Route::post('/edit', 'SubEdit')->name('sub_admin.follow.up.edit');
                Route::post('/store', 'SubStore')->name('sub_admin.follow.up.store');
                Route::post('/search', 'SubSearch')->name('sub_admin.follow.up.search');
                Route::post('/import-store', 'SubImportStore')->name('sub_admin.follow.up.bulk.store');

            });
        });

        Route::group(['prefix'=>'campaign-details'],function (){
            Route::controller(CampaignController::class)->group(function () {
                Route::get('/', 'Subview')->name('sub_admin.campaign.details.view');
                Route::get('/delete/{id}', 'Subdelete')->name('sub_admin.campaign.details.delete');
                Route::get('/add', 'Subadd')->name('sub_admin.campaign.details.add');
                
                Route::post('/store', 'Substore')->name('sub_admin.campaign.details.store');
            });
        });

        Route::group(['prefix'=>'my-bills'],function (){
            Route::controller(MyBillController::class)->group(function () {
                Route::get('/view', 'Subview')->name('sub_admin.my.bills.view');
                Route::get('/delete/{id}', 'Subdelete')->name('sub_admin.my.bills.delete');

                Route::post('/edit', 'Subedit')->name('sub_admin.my.bills.edit');
                Route::post('/store', 'Substore')->name('sub_admin.my.bills.store');
            });
        });

    });
});

Route::group(['prefix'=>'super-admin'],function (){
    Route::middleware(['super_admin', 'auth'])->group(function() {

        Route::get('', [App\Http\Controllers\HomeController::class, 'admin_index'])->name('admin.index');
        Route::get('/profile', [App\Http\Controllers\HomeController::class, 'AdminProfile'])->name('admin.profile');
        Route::post('/profile/upload/{id}', [App\Http\Controllers\HomeController::class, 'AdminProfileUpload'])->name('admin.profile.upload');

        Route::post('/incentive-save', [App\Http\Controllers\HomeController::class, 'IncentiveSave'])->name('admin.incentive.save');
        Route::post('/incentive-group-save', [App\Http\Controllers\HomeController::class, 'IncentiveGroupSave'])->name('admin.incentive.group.save');

        Route::post('/g-suide-save', [App\Http\Controllers\HomeController::class, 'GSuideSave'])->name('admin.gsuide.save');

        Route::post('/project-details', [App\Http\Controllers\HomeController::class, 'ProjectDetails'])->name('super.admin.project.details');

        Route::post('/user-model-view', [App\Http\Controllers\HomeController::class, 'SuperAdminUserModel'])->name('super.admin.user.model.render');

        Route::get('/this-month-projects/{month}/{year}', [App\Http\Controllers\HomeController::class, 'ThisMonthProjects'])->name('super.admin.this.month.projects');


        //Admin
        Route::group(['prefix'=>'admin'],function (){
            Route::controller(AdminController::class)->group(function () {
                Route::get('/create', 'create')->name('admin.admin.create');
                Route::get('/view', 'view')->name('admin.admin.view');
                Route::get('/edit/{id}', 'edit')->name('admin.admin.edit');
                Route::get('/delete/{id}', 'delete')->name('admin.admin.delete');

                Route::post('/store', 'store')->name('admin.admin.store');
                Route::post('/update/{id}', 'update')->name('admin.admin.update');
            });
        });

        Route::group(['prefix'=>'proposal'],function (){
            Route::controller(AdminProposalController::class)->group(function () {
                Route::get('/create', 'create')->name('admin.proposal.create');
                Route::get('/view', 'view')->name('admin.proposal.view');
                Route::get('/edit/{id}', 'edit')->name('admin.proposal.edit');
                Route::get('/delete/{id}', 'delete')->name('admin.proposal.delete');
                Route::get('/status/{id}', 'status')->name('admin.proposal.status');

                Route::post('/store', 'store')->name('admin.proposal.store');
                Route::post('/update/{id}', 'update')->name('admin.proposal.update');
            });
        });

        Route::group(['prefix'=>'projects'],function (){
            Route::controller(AdminProjectController::class)->group(function () {
                Route::get('/create', 'create')->name('admin.projects.create');
                Route::get('/view', 'view')->name('admin.projects.view');
                Route::get('/edit/{id}', 'edit')->name('admin.projects.edit');
                Route::get('/delete/{id}', 'delete')->name('admin.projects.delete');
                Route::get('/status/{id}', 'status')->name('admin.projects.status');
                Route::get('/renewal-delete/{id}', 'RenewalDelete')->name('admin.projects.renewal.delete');
                Route::get('/download-project', 'DownloadProject')->name('admin.download.projects.view');
                Route::get('/payment-delete/{id}', 'PaymentDelete')->name('admin.projects.payment.delete');
                Route::get('/stop-recurring-project/{id}', 'StopRecurringProject')->name('admin.projects.stop.recurring');

                Route::get('/year-wise-project/{id}', 'YearWiseProject')->name('admin.projects.year.wise');

                Route::post('/store', 'store')->name('admin.projects.store');
                Route::post('/follow-store', 'follow_store')->name('admin.projects.follow.store');
                Route::post('/payment_update', 'payment_update')->name('admin.projects.payment.update');
                Route::post('/payment_details', 'payment_details')->name('admin.projects.payment.details');
                Route::post('/payment-edit', 'payment_edit_details')->name('admin.projects.payment.edit.details');
                Route::post('/payment-update', 'payment_edit_update')->name('admin.projects.payment.edit.update');
                Route::post('/update/{id}', 'update')->name('admin.projects.update');
                Route::post('/filter', 'filter')->name('admin.projects.filter');
                Route::post('/start-new-recuring', 'StartNewRecurring')->name('admin.start.new.recuring');

                Route::get('/on-going-project', 'OnGoingProject')->name('admin.projects.going');
                Route::get('/pending-project', 'PendingProject')->name('admin.projects.pending');
                Route::get('/on-hold-project', 'OnHoldProject')->name('admin.projects.hold');
                Route::get('/completed-project', 'CompletedProject')->name('admin.projects.completed');
                Route::get('/cancel-project', 'CancelProject')->name('admin.projects.cancel');
                Route::get('/renewal-project', 'RenewalProject')->name('admin.projects.renewal');
                Route::get('/completed-payment-project', 'PaymentCompleteView')->name('admin.completed.payment.project');
                Route::get('/project-view/{id}', 'ViewProject')->name('admin.projects.view.details');

                Route::get('/renewal-project-view', 'RenewalProjectView')->name('admin.projects.renewal.view');

                Route::post('/search-view', 'SearchView')->name('admin.projects.search.view');
                Route::post('/completed-search-view', 'CompletedSearchView')->name('admin.projects.completed.search.view');

                Route::get('/employee-project-report', 'EmployeeProject')->name('admin.projects.employee.report');



                Route::post('/year-wise-store', 'YearWiseStore')->name('admin.projects.year.wise.store');

            });
        });
        
        Route::group(['prefix'=>'sales'],function (){
            Route::controller(AdminSalesController::class)->group(function () {
                Route::get('/create', 'create')->name('admin.sales.create');
                Route::get('/view', 'view')->name('admin.sales.view');
                Route::get('/edit/{id}', 'edit')->name('admin.sales.edit');
                Route::get('/delete/{id}', 'delete')->name('admin.sales.delete');

                Route::post('/store', 'store')->name('admin.sales.store');
                Route::post('/update/{id}', 'update')->name('admin.sales.update');
            });
        });

        Route::group(['prefix'=>'customers'],function (){
            Route::controller(AdminCustomersController::class)->group(function () {
                Route::get('/view', 'view')->name('admin.customers.view');
                Route::get('/export', 'export')->name('admin.customers.export');
                Route::get('/delete/{id}', 'delete')->name('admin.customers.delete');

                Route::post('/edit', 'edit')->name('admin.customers.edit');
                Route::post('/user-store', 'user_store')->name('admin.customers.user.store');
                Route::post('/store', 'store')->name('admin.customers.store');
                Route::post('/search', 'search')->name('admin.customers.search');
                Route::post('/import-store', 'ImportStore')->name('admin.customers.bulk.store');

                Route::get('/details/{id}', 'details')->name('admin.customers.details');
            });
        });

        Route::group(['prefix'=>'branches'],function (){
            Route::controller(AdminBranchesController::class)->group(function () {
                Route::get('/view', 'view')->name('admin.branches.view');
                Route::get('/delete/{id}', 'delete')->name('admin.branches.delete');

                Route::post('/edit', 'edit')->name('admin.branches.edit');
                Route::post('/store', 'store')->name('admin.branches.store');
            });
        });

        Route::group(['prefix'=>'domain-and-hosting'],function (){
            Route::controller(AdminRenewalController::class)->group(function () {
                Route::get('/create', 'create')->name('admin.domain.hosting.create');
                Route::get('/view', 'view')->name('admin.domain.hosting.view');
                Route::get('/not-interest-list', 'notview')->name('admin.domain.hosting.view.interest');
                Route::get('/edit/{id}', 'edit')->name('admin.domain.hosting.edit1');
                Route::get('/invoice/{id}', 'invoice')->name('admin.domain.hosting.invoice');
                Route::get('/not-interest/{id}', 'NotInterest')->name('admin.domain.hosting.not.interest');
                Route::get('/interest/{id}', 'Interest')->name('admin.domain.hosting.interest');
                Route::get('/delete/{id}', 'delete')->name('admin.domain.hosting.delete');

                Route::post('/store', 'store')->name('admin.domain.hosting.store');
                Route::post('/update/{id}', 'update')->name('admin.domain.hosting.update');
                Route::post('/user-details', 'UserDetails')->name('admin.domain.hosting.user.details');

                Route::post('/search', 'search')->name('admin.domain.hosting.search');
            });
        });

        Route::group(['prefix'=>'gsuide'],function (){
            Route::controller(AdminGSuiteController::class)->group(function () {
                Route::get('/create', 'create')->name('admin.gsuide.create');
                Route::get('/view', 'view')->name('admin.gsuide.view');
                Route::get('/not-interest-list', 'notview')->name('admin.gsuide.view.interest');
                Route::get('/edit/{id}', 'edit')->name('admin.gsuide.edit1');
                Route::get('/invoice/{id}', 'invoice')->name('admin.gsuide.invoice');
                Route::get('/not-interest/{id}', 'NotInterest')->name('admin.gsuide.not.interest');
                Route::get('/interest/{id}', 'Interest')->name('admin.gsuide.interest');
                Route::get('/delete/{id}', 'delete')->name('admin.gsuide.delete');

                Route::post('/store', 'store')->name('admin.gsuide.store');
                Route::post('/update/{id}', 'update')->name('admin.gsuide.update');
                Route::post('/details-get', 'DetailsGet')->name('admin.gsuide.details.get');
                Route::post('/user-details', 'UserDetails')->name('admin.gsuide.user.details');

                Route::post('/search', 'search')->name('admin.gsuide.search');
            });
        });

        Route::group(['prefix'=>'amc'],function (){
            Route::controller(AdminAmcController::class)->group(function () {
                Route::get('/create', 'create')->name('admin.amc.create');
                Route::get('/view', 'view')->name('admin.amc.view');
                Route::get('/not-interest-list', 'notview')->name('admin.amc.view.interest');
                Route::get('/edit/{id}', 'edit')->name('admin.amc.edit1');
                Route::get('/invoice/{id}', 'invoice')->name('admin.amc.invoice');
                Route::get('/not-interest/{id}', 'NotInterest')->name('admin.amc.not.interest');
                Route::get('/interest/{id}', 'Interest')->name('admin.amc.interest');
                Route::get('/delete/{id}', 'delete')->name('admin.amc.delete');

                Route::post('/store', 'store')->name('admin.amc.store');
                Route::post('/update/{id}', 'update')->name('admin.amc.update');
                Route::post('/user-details', 'UserDetails')->name('admin.amc.user.details');

                Route::post('/search', 'search')->name('admin.amc.search');
            });
        });

        //Sub Admin
        Route::group(['prefix'=>'sub-admin'],function (){
            Route::controller(HomeController::class)->group(function () {
                Route::get('/create', 'SubAdminCreate')->name('sub.admin.create');
                Route::get('/view', 'SubAdminView')->name('sub.admin.view');
                Route::get('/edit/{id}', 'SubAdminEdit')->name('sub.admin.edit');
                Route::get('/delete/{id}', 'SubAdminDelete')->name('sub.admin.delete');
                Route::get('/task/details/{id}', 'SubAdminTaskDetails')->name('sub.admin.task.details');

                Route::post('/store', 'SubAdminStore')->name('sub.admin.store');
                Route::post('/update/{id}', 'SubAdminUpdate')->name('sub.admin.update');
                Route::post('/download', 'SubAdminReport')->name('sub.admin.staff.report');
                Route::get('/recurring-download/{id}', 'SubAdminRecurringReport')->name('sub.admin.recurring.report');

            });
        });

        Route::group(['prefix'=>'staff'],function (){
            Route::controller(StaffController::class)->group(function () {
                Route::get('/create', 'SubStaffCreate')->name('sub.staff.create');
                Route::get('/view', 'SubStaffView')->name('sub.staff.view');
                Route::get('/edit/{id}', 'SubStaffEdit')->name('sub.staff.edit');
                Route::get('/delete/{id}', 'SubStaffDelete')->name('sub.staff.delete');
                Route::get('/task/details/{id}', 'SubStafTaskDetails')->name('sub.task.details');
                

                Route::post('/store', 'SubStaffStore')->name('sub.staff.store');
                
                Route::post('/update/{id}', 'SubStaffUpdate')->name('sub.staff.update');
                Route::post('/download', 'SubStaffReport')->name('sub.staff.report');
                Route::get('/recurring-download/{id}', 'SubStaffRecurringReport')->name('sub.staff.recurring.report');

                Route::get('/group', 'SubStafGroup')->name('sub.task.group');
                Route::get('/group-add', 'SubStafGroupAdd')->name('sub.task.group.add');
                Route::get('/group-edit/{id}', 'SubStaffGroupEdit')->name('sub.staff.group.edit');
                Route::get('/group-delete/{id}', 'SubStaffGroupDelete')->name('sub.staff.group.delete');

                Route::post('/group-store', 'SubStaffGroupStore')->name('sub.staff.group.store');
                Route::post('/group-update/{id}', 'SubStaffGroupUpdate')->name('sub.staff.group.update');

                Route::get('/incentive', 'SubStafIncentive')->name('sub.task.incentive');
                Route::get('/incentive-add', 'SubStafIncentiveAdd')->name('sub.task.incentive.add');
                Route::get('/incentive-edit/{id}', 'SubStaffIncentiveEdit')->name('sub.staff.incentive.edit');
                Route::get('/incentive-delete/{id}', 'SubStaffIncentiveDelete')->name('sub.staff.incentive.delete');

                Route::post('/incentive-store', 'SubStaffIncentiveStore')->name('sub.staff.incentive.store');
                Route::post('/incentive-update', 'SubStaffIncentiveUpdate')->name('sub.staff.incentive.update');

            });
        });

        Route::group(['prefix'=>'freelancer'],function (){
            Route::controller(FreeLancerController::class)->group(function () {

                Route::get('/create', 'create')->name('admin.freelancer.create');
                Route::get('/view', 'view')->name('admin.freelancer.view');
                Route::get('/edit/{id}', 'edit')->name('admin.freelancer.edit');
                Route::get('/delete/{id}', 'delete')->name('admin.freelancer.delete');

                Route::post('/store', 'store')->name('admin.freelancer.store');
                Route::post('/update/{id}', 'update')->name('admin.freelancer.update');

                // Freelancer Task Request
                Route::get('/estimate-create', 'EstimateCreate')->name('admin.freelancer.request.create');
                Route::get('/task-request', 'TaskRequest')->name('admin.freelancer.request');
                Route::get('/request-details/{id}', 'TaskRequestDetails')->name('admin.freelancer.task.details');
                Route::get('/request-edit/{id}', 'TaskRequestEdit')->name('admin.freelancer.task.edit');
                Route::get('/details/delete/{id}', 'DeleteDetailsTask')->name('admin.freelancer.details.delete');
                Route::get('/estimate/delete/{id}', 'DeleteEstimateTask')->name('admin.freelancer.estimate.delete');
                Route::get('/estimate-commen5/delete/{id}', 'DeleteEstimateComment')->name('admin.freelancer.task.estimate.comment.delete');

                Route::post('/estimate-store', 'EstimateStore')->name('admin.freelancer.request.store');
                Route::post('/estimate-extra.store', 'EstimateExtraStore')->name('admin.freelancer.request.extra.store');
                Route::post('/task-request-update', 'TaskRequestUpdate')->name('admin.freelancer.request.update');
                Route::post('/task-request-comment-update', 'TaskRequestCommentUpdate')->name('admin.freelancer.request.comment.update');
                Route::post('/task-request-payment-update', 'TaskRequestPaymentUpdate')->name('admin.freelancer.request.payment.update');
                Route::post('/task-estimate-close', 'TaskEstimateClose')->name('admin.freelancer.estimate.close');
                Route::post('/estimate-edit-store/{id}', 'EstimateEditStore')->name('admin.freelancer.request.edit.store');
                
                Route::post('/estimate-description', 'EstimateDescription')->name('admin.freelancer.request.description');
                Route::post('/estimate-pay', 'EstimatePay')->name('admin.freelancer.request.pay');

            });
        });

        Route::group(['prefix'=>'task'],function (){
            Route::controller(TaskController::class)->group(function () {
                Route::get('/create', 'TaskCreate')->name('task.create');
                Route::get('/recommend-view', 'RecommandTaskView')->name('admin.recommand.task.view');
                Route::get('/recommend-task', 'RecommandTaskCreate')->name('admin.recommand.task.create');
                Route::get('/single-task', 'TaskView')->name('task.view');
                Route::get('/recurring-task', 'TaskRecurringView')->name('recurring.task.view');
                Route::get('/admin-task', 'AdminTaskView')->name('admin.task.view');
                Route::get('/edit/{id}', 'TaskEdit')->name('task.edit');
                Route::get('/delete/{id}', 'TaskDelete')->name('task.delete');
                Route::get('/status/{id}', 'TaskStatusUpdate')->name('admin.task.status');
                Route::get('/delete/{id}', 'TaskDelete')->name('admin.task.delete'); 
                Route::get('/single-delete/{id}', 'TaskSingleDelete')->name('admin.task.single.delete');
                Route::get('/comment-delete/{id}', 'TaskCommentDelete')->name('task.comment.delete');

                Route::post('/model-view', 'SuperAdminModel')->name('super.admin.model.render');
                Route::post('/store', 'TaskStore')->name('task.store');
                Route::post('/recommend-store', 'RecommandTaskStore')->name('recommand.task.store');
                Route::post('/status/update', 'TaskStatus')->name('admin.task.status.update');
                Route::post('/reopen', 'Reopen')->name('admin.task.reopen');
                Route::post('/update/{id}', 'TaskUpdate')->name('task.update');
                Route::post('/admin-check', 'SubAdminCheck')->name('admin.staff.check');
                Route::post('/admin/comment', 'TaskAddComment')->name('admin.task.add.comment');
                Route::post('/admin/update_comment', 'TaskUpdateComment')->name('admin.task.update.comment');
                Route::post('/time/update', 'TaskDetailsTimeUpdate')->name('admin.task.details.time.update');
                Route::get('/task_details/{id}', 'TaskDetailsGet')->name('admin.task.details.get');
                Route::post('/task_details/update', 'TaskDetailsUpdate')->name('admin.task.details.update');
                Route::get('/closed-status/{id}', 'ClosedStatusTask')->name('admin.closed.status.task');
                Route::post('/download/report', 'SuperAdminDownload')->name('super_admin.report.download');
                Route::post('/admin-staff-check', 'AdminTaskCheck')->name('admin.staff.task.check');
                Route::post('/admin-staff-get', 'AdminStaffGet')->name('admin.staff.get');
                Route::post('/admin-task-search-get', 'AdminSearchTask')->name('admin.task.search');
                Route::post('/admin-task-update-time', 'AdminTaskUpdateTime')->name('admin.task.hours.update');

                Route::post('/follow-up-task/comments', 'FollowUpProjectComments')->name('admin.follow.up.comments.project');
                // Follow up Payment
                Route::post('/follow-up-payment', 'FollowUpProjectPayment')->name('admin.follow.up.project.payment');

                // Client Task Request
                Route::get('/estimate-create', 'EstimateCreate')->name('admin.task.request.create');
                Route::get('/task-request', 'TaskRequest')->name('task.request');
                Route::get('/request-details/{id}', 'TaskRequestDetails')->name('task.request.details');
                Route::get('/request-edit/{id}', 'TaskRequestEdit')->name('admin.task.request.edit');
                Route::get('/details/delete/{id}', 'DeleteDetailsTask')->name('admin.projects.task.details.delete');
                Route::get('/estimate/delete/{id}', 'DeleteEstimateTask')->name('admin.projects.task.estimate.delete');
                Route::get('/estimate-comment/delete/{id}', 'DeleteEstimateComment')->name('admin.projects.task.estimate.comment.delete');

                Route::post('/estimate-store', 'EstimateStore')->name('admin.task.request.store');
                Route::post('/estimate-extra.store', 'EstimateExtraStore')->name('admin.task.request.extra.store');
                Route::post('/task-request-update', 'TaskRequestUpdate')->name('admin.task.request.update');
                Route::post('/task-request-comment-update', 'TaskRequestCommentUpdate')->name('admin.task.request.comment.update');
                Route::post('/task-estimate-close', 'TaskEstimateClose')->name('admin.task.estimate.close');
                Route::post('/estimate-edit-store/{id}', 'EstimateEditStore')->name('admin.task.request.edit.store');
                
                Route::post('/estimate-description', 'EstimateDescription')->name('admin.task.request.description');

                // Freelancer Task Request
                Route::get('/freelancer-estimate-create', 'EstimateFreelancerCreate')->name('admin.task.freelancer.request.create');
            });
        });

        Route::group(['prefix'=>'notification'],function (){
            Route::controller(NotificationController::class)->group(function () {
                Route::get('', 'ViewAdminNotification')->name('admin.notification.view');
                Route::post('/status', 'NotificationAdminUpdate')->name('admin.notification.update');
            });
        });

        Route::group(['prefix'=>'services'],function (){
            Route::controller(ClientController::class)->group(function () {

                Route::get('/', 'index')->name('admin.service.view');
                Route::get('/services-delete/{id}', 'delete')->name('admin.service.delete');

                Route::post('/services-edit', 'edit')->name('admin.service.edit');
                Route::post('/services-store', 'store')->name('admin.service.store');
            });
        });

        Route::group(['prefix'=>'clients'],function (){
            Route::controller(ClientController::class)->group(function () {

                Route::get('/', 'ClientIndex')->name('admin.client.view');
                Route::get('/client-delete/{id}', 'ClientDelete')->name('admin.client.delete');

                Route::post('/client-edit', 'ClientEdit')->name('admin.client.edit');
                Route::post('/client-store', 'ClientStore')->name('admin.client.store');

                Route::post('/client-import-store', 'clientImportStore')->name('admin.client.bulk.store');
            });
        });

        Route::group(['prefix'=>'leads-from'],function (){
            Route::controller(LeadsFromController::class)->group(function () {

                Route::get('/', 'view')->name('admin.leads.from.view');
                Route::get('/leads-from-delete/{id}', 'delete')->name('admin.leads.from.delete');
                Route::get('/leads-from-complete/{id}', 'doneLeads')->name('admin.leads.from.done');
                Route::get('/leads-from-download', 'DownloadLeads')->name('admin.leads.from.download');

                Route::post('/leads-from-edit', 'edit')->name('admin.leads.from.edit');
                Route::post('/leads-from-store', 'store')->name('admin.leads.from.store');

                Route::post('/leads-from-bulk-upload', 'bulk_upload')->name('admin.leads.from.bulk.upload');
                Route::post('/leads-from-report', 'Report')->name('admin.leads.from.report');
            });
        });

        Route::group(['prefix'=>'status-list'],function (){
            Route::controller(LeadStatusController::class)->group(function () {

                Route::get('/', 'view')->name('admin.status.list.view');
                Route::get('/delete/{id}', 'delete')->name('admin.status.list.delete');

                Route::post('/edit', 'edit')->name('admin.status.list.edit');
                Route::post('/store', 'store')->name('admin.status.list.store');

            });
        });

        Route::group(['prefix'=>'imcome-amount'],function (){
            Route::controller(IncomeAmountController::class)->group(function () {

                Route::get('/', 'view')->name('admin.imcome.amount.view');
                Route::get('/delete/{id}', 'delete')->name('admin.imcome.amount.delete');

                Route::post('/edit', 'edit')->name('admin.imcome.amount.edit');
                Route::post('/store', 'store')->name('admin.imcome.amount.store');
                
                Route::post('/export-data', 'Export')->name('admin.imcome.amount.export');

            });
        });

        Route::group(['prefix'=>'expenses-amount'],function (){
            Route::controller(ExpensiveAmountController::class)->group(function () {

                Route::get('/', 'view')->name('admin.expenses.amount.view');
                Route::get('/delete/{id}', 'delete')->name('admin.expenses.amount.delete');

                Route::post('/edit', 'edit')->name('admin.expenses.amount.edit');
                Route::post('/store', 'store')->name('admin.expenses.amount.store');

                Route::post('/search-data', 'Search')->name('admin.expenses.amount.search');

            });
        });

        Route::group(['prefix'=>'credentials'],function (){
            Route::controller(CredentialController::class)->group(function () {

                Route::get('/', 'view')->name('admin.credentials.view');
                Route::get('/delete/{id}', 'delete')->name('admin.credentials.delete');

                Route::post('/edit', 'edit')->name('admin.credentials.edit');
                Route::post('/store', 'store')->name('admin.credentials.store');

                Route::post('/credentials-bulk-upload', 'bulk_upload')->name('admin.credentials.bulk.upload');
                Route::post('/admin/credentials/bulk-delete','credentialsBulkDelete')->name('credentials.bulk.delete');


            });
        });

        Route::group(['prefix'=>'event-details'],function (){
            Route::controller(EventDetailController::class)->group(function () {
                Route::get('/view', 'view')->name('admin.event.details.view');
                Route::get('/delete/{id}', 'delete')->name('admin.event.details.delete');

                Route::post('/edit', 'edit')->name('admin.event.details.edit');
                Route::post('/store', 'store')->name('admin.event.details.store');
            });
        });

        Route::group(['prefix'=>'my-bills'],function (){
            Route::controller(MyBillController::class)->group(function () {
                Route::get('/view', 'view')->name('admin.my.bills.view');
                Route::get('/delete/{id}', 'delete')->name('admin.my.bills.delete');

                Route::post('/edit', 'edit')->name('admin.my.bills.edit');
                Route::post('/store', 'store')->name('admin.my.bills.store');
            });
        });

        Route::group(['prefix'=>'campaign-details'],function (){
            Route::controller(CampaignController::class)->group(function () {
                Route::get('/', 'view')->name('admin.campaign.details.view');
                Route::get('/delete/{id}', 'delete')->name('admin.campaign.details.delete');
                Route::get('/add', 'add')->name('admin.campaign.details.add');
                
                Route::post('/store', 'store')->name('admin.campaign.details.store');
            });
        });

        Route::group(['prefix'=>'follow-up'],function (){
            Route::controller(FollowUpController::class)->group(function () {
                Route::get('/view', 'view')->name('admin.follow.up.view');
                Route::get('/delete/{id}', 'delete')->name('admin.follow.up.delete');
                Route::get('/project/{id}', 'project')->name('admin.follow.up.project');
                Route::get('/export', 'export')->name('admin.follow.up.export');

                Route::post('/edit', 'edit')->name('admin.follow.up.edit');
                Route::post('/store', 'store')->name('admin.follow.up.store');
                Route::post('/search', 'search')->name('admin.follow.up.search');
                Route::post('/import-store', 'ImportStore')->name('admin.follow.up.bulk.store');

            });
        });

    });

});