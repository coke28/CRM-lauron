<?php

namespace App\Http\Controllers;

use App\Models\AccountCallHistory;
use App\Models\AccountHistory;
use App\Models\CampaignUpload;
use App\Models\LauronAccount;
use App\Models\LauronLead;
use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Log;

class LauronAccountController extends Controller
{
    //
    public function listAccountListLauron(Request $request)
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: *');

        $tableColumns = array(
            'id',
            'campaignName',
            'agentName',
            'customerName',
            'mobileNumber',
            'campaignID',
            'leadStatus'
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


        $accountListLauron = DB::table('lauronAccounts')
        ->join('lauronLeads', 'lauronLeads.id', '=', 'lauronAccounts.leadID')
        ->select('lauronAccounts.*', 'lauronLeads.deleted')
        ->where('lauronLeads.deleted', '0')
        ->where('lauronAccounts.deleted', '0');

        // $accountListLauron = LauronAccount::where('deleted', '0');
        $accountListLauron = $accountListLauron->where(function ($query) use ($search) {
            return $query->where('lauronAccounts.campaignName', 'like', '%' . $search . '%')
                ->orWhere('lauronAccounts.agentName', 'like', '%' . $search . '%')
                ->orWhere('lauronAccounts.customerName', 'like', '%' . $search . '%')
                ->orWhere('lauronAccounts.mobileNumber', 'like', '%' . $search . '%')
                ->orWhere('lauronAccounts.campaignID', 'like', '%' . $search . '%')
                ->orWhere('lauronAccounts.leadStatus', 'like', '%' . $search . '%')
                ->orWhere('lauronAccounts.id', 'like', '%' . $search . '%');
        })
            ->orderBy($tableColumns[$sortIndex], $sortOrder);
        $accountListLauronCount = $accountListLauron->count();
        $accountListLauron = $accountListLauron->offset($offset)
            ->limit($limit)
            ->get();

        foreach ($accountListLauron as $account) {
            $checkAccountHistory = AccountHistory::where('mobileNumber', $account->mobileNumber)->where('campaignID', $account->campaignID)->where('account', $account->campaignName)->where('leadsID',$request->leadID)->first();

            if (!empty($checkAccountHistory)) {
                if (empty($checkAccountHistory->statusCode)) {
                    $account->verifier = '0';
                    $account->save();
                    continue;
                }

                if (empty($checkAccountHistory->statusCode1)) {
                    $account->verifier = '1';
                    $account->save();
                    continue;
                }

                if (empty($checkAccountHistory->statusCode2)) {
                    $account->verifier = '2';
                    $account->save();
                    continue;
                }

                if (empty($checkAccountHistory->statusCode3)) {
                    $account->verifier = '3';
                    $account->save();
                    continue;
                }

                if (empty($checkAccountHistory->statusCode4)) {
                    $account->verifier = '4';
                    $account->save();
                    continue;
                }

                if (empty($checkAccountHistory->statusCode5)) {
                    $account->verifier = '5';
                    $account->save();
                    continue;
                }

                if (empty($checkAccountHistory->statusCode6)) {
                    $account->verifier = '6';
                    $account->save();
                    continue;
                }

                if (empty($checkAccountHistory->statusCode7)) {
                    $account->verifier = '7';
                    $account->save();
                    continue;
                }

                if (empty($checkAccountHistory->statusCode8)) {
                    $account->verifier = '8';
                    $account->save();
                    continue;
                }
                if (empty($checkAccountHistory->statusCode9)) {
                    $account->verifier = '9';
                    $account->save();
                    continue;
                }

                if (!empty($checkAccountHistory->statusCode9)) {
                    $account->verifier = 'full';
                    $account->save();
                    continue;
                }
            } 
            // else {
            //     $account->verifier = '0';
            //     $account->save();
            // }
        }
        $result = [
            'recordsTotal'    => $accountListLauronCount,
            'recordsFiltered' => $accountListLauronCount,
            'data'            => $accountListLauron,
        ];

        // reponse must be in  array
        return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function editLauronAccount(Request $request)
    {
        // check if account globe list has request lead
        $level = auth()->user()->level;
        //code...
        // $account = LauronAccount::where('mobileNumber', $request->mobileNumber)->where('deleted', '0')->first();
        $account = LauronAccount::where('mobileNumber', $request->existingMobileNumber)->where('campaignID', $request->campaignID)->where('campaignName', $request->campaignName)->where('leadID',$request->leadID)->where('deleted', '0')->first();
        if (empty($account)) {
            return json_encode(array(
                'success' => false,
                'level' => $level,
                'fromVerified' => $request->fromVerified,
                'message' => 'Account Not Found'
            ));
        }

        // $campaignUpload = CampaignUpload::where('campaignID', $request->campaignID)->where('campaignName', $request->campaignName)->where('deleted', '0')->first();
        $campaignUpload = CampaignUpload::where('campaignID', $request->campaignID)->where('deleted', '0')->first();
        if (empty($campaignUpload)) {
            return json_encode(array(
                'success' => false,
                'level' => $level,
                'fromVerified' => $request->fromVerified,
                'message' => 'Campaign Not Found'
            ));
        }
        $selectedUser = User::where('agentNum',$request->agentName)->first();
        if (auth()->user()->level > 0) {
            //finish status
            $account->agentName = $selectedUser->first_name." ".$selectedUser->middle_name." ".$selectedUser->last_name;
            $account->agentNumber = $request->agentName;
            $account->campaignName = $request->campaignName;
            $account->campaignID = $request->campaignID;
            $account->leadStatus = $request->leadStatus;

            $status = Status::where('statusName', $request->leadStatus)->first();
            $account->leadStatusID = $status->id;

            $account->reasonForDenial = $request->reason;
            $account->remarkStatus = $request->remarkStatus;
            //  $account->leadID = $lead->id;

            $account->segment = $request->segment;
            $account->collectionEffort = $request->collectionEffort;
            $account->transaction = $request->transaction;
            $account->placeOfContact = $request->placeOfContact;
            $account->pointOfContact = $request->pointOfContact;

            $account->accountNumber = $request->accountNumber;
            $account->endoDate = $request->endoDate;
            $account->pullOutDate = $request->pullOutDate;
            $account->writeOffDate = $request->writeOffDate;
            $account->activationDate = $request->activationDate;

            $account->homeAddress = $request->homeAddress;
            $account->companyName = $request->companyName;
            $account->CEAddressBusinessAddress = $request->companyAddress;

            $account->firstname = $request->firstName;
            $account->middlename = $request->middleName;
            $account->lastname = $request->lastName;


            $account->dateOfBirth = $request->dateOfBirth;
            $account->civilStatus = $request->civilStatus;
            $account->emailAddress = $request->email;
            $account->mobileNumber  = $request->mobileNumber;
            $account->homeNumber = $request->homeNumber;
            $account->officeNumber = $request->officeNumber;
            $account->otherContact1 = $request->otherContact1;
            $account->otherContact2 = $request->otherContact2;
            $account->otherContact3 = $request->otherContact3;
            $account->otherAddress1 = $request->otherAddress1;
            $account->otherAddress2 = $request->otherAddress2;
            $account->motherMaidenname = $request->motherMaidenName;

            $account->customerName = $request->firstName . " " . $request->middleName . " " . $request->lastName;

            $account->originalBalance = $request->originalBalance;
            $account->principalBalance = $request->principalBalance;
            $account->penalties = $request->penalties;
            $account->totalAmountDue = $request->totalAmountDue;
            $account->lastPaymentDate = $request->lastPaymentDate;
            $account->lastPaymentAmount = $request->lastPaymentAmount;

            $account->autoloanCarInfo = $request->autoloanCarInfo;
            $account->area = $request->area;
            $account->notes = $request->note;
            $account->ptpAmount = $request->ptpAmount;
            $account->ptpDate = $request->ptpDate;

            // $account->callbackDate = date_format($request->callbackDate, 'Y-m-d H:i:s');
            // Carbon::parse($item['created_at']);
            $account->callbackDate = Carbon::parse($request->callbackDate);
            // $account->callbackDate = $request->callbackDate;
        } else {

            // $account->agentName = $request->agentName;
            // $account->agentNumber = $lead->agent;
            $account->campaignName = $request->campaignName;
            $account->campaignID = $request->campaignID;
            $account->leadStatus = $request->leadStatus;

            $status = Status::where('statusName', $request->leadStatus)->first();
            $account->leadStatusID = $status->id;

            $account->reasonForDenial = $request->reason;
            $account->remarkStatus = $request->remarkStatus;
            // $account->leadID = $lead->id;
            $account->collectionEffort = $request->collectionEffort;
            $account->transaction = $request->transaction;

            $account->placeOfContact = $request->placeOfContact;
            $account->pointOfContact = $request->pointOfContact;
            $account->otherContact1 = $request->otherContact1;
            $account->otherContact2 = $request->otherContact2;
            $account->otherContact3 = $request->otherContact3;

            $account->notes = $request->note;
            $account->ptpAmount = $request->ptpAmount;
            $account->ptpDate = $request->ptpDate;

            $account->callbackDate = $request->callbackDate;

        }

        $level = auth()->user()->level;

        $checkLead = LauronLead::where('id', $account->leadID)->where('campaignID', $request->campaignID)->where('campaignName', $request->campaignName)->first();
        if (!empty($checkLead)) {
            // $checkLead->status = "1";
            $checkLead->called = "1";
            $checkLead->save();
        }
        $user = User::where('id', auth()->user()->id)->where('deleted', '0')->first();
        // dd($user);
        if (!empty($user)) {
            $user->onCall = "0";
            $user->save();
        } else {
            // return Redirect::to("list/account")->withErrors(['msg' => 'Logged in user not found']);

            return json_encode(array(
                'success' => false,
                'level' => $level,
                'fromVerified' => $request->fromVerified,
                'message' => 'Logged in user not found.'
            ));
        }

        // Account Call History
        $checkCallHistory = AccountHistory::where('mobileNumber', $request->mobileNumber)->where('campaignID', $request->campaignID)->where('account', $request->campaignName)->where('leadsID',$request->leadID)->first();

        $accountCallHistory = new AccountCallHistory();
        $accountCallHistory->agent = auth()->user()->agentNum;
        $accountCallHistory->date = $request->dateStamp;
        if (empty($checkCallHistory)) {
            $accountCallHistory->action = "ADDED NEW ENTRY";
        } else {
            $accountCallHistory->action = "UPDATED ENTRY";
        }
        $accountCallHistory->mobileNumber = $request->mobileNumber;
        $accountCallHistory->firstname = $request->firstName;
        $accountCallHistory->lastname = $request->lastName;
        $accountCallHistory->account = $request->campaignName;

        $status = Status::where('statusName', $request->leadStatus)->first();
        $accountCallHistory->statusCode = $status->statusCode;
        $accountCallHistory->statusID = $status->statusID;

        $accountCallHistory->leadsID = $account->leadID;
        $accountCallHistory->campaignID = $request->campaignID;
        $accountCallHistory->ip = \Request::ip();
        $accountCallHistory->fullname = $account->customerName;
        $accountCallHistory->origTimestamp = $request->dateStamp;
        $accountCallHistory->remark =  $request->reason;
        $accountCallHistory->callEnded = \Carbon\Carbon::now('Asia/Singapore')->toDateTimeString();

        $startTime = Carbon::parse($request->dateStamp);
        $endTime = Carbon::parse($accountCallHistory->callEnded);

        $accountCallHistory->aht = $endTime->diffInSeconds($startTime);



        //Account History
        $checkAccountHistory = AccountHistory::where('mobileNumber', $request->mobileNumber)->where('campaignID', $request->campaignID)->where('account', $request->campaignName)->where('leadsID',$request->leadID)->first();
        if (!empty($checkAccountHistory)) {


            if (empty($checkAccountHistory->statusCode1)) {
                $checkAccountHistory->statusCode1 = $status->statusCode;
                $checkAccountHistory->statusID1 = $status->statusID;
                $checkAccountHistory->callEnded1 = \Carbon\Carbon::now('Asia/Singapore')->toDateTimeString();
                $checkAccountHistory->callRemark1 = $request->reason;
                $checkAccountHistory->callstart1 = $request->dateStamp;
                $checkAccountHistory->action1 = "UPDATED ENTRY";

                $startTime = Carbon::parse($request->dateStamp);
                $endTime = Carbon::parse($checkAccountHistory->callEnded1);
                // $checkAccountHistory->aht1 = $endTime->diffForHumans($startTime);
                // $checkAccountHistory->aht1 = $endTime->diffInSeconds($startTime)." Seconds";
                $checkAccountHistory->aht1 = $endTime->diffInSeconds($startTime);
                $account->verifier = '2';
                $checkAccountHistory->save();
                $account->save();
                $accountCallHistory->save();
                // $lead->save();

                return json_encode(array(
                    'success' => true,
                    'level' => $level,
                    'fromVerified' => $request->fromVerified,
                    'message' => 'Updated successfully.'
                ));
            }

            if (empty($checkAccountHistory->statusCode2)) {
                $checkAccountHistory->statusCode2 = $status->statusCode;
                $checkAccountHistory->statusID2 = $status->statusID;
                $checkAccountHistory->callEnded2 = \Carbon\Carbon::now('Asia/Singapore')->toDateTimeString();
                $checkAccountHistory->callRemark2 = $request->reason;
                $checkAccountHistory->callstart2 = $request->dateStamp;
                $checkAccountHistory->action2 = "UPDATED ENTRY";

                $startTime = Carbon::parse($request->dateStamp);
                $endTime = Carbon::parse($checkAccountHistory->callEnded2);
                // $checkAccountHistory->aht2 = $endTime->diffForHumans($startTime);
                // $checkAccountHistory->aht2 = $endTime->diffInSeconds($startTime)." Seconds";
                $checkAccountHistory->aht2 = $endTime->diffInSeconds($startTime);
                $account->verifier = '3';
                $checkAccountHistory->save();
                $account->save();
                $accountCallHistory->save();
                // $lead->save();

                return json_encode(array(
                    'success' => true,
                    'level' => $level,
                    'fromVerified' => $request->fromVerified,
                    'message' => 'Updated successfully.'
                ));
            }

            if (empty($checkAccountHistory->statusCode3)) {
                $checkAccountHistory->statusCode3 = $status->statusCode;
                $checkAccountHistory->statusID3 = $status->statusID;
                $checkAccountHistory->callEnded3 = \Carbon\Carbon::now('Asia/Singapore')->toDateTimeString();
                $checkAccountHistory->callRemark3 = $request->reason;
                $checkAccountHistory->callstart3 = $request->dateStamp;
                $checkAccountHistory->action3 = "UPDATED ENTRY";

                $startTime = Carbon::parse($request->dateStamp);
                $endTime = Carbon::parse($checkAccountHistory->callEnded3);
                // $checkAccountHistory->aht3 = $endTime->diffForHumans($startTime);
                // dd($endTime->diffInSeconds($startTime)." Seconds");
                // $checkAccountHistory->aht3 = $endTime->diffInSeconds($startTime)." Seconds";
                $checkAccountHistory->aht3 = $endTime->diffInSeconds($startTime);
                $account->verifier = '4';
                $checkAccountHistory->save();
                $account->save();
                $accountCallHistory->save();
                // $lead->save();

                return json_encode(array(
                    'success' => true,
                    'level' => $level,
                    'fromVerified' => $request->fromVerified,
                    'message' => 'Updated successfully.'
                ));
            }

            if (empty($checkAccountHistory->statusCode4)) {
                $checkAccountHistory->statusCode4 = $status->statusCode;
                $checkAccountHistory->statusID4 = $status->statusID;
                $checkAccountHistory->callEnded4 = \Carbon\Carbon::now('Asia/Singapore')->toDateTimeString();
                $checkAccountHistory->callRemark4 = $request->reason;
                $checkAccountHistory->callstart4 = $request->dateStamp;
                $checkAccountHistory->action4 = "UPDATED ENTRY";

                $startTime = Carbon::parse($request->dateStamp);
                $endTime = Carbon::parse($checkAccountHistory->callEnded4);
                // $checkAccountHistory->aht4 = $endTime->diffForHumans($startTime);
                // $checkAccountHistory->aht4 = $endTime->diffInSeconds($startTime)." Seconds";
                $checkAccountHistory->aht4 = $endTime->diffInSeconds($startTime);
                $account->verifier = '5';
                $checkAccountHistory->save();
                $account->save();
                $accountCallHistory->save();
                // $lead->save();

                return json_encode(array(
                    'success' => true,
                    'level' => $level,
                    'fromVerified' => $request->fromVerified,
                    'message' => 'Updated successfully.'
                ));
            }

            if (empty($checkAccountHistory->statusCode5)) {
                $checkAccountHistory->statusCode5 = $status->statusCode;
                $checkAccountHistory->statusID5 = $status->statusID;
                $checkAccountHistory->callEnded5 = \Carbon\Carbon::now('Asia/Singapore')->toDateTimeString();
                $checkAccountHistory->callRemark5 = $request->reason;
                $checkAccountHistory->callstart5 = $request->dateStamp;
                $checkAccountHistory->action5 = "UPDATED ENTRY";

                $startTime = Carbon::parse($request->dateStamp);
                $endTime = Carbon::parse($checkAccountHistory->callEnded5);
                // $checkAccountHistory->aht5 = $endTime->diffForHumans($startTime);
                // $checkAccountHistory->aht5 = $endTime->diffInSeconds($startTime)." Seconds";
                $checkAccountHistory->aht5 = $endTime->diffInSeconds($startTime);
                $account->verifier = '6';
                $checkAccountHistory->save();
                $account->save();
                $accountCallHistory->save();
                // $lead->save();

                return json_encode(array(
                    'success' => true,
                    'level' => $level,
                    'fromVerified' => $request->fromVerified,
                    'message' => 'Updated successfully.'
                ));
            }

            if (empty($checkAccountHistory->statusCode6)) {
                $checkAccountHistory->statusCode6 = $status->statusCode;
                $checkAccountHistory->statusID6 = $status->statusID;
                $checkAccountHistory->callEnded6 = \Carbon\Carbon::now('Asia/Singapore')->toDateTimeString();
                $checkAccountHistory->callRemark6 = $request->reason;
                $checkAccountHistory->callstart6 = $request->dateStamp;
                $checkAccountHistory->action6 = "UPDATED ENTRY";

                $startTime = Carbon::parse($request->dateStamp);
                $endTime = Carbon::parse($checkAccountHistory->callEnded6);
                // $checkAccountHistory->aht6 = $endTime->diffForHumans($startTime);
                // $checkAccountHistory->aht6 = $endTime->diffInSeconds($startTime)." Seconds";
                $checkAccountHistory->aht6 = $endTime->diffInSeconds($startTime);
                $account->verifier = '7';
                $checkAccountHistory->save();
                $account->save();
                $accountCallHistory->save();
                // $lead->save();

                return json_encode(array(
                    'success' => true,
                    'level' => $level,
                    'fromVerified' => $request->fromVerified,
                    'message' => 'Updated successfully.'
                ));
            }

            if (empty($checkAccountHistory->statusCode7)) {
                $checkAccountHistory->statusCode7 = $status->statusCode;
                $checkAccountHistory->statusID7 = $status->statusID;
                $checkAccountHistory->callEnded7 = \Carbon\Carbon::now('Asia/Singapore')->toDateTimeString();
                $checkAccountHistory->callRemark7 = $request->reason;
                $checkAccountHistory->callstart7 = $request->dateStamp;
                $checkAccountHistory->action7 = "UPDATED ENTRY";

                $startTime = Carbon::parse($request->dateStamp);
                $endTime = Carbon::parse($checkAccountHistory->callEnded7);
                // $checkAccountHistory->aht7 = $endTime->diffForHumans($startTime);
                // $checkAccountHistory->aht7 = $endTime->diffInSeconds($startTime)." Seconds";
                $checkAccountHistory->aht7 = $endTime->diffInSeconds($startTime);
                $account->verifier = '8';
                $checkAccountHistory->save();
                $account->save();
                $accountCallHistory->save();
                // $lead->save();

                return json_encode(array(
                    'success' => true,
                    'level' => $level,
                    'fromVerified' => $request->fromVerified,
                    'message' => 'Updated successfully.'
                ));
            }

            if (empty($checkAccountHistory->statusCode8)) {
                $checkAccountHistory->statusCode8 = $status->statusCode;
                $checkAccountHistory->statusID8 = $status->statusID;
                $checkAccountHistory->callEnded8 = \Carbon\Carbon::now('Asia/Singapore')->toDateTimeString();
                $checkAccountHistory->callRemark8 = $request->reason;
                $checkAccountHistory->callstart8 = $request->dateStamp;
                $checkAccountHistory->action8 = "UPDATED ENTRY";

                $startTime = Carbon::parse($request->dateStamp);
                $endTime = Carbon::parse($checkAccountHistory->callEnded8);
                // $checkAccountHistory->aht8 = $endTime->diffForHumans($startTime);
                // $checkAccountHistory->aht8 = $endTime->diffInSeconds($startTime)." Seconds";
                $checkAccountHistory->aht8 = $endTime->diffInSeconds($startTime);
                $account->verifier = '9';
                $checkAccountHistory->save();
                $account->save();
                $accountCallHistory->save();
                // $lead->save();

                return json_encode(array(
                    'success' => true,
                    'level' => $level,
                    'fromVerified' => $request->fromVerified,
                    'message' => 'Updated successfully.'
                ));
            }

            if (empty($checkAccountHistory->statusCode9)) {
                $checkAccountHistory->statusCode9 = $status->statusCode;
                $checkAccountHistory->statusID9 = $status->statusID;
                $checkAccountHistory->callEnded9 = \Carbon\Carbon::now('Asia/Singapore')->toDateTimeString();
                $checkAccountHistory->callRemark9 = $request->reason;
                $checkAccountHistory->callstart9 = $request->dateStamp;
                $checkAccountHistory->action9 = "UPDATED ENTRY";

                $startTime = Carbon::parse($request->dateStamp);
                $endTime = Carbon::parse($checkAccountHistory->callEnded9);
                // $checkAccountHistory->aht9 = $endTime->diffForHumans($startTime);
                // $checkAccountHistory->aht9 = $endTime->diffInSeconds($startTime)." Seconds";
                $checkAccountHistory->aht9 = $endTime->diffInSeconds($startTime);
                $account->verifier = 'full';
                $checkAccountHistory->save();
                $account->save();
                $accountCallHistory->save();
                // $lead->save();

                return json_encode(array(
                    'success' => true,
                    'level' => $level,
                    'fromVerified' => $request->fromVerified,
                    'message' => 'Updated successfully.'
                ));
            }
        } else {

            $accountHistory = new AccountHistory();
            $accountHistory->agent = auth()->user()->agentNum;
            $accountHistory->date = $request->dateStamp;
            $accountHistory->action = "ADDED NEW ENTRY";
            $accountHistory->mobileNumber = $request->mobileNumber;

            $accountHistory->firstname = $request->firstName;
            $accountHistory->lastname = $request->lastName;

            $accountHistory->account = $request->campaignName;
            $accountHistory->statusCode = $status->statusCode;
            $accountHistory->statusID = $status->statusID;

            $accountHistory->leadsID = $account->leadID;
            $accountHistory->campaignID = $request->campaignID;
            $accountHistory->ip = \Request::ip();
            $accountHistory->fullname = $account->customerName;
            $accountHistory->origTimestamp = $request->dateStamp;
            $accountHistory->remark =  $request->reason;
            $accountHistory->callEnded = \Carbon\Carbon::now('Asia/Singapore')->toDateTimeString();

            $startTime = Carbon::parse($request->dateStamp);
            $endTime = Carbon::parse($accountHistory->callEnded);
            // $accountHistory->aht = $endTime->diffForHumans($startTime);
            // $accountHistory->aht = $endTime->diffInSeconds($startTime)." Seconds";
            $accountHistory->aht = $endTime->diffInSeconds($startTime);
            $account->verifier = '1';
            $accountHistory->save();
        }



        // $accountCallHistory->tapped = $lead->dl;
      
        $account->save();
        $accountCallHistory->save();
        // $lead->save();



        return json_encode(array(
            'success' => true,
            'level' => $level,
            'fromVerified' => $request->fromVerified,
            'message' => 'Account submitted successfully.'
        ));
    }

    public function listLeadAgent(Request $request)
    {

        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: *');

        $tableColumns = array(
            'campaignName',
            'customerName',
            'mobileNumber',
            'campaignID',
            'leadStatus',
            // 'campaignTimestamp'
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

        // $query = DB::table('leads')
        // ->join('accountHistory', 'leads.id', '=', 'accountHistory.leadsID')
        // ->select(
        //     'leads.product',
        //     'leads.customerName',
        //     'leads.mobileNumber',
        //     'accountHistory.campaignID',
        //     'accountHistory.statusCode',
        //     'accountHistory.origTimestamp',
        // )
        // // ->where('campaignName', $parsedCampaignName)
        // ->where('called','1')
        // ->where('accountHistory.agent',auth()->agentNum)
        // ->where('deleted', '0');

        $leadList = DB::table('lauronAccounts')
            ->join('lauronLeads', 'lauronLeads.id', '=', 'lauronAccounts.leadID')
            ->select('lauronAccounts.*', 'lauronLeads.deleted')
            ->where('lauronLeads.deleted', '0')
            ->where('lauronAccounts.deleted', '0')
            ->where('lauronAccounts.agentNumber', auth()->user()->agentNum);
            // ->get();

        // $leadList = LauronAccount::where('deleted', '0')->where('agentNumber', auth()->user()->agentNum);
        $leadList = $leadList->where(function ($query) use ($search) {
            return $query->where('lauronAccounts.campaignName', 'like', '%' . $search . '%')
                ->orWhere('lauronAccounts.customerName', 'like', '%' . $search . '%')
                ->orWhere('lauronAccounts.mobileNumber', 'like', '%' . $search . '%')
                ->orWhere('lauronAccounts.campaignID', 'like', '%' . $search . '%')
                ->orWhere('lauronAccounts.leadStatus', 'like', '%' . $search . '%');
            //   ->orWhere('campaignTimestamp', 'like', '%' . $search . '%');
        })
            ->orderBy($tableColumns[$sortIndex], $sortOrder);
        $leadListCount = $leadList->count();
        $leadList = $leadList->offset($offset)
            ->limit($limit)
            ->get();

        $result = [
            'recordsTotal'    => $leadListCount,
            'recordsFiltered' => $leadListCount,
            'data'            => $leadList,
        ];

        // reponse must be in  array
        return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function listAgentCallHistory(Request $request)
    {

        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: *');

        $tableColumns = array(
            'action',
            'fullname',
            'agent',
            'date',
            'mobileNumber',
            'statusCode',
            'campaignID',
            'ip',
            'aht'
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

        $accountCallHistory = AccountCallHistory::where('mobileNumber', $request->mobileNumber)
        ->where('campaignID', $request->campaignID)->where('account',$request->campaignName)->where('leadsID', $request->leadID);
        $sql = $accountCallHistory->toSql();
        $accountCallHistory = $accountCallHistory->where(function ($query) use ($search) {
            return $query->where('action', 'like', '%' . $search . '%')
                ->orWhere('fullname', 'like', '%' . $search . '%')
                ->orWhere('agent', 'like', '%' . $search . '%')
                ->orWhere('date', 'like', '%' . $search . '%')
                ->orWhere('mobileNumber', 'like', '%' . $search . '%')
                ->orWhere('statusCode', 'like', '%' . $search . '%')
                ->orWhere('campaignID', 'like', '%' . $search . '%')
                ->orWhere('ip', 'like', '%' . $search . '%')
                ->orWhere('aht', 'like', '%' . $search . '%');
        })
            ->orderBy($tableColumns[$sortIndex], $sortOrder);
        $accountCallHistoryCount = $accountCallHistory->count();
        $accountCallHistory = $accountCallHistory->offset($offset)
            ->limit($limit)
            ->get();

        $result = [
            'recordsTotal'    => $accountCallHistoryCount,
            'recordsFiltered' => $accountCallHistoryCount,
            'data'            => $accountCallHistory,
            'sql'            => "",

        ];

        // reponse must be in  array
        return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function exitAccount()
    {

        $user = User::where('id', auth()->user()->id)->where('deleted', '0')->first();

        if (!empty($user)) {
            $user->onCall = "0";
            $user->save();

            return json_encode(array(
                'success' => true,
            ));
        }
        return json_encode(array(
            'success' => false,
            'message' => 'User not found.'
        ));
    }

    public function listCallBack(Request $request)
    {

        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: *');

        $tableColumns = array(
            'id',
            'campaignName',
            'remarkStatus',
            'firstname',
            'middlename',
            'lastname',
            'mobileNumber',
            'campaignID',
            'leadStatus'
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

        // $query = DB::table('leads')
        // ->join('accountHistory', 'leads.id', '=', 'accountHistory.leadsID')
        // ->select(
        //     'leads.product',
        //     'leads.customerName',
        //     'leads.mobileNumber',
        //     'accountHistory.campaignID',
        //     'accountHistory.statusCode',
        //     'accountHistory.origTimestamp',
        // )
        // // ->where('campaignName', $parsedCampaignName)
        // ->where('called','1')
        // ->where('accountHistory.agent',auth()->agentNum)
        // ->where('deleted', '0');


        $callBackList = DB::table('lauronAccounts')
        ->join('lauronLeads', 'lauronLeads.id', '=', 'lauronAccounts.leadID')
        ->join('status', 'status.id', '=', 'lauronAccounts.leadStatusID')
        ->select('lauronAccounts.*', 'lauronLeads.deleted')
        ->where('lauronLeads.deleted', '0')
        ->where('lauronAccounts.deleted', '0')
        ->where('lauronAccounts.agentNumber', auth()->user()->agentNum)
        ->whereRaw('FIND_IN_SET("callback", REPLACE(status.displayTable, " ", "")) > 0');
        // ->where(function ($query) {
        //     $query->where('leadStatus', 'like', '%CB -%');
        //     //   ->orWhere('leadStatus', 'like', '%CAL%')
        // });

        // $callBackList = LauronAccount::where('deleted', '0')->where('agentNumber', auth()->user()->agentNum)->where(function ($query) {
        //     $query->where('leadStatus', 'like', '%CB -%');
        //     //   ->orWhere('leadStatus', 'like', '%CAL%')
        // });

        $callBackList = $callBackList->where(function ($query) use ($search) {
            return $query->where('lauronAccounts.id', 'like', '%' . $search . '%')
                ->orWhere('lauronAccounts.campaignName', 'like', '%' . $search . '%')
                ->orWhere('lauronAccounts.firstname', 'like', '%' . $search . '%')
                ->orWhere('lauronAccounts.middlename', 'like', '%' . $search . '%')
                ->orWhere('lauronAccounts.lastname', 'like', '%' . $search . '%')
                ->orWhere('lauronAccounts.mobileNumber', 'like', '%' . $search . '%')
                ->orWhere('lauronAccounts.campaignID', 'like', '%' . $search . '%')
                ->orWhere('lauronAccounts.leadStatus', 'like', '%' . $search . '%');
        })
            ->orderBy($tableColumns[$sortIndex], $sortOrder);
        $callBackListCount = $callBackList->count();
        $callBackList = $callBackList->offset($offset)
            ->limit($limit)
            ->get();

        $result = [
            'recordsTotal'    => $callBackListCount,
            'recordsFiltered' => $callBackListCount,
            'data'            => $callBackList,
        ];

        // reponse must be in  array
        return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function listVerifyAccountLauron(Request $request)
    {

        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: *');

        $tableColumns = array(
            'id',
            'campaignName',
            'remarkStatus',
            'firstname',
            'middlename',
            'lastname',
            'mobileNumber',
            'campaignID',
            'leadStatus'
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

        // $query = DB::table('leads')
        // ->join('accountHistory', 'leads.id', '=', 'accountHistory.leadsID')
        // ->select(
        //     'leads.product',
        //     'leads.customerName',
        //     'leads.mobileNumber',
        //     'accountHistory.campaignID',
        //     'accountHistory.statusCode',
        //     'accountHistory.origTimestamp',
        // )
        // // ->where('campaignName', $parsedCampaignName)
        // ->where('called','1')
        // ->where('accountHistory.agent',auth()->agentNum)
        // ->where('deleted', '0');

        $verifyAccounts = DB::table('lauronAccounts')
        ->join('lauronLeads', 'lauronLeads.id', '=', 'lauronAccounts.leadID')
        ->join('status', 'status.id', '=', 'lauronAccounts.leadStatusID')
        ->select('lauronAccounts.*', 'lauronLeads.deleted')
        ->where('lauronLeads.deleted', '0')
        ->where('lauronAccounts.deleted', '0')
        ->whereRaw('FIND_IN_SET("verifyAccounts", REPLACE(status.displayTable, " ", "")) > 0');
        // ->where(function ($query) {
        //     $query->where('leadStatus', 'like', '%PTP%')
        //         ->orWhere('leadStatus', 'like', '%BRK%');
        // });

        // $verifyAccounts = LauronAccount::where('deleted', '0')->where(function ($query) {
        //     $query->where('leadStatus', 'like', '%PTP%')
        //         ->orWhere('leadStatus', 'like', '%BRK%');
        // });

        $verifyAccounts = $verifyAccounts->where(function ($query) use ($search) {
            return $query->where('lauronAccounts.id', 'like', '%' . $search . '%')
                ->orWhere('lauronAccounts.campaignName', 'like', '%' . $search . '%')
                ->orWhere('lauronAccounts.firstname', 'like', '%' . $search . '%')
                ->orWhere('lauronAccounts.middlename', 'like', '%' . $search . '%')
                ->orWhere('lauronAccounts.lastname', 'like', '%' . $search . '%')
                ->orWhere('lauronAccounts.mobileNumber', 'like', '%' . $search . '%')
                ->orWhere('lauronAccounts.campaignID', 'like', '%' . $search . '%')
                ->orWhere('lauronAccounts.leadStatus', 'like', '%' . $search . '%');
        })
            ->orderBy($tableColumns[$sortIndex], $sortOrder);
        $verifyAccountsCount = $verifyAccounts->count();
        $verifyAccounts = $verifyAccounts->offset($offset)
            ->limit($limit)
            ->get();

        $result = [
            'recordsTotal'    => $verifyAccountsCount,
            'recordsFiltered' => $verifyAccountsCount,
            'data'            => $verifyAccounts,
        ];

        // reponse must be in  array
        return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function listPtpAndPaid(Request $request)
    {

        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: *');

        $tableColumns = array(
            'id',
            'campaignName',
            'remarkStatus',
            'firstname',
            'middlename',
            'lastname',
            'mobileNumber',
            'campaignID',
            'leadStatus'
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
        $loggedinUserCampaign = auth()->user()->product;

        // $ptpAndPaidList = LauronAccount::where('deleted', '0')
        // ->where('campaignName',$loggedinUserCampaign)
        // ->where(function ($query) {
        //     $query->where('leadStatus', 'like', '%PTP%')
        //     ->orWhere('leadStatus', 'like', '%Paid%');
        // });

        $ptpAndPaidList = DB::table('lauronAccounts')
        ->join('lauronLeads', 'lauronLeads.id', '=', 'lauronAccounts.leadID')
        ->join('status', 'status.id', '=', 'lauronAccounts.leadStatusID')
        ->select('lauronAccounts.*', 'lauronLeads.deleted')
        ->where('lauronLeads.deleted', '0')
        ->where('lauronAccounts.deleted', '0')
        ->where('lauronAccounts.campaignName',$loggedinUserCampaign)
        ->whereRaw('FIND_IN_SET("ptpAndPaid", REPLACE(status.displayTable, " ", "")) > 0');
        // ->where(function ($query) {
        //     $query->where('leadStatus', 'like', '%PTP%')
        //     ->orWhere('leadStatus', 'like', '%Paid%');
        // });

        $ptpAndPaidList = $ptpAndPaidList->where(function ($query) use ($search) {
            return $query->where('lauronAccounts.id', 'like', '%' . $search . '%')
                ->orWhere('lauronAccounts.campaignName', 'like', '%' . $search . '%')
                ->orWhere('lauronAccounts.firstname', 'like', '%' . $search . '%')
                ->orWhere('lauronAccounts.middlename', 'like', '%' . $search . '%')
                ->orWhere('lauronAccounts.lastname', 'like', '%' . $search . '%')
                ->orWhere('lauronAccounts.mobileNumber', 'like', '%' . $search . '%')
                ->orWhere('lauronAccounts.campaignID', 'like', '%' . $search . '%')
                ->orWhere('lauronAccounts.leadStatus', 'like', '%' . $search . '%');
        })
            ->orderBy($tableColumns[$sortIndex], $sortOrder);
        $ptpAndPaidListCount = $ptpAndPaidList->count();
        $ptpAndPaidList = $ptpAndPaidList->offset($offset)
            ->limit($limit)
            ->get();

        $result = [
            'recordsTotal'    => $ptpAndPaidListCount,
            'recordsFiltered' => $ptpAndPaidListCount,
            'data'            => $ptpAndPaidList,
        ];

        // reponse must be in  array
        return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}
