<?php

namespace App\Http\Controllers;

use App\Models\AccountListGlobe;
use App\Models\Area;
use App\Models\CampaignUpload;
use App\Models\User;
use App\Models\Category;
use App\Models\CollectionEffort;
use App\Models\CrmClient;
use App\Models\Group;
use App\Models\LauronAccount;
use App\Models\LauronLead;
use App\Models\Lead;
use App\Models\PaymentMethod;
use App\Models\Phone;
use App\Models\PhoneBrand;
use App\Models\PlaceOfContact;
use App\Models\PointOfContact;
use App\Models\PromoName;
use App\Models\ReasonForDenial;
use App\Models\Segment;
use App\Models\Status;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Redirect;

class PagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        // Get view file location from menu config
        $view = theme()->getOption('page', 'view');

        // Check if the page view file exist
        if (view()->exists('pages.' . $view)) {
            // return view('pages.'.$view);

            $campaignUploads = CampaignUpload::where('deleted', '0')->get();
            $campaignUploadsCount = $campaignUploads->count();
            $usersOnCall = User::where('onCall', '1')->where('online', '1')->where('deleted', '0')->get();
            $usersOnCallCount = $usersOnCall->count();
            $usersOnline = User::where('online', '1')->where('deleted', '0')->get();
            $usersOnlineCount = $usersOnline->count();
            return view('pages.' . $view, [
                'campaignUploads' => $campaignUploads,
                'campaignUploadsCount' => $campaignUploadsCount,
                'usersOnlineCount' => $usersOnlineCount,
                'usersOnCallCount' =>  $usersOnCallCount

            ]);
        }

        // Get the default inner page
        return view('inner');
    }

    public function manageCategory()
    {
        return view('admintools.manageCategory.view');
    }
    public function manageProduct()
    {
        $category = Category::where('deleted', '0')->where('status', '1')->get();

        // dd($category);

        return view('admintools.manageProduct.view', [
            'categories' => $category
        ]);
        // return view('admintools.manageProduct.view');
    }
    public function managePhoneBrand()
    {
        return view('admintools.managePhoneBrand.view');
    }
    public function managePhone()
    {
        $phoneBrands = PhoneBrand::where('deleted', '0')->where('status', '1')->get();

        // dd($phoneBrands);

        return view('admintools.managePhone.view', [
            'phoneBrands' => $phoneBrands
        ]);
    }
    public function manageUser()
    {
        $groups = Group::where('deleted', '0')->where('status', '1')->get();
        $clients = CrmClient::where('deleted', '0')->where('status', '1')->get();
        return view('admintools.manageUsers.view', [

            'groups' => $groups,
            'clients' => $clients
        ]);
    }
    public function manageGroup()
    {
        $clients = CrmClient::where('deleted', '0')->where('status', '1')->get();

        // dd($phoneBrands);

        return view('admintools.manageGroups.view', [
            'clients' => $clients
        ]);
    }
    public function manageStatus()
    {

        $clients = CrmClient::where('deleted', '0')->where('status', '1')->get();

        // dd($phoneBrands);

        return view('admintools.manageStatus.view', [
            'clients' => $clients
        ]);
    }
    public function manageCampaign()
    {
        $clients = CrmClient::where('deleted', '0')->where('status', '1')->get();

        // dd($phoneBrands);

        return view('admintools.manageCampaign.view', [
            'clients' => $clients
        ]);
    }
    public function manageProductName()
    {
        $clients = CrmClient::where('deleted', '0')->where('status', '1')->get();

        // dd($phoneBrands);

        return view('admintools.manageProductName.view', [
            'clients' => $clients
        ]);
    }
    public function managePlan()
    {

        $clients = CrmClient::where('deleted', '0')->where('status', '1')->get();

        // dd($phoneBrands);

        return view('admintools.managePlans.view', [
            'clients' => $clients
        ]);
    }
    public function managePlanBreakdown()
    {
        $clients = CrmClient::where('deleted', '0')->where('status', '1')->get();

        // dd($phoneBrands);

        return view('admintools.managePlanBreakdown.view', [
            'clients' => $clients
        ]);
    }
    public function managePlanFee()
    {
        $clients = CrmClient::where('deleted', '0')->where('status', '1')->get();

        // dd($phoneBrands);

        return view('admintools.managePlanFee.view', [
            'clients' => $clients
        ]);
    }
    public function managePromoName()
    {
        $clients = CrmClient::where('deleted', '0')->where('status', '1')->get();

        // dd($phoneBrands);

        return view('admintools.managePromoName.view', [
            'clients' => $clients
        ]);
    }
    public function manageInstallationFee()
    {
        $clients = CrmClient::where('deleted', '0')->where('status', '1')->get();

        // dd($phoneBrands);

        return view('admintools.manageInstallationFee.view', [
            'clients' => $clients
        ]);
    }
    public function manageModemFee()
    {
        $clients = CrmClient::where('deleted', '0')->where('status', '1')->get();

        // dd($phoneBrands);

        return view('admintools.manageModemFee.view', [
            'clients' => $clients
        ]);
    }
    public function manageTechnology()
    {
        $clients = CrmClient::where('deleted', '0')->where('status', '1')->get();

        // dd($phoneBrands);

        return view('admintools.manageTechnology.view', [
            'clients' => $clients
        ]);
    }
    public function manageInstallType()
    {
        $clients = CrmClient::where('deleted', '0')->where('status', '1')->get();

        // dd($phoneBrands);

        return view('admintools.manageInstallType.view', [
            'clients' => $clients
        ]);
    }
    public function manageUpfrontFee()
    {
        $clients = CrmClient::where('deleted', '0')->where('status', '1')->get();

        // dd($phoneBrands);

        return view('admintools.manageUpfrontFee.view', [
            'clients' => $clients
        ]);
    }
    public function manageLockup()
    {
        $clients = CrmClient::where('deleted', '0')->where('status', '1')->get();

        return view('admintools.manageLockup.view', [
            'clients' => $clients
        ]);
    }
    public function manageApplicationType()
    {
        $clients = CrmClient::where('deleted', '0')->where('status', '1')->get();

        // dd($phoneBrands);

        return view('admintools.manageApplicationType.view', [
            'clients' => $clients
        ]);
    }
    public function manageCCRemark()
    {
        $clients = CrmClient::where('deleted', '0')->where('status', '1')->get();

        // dd($phoneBrands);

        return view('admintools.manageCCRemark.view', [
            'clients' => $clients
        ]);
    }
    public function manageFreebie()
    {
        $clients = CrmClient::where('deleted', '0')->where('status', '1')->get();

        // dd($phoneBrands);

        return view('admintools.manageFreebie.view', [
            'clients' => $clients
        ]);
    }
    public function managePaymentMethod()
    {
        return view('admintools.managePaymentMethod.view');
    }

    public function manageClient()
    {
        return view('admintools.manageCrmClients.view');
    }

    public function manageSegment()
    {
        $clients = CrmClient::where('deleted', '0')->where('status', '1')->get();

        // dd($phoneBrands);

        return view('admintools.manageSegment.view', [
            'clients' => $clients
        ]);

        // return view('admintools.manageSegment.view');
    }

    public function manageCollectionEffort()
    {
        return view('admintools.manageCollectionEffort.view');
    }

    public function manageTransaction()
    {
        return view('admintools.manageTransaction.view');
    }

    public function managePlaceOfContact()
    {
        return view('admintools.managePlaceOfContact.view');
    }

    public function managePointOfContact()
    {
        return view('admintools.managePointOfContact.view');
    }

    public function manageReasonForDenial()
    {
        return view('admintools.manageReasonForDenial.view');
    }

    public function manageArea()
    {
        return view('admintools.manageArea.view');
    }

    public function manageVerifyAccount()
    {
        $campaignUploads = CampaignUpload::where('deleted', '0')->get();
        return view('supervisor.verifyAccounts.view', [
            'campaignUploads' => $campaignUploads,
        ]);
    }

    public function manageListLead()
    {
        $campaignUploads = CampaignUpload::where('deleted', '0')->get();
        return view('reportsAndLists.leadsList.view', [
            'campaignUploads' => $campaignUploads,
        ]);
        // return view('reportsAndLists.leadsList.view');
    }
    public function manageListCampaign()
    {
        return view('reportsAndLists.campaignList.view');
    }


    public function manageListAccount()
    {
        $campaignUploads = CampaignUpload::where('deleted', '0')->get();
        return view('reportsAndLists.accountList.view', [
            'campaignUploads' => $campaignUploads,
        ]);
        // return view('reportsAndLists.accountList.view');
    }
    // public function manageReportAdmin()
    // {
    //     $groups = Group::where('status','1')->where('deleted', '0')->get();
    //     $clients = CrmClient::where('deleted','0')->where('status','1')->get();

    //     return view('reportsAndLists.adminReport.view',[
    //         'groups'=> $groups,
    //         'clients'=> $clients,
    //     ]);
    // }

    // public function editLead($id,Request $request)
    // {

    //     $lead = Lead::where('mobileNumber', $request->mobileNumber)->where('campaignID',$request->campaignID)->where('deleted','0')->get();
    //     dd($lead);
    //     return view('reportsAndLists.leadsList.editLead',[
    //         'id' => $id,
    //         'mobileNumber' => $request->mobileNumber,
    //         'campaignID' => $request->campaignID,
    //         'lead' => $lead
    //     ]);
    // }

    public function editLeadStatus(Request $request)
    {
        try {
            //code...
            $lead = Lead::where('mobileNumber', $request->mobileNumber)->where('campaignID', $request->campaignID)->where('deleted', '0')->first();
            if ($lead->done == 1) {
                return Redirect::to("list/lead")->withErrors(['msg' => 'Lead already has a Globe Account In Use']);
            }
            $level = auth()->user()->level;
            $agent = User::where('agentNum', $lead->agent)->where('deleted', '0')->first();
            // $status = Status::where('status', '1')->where('deleted', '0')->get();
            $status = Status::where('status', '1')->where('client',$lead->product)->where('deleted', '0')->get();
            $phones = Phone::where('status', '1')->where('deleted', '0')->get();
            $paymentMethods = PaymentMethod::where('status', '1')->where('deleted', '0')->get();
            $promoNames = PromoName::where('status', '1')->where('deleted', '0')->get();

            if (empty($agent) || empty($lead)) {
                return Redirect::to("list/lead")->withErrors(['msg' => 'No Agent Assigned To Lead/Lead not found']);
            }
            $currentDatestamp = \Carbon\Carbon::now()->toDateTimeString();
            return view('reportsAndLists.leadsList.editLead', [
                'agent' => $agent,
                'lead' => $lead,
                'status' => $status,
                'phones' => $phones,
                'paymentMethods' => $paymentMethods,
                'promoNames' => $promoNames,
                'level' => $level,
                'currentDatestamp' => $currentDatestamp
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Redirect::to("list/lead")->withErrors(['msg' => 'An Error Occurred']);
        }
    }

    public function editLauronLead(Request $request)
    {
        try {
            //code...
            $lead = LauronLead::where('mobileNumber', $request->mobileNumber)->where('campaignID', $request->campaignID)->where('campaignName',$request->campaignName)->where('id',$request->id)->where('deleted', '0')->first();
            if ($lead->done == 1) {
                return Redirect::to("list/lead")->withErrors(['msg' => 'Lead already has a Account In Use']);
            }
            $level = auth()->user()->level;
            $agent = User::where('agentNum', $lead->agent)->where('deleted', '0')->first();
            if($level == 0){
                $status = Status::where('status', '1')->where('client',$level)->where('deleted', '0')->get();
            }
            if($level == 1 || $level == 2){
                $status = Status::where('status', '1')->where('deleted', '0')->get();
            }
            

            $segments = Segment::where('status', '1')->where('product',$request->campaignName)->where('deleted', '0')->get();
            $collectionEfforts = CollectionEffort::where('status', '1')->where('deleted', '0')->get();
            $transactions = Transaction::where('status', '1')->where('deleted', '0')->get();
            $placeOfContacts = PlaceOfContact::where('status', '1')->where('deleted', '0')->get();
            $pointOfContacts = PointOfContact::where('status', '1')->where('deleted', '0')->get();
            $reasonForDenials = ReasonForDenial::where('status', '1')->where('deleted', '0')->get();
            $areas = Area::where('status', '1')->where('deleted', '0')->get();
            $users = User::where('level','0')->where('deleted','0')->get();
            // dd($lead->segment);

            $selectedSegment = $lead->segment;
            // dd($segments);
            if (empty($agent) || empty($lead)) {
                return Redirect::to("list/lead")->withErrors(['msg' => 'No Agent Assigned To Lead/Lead not found']);
            }
            // $currentDatestamp = \Carbon\Carbon::now()->toDateTimeString();
            $currentDatestamp = \Carbon\Carbon::now('Asia/Singapore')->toDateTimeString();
            return view('reportsAndLists.leadsList.editLead', [
                'agent' => $agent,
                'users' => $users,
                'lead' => $lead,
                'status' => $status,
                'segments' => $segments,
                'collectionEfforts' => $collectionEfforts,
                'transactions' => $transactions,
                'placeOfContacts' => $placeOfContacts,
                'pointOfContacts' => $pointOfContacts,
                'reasonForDenials' => $reasonForDenials,
                'areas' => $areas,
                'selectedSegment' => $selectedSegment,
                'level' => $level,
                'currentDatestamp' => $currentDatestamp,
                'id' => $request->id
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Redirect::to("list/lead")->withErrors(['msg' => 'An Error Occurred']);
        }
    }

    public function editLauronAccount(Request $request)
    {
        try {
            //code...
            $level = auth()->user()->level;
            $account = LauronAccount::where('mobileNumber', $request->mobileNumber)->where('campaignID', $request->campaignID)->where('campaignName',$request->campaignName)->where('leadID',$request->leadID)->where('deleted', '0')->first();
            // $account = LauronAccount::where('mobileNumber', $request->mobileNumber)->where('campaignID', $request->campaignID)->where('deleted', '0')->first();
            // $agent = User::where('agentNum', $account->agentName)->where('deleted', '0')->first();
            // $status = Status::where('status', '1')->where('deleted', '0')->get();
          

            
            $agent = User::where('agentNum', $account->agentNumber)->where('deleted', '0')->first();
            if($level == 0){
                $status = Status::where('status', '1')->where('client',$level)->where('deleted', '0')->get();
            }
            if($level == 1 || $level == 2 ){
                $status = Status::where('status', '1')->where('deleted', '0')->get();
            }
            $segments = Segment::where('status', '1')->where('product',$request->campaignName)->where('deleted', '0')->get();
            $collectionEfforts = CollectionEffort::where('status', '1')->where('deleted', '0')->get();
            $transactions = Transaction::where('status', '1')->where('deleted', '0')->get();
            $placeOfContacts = PlaceOfContact::where('status', '1')->where('deleted', '0')->get();
            $pointOfContacts = PointOfContact::where('status', '1')->where('deleted', '0')->get();
            $reasonForDenials = ReasonForDenial::where('status', '1')->where('deleted', '0')->get();
            $areas = Area::where('status', '1')->where('deleted', '0')->get();
            $users = User::where('level','0')->where('deleted','0')->get();

            // dd($segments);

            $user = User::where('id', auth()->user()->id)->where('deleted', '0')->first();
          
            if (!empty($user)) {
                $user->onCall = "1";
                $user->save();
            
            } else {

                if($level == "0"){
                    if($request->fromVerified == 1){
                        return Redirect::to("agent/ptpAndPaid")->withErrors(['msg' => 'Logged in user not found']);
                    }else{
                        return Redirect::to("agent/callBack")->withErrors(['msg' => 'Logged in user not found']);
                    }

                    
                }else{
                    return Redirect::to("list/account")->withErrors(['msg' => 'Logged in user not found']);
                }
              
            }

            if (empty($agent) || empty($account)) {
                if($level == "0"){
                    if($request->fromVerified == 1){
                        return Redirect::to("agent/ptpAndPaid")->withErrors(['msg' => 'Logged in user not found']);
                    }else{
                        return Redirect::to("agent/callBack")->withErrors(['msg' => 'Logged in user not found']);
                    }
                }else{
                    return Redirect::to("list/account")->withErrors(['msg' => 'Account or Agent not found']);
                }
            }

            $currentDatestamp = \Carbon\Carbon::now('Asia/Singapore')->toDateTimeString();
            // dd($currentDatestamp);
            return view('reportsAndLists.accountList.editAccount', [
                'agent' => $agent,
                'users' => $users,
                'account' => $account,
                'status' => $status,
                'segments' => $segments,
                'collectionEfforts' => $collectionEfforts,
                'transactions' => $transactions,
                'placeOfContacts' => $placeOfContacts,
                'pointOfContacts' => $pointOfContacts,
                'reasonForDenials' => $reasonForDenials,
                'areas' => $areas,
                'level' => $level,
                'currentDatestamp' => $currentDatestamp,
                'leadID' => $request->leadID,
                'fromVerified' =>$request->fromVerified
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            // return view('reportsAndLists.accountList.view');
            // return Redirect::to("list/account")->withErrors(['msg' => 'Something went wrong']);
            // dd($th);
            if($level == "0"){
                return Redirect::to("agent/callBack")->withErrors(['msg' => 'Logged in user not found KOKI']);
            }else{
                return Redirect::to("list/account")->withErrors(['msg' => 'Something went wrong']);
            }
        }
    }

    public function editAccount(Request $request)
    {
        try {
            //code...
            $account = AccountListGlobe::where('mobileContactNumber', $request->mobileContactNumber)->where('campaignID', $request->campaignID)->where('deleted', '0')->first();
            $agent = User::where('agentNum', $account->agentName)->where('deleted', '0')->first();
            // $status = Status::where('status', '1')->where('deleted', '0')->get();
            $level = auth()->user()->level;
            $status = Status::where('status', '1')->where('deleted', '0')->get();
            $phones = Phone::where('status', '1')->where('deleted', '0')->get();
            $paymentMethods = PaymentMethod::where('status', '1')->where('deleted', '0')->get();
            $promoNames = PromoName::where('status', '1')->where('deleted', '0')->get();

            $user = User::where('id', auth()->user()->id)->where('deleted', '0')->first();
            if (!empty($user)) {
                $user->onCall = "1";
                $user->save();
            } else {

                if($level == "0"){
                    return Redirect::to("agent/callBack")->withErrors(['msg' => 'Logged in user not found']);
                }else{
                    return Redirect::to("list/account")->withErrors(['msg' => 'Logged in user not found']);
                }
              
            }

            if (empty($agent) || empty($account)) {
                // return Redirect::to("list/account")->withErrors(['msg' => 'No Agent Assigned To Account/Account not found']);
                if($level == "0"){
                    return Redirect::to("agent/callBack")->withErrors(['msg' => 'Logged in user not found']);
                }else{
                    return Redirect::to("list/account")->withErrors(['msg' => 'Logged in user not found']);
                }
            }

            $currentDatestamp = \Carbon\Carbon::now()->toDateTimeString();
            return view('reportsAndLists.accountList.editAccount', [
                'agent' => $agent,
                'account' => $account,
                'status' => $status,
                'phones' => $phones,
                'paymentMethods' => $paymentMethods,
                'promoNames' => $promoNames,
                'level' => $level,
                'currentDatestamp' => $currentDatestamp
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            // return view('reportsAndLists.accountList.view');
            // return Redirect::to("list/account")->withErrors(['msg' => 'Something went wrong']);
            if($level == "0"){
                return Redirect::to("agent/callBack")->withErrors(['msg' => 'Logged in user not found']);
            }else{
                return Redirect::to("list/account")->withErrors(['msg' => 'Something went wrong']);
            }
        }
    }

    public function adminReport()
    {
        $groups = Group::where('status', '1')->where('deleted', '0')->get();
        $campaignIDs = CampaignUpload::where('deleted', '0')->get();
        $clients = CrmClient::where('deleted', '0')->where('status', '1')->get();

        $year = date_create('today')->format('Y');
        //remove comment next line for test's
        //$year = 2001;

        $dtStart = date_create('2 jan ' . $year)->modify('last Monday');
        $dtEnd = date_create('last monday of Dec ' . $year);

        for ($weeks = []; $dtStart <= $dtEnd; $dtStart->modify('+1 week')) {
            $key = $dtStart->format('W-Y');
            $from = $dtStart->format('Y/m/d');
            $to = (clone $dtStart)->modify('+6 Days')->format('Y/m/d');
            $weeks[$key] = $from . '-' . $to;
        }
        // dd($weeks);

        return view('reportsAndLists.adminReport.view', [
            'weeks' => $weeks,
            'groups' => $groups,
            'clients' => $clients,
            'campaignIDs' => $campaignIDs
        ]);
    }

    public function uploadCSV()
    {
        $current_date_time = \Carbon\Carbon::now()->toDateTimeString();
        // dd($current_date_time);

        $clients = CrmClient::where('deleted', '0')->where('status', '1')->get();
        $groups = Group::where('deleted', '0')->where('status', '1')->get();

        // dd($phoneBrands);

        return view('misc.uploadCSV.view', [
            'clients' => $clients,
            'groups' => $groups,
            'current_date_time' => $current_date_time
        ]);
    }

    public function showCampaignUpload()
    {
        return view('pages.campaignUpload.view');
    }

    public function showAuditLog()
    {
        return view('pages.auditLog.view');
    }

    public function manageAgentLead()
    {
        $campaignUploads = CampaignUpload::where('deleted', '0')->get();
        return view('agent.lead.view', [
            'campaignUploads' => $campaignUploads,
        ]);
    }

    public function manageCallHistory(Request $request)
    {
    
        $campaignID = $request->campaignID;
        $mobileNumber = $request->mobileNumber;
        $campaignName = $request->campaignName;
        $leadID = $request->leadID;

        return view('agent.lead.CallHistory', [
            'campaignID' => $campaignID,
            'mobileNumber' => $mobileNumber,
            'campaignName' => $campaignName,
            'leadID' => $leadID,
        ]);
    }

    public function manageManualCall()
    {
        $campaignUploads = CampaignUpload::where('deleted', '0')->get();
        return view('agent.manualCall.view', [
            'campaignUploads' => $campaignUploads,
        ]);
    }

    public function editLeadStatusAgent(Request $request)
    {
        try {
            //code...
            $lead = Lead::where('mobileNumber', $request->mobileNumber)->where('campaignID', $request->campaignID)->where('deleted', '0')->first();
            if ($lead->done == 1) {
                return Redirect::to("agent/manualCall")->withErrors(['msg' => 'Lead already has a Globe Account In Use']);
            }
            $lead->locked = "1";
            $lead->agent = auth()->user()->agentNum;
            $lead->save();
            $level = auth()->user()->level;
            $agent = User::where('agentNum', $lead->agent)->where('deleted', '0')->first();
            $status = Status::where('status', '1')->where('deleted', '0')->get();
            $phones = Phone::where('status', '1')->where('deleted', '0')->get();
            $paymentMethods = PaymentMethod::where('status', '1')->where('deleted', '0')->get();
            $promoNames = PromoName::where('status', '1')->where('deleted', '0')->get();

            if (empty($agent) || empty($lead)) {
                return Redirect::to("agent/manualCall")->withErrors(['msg' => 'No Agent Assigned To Lead/Lead not found']);
            }
            $currentDatestamp = \Carbon\Carbon::now()->toDateTimeString();
            return view('reportsAndLists.leadsList.editLead', [
                'agent' => $agent,
                'lead' => $lead,
                'status' => $status,
                'phones' => $phones,
                'paymentMethods' => $paymentMethods,
                'promoNames' => $promoNames,
                'level' => $level,
                'currentDatestamp' => $currentDatestamp
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Redirect::to("agent/manualCall")->withErrors(['msg' => 'An Error Occurred']);
        }
    }

    public function editLauronLeadStatusAgent(Request $request)
    {
        try {
            //code...
            $lead = LauronLead::where('mobileNumber', $request->mobileNumber)->where('campaignID', $request->campaignID)->where('campaignName',$request->campaignName)->where('id',$request->id)->where('deleted', '0')->first();
            // $lead = LauronLead::where('mobileNumber', $request->mobileNumber)->where('campaignID', $request->campaignID)->where('deleted', '0')->first();
            if ($lead->done == 1) {
                return Redirect::to("agent/manualCall")->withErrors(['msg' => 'Lead already has an Account In Use']);
            }
            $lead->locked = "1";
            $lead->agent = auth()->user()->agentNum;
            $lead->save();
            $level = auth()->user()->level;
            $agent = User::where('agentNum', $lead->agent)->where('deleted', '0')->first();

            if($level == 0){
                $status = Status::where('status', '1')->where('client',$level)->where('deleted', '0')->get();
            }
            if($level == 1 || $level == 2){
                $status = Status::where('status', '1')->where('deleted', '0')->get();
            }
            $segments = Segment::where('status', '1')->where('product',$request->campaignName)->where('deleted', '0')->get();
            $collectionEfforts = CollectionEffort::where('status', '1')->where('deleted', '0')->get();
            $transactions = Transaction::where('status', '1')->where('deleted', '0')->get();
            $placeOfContacts = PlaceOfContact::where('status', '1')->where('deleted', '0')->get();
            $pointOfContacts = PointOfContact::where('status', '1')->where('deleted', '0')->get();
            $reasonForDenials = ReasonForDenial::where('status', '1')->where('deleted', '0')->get();
            $areas = Area::where('status', '1')->where('deleted', '0')->get();
            $users = User::where('level','0')->where('deleted','0')->get();
            // dd($lead->segment);

            $selectedSegment = $lead->segment;

            if (empty($agent) || empty($lead)) {
                return Redirect::to("agent/manualCall")->withErrors(['msg' => 'No Agent Assigned To Lead/Lead not found']);
            }
            // $currentDatestamp = \Carbon\Carbon::now()->toDateTimeString();
            $currentDatestamp = \Carbon\Carbon::now('Asia/Singapore')->toDateTimeString();
            return view('reportsAndLists.leadsList.editLead', [
                'agent' => $agent,
                'users' => $users,
                'lead' => $lead,
                'status' => $status,
                'segments' => $segments,
                'collectionEfforts' => $collectionEfforts,
                'transactions' => $transactions,
                'placeOfContacts' => $placeOfContacts,
                'pointOfContacts' => $pointOfContacts,
                'reasonForDenials' => $reasonForDenials,
                'areas' => $areas,
                'selectedSegment' => $selectedSegment,
                'level' => $level,
                'currentDatestamp' => $currentDatestamp,
                'id' => $request->id

            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Redirect::to("agent/manualCall")->withErrors(['msg' => 'An Error Occurred']);
        }
    }
    public function manageCallBack()
    {

        $campaignUploads = CampaignUpload::where('deleted', '0')->get();
        return view('agent.callBack.view', [
            'campaignUploads' => $campaignUploads,
        ]);
    }

    public function managePtpAndPaid()
    {

        $campaignUploads = CampaignUpload::where('deleted', '0')->get();
        return view('agent.ptpAndPaid.view', [
            'campaignUploads' => $campaignUploads,
        ]);
    }

    public function manageHotLead()
    {

        $campaignUploads = CampaignUpload::where('deleted', '0')->get();
        return view('agent.hotLead.view', [
            'campaignUploads' => $campaignUploads,
        ]);
    }

    public function viewHotLead(Request $request)
    {
        try {
            //code...
            $account = AccountListGlobe::where('mobileContactNumber', $request->mobileContactNumber)->where('campaignID', $request->campaignID)->where('deleted', '0')->first();
            $agent = User::where('agentNum', $account->agentName)->where('deleted', '0')->first();
            // $status = Status::where('status', '1')->where('deleted', '0')->get();
            $level = auth()->user()->level;
            $status = Status::where('status', '1')->where('client',$account->campaignName)->where('deleted', '0')->get();
            $phones = Phone::where('status', '1')->where('deleted', '0')->get();
            $paymentMethods = PaymentMethod::where('status', '1')->where('deleted', '0')->get();
            $promoNames = PromoName::where('status', '1')->where('deleted', '0')->get();

            $user = User::where('id', auth()->user()->id)->where('deleted', '0')->first();
            if (!empty($user)) {
                // $user->onCall = "1";
                // $user->save();
            } else {

                if($level == "0"){
                    return Redirect::to("agent/leads")->withErrors(['msg' => 'Logged in user not found']);
                }else{
                    return Redirect::to("list/account")->withErrors(['msg' => 'Logged in user not found']);
                }
              
            }

            if (empty($agent) || empty($account)) {
                // return Redirect::to("list/account")->withErrors(['msg' => 'No Agent Assigned To Account/Account not found']);
                if($level == "0"){
                    return Redirect::to("agent/leads")->withErrors(['msg' => 'Logged in user not found']);
                }else{
                    return Redirect::to("list/account")->withErrors(['msg' => 'Logged in user not found']);
                }
            }

            $currentDatestamp = \Carbon\Carbon::now()->toDateTimeString();
            return view('agent.hotLead.details', [
                'agent' => $agent,
                'account' => $account,
                'status' => $status,
                'phones' => $phones,
                'paymentMethods' => $paymentMethods,
                'promoNames' => $promoNames,
                'level' => $level,
                'currentDatestamp' => $currentDatestamp
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            // return view('reportsAndLists.accountList.view');
            // return Redirect::to("list/account")->withErrors(['msg' => 'Something went wrong']);
            if($level == "0"){
                return Redirect::to("agent/hotLeadDetails")->withErrors(['msg' => 'Logged in user not found']);
            }else{
                return Redirect::to("list/account")->withErrors(['msg' => 'Something went wrong']);
            }
        }
    }
}
