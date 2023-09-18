<?php

namespace App\Http\Controllers;

use App\Models\CampaignUpload;
use App\Models\LauronAccount;
use App\Models\LauronLead;
use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Log;
use PHPUnit\Framework\Constraint\IsEmpty;

class LauronLeadController extends Controller
{
    //
    public function listLauronLead(Request $request)
    {

        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: *');

        $tableColumns = array(
            'id',
            // 'product',
            'customerName',
            'mobileNumber',
            'campaignName',
            'campaignID'
        );

        // if ($request->isDashboardTB == 1) {
        //   $tableColumns = array(
        //     'first_name',
        //     'first_name',
        //     'last_name',
        //     'user_level_id',
        //   );
        // }

        // offset and limit
        $offset = 0;
        $limit = 10;
        if (isset($request->length)) {
            $offset = isset($request->start) ? $request->start : $offset;
            $limit = isset($request->length) ? $request->length : $limit;
        }

        // searchText
        $search = '';
        if (isset($request->search) && isset($request->search['value'])) {
            $search = $request->search['value'];
        }

        // ordering
        $sortIndex = 0;
        $sortOrder = 'desc';
        if (isset($request->order) && isset($request->order[0]) && isset($request->order[0]['column'])) {
            $sortIndex = $request->order[0]['column'];
        }
        if (isset($request->order) && isset($request->order[0]) && isset($request->order[0]['dir'])) {
            $sortOrder = $request->order[0]['dir'];
        }

        $lead = LauronLead::where('deleted', '0');
        $lead = $lead->where(function ($query) use ($search) {
            return $query->where('customerName', 'like', '%' . $search . '%')
                ->orWhere('mobileNumber', 'like', '%' . $search . '%')
                ->orWhere('campaignName', 'like', '%' . $search . '%')
                ->orWhere('campaignID', 'like', '%' . $search . '%')
                ->orWhere('id', 'like', '%' . $search . '%');
        })
            ->orderBy($tableColumns[$sortIndex], $sortOrder);
        $leadCount = $lead->count();
        $lead = $lead->offset($offset)
            ->limit($limit)
            ->get();

        // make loop here to encrypt on new column name
        //making new column
        $lead->newcolumn = "";

        $result = [
            'recordsTotal'    => $leadCount,
            'recordsFiltered' => $leadCount,
            'data'            => $lead,
        ];

        // reponse must be in  array
        return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function editLauronLeadStatus(Request $request)
    {
        // check if account globe list has request lead

        //code...

        $lead = LauronLead::where('mobileNumber', $request->existingMobileNumber)->where('campaignID', $request->campaignID)->where('campaignName', $request->campaignName)->where('id',$request->id)->where('deleted', '0')->first();
        if ($lead != null) {
            $lauronAccount = LauronAccount::where('leadID', $lead->id)->where('deleted', '0')->get()->count();
            if ($lauronAccount > 0) {
                return json_encode(array(
                    'success' => false,
                    'message' => 'Lead already has an Account already in use.'
                ));
            }
        }

        $campaignUpload = CampaignUpload::where('campaignID', $request->campaignID)->where('deleted', '0')->first();
        // $campaignUpload = CampaignUpload::where('campaignID', $request->campaignID)->where('campaignName', $request->campaignName)->where('deleted', '0')->first();
        if (empty($campaignUpload)) {
            return json_encode(array(
                'success' => false,
                'message' => 'Campaign Not Found'
            ));
        }
        //Account UPDATE
        $lauronAccount = new LauronAccount();
        $selectedUser = User::where('agentNum',$request->agentName)->first();
        
        // Admin/Verifier Edit
        if (auth()->user()->level > 0) {
        
            $lauronAccount->agentName = $selectedUser->first_name." ".$selectedUser->middle_name." ".$selectedUser->last_name;
            $lauronAccount->agentNumber = $request->agentName;
            $lauronAccount->campaignName = $request->campaignName;
            $lauronAccount->campaignID = $request->campaignID;
            $lauronAccount->leadStatus = $request->leadStatus;

            $status = Status::where('statusName', $request->leadStatus)->first();
            $lauronAccount->leadStatusID = $status->id;

            $lauronAccount->reasonForDenial = $request->reason;
            $lauronAccount->remarkStatus = $request->remarkStatus;
            $lauronAccount->leadID = $lead->id;

            $lauronAccount->segment = $request->segment;
            $lauronAccount->collectionEffort = $request->collectionEffort;
            $lauronAccount->transaction = $request->transaction;
            $lauronAccount->placeOfContact = $request->placeOfContact;
            $lauronAccount->pointOfContact = $request->pointOfContact;

            $lauronAccount->accountNumber = $request->accountNumber;
            $lauronAccount->endoDate = $request->endoDate;
            $lauronAccount->pullOutDate = $request->pullOutDate;
            $lauronAccount->writeOffDate = $request->writeOffDate;
            $lauronAccount->activationDate = $request->activationDate;

            $lauronAccount->homeAddress = $request->homeAddress;
            $lauronAccount->companyName = $request->companyName;
            $lauronAccount->CEAddressBusinessAddress = $request->companyAddress;

            $lauronAccount->firstname = $request->firstName;
            $lauronAccount->middlename = $request->middleName;
            $lauronAccount->lastname = $request->lastName;


            $lauronAccount->dateOfBirth = $request->dateOfBirth;
            $lauronAccount->civilStatus = $request->civilStatus;
            $lauronAccount->emailAddress = $request->email;
            $lauronAccount->mobileNumber  = $request->mobileNumber;
            $lauronAccount->homeNumber = $request->homeNumber;
            $lauronAccount->officeNumber = $request->officeNumber;
            $lauronAccount->otherContact1 = $request->otherContact1;
            $lauronAccount->otherContact2 = $request->otherContact2;
            $lauronAccount->otherContact3 = $request->otherContact3;
            $lauronAccount->otherAddress1 = $request->otherAddress1;
            $lauronAccount->otherAddress2 = $request->otherAddress2;
            $lauronAccount->motherMaidenname = $request->motherMaidenName;

            $lauronAccount->customerName = $request->firstName . " " . $request->middleName . " " . $request->lastName;

            $lauronAccount->originalBalance = $request->originalBalance;
            $lauronAccount->principalBalance = $request->principalBalance;
            $lauronAccount->penalties = $request->penalties;
            $lauronAccount->totalAmountDue = $request->totalAmountDue;
            $lauronAccount->lastPaymentDate = $request->lastPaymentDate;
            $lauronAccount->lastPaymentAmount = $request->lastPaymentAmount;
            $lauronAccount->autoloanCarInfo = $request->autoloanCarInfo;

            $lauronAccount->area = $request->area;
            $lauronAccount->notes = $request->note;

            $lauronAccount->ptpAmount = $request->ptpAmount;
            $lauronAccount->ptpDate = $request->ptpDate;

            
            $lauronAccount->callbackDate = Carbon::parse($request->callbackDate);
            // $lauronAccount->callbackDate = $request->callbackDate;

            $lauronAccount->dl = $lead->dl;
            $lauronAccount->port = $lead->portingNumber;
            $lauronAccount->verifier = "0";
            $lauronAccount->groupz = $lead->groupz;
            $lead->status = "1";
            $lead->done = "1";

            
            // LEAD UPDATE
            // $lead->agentName = $selectedUser->firstname." ".$selectedUser->middlename." ".$selectedUser->lastname;
            $lead->agent = $request->agentName;

            $lead->segment = $request->segment;
            $lead->collectionEffort = $request->collectionEffort;
            $lead->transaction = $request->transaction;
            $lead->placeOfContact = $request->placeOfContact;
            $lead->pointOfContact = $request->pointOfContact;

            $lead->accountNumber = $request->accountNumber;
            $lead->endoDate = $request->endoDate;
            $lead->pullOutDate = $request->pullOutDate;
            $lead->writeOffDate = $request->writeOffDate;
            $lead->activationDate = $request->activationDate;

            $lead->homeAddress = $request->homeAddress;
            $lead->companyName = $request->companyName;
            $lead->CEAddressBusinessAddress = $request->companyAddress;

            $lead->firstname = $request->firstName;
            $lead->middlename = $request->middleName;
            $lead->lastname = $request->lastName;


            $lead->dateOfBirth = $request->dateOfBirth;
            $lead->civilStatus = $request->civilStatus;
            $lead->emailAddress = $request->email;
            $lead->mobileNumber  = $request->mobileNumber;
            $lead->homeNumber = $request->homeNumber;
            $lead->officeNumber = $request->officeNumber;
            $lead->otherContact1 = $request->otherContact1;
            $lead->otherContact2 = $request->otherContact2;
            $lead->otherContact3 = $request->otherContact3;
            $lead->otherAddress1 = $request->otherAddress1;
            $lead->otherAddress2 = $request->otherAddress2;
            $lead->motherMaidenname = $request->motherMaidenName;

            $lead->customerName = $request->firstName . " " . $request->middleName . " " . $request->lastName;

            $lead->originalBalance = $request->originalBalance;
            $lead->principalBalance = $request->principalBalance;
            $lead->penalties = $request->penalties;
            $lead->totalAmountDue = $request->totalAmountDue;
            $lead->lastPaymentDate = $request->lastPaymentDate;
            $lead->lastPaymentAmount = $request->lastPaymentAmount;

            $lead->autoloanCarInfo = $request->autoloanCarInfo;

            $lead->area = $request->area;
            $lead->notes = $request->note;
            $lead->ptpAmount = $request->ptpAmount;
            $lead->ptpDate = $request->ptpDate;

            $lead->callbackDate = $request->callbackDate;
           
        }
        // Agent Edit
        else{

            $lauronAccount->agentName = $request->agentName;
            $lauronAccount->agentNumber = $lead->agent;
            $lauronAccount->campaignName = $request->campaignName;
            $lauronAccount->campaignID = $request->campaignID;
            $lauronAccount->leadStatus = $request->leadStatus;

            $status = Status::where('statusName', $request->leadStatus)->first();
            $lauronAccount->leadStatusID = $status->id;

            $lauronAccount->reasonForDenial = $request->reason;
            $lauronAccount->remarkStatus = $request->remarkStatus;
            $lauronAccount->leadID = $lead->id;

            $lauronAccount->segment = $lead->segment;
            $lauronAccount->collectionEffort = $request->collectionEffort;
            $lauronAccount->transaction = $request->transaction;
            // $lauronAccount->placeOfContact = $lead->placeOfContact;
            // $lauronAccount->pointOfContact = $lead->pointOfContact;
            $lauronAccount->placeOfContact = $request->placeOfContact;
            $lauronAccount->pointOfContact = $request->pointOfContact;
            $lauronAccount->accountNumber = $lead->accountNumber;
            $lauronAccount->endoDate = $lead->endoDate;
            $lauronAccount->pullOutDate = $lead->pullOutDate;
            $lauronAccount->writeOffDate = $lead->writeOffDate;
            $lauronAccount->activationDate = $lead->activationDate;
            $lauronAccount->homeAddress = $lead->homeAddress;
            $lauronAccount->companyName = $lead->companyName;
            $lauronAccount->CEAddressBusinessAddress = $lead->CEAddressBusinessAddress;
            $lauronAccount->firstname = $lead->firstname;
            $lauronAccount->middlename = $lead->middlename;
            $lauronAccount->lastname = $lead->lastname;
            $lauronAccount->dateOfBirth = $lead->dateOfBirth;
            $lauronAccount->civilStatus = $lead->civilStatus;
            $lauronAccount->emailAddress = $lead->emailAddress;
            $lauronAccount->mobileNumber  = $lead->mobileNumber;
            $lauronAccount->homeNumber = $lead->homeNumber;
            $lauronAccount->officeNumber = $lead->officeNumber;

            $lauronAccount->otherContact1 = $request->otherContact1;
            $lauronAccount->otherContact2 = $request->otherContact2;
            $lauronAccount->otherContact3 = $request->otherContact3;

            $lauronAccount->otherAddress1 = $lead->otherAddress1;
            $lauronAccount->otherAddress2 = $lead->otherAddress2;
            $lauronAccount->motherMaidenname = $lead->motherMaidenname;
            $lauronAccount->customerName = $lead->firstname . " " . $lead->middlename . " " . $lead->lastname;
            $lauronAccount->originalBalance = $lead->originalBalance;
            $lauronAccount->principalBalance = $lead->principalBalance;
            $lauronAccount->penalties = $lead->penalties;
            $lauronAccount->totalAmountDue = $lead->totalAmountDue;
            $lauronAccount->lastPaymentDate = $lead->lastPaymentDate;
            $lauronAccount->lastPaymentAmount = $lead->lastPaymentAmount;
            $lauronAccount->autoloanCarInfo = $lead->autoloanCarInfo;
            $lauronAccount->area = $lead->area;

            $lauronAccount->notes = $request->note;
            $lauronAccount->ptpAmount = $request->ptpAmount;
            $lauronAccount->ptpDate = $request->ptpDate;

            $lauronAccount->callbackDate = $request->callbackDate;

            $lauronAccount->dl = $lead->dl;
            $lauronAccount->port = $lead->portingNumber;
            $lauronAccount->verifier = "0";
            $lauronAccount->groupz = $lead->groupz;
            $lead->status = "1";
            $lead->done = "1";

            $lead->placeOfContact = $request->placeOfContact;
            $lead->pointOfContact = $request->pointOfContact;
            $lead->otherContact1 = $request->otherContact1;
            $lead->otherContact2 = $request->otherContact2;
            $lead->otherContact3 = $request->otherContact3;

            $lead->notes = $request->note;
            $lead->ptpAmount = $request->ptpAmount;
            $lead->ptpDate = $request->ptpDate;

            $lead->callbackDate = $request->callbackDate;



        }
        $lauronAccount->save();
        $lead->save();
        $level = auth()->user()->level;

        return json_encode(array(
            'success' => true,
            'message' => 'Lead submitted successfully.',
            'level' => $level
        ));
    }

    public function listCampaign(Request $request)
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: *');

        $tableColumns = array(
            'id',
            'campaignName',
            'campaignID',
            'Total Leads',
        );

        // if ($request->isDashboardTB == 1) {
        //   $tableColumns = array(
        //     'first_name',
        //     'first_name',
        //     'last_name',
        //     'user_level_id',
        //   );
        // }

        // offset and limit
        $offset = 0;
        $limit = 10;
        if (isset($request->length)) {
            $offset = isset($request->start) ? $request->start : $offset;
            $limit = isset($request->length) ? $request->length : $limit;
        }

        // searchText
        $search = '';
        if (isset($request->search) && isset($request->search['value'])) {
            $search = $request->search['value'];
        }

        if (isset($request->statusFilter)) {
            $statusFilter = $request->statusFilter;
        }
        // Log::info($request->statusFilter);





        // ordering
        $sortIndex = 0;
        $sortOrder = 'desc';
        if (isset($request->order) && isset($request->order[0]) && isset($request->order[0]['column'])) {
            $sortIndex = $request->order[0]['column'];
        }
        if (isset($request->order) && isset($request->order[0]) && isset($request->order[0]['dir'])) {
            $sortOrder = $request->order[0]['dir'];
        }

        if ($statusFilter == "2") {
            $lead = DB::table('lauronLeads')
                ->select('campaignName', 'campaignID', 'status')
                ->selectRaw("COUNT(campaignName) AS 'totalLeads'")
                ->selectRaw("SUM(case called when '0' then 1 else 0 end) AS 'notCalled'")
                ->selectRaw("SUM(called) AS 'called'")
                ->where('deleted', '0')
                ->groupBy('campaignName', 'campaignID', 'status')
                ->orderBy($tableColumns[$sortIndex], $sortOrder);
        } else {
            $lead = DB::table('lauronLeads')
                ->select('campaignName', 'campaignID', 'status')
                ->selectRaw("COUNT(campaignName) AS 'totalLeads'")
                ->selectRaw("SUM(case called when '0' then 1 else 0 end) AS 'notCalled'")
                ->selectRaw("SUM(called) AS 'called'")
                ->where('deleted', '0')
                ->groupBy('campaignName', 'campaignID', 'status');

            // $lead = $lead->where(function ($query) use ($search) {
            //     return $query->where('status', 'like', '%' . $search . '%');
            // })
            // $lead = $lead->where(function ($query) use ($search) {
            //     return $query->where('id', 'like', '%' . $search . '%')
            //         ->orWhere('campaignName', 'like', '%' . $search . '%')
            //         ->orWhere('campaignID', 'like', '%' . $search . '%');
            //         // ->orWhere('status', 'like', '%' . $search . '%');
            // })

            $lead = $lead->where('status', $statusFilter)
                ->orderBy($tableColumns[$sortIndex], $sortOrder);
        }

        $leadCount = $lead->count();
        $lead = $lead->offset($offset)
            ->limit($limit)
            ->get();

        foreach ($lead as $p) {

            switch ($p->status) {
                case 0:
                    // code block
                    $p->status = "Inactive";
                    break;
                case 1:
                    // code block
                    $p->status = "Active";
                    break;
                default:
                    // code block
            }
        }

        $result = [
            'recordsTotal'    => $leadCount,
            'recordsFiltered' => $leadCount,
            'data'            => $lead,
        ];

        // reponse must be in  array
        return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function deleteCampaignList(Request $request)
    {


        $deleteLeads = LauronLead::where('campaignName', $request->campaignName)->where('campaignID', $request->campaignID)->get();
        // $deleteCampaignUpload = CampaignUpload::where('campaignName', $request->campaignName)->where('campaignID', $request->campaignID)->where('deleted', '0')->first();
        $deleteCampaignUpload = CampaignUpload::where('campaignID', $request->campaignID)->where('deleted', '0')->first();


        if (!empty($deleteLeads) & !empty($deleteCampaignUpload)) {


            foreach ($deleteLeads as $deleteLead) {

                $deleteLead->deleted = 1;
                $deleteLead->save();
            }

            $deleteCampaignUpload->deleted = 1;
            $deleteCampaignUpload->save();


            // $auditLog = new AuditLog();
            // $auditLog->agent = auth()->user()->id;
            // $auditLog->action = "Deleted ID #" . " $deleteCCRemark->id " . "CC Remark";
            // $auditLog->table = "ccRemark";
            // $auditLog->nID = "Deleted =" . $deleteCCRemark->deleted;
            // $auditLog->ip = \Request::ip();
            // $auditLog->save();

            return 'Campaign deleted successfully.';
        } else {

            return 'Campaign deleted unsuccessfully.';
        }
    }

    public function listManualCall(Request $request)
    {

        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: *');

        $tableColumns = array(
            'segment',
            'campaignName',
            'customerName',
            'mobileNumber',
            'originalBalance',
            'agent',
            'locked'
        );

        // offset and limit
        $offset = 0;
        $limit = 10;
        if (isset($request->length)) {
            $offset = isset($request->start) ? $request->start : $offset;
            $limit = isset($request->length) ? $request->length : $limit;
        }

        // searchText
        $search = '';
        if (isset($request->search) && isset($request->search['value'])) {
            $search = $request->search['value'];
        }

        // ordering
        $sortIndex = 0;
        $sortOrder = 'desc';
        if (isset($request->order) && isset($request->order[0]) && isset($request->order[0]['column'])) {
            $sortIndex = $request->order[0]['column'];
        }
        if (isset($request->order) && isset($request->order[0]) && isset($request->order[0]['dir'])) {
            $sortOrder = $request->order[0]['dir'];
        }

        $manualCall = LauronLead::where('done', '0')->where('agent', auth()->user()->agentNum)->where('deleted', '0');
        $manualCall = $manualCall->where(function ($query) use ($search) {
            return $query->where('segment', 'like', '%' . $search . '%')
                ->orWhere('campaignName', 'like', '%' . $search . '%')
                ->orWhere('customerName', 'like', '%' . $search . '%')
                ->orWhere('mobileNumber', 'like', '%' . $search . '%')
                ->orWhere('originalBalance', 'like', '%' . $search . '%')
                ->orWhere('agent', 'like', '%' . $search . '%');
        })
            ->orderBy($tableColumns[$sortIndex], $sortOrder);
        $manualCallCount = $manualCall->count();
        $manualCall = $manualCall->offset($offset)
            ->limit($limit)
            ->get();

        foreach ($manualCall as $p) {

            switch ($p->locked) {
                case 0:
                    // code block
                    $p->locked = "NO";
                    break;
                case 1:
                    // code block
                    $p->locked = "YES";

                    break;
                default:
            }
        }

        $result = [
            'recordsTotal'    => $manualCallCount,
            'recordsFiltered' => $manualCallCount,
            'data'            => $manualCall,
        ];

        // reponse must be in  array
        return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function listCampaignDashboard(Request $request)
    {
        $campaign = DB::table('lauronLeads')
            ->select('campaignName', 'campaignID', 'status')
            ->selectRaw("COUNT(campaignName) AS 'totalLeads'")
            ->selectRaw("SUM(case called when '0' then 1 else null end) AS 'notCalled'")
            ->selectRaw("SUM(called) AS 'called'")
            ->where('campaignID', $request->campaignID)
            ->where('status', '1')
            ->where('deleted', '0')
            ->groupBy('campaignName', 'campaignID', 'status')->first();
        return json_encode($campaign);
    }
}
