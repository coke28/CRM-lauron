<?php

use App\Http\Controllers\Account\SettingsController;
use App\Http\Controllers\AccountCallHistoryController;
use App\Http\Controllers\AccountListGlobeController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\ApplicationTypeController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\SocialiteLoginController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CampaignUploadController;
use App\Http\Controllers\CCRemarkController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CollectionEffortController;
use App\Http\Controllers\CrmClientController;
use App\Http\Controllers\DevController;
use App\Http\Controllers\Documentation\ReferencesController;
use App\Http\Controllers\FreebieController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\InstallationFeeController;
use App\Http\Controllers\InstallTypeController;
use App\Http\Controllers\LauronAccountController;
use App\Http\Controllers\LauronLeadController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LockupController;
use App\Http\Controllers\Logs\AuditLogsController;
use App\Http\Controllers\Logs\SystemLogsController;
use App\Http\Controllers\ModemFeeController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\PhoneBrandController;
use App\Http\Controllers\PhoneController;
use App\Http\Controllers\PlaceOfContactController;
use App\Http\Controllers\PlanBreakdownController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PlanFeeController;
use App\Http\Controllers\PointOfContactController;
use App\Http\Controllers\ProductNameController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\PromoNameController;
use App\Http\Controllers\ReasonForDenialController;
use App\Http\Controllers\SegmentController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\TechnologyController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UpfrontFeeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CallController;
use App\Models\AccountCallHistory;
use App\Models\PlanBreakdown;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Administrator;
use App\Models\LauronAccount;
use App\Models\PlaceOfContact;
use App\Models\Transaction;

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

Route::get('/', function () {
    return redirect('index');
})->name('home');


$menu = theme()->getMenu();
array_walk($menu, function ($val) {
    if (isset($val['path'])) {
        $route = Route::get($val['path'], [PagesController::class, 'index']);

        // Exclude documentation from auth middleware
        if (!Str::contains($val['path'], 'documentation')) {
            $route->middleware('auth');
        }
    }
});

// Documentations pages
Route::prefix('documentation')->group(function () {
    Route::get('getting-started/references', [ReferencesController::class, 'index']);
    Route::get('getting-started/changelog', [PagesController::class, 'index']);
});
//Admin tools view routes

// Route::get('dev', [DevController::class, 'test'])->name('admintools.category');
Route::middleware('auth')->group(function () {

    //Admin Routes
    Route::group(["middleware" => "roleChecker:admin"], function () {
        Route::prefix('admintools')->group(function () {
            Route::get('category', [PagesController::class, 'manageCategory'])->name('admintools.category');
            Route::get('product', [PagesController::class, 'manageProduct'])->name('admintools.product');
            Route::get('phoneBrand', [PagesController::class, 'managePhoneBrand'])->name('admintools.phoneBrand');
            Route::get('phone', [PagesController::class, 'managePhone'])->name('admintools.phone');
            Route::get('user', [PagesController::class, 'manageUser'])->name('admintools.user');
            Route::get('group', [PagesController::class, 'manageGroup'])->name('admintools.group');
            Route::get('status', [PagesController::class, 'manageStatus'])->name('admintools.status');
            Route::get('campaign', [PagesController::class, 'manageCampaign'])->name('admintools.campaign');
            Route::get('productName', [PagesController::class, 'manageProductName'])->name('admintools.productName');
            Route::get('plan', [PagesController::class, 'managePlan'])->name('admintools.plan');
            Route::get('planBreakdown', [PagesController::class, 'managePlanBreakdown'])->name('admintools.planBreakdown');
            Route::get('planFee', [PagesController::class, 'managePlanFee'])->name('admintools.planFee');
            Route::get('promoName', [PagesController::class, 'managePromoName'])->name('admintools.promoName');
            Route::get('installationFee', [PagesController::class, 'manageInstallationFee'])->name('admintools.installationFee');
            Route::get('modemFee', [PagesController::class, 'manageModemFee'])->name('admintools.modemFee');
            Route::get('technology', [PagesController::class, 'manageTechnology'])->name('admintools.technology');
            Route::get('installType', [PagesController::class, 'manageInstallType'])->name('admintools.installType');
            Route::get('upfrontFee', [PagesController::class, 'manageUpfrontFee'])->name('admintools.upfrontFee');
            Route::get('lockup', [PagesController::class, 'manageLockup'])->name('admintools.lockup');
            Route::get('applicationType', [PagesController::class, 'manageApplicationType'])->name('admintools.applicationType');
            Route::get('CCRemark', [PagesController::class, 'manageCCRemark'])->name('admintools.CCRemark');
            Route::get('freebie', [PagesController::class, 'manageFreebie'])->name('admintools.freebie');
            Route::get('paymentMethod', [PagesController::class, 'managePaymentMethod'])->name('admintools.paymentMethod');
            Route::get('lead', [PagesController::class, 'manageLead'])->name('admintools.lead');
            Route::get('client', [PagesController::class, 'manageClient'])->name('admintools.client');
            Route::get('segment', [PagesController::class, 'manageSegment'])->name('admintools.segment');
            Route::get('collectionEffort', [PagesController::class, 'manageCollectionEffort'])->name('admintools.collectionEffort');
            Route::get('transaction', [PagesController::class, 'manageTransaction'])->name('admintools.transaction');
            Route::get('placeOfContact', [PagesController::class, 'managePlaceOfContact'])->name('admintools.placeOfContact');
            Route::get('pointOfContact', [PagesController::class, 'managePointOfContact'])->name('admintools.pointOfContact');
            Route::get('reasonForDenial', [PagesController::class, 'manageReasonForDenial'])->name('admintools.reasonForDenial');
            Route::get('area', [PagesController::class, 'manageArea'])->name('admintools.area');
       
        });

        //Manage Category Routes
        Route::post('categoryDataTable', [CategoryController::class, 'listCategory'])->name('category.list');
        Route::post('categoryAdd', [CategoryController::class, 'addCategory'])->name('category.add');
        Route::post('categoryEdit', [CategoryController::class, 'editCategory'])->name('category.edit');
        Route::post('categoryGetEdit', [CategoryController::class, 'getEditCategory'])->name('category.getEdit');
        Route::post('categoryDelete', [CategoryController::class, 'deleteCategory'])->name('category.delete');

        //Manage Segment Routes
        Route::post('segmentDataTable', [SegmentController::class, 'listSegment'])->name('segment.list');
        Route::post('segmentAdd', [SegmentController::class, 'addSegment'])->name('segment.add');
        Route::post('segmentEdit', [SegmentController::class, 'editSegment'])->name('segment.edit');
        Route::post('segmentGetEdit', [SegmentController::class, 'getEditSegment'])->name('segment.getEdit');
        Route::post('segmentDelete', [SegmentController::class, 'deleteSegment'])->name('segment.delete');

        //Manage Collection Effort Routes
        Route::post('collectionEffortDataTable', [CollectionEffortController::class, 'listCollectionEffort'])->name('collectionEffort.list');
        Route::post('collectionEffortAdd', [CollectionEffortController::class, 'addCollectionEffort'])->name('collectionEffort.add');
        Route::post('collectionEffortEdit', [CollectionEffortController::class, 'editCollectionEffort'])->name('collectionEffort.edit');
        Route::post('collectionEffortGetEdit', [CollectionEffortController::class, 'getEditCollectionEffort'])->name('collectionEffort.getEdit');
        Route::post('collectionEffortDelete', [CollectionEffortController::class, 'deleteCollectionEffort'])->name('collectionEffort.delete');

        //Manage Transaction Routes
        Route::post('transactionDataTable', [TransactionController::class, 'listTransaction'])->name('transaction.list');
        Route::post('transactionAdd', [TransactionController::class, 'addTransaction'])->name('transaction.add');
        Route::post('transactionEdit', [TransactionController::class, 'editTransaction'])->name('transaction.edit');
        Route::post('transactionGetEdit', [TransactionController::class, 'getEditTransaction'])->name('transaction.getEdit');
        Route::post('transactionDelete', [TransactionController::class, 'deleteTransaction'])->name('transaction.delete');

        //Manage Place Of Contact Routes
        Route::post('placeOfContactDataTable', [PlaceOfContactController::class, 'listPlaceOfContact'])->name('placeOfContact.list');
        Route::post('placeOfContactAdd', [PlaceOfContactController::class, 'addPlaceOfContact'])->name('placeOfContact.add');
        Route::post('placeOfContactEdit', [PlaceOfContactController::class, 'editPlaceOfContact'])->name('placeOfContact.edit');
        Route::post('placeOfContactGetEdit', [PlaceOfContactController::class, 'getEditPlaceOfContact'])->name('placeOfContact.getEdit');
        Route::post('placeOfContactDelete', [PlaceOfContactController::class, 'deletePlaceOfContact'])->name('placeOfContact.delete');

        //Manage Point Of Contact Routes
        Route::post('pointOfContactDataTable', [PointOfContactController::class, 'listPointOfContact'])->name('pointOfContact.list');
        Route::post('pointOfContactAdd', [PointOfContactController::class, 'addPointOfContact'])->name('pointOfContact.add');
        Route::post('pointOfContactEdit', [PointOfContactController::class, 'editPointOfContact'])->name('pointOfContact.edit');
        Route::post('pointOfContactGetEdit', [PointOfContactController::class, 'getEditPointOfContact'])->name('pointOfContact.getEdit');
        Route::post('pointOfContactDelete', [PointOfContactController::class, 'deletePointOfContact'])->name('pointOfContact.delete');

        //Manage Reason for denial Routes
        Route::post('reasonForDenialDataTable', [ReasonForDenialController::class, 'listReasonForDenial'])->name('reasonForDenial.list');
        Route::post('reasonForDenialAdd', [ReasonForDenialController::class, 'addReasonForDenial'])->name('reasonForDenial.add');
        Route::post('reasonForDenialEdit', [ReasonForDenialController::class, 'editReasonForDenial'])->name('reasonForDenial.edit');
        Route::post('reasonForDenialGetEdit', [ReasonForDenialController::class, 'getEditReasonForDenial'])->name('reasonForDenial.getEdit');
        Route::post('reasonForDenialDelete', [ReasonForDenialController::class, 'deleteReasonForDenial'])->name('reasonForDenial.delete');

        //Manage User Routes
        Route::post('userDataTable', [UserController::class, 'listUsers'])->name('user.list');
        Route::post('userAdd', [UserController::class, 'addUser'])->name('user.add');
        Route::post('userEdit', [UserController::class, 'editUser'])->name('user.edit');
        Route::post('userGetEdit', [UserController::class, 'getEditUser'])->name('user.getEdit');
        Route::post('userDelete', [UserController::class, 'deleteUser'])->name('user.delete');

        //Manage Product Routes
        Route::post('productDataTable', [ProductsController::class, 'listProducts'])->name('products.list');
        Route::post('productsAdd', [ProductsController::class, 'addProducts'])->name('products.add');
        Route::post('productsEdit', [ProductsController::class, 'editProducts'])->name('products.edit');
        Route::post('productsGetEdit', [ProductsController::class, 'getEditProducts'])->name('products.getProducts');
        Route::post('productsDelete', [ProductsController::class, 'deleteProducts'])->name('products.delete');
        // Route::get('test', [DevController::class, 'test'])->name('test.test');

        //Manage Phone Brands Routes
        Route::post('phoneBrandDataTable', [PhoneBrandController::class, 'listPhoneBrand'])->name('phoneBrand.list');
        Route::post('phoneBrandAdd', [PhoneBrandController::class, 'addPhoneBrand'])->name('phoneBrand.add');
        Route::post('phoneBrandEdit', [PhoneBrandController::class, 'editPhoneBrand'])->name('phoneBrand.edit');
        Route::post('phoneBrandGetEdit', [PhoneBrandController::class, 'getEditPhoneBrand'])->name('phoneBrand.getPhoneBrand');
        Route::post('phoneBrandDelete', [PhoneBrandController::class, 'deletePhoneBrand'])->name('phoneBrand.delete');

        //Manage Phone Routes
        Route::post('phoneDataTable', [PhoneController::class, 'listPhone'])->name('phone.list');
        Route::post('phoneAdd', [PhoneController::class, 'addPhone'])->name('phone.add');
        Route::post('phoneEdit', [PhoneController::class, 'editPhone'])->name('phone.edit');
        Route::post('phoneGetEdit', [PhoneController::class, 'getEditPhone'])->name('phone.getPhone');
        Route::post('phoneDelete', [PhoneController::class, 'deletePhone'])->name('phone.delete');

        //Manage CRM Clients Routes
        Route::post('crmClientDataTable', [CrmClientController::class, 'listCrmClient'])->name('crmClient.list');
        Route::post('crmClientAdd', [CrmClientController::class, 'addCrmClient'])->name('crmClient.add');
        Route::post('crmClientEdit', [CrmClientController::class, 'editCrmClient'])->name('crmClient.edit');
        Route::post('crmClientGetEdit', [CrmClientController::class, 'getEditCrmClient'])->name('crmClient.getCrmClient');
        Route::post('crmClientDelete', [CrmClientController::class, 'deleteCrmClient'])->name('crmClient.delete');


        //Manage Groups Routes
        Route::post('groupDataTable', [GroupController::class, 'listGroup'])->name('group.list');
        Route::post('groupAdd', [GroupController::class, 'addGroup'])->name('group.add');
        Route::post('groupEdit', [GroupController::class, 'editGroup'])->name('group.edit');
        Route::post('groupGetEdit', [GroupController::class, 'getEditGroup'])->name('group.getGroup');
        Route::post('groupDelete', [GroupController::class, 'deleteGroup'])->name('group.delete');

        //Manage Status Routes
        Route::post('statusDataTable', [StatusController::class, 'listStatus'])->name('status.list');
        Route::post('statusAdd', [StatusController::class, 'addStatus'])->name('status.add');
        Route::post('statusEdit', [StatusController::class, 'editStatus'])->name('status.edit');
        Route::post('statusGetEdit', [StatusController::class, 'getEditStatus'])->name('status.getStatus');
        Route::post('statusDelete', [StatusController::class, 'deleteStatus'])->name('status.delete');

        //Manage Campaign Routes
        Route::post('campaignDataTable', [CampaignController::class, 'listCampaign'])->name('campaign.list');
        Route::post('campaignAdd', [CampaignController::class, 'addCampaign'])->name('campaign.add');
        Route::post('campaignEdit', [CampaignController::class, 'editCampaign'])->name('campaign.edit');
        Route::post('campaignGetEdit', [CampaignController::class, 'getEditCampaign'])->name('campaign.getCampaign');
        Route::post('campaignDelete', [CampaignController::class, 'deleteCampaign'])->name('campaign.delete');

        //Manage Product Name Routes
        Route::post('productNameDataTable', [ProductNameController::class, 'listProductName'])->name('productName.list');
        Route::post('productNameAdd', [ProductNameController::class, 'addProductName'])->name('productName.add');
        Route::post('productNameEdit', [ProductNameController::class, 'editProductName'])->name('productName.edit');
        Route::post('productNameGetEdit', [ProductNameController::class, 'getEditProductName'])->name('productName.getProductName');
        Route::post('productNameDelete', [ProductNameController::class, 'deleteProductName'])->name('productName.delete');

        //Manage Plan Routes
        Route::post('planDataTable', [PlanController::class, 'listPlan'])->name('plan.list');
        Route::post('planAdd', [PlanController::class, 'addPlan'])->name('plan.add');
        Route::post('planEdit', [PlanController::class, 'editPlan'])->name('plan.edit');
        Route::post('planGetEdit', [PlanController::class, 'getEditPlan'])->name('plan.getPlan');
        Route::post('planDelete', [PlanController::class, 'deletePlan'])->name('plan.delete');

        //Manage Plan Breakdown Routes
        Route::post('planBreakdownDataTable', [PlanBreakdownController::class, 'listPlanBreakdown'])->name('planBreakdown.list');
        Route::post('planBreakdownAdd', [PlanBreakdownController::class, 'addPlanBreakdown'])->name('planBreakdown.add');
        Route::post('planBreakdownEdit', [PlanBreakdownController::class, 'editPlanBreakdown'])->name('planBreakdown.edit');
        Route::post('planBreakdownGetEdit', [PlanBreakdownController::class, 'getEditPlanBreakdown'])->name('planBreakdown.getPlanBreakdown');
        Route::post('planBreakdownDelete', [PlanBreakdownController::class, 'deletePlanBreakdown'])->name('planBreakdown.delete');

        //Manage Plan Fee Routes
        Route::post('planFeeDataTable', [PlanFeeController::class, 'listPlanFee'])->name('planFee.list');
        Route::post('planFeeAdd', [PlanFeeController::class, 'addPlanFee'])->name('planFee.add');
        Route::post('planFeeEdit', [PlanFeeController::class, 'editPlanFee'])->name('planFee.edit');
        Route::post('planFeeGetEdit', [PlanFeeController::class, 'getEditPlanFee'])->name('planFee.getPlanFee');
        Route::post('planFeeDelete', [PlanFeeController::class, 'deletePlanFee'])->name('planFee.delete');

        //Manage Installation Fee Routes
        Route::post('installationFeeDataTable', [InstallationFeeController::class, 'listInstallationFee'])->name('installationFee.list');
        Route::post('installationFeeAdd', [InstallationFeeController::class, 'addInstallationFee'])->name('installationFee.add');
        Route::post('installationFeeEdit', [InstallationFeeController::class, 'editInstallationFee'])->name('installationFee.edit');
        Route::post('installationFeeGetEdit', [InstallationFeeController::class, 'getEditInstallationFee'])->name('installationFee.getInstallationFee');
        Route::post('installationFeeDelete', [InstallationFeeController::class, 'deleteInstallationFee'])->name('installationFee.delete');

        //Manage Modem Fee Routes
        Route::post('modemFeeDataTable', [ModemFeeController::class, 'listModemFee'])->name('modemFee.list');
        Route::post('modemFeeAdd', [ModemFeeController::class, 'addModemFee'])->name('modemFee.add');
        Route::post('modemFeeEdit', [ModemFeeController::class, 'editModemFee'])->name('modemFee.edit');
        Route::post('modemFeeGetEdit', [ModemFeeController::class, 'getEditModemFee'])->name('modemFee.getModemFee');
        Route::post('modemFeeDelete', [ModemFeeController::class, 'deleteModemFee'])->name('modemFee.delete');

        //Manage Promo Name Routes
        Route::post('promoNameDataTable', [PromoNameController::class, 'listPromoName'])->name('promoName.list');
        Route::post('promoNameAdd', [PromoNameController::class, 'addPromoName'])->name('promoName.add');
        Route::post('promoNameEdit', [PromoNameController::class, 'editPromoName'])->name('promoName.edit');
        Route::post('promoNameGetEdit', [PromoNameController::class, 'getEditPromoName'])->name('promoName.getPromoName');
        Route::post('promoNameDelete', [PromoNameController::class, 'deletePromoName'])->name('promoName.delete');

        //Manage Technology Routes
        Route::post('technologyDataTable', [TechnologyController::class, 'listTechnology'])->name('technology.list');
        Route::post('technologyAdd', [TechnologyController::class, 'addTechnology'])->name('technology.add');
        Route::post('technologyEdit', [TechnologyController::class, 'editTechnology'])->name('technology.edit');
        Route::post('technologyGetEdit', [TechnologyController::class, 'getEditTechnology'])->name('technology.getTechnology');
        Route::post('technologyDelete', [TechnologyController::class, 'deleteTechnology'])->name('technology.delete');

        //Manage Install Type Routes
        Route::post('installTypeDataTable', [InstallTypeController::class, 'listInstallType'])->name('installType.list');
        Route::post('installTypeAdd', [InstallTypeController::class, 'addInstallType'])->name('installType.add');
        Route::post('installTypeEdit', [InstallTypeController::class, 'editInstallType'])->name('installType.edit');
        Route::post('installTypeGetEdit', [InstallTypeController::class, 'getEditInstallType'])->name('installType.getInstallType');
        Route::post('installTypeDelete', [InstallTypeController::class, 'deleteInstallType'])->name('installType.delete');

        //Manage Upfront Fee Routes
        Route::post('upfrontFeeDataTable', [UpfrontFeeController::class, 'listUpfrontFee'])->name('upfrontFee.list');
        Route::post('upfrontFeeAdd', [UpfrontFeeController::class, 'addUpfrontFee'])->name('upfrontFee.add');
        Route::post('upfrontFeeEdit', [UpfrontFeeController::class, 'editUpfrontFee'])->name('upfrontFee.edit');
        Route::post('upfrontFeeGetEdit', [UpfrontFeeController::class, 'getEditUpfrontFee'])->name('upfrontFee.getUpfrontFee');
        Route::post('upfrontFeeDelete', [UpfrontFeeController::class, 'deleteUpfrontFee'])->name('upfrontFee.delete');

        //Manage Lockup Fee Routes
        Route::post('lockupDataTable', [LockupController::class, 'listLockup'])->name('lockup.list');
        Route::post('lockupAdd', [LockupController::class, 'addLockup'])->name('lockup.add');
        Route::post('lockupEdit', [LockupController::class, 'editLockup'])->name('lockup.edit');
        Route::post('lockupGetEdit', [LockupController::class, 'getEditLockup'])->name('lockup.getLockup');
        Route::post('lockupDelete', [LockupController::class, 'deleteLockup'])->name('lockup.delete');

        //Manage Application Type Routes
        Route::post('applicationTypeDataTable', [ApplicationTypeController::class, 'listApplicationType'])->name('applicationType.list');
        Route::post('applicationTypeAdd', [ApplicationTypeController::class, 'addApplicationType'])->name('applicationType.add');
        Route::post('applicationTypeEdit', [ApplicationTypeController::class, 'editApplicationType'])->name('applicationType.edit');
        Route::post('applicationTypeGetEdit', [ApplicationTypeController::class, 'getEditApplicationType'])->name('applicationType.getApplicationType');
        Route::post('applicationTypeDelete', [ApplicationTypeController::class, 'deleteApplicationType'])->name('applicationType.delete');

        //Manage CC Remark Routes
        Route::post('ccRemarkDataTable', [CCRemarkController::class, 'listCCRemark'])->name('ccRemark.list');
        Route::post('ccRemarkAdd', [CCRemarkController::class, 'addCCRemark'])->name('ccRemark.add');
        Route::post('ccRemarkEdit', [CCRemarkController::class, 'editCCRemark'])->name('ccRemark.edit');
        Route::post('ccRemarkGetEdit', [CCRemarkController::class, 'getEditCCRemark'])->name('ccRemark.getCCRemark');
        Route::post('ccRemarkDelete', [CCRemarkController::class, 'deleteCCRemark'])->name('ccRemark.delete');

        //Manage Freebie Routes
        Route::post('freebieDataTable', [FreebieController::class, 'listFreebie'])->name('freebie.list');
        Route::post('freebieAdd', [FreebieController::class, 'addFreebie'])->name('freebie.add');
        Route::post('freebieEdit', [FreebieController::class, 'editFreebie'])->name('freebie.edit');
        Route::post('freebieGetEdit', [FreebieController::class, 'getEditFreebie'])->name('freebie.getFreebie');
        Route::post('freebieDelete', [FreebieController::class, 'deleteFreebie'])->name('freebie.delete');

        //Manage Payment Method Routes
        Route::post('paymentMethodDataTable', [PaymentMethodController::class, 'listPaymentMethod'])->name('paymentMethod.list');
        Route::post('paymentMethodAdd', [PaymentMethodController::class, 'addPaymentMethod'])->name('paymentMethod.add');
        Route::post('paymentMethodEdit', [PaymentMethodController::class, 'editPaymentMethod'])->name('paymentMethod.edit');
        Route::post('paymentMethodGetEdit', [PaymentMethodController::class, 'getEditPaymentMethod'])->name('paymentMethod.getPaymentMethod');
        Route::post('paymentMethodDelete', [PaymentMethodController::class, 'deletePaymentMethod'])->name('paymentMethod.delete');

         //Manage Area Routes
         Route::post('areaDataTable', [AreaController::class, 'listArea'])->name('area.list');
         Route::post('areaAdd', [AreaController::class, 'addArea'])->name('area.add');
         Route::post('areaEdit', [AreaController::class, 'editArea'])->name('area.edit');
         Route::post('areaGetEdit', [AreaController::class, 'getEditArea'])->name('area.getArea');
         Route::post('areaDelete', [AreaController::class, 'deleteArea'])->name('area.delete');

        //Audit Routes
        Route::post('auditLogDataTable', [AuditLogController::class, 'listAuditLog'])->name('auditLog.list');
        Route::post('auditLogDelete', [AuditLogController::class, 'deleteAuditLog'])->name('auditLog.delete');

        //Campaign Upload Routes
        Route::post('upload', [CampaignUploadController::class, 'mainFunction'])->name('campaignUpload.main');
        Route::post('campaignUploadDataTable', [CampaignUploadController::class, 'listCampaignUpload'])->name('campaignUpload.list');
        Route::post('campaignUploadDelete', [CampaignUploadController::class, 'deleteCampaignUpload'])->name('campaignUpload.delete');

        //Verify Account routes
        Route::get('verify', [PagesController::class, 'manageVerifyAccount'])->name('verify.account');
        Route::post('verifyDataTable', [LauronAccountController::class, 'listVerifyAccountLauron'])->name('verify.verifyAccountLauron');


        //Lists Page Routes
        Route::prefix('list')->group(function () {

            Route::get('lead', [PagesController::class, 'manageListLead'])->name('list.lead');
         
            Route::get('editLeadStatus', [PagesController::class, 'editLauronLead'])->name('edit.editLeadStatus');
            Route::post('accountCallHistoryDataTable', [AccountCallHistoryController::class, 'listAccountCallHistory'])->name('AccountCallHistory.list');

            Route::get('account', [PagesController::class, 'manageListAccount'])->name('list.account');
            Route::post('accountListLauronDataTable', [LauronAccountController::class, 'listAccountListLauron'])->name('list.accountListLauron');

            // Route::post('exitEditAccount', [AccountListGlobeController::class, 'exitAccount'])->name('exit.editAccount');

            Route::get('campaign', [PagesController::class, 'manageListCampaign'])->name('list.campaignList');
            Route::post('campaignDataTable', [LauronLeadController::class, 'listCampaign'])->name('campaignList.list');
            Route::post('campaignListDelete', [LauronLeadController::class, 'deleteCampaignList'])->name('delete.campaignList');
            Route::post('campaignListDashboard', [LauronLeadController::class, 'listCampaignDashboard'])->name('dashboard.campaignList');

            Route::get('admin', [PagesController::class, 'adminReport'])->name('report.admin');
            Route::post('generateReport', [AdminReportController::class, 'generateReport'])->name('report.generate');
            Route::get('export', [AdminReportController::class, 'export']);
        });

        //Misc Page Routes
        Route::prefix('misc')->group(function () {
            Route::get('uploadCSV', [PagesController::class, 'uploadCSV'])->name('misc.upload');
            Route::get('auditLog', [PagesController::class, 'showAuditLog'])->name('misc.auditLog');
            Route::get('campaignUpload', [PagesController::class, 'showCampaignUpload'])->name('misc.campaignUpload');
        });
    });

    //routes both used by Agent and Admin
    Route::post('/vl-control', [CallController::class, 'initVL'])->name('voicelink.control');
    //list routes
    Route::prefix('list')->group(function () {
        Route::post('editLeadStatus', [LauronLeadController::class, 'editLauronLeadStatus']);
        Route::post('leadDataTable', [LauronLeadController::class, 'listLauronLead']);
        Route::post('accountCallHistoryDataTable', [AccountCallHistoryController::class, 'listAccountCallHistory']);

        Route::get('editAccount', [PagesController::class, 'editLauronAccount'])->name('edit.account');
        // Route::post('editAccount', [AccountListGlobeController::class, 'editAccount'])->name('list.editAccount');
        Route::post('editAccount', [LauronAccountController::class, 'editLauronAccount'])->name('list.editAccount');
        Route::post('exitEditAccount', [LauronAccountController::class, 'exitAccount'])->name('exit.editAccount');
        Route::post('campaignListDashboard', [LauronLeadController::class, 'listCampaignDashboard'])->name('dashboard.campaignList');
    });

    //chat routes
    Route::prefix('chat')->group(function () {
        Route::post('/get-chatlist', [ChatController::class, 'getChatList'])->name('get.chatlist');
        Route::post('/get-chatmessage', [ChatController::class, 'getChatMessageData'])->name('get.chatmessage');
        Route::post('/send-chatmessage', [ChatController::class, 'sendChatMessage'])->name('send.chatmessage');
 
    });

    //Agent routes
    Route::group(["middleware" => "roleChecker:agent"], function () {
        Route::prefix('agent')->group(function () {
            Route::get('lead', [PagesController::class, 'manageAgentLead'])->name('agent.lead');
            Route::get('manualCall', [PagesController::class, 'manageManualCall'])->name('agent.manualCall');
            Route::get('callBack', [PagesController::class, 'manageCallBack'])->name('agent.callBack');
            Route::get('hotLead', [PagesController::class, 'manageHotLead'])->name('agent.hotLead');
            Route::get('ptpAndPaid', [PagesController::class, 'managePtpAndPaid'])->name('agent.ptpAndPaid');
            Route::post('leadAgent', [LauronAccountController::class, 'listLeadAgent'])->name('agent.accountGlobe');
            Route::get('agentCallHistory', [PagesController::class, 'manageCallHistory'])->name('agent.callHistory');
            Route::post('agentCallHistoryDataTable', [LauronAccountController::class, 'listAgentCallHistory'])->name('agentCallHistory.list');

            Route::post('manualCall', [LauronLeadController::class, 'listManualCall'])->name('manualCall.list');
            Route::get('editLeadStatusZ', [PagesController::class, 'editLauronLeadStatusAgent'])->name('edit.editLeadStatusAgent');
            Route::post('callBack', [LauronAccountController::class, 'listCallBack'])->name('callBack.list');
            //Lists Page Routes
            Route::post('ptpAndPaid', [LauronAccountController::class, 'listPtpAndPaid'])->name('ptpAndPaid.list');
            Route::post('hotLead', [AccountListGlobeController::class, 'listHotLead'])->name('hotlead.list');
            // Route::get('hotLeadDetails', [PagesController::class, 'viewHotLead'])->name('hotlead.details');
            Route::post('hotLeadExport', [AccountListGlobeController::class, 'hotLeadExport'])->name('hotlead.export');
           
        });
    });

    // Account pages
    Route::prefix('account')->group(function () {
        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::put('settings/email', [SettingsController::class, 'changeEmail'])->name('settings.changeEmail');
        Route::put('settings/password', [SettingsController::class, 'changePassword'])->name('settings.changePassword');
    });



    // Logs pages
    Route::prefix('log')->name('log.')->group(function () {
        Route::resource('system', SystemLogsController::class)->only(['index', 'destroy']);
        Route::resource('audit', AuditLogsController::class)->only(['index', 'destroy']);
    });
});


/**
 * Socialite login using Google service
 * https://laravel.com/docs/8.x/socialite
 */
Route::get('/auth/redirect/{provider}', [SocialiteLoginController::class, 'redirect']);

require __DIR__ . '/auth.php';
