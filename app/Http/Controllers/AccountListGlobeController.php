<?php

namespace App\Http\Controllers;

use App\Models\AccountCallHistory;
use App\Models\AccountHistory;
use App\Models\AccountListGlobe;
use App\Models\CampaignUpload;
use App\Models\Lead;
use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use DB;
use Illuminate\Http\Request;
use App\Exports\HotLeadExport;
use Log;
use Redirect;
use Excel;


class AccountListGlobeController extends Controller
{
  //Return data for admin Account List
  public function listAccountListGlobe(Request $request)
  {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: *');

    $tableColumns = array(
      'id',
      'product',
      'agentName',
      'origName',
      'mobileContactNumber',
      'campaignID',
      'campaignStatus'
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


    $accountListGlobe = AccountListGlobe::where('deleted', '0');
    $accountListGlobe = $accountListGlobe->where(function ($query) use ($search) {
      return $query->where('product', 'like', '%' . $search . '%')
        ->orWhere('agentName', 'like', '%' . $search . '%')
        ->orWhere('origName', 'like', '%' . $search . '%')
        ->orWhere('mobileContactNumber', 'like', '%' . $search . '%')
        ->orWhere('campaignID', 'like', '%' . $search . '%')
        ->orWhere('campaignStatus', 'like', '%' . $search . '%')
        ->orWhere('id', 'like', '%' . $search . '%');
    })
      ->orderBy($tableColumns[$sortIndex], $sortOrder);
    $accountListGlobeCount = $accountListGlobe->count();
    $accountListGlobe = $accountListGlobe->offset($offset)
      ->limit($limit)
      ->get();

    foreach ($accountListGlobe as $account) {
      $checkAccountHistory = AccountHistory::where('mobileNumber', $account->mobileContactNumber)->where('campaignID', $account->campaignID)->first();

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
      } else {
        $account->verifier = '0';
        $account->save();
      }
    }
    $result = [
      'recordsTotal'    => $accountListGlobeCount,
      'recordsFiltered' => $accountListGlobeCount,
      'data'            => $accountListGlobe,
    ];

    // reponse must be in  array
    return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
  }
  //Edit account
  public function editAccount(Request $request)
  {
    // check if account globe list has request lead
    $level = auth()->user()->level;
    //code...
    $account = AccountListGlobe::where('mobileContactNumber', $request->mobileNumber)->where('deleted', '0')->first();
    if (empty($account)) {
      return json_encode(array(
        'success' => false,
        'level' => $level,
        'message' => 'Account Not Found'
      ));
    }

    $campaignUpload = CampaignUpload::where('campaignID', $request->campaignID)->where('campaignName',$request->campaignName)->where('deleted', '0')->first();
    if (empty($campaignUpload)) {
      return json_encode(array(
        'success' => false,
        'level' => $level,
        'message' => 'Campaign Not Found'
      ));
    }


    //Account List Globe
    // $accountListGlobe = new AccountListGlobe();

    //finish status
    $account->agentName = $request->agentName;
    // $accountListGlobe->agentNumber = $lead->agent;
    $account->campaignName = $request->campaignName;
    $account->campaignID = $request->campaignID;
    $account->campaignStatus = $request->leadStatus;

    $status = Status::where('statusName', $request->leadStatus)->first();
    $account->campaignStatusID = $status->statusID;


    // $accountListGlobe->dcRemark = $request->reason;
    $account->campaignRemark = $request->remarkStatus;
    // $accountListGlobe->leadsID = $lead->id;

    $account->extraField1 = $request->extraFieldEnd1;
    $account->extraField2 = $request->extraFieldEnd2;
    $account->extraField3 = $request->extraFieldEnd3;

    $account->referenceNumber = $request->reference;

    $account->origName = $request->customerName;

    $account->firstname = $request->fname;
    $account->middlename = $request->mname;
    $account->lastname = $request->lname;

    $account->creditLimit = $request->creditLimit;
    $account->product = $request->product;
    $account->orderNumberUltima = $request->orderNumberUltima;
    $account->transmittalDate = $request->CCandTransmittalDate;

    $account->globeAccount = $request->globeAccountNumber;
    $account->salutation = $request->salutation;

    // installation date 1
    // installation date 2
    // installation date 3
    // applicationType



    $account->doculink1 = $request->docksLink;
    // $accountListGlobe->product = $request->product;
    $account->gender = $request->gender;
    $account->birthday = $request->birthday;
    $account->civilStatus = $request->civilStatus;

    //fix name
    // $accountListGlobe->product = $request->lname;
    // $accountListGlobe->product = $request->fname;
    // $accountListGlobe->product = $request->mname;

    $account->motherFirstname = $request->motherFname;
    $account->motherMiddlename = $request->motherMname;
    $account->motherLastname = $request->motherLname;
    $account->NumberOfChildren = $request->numberOfChildren;
    $account->homeOwnership = $request->homeOwnership;
    $account->addHB = $request->houseBuilding;
    $account->addUnit = $request->unitNumber;
    $account->addBuilding = $request->buildingVillage;

    // $accountListGlobe->houseRoomFloor = $request->buildingVillage;
    $account->addStreet = $request->addressStreet;
    $account->addBarangay = $request->addressBarangay;

    $account->addCity = $request->addressCity;
    $account->addProvince = $request->addressProvince;
    $account->addPostal = $request->addressPostal;
    $account->addressRegion = $request->addressRegion;
    $account->addressRemark = $request->addressRemark;
    $account->lengthOfStay = $request->lengthOfStay;
    $account->landlineContact = $request->landlineContactNumber;
    $account->existingMobile = $request->existingMobileNumber;
    $account->mobileContactNumber = $request->mobileNumber;
    $account->email = $request->email;
    $account->tin = $request->tin;
    $account->gsiss = $request->gsiss;
    $account->citizenship = $request->citizenship;

    $account->ifForeignCountry = $request->ifForeign;
    $account->spousename = $request->spouseLname;
    $account->spouseFirstname = $request->spouseFname;
    $account->spouseMiddlename = $request->spouseMname;
    $account->spouseBirthday = $request->birthdaySpouse;
    $account->spouseContactNumber = $request->contactNumberSpouse;
    $account->officeName = $request->officeName;
    $account->officeAddressPostal = $request->officeAddress;
    $account->dateOfemployment = $request->dateOfEmployment;
    $account->officeTelephoneNumber = $request->officeTelephoneNumber;
    $account->yearsInCompany = $request->yearsInCompany;

    $account->occupation = $request->occupation;
    $account->monthlyIncome = $request->monthlyIncome;
    $account->authorizedContactPerson = $request->authorizedContactName;
    $account->authorizedContactNumber = $request->authorizedContact;
    $account->relation = $request->authorizedContactRelation;

    $account->homeOfficePaperless = $request->billingType;


    $account->planType = $request->planType;

    $account->planMSF = $request->planMSF;
    $account->planCombo = $request->combos;
    $account->planBooster = $request->addOnBooster;
    $account->mandatoryArrowAddon = $request->mandatoryBoosterForArrow;
    $account->goSurfBundle = $request->lifestyleBundle;
    $account->arrowAddon = $request->arrowBundleFree;
    $account->handset = $request->handset;

    $account->cashAmount = $request->cashOut;
    $account->promoPriceBulletin = $request->promo;
    $account->paymentMethod = $request->modeOfPayment;
    $account->valueAddedService = $request->valueAddedService;

    $account->hbp = $request->hbp;
    $account->lockupPeriod = $request->LockUpPeriod;
    $account->transmittalType = $request->transmittalType;
    $account->sourceOfSales = $request->sourceOfSales;
    $account->applicationMode = $request->applicationMode;
    $account->remark = $request->remark;
    $account->salesmanID = $request->salesmanID;
    $account->salesmanName = $request->salesmanName;
    $account->agencyName = $request->agencyName;
    $account->accountManager = $request->accountManager;
    $account->salesChannel = $request->salesChannel;
    $account->projectPromo = $request->projectPromo;
    $account->appReceiveSource = $request->appsReceivedSource;
    $account->stimestamp = $request->timestamp;
    $account->typeOfPOID = $request->poidType;
    $account->poidNumber = $request->poidNumber;
    $account->doculink = $request->docksLink;
    $account->leadType = $request->leadType;
    $account->salesAgentName = $request->salesAgentName;
    $account->appDate = $request->applicationDate;
    $account->gcashGui = $request->gcashGUI;

    $account->eviaFastlane = $request->eligablePlansFastlane;
    $account->eplanGscore = $request->eligablePlansGscore;
    $account->deliveryAddress = $request->deliveryAddress;
    $account->sadmin = $request->admin;
    $account->gdfPromoTag = $request->GDFPromo;
    $account->dateCalled = $request->dateCalled;
    // $accountListGlobe->dateExtract = $lead->dateExtract;
    // $accountListGlobe->dateAdded = $lead->dateAdded;
    $account->timeEdit = $request->timestamp;
    $account->dateEdit = $request->timestamp;

    $account->campaignTimestamp = $campaignUpload->campaignDateUploaded;
    // $accountListGlobe->globeStatus = $lead->globeStatus;
    // $accountListGlobe->globeProduct = $lead->globeProduct;
    // $accountListGlobe->documentReference = $lead->documentReference;
    $account->referenceID = $request->referenceID;

    // $accountListGlobe->renewalReference = $lead->renewalReference;
    // $accountListGlobe->dataPrivacy = $lead->dataPrivacy;
    // $accountListGlobe->newsletter = $lead->newsletter;
    // $accountListGlobe->prefCallDate = $lead->prefCallDate;
    // $accountListGlobe->appTime = $lead->appTime;
    // $accountListGlobe->desiredPlan = $account->desiredPlan;
    // $accountListGlobe->salesInquiry = $lead->salesInquiry;

    // $accountListGlobe->sourceAttribution = $lead->sourceAttribution;
    $account->dateCompiled = $request->dateCompiled;
    $account->qualified = $request->qualified;
    $account->deliveryZipCode = $request->deliveryZipCode;
    $account->andaleArea = $request->andaleArea;
    $account->projectChamomile = $request->projectChamomile;
    $account->gadgetCareAmount = $request->gadgetCareAmount;
    // $account->extraField1 = $request->extraFieldEnd1;
    // $account->extraField2 = $request->extraFieldEnd2;
    // $account->extraField3 = $request->extraFieldEnd3;

    //Missing Migration fields not in edit lead
    // $accountListGlobe->technology = $lead->technology;
    // $accountListGlobe->oldPlanDescription = $lead->oldPlan;
    // $accountListGlobe->newMpID = $lead->newMpID;
    // $accountListGlobe->latlong = $lead->latitude . " " . $lead->longitude;
    // $accountListGlobe->duoNumber = $lead->duo;
    // $accountListGlobe->shpNumber = $lead->shpNumber;


    //Missing PP fields not in edit lead
    // $accountListGlobe->alternateContactNumber = $lead->otherContactNumber;
    // $accountListGlobe->cartContent = $lead->cartContent;
    // $accountListGlobe->promoToOffer = $request->promo;


    //Missing Schedule request fields not in edit lead
    // $accountListGlobe->allocDate = $lead->allocDate;
    // $accountListGlobe->prefCallDate = $lead->prefCallDate;
    // $accountListGlobe->dateAdded = $lead->dateAdded;

    //Missing Serviceable fields not in edit lead


    //Missing ASSIST fields not in edit lead

    // $accountListGlobe->team = $lead->team;

    //Missing Broadband fields not in edit lead

    // $accountListGlobe->leadMonth = $lead->leadMonth;
    // $accountListGlobe->appPeriod = $lead->appPeriod;
    // $accountListGlobe->dateModified = $lead->dateModified;
    // $accountListGlobe->optinAbandon = $lead->optinaBandon;
    // $accountListGlobe->cartContent = $lead->cartContent;
    // $accountListGlobe->ex1 = $lead->ex1;
    // $accountListGlobe->ex2 = $lead->ex2;
    // $accountListGlobe->ex3 = $lead->ex3;
    // $accountListGlobe->dl = $lead->dl;

    $account->portingNumber = $request->portingNumber;
    $checkLead = Lead::where('id', $account->leadsID)->where('campaignID', $request->campaignID)->first();
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
        'message' => 'Logged in user not found.'
      ));

    
    }







    // $lead->done = "1";

    // Account Call History
    $checkCallHistory = AccountHistory::where('mobileNumber', $request->mobileNumber)->where('campaignID', $request->campaignID)->first();




    $accountCallHistory = new AccountCallHistory();
    $accountCallHistory->agent = $request->agentName;
    $accountCallHistory->date = $request->timestamp;
    if (empty($checkCallHistory)) {
      $accountCallHistory->action = "ADDED NEW ENTRY";
    } else {
      $accountCallHistory->action = "UPDATED ENTRY";
    }
    $accountCallHistory->mobileNumber = $request->mobileNumber;
    $accountCallHistory->firstname = $request->fname;
    $accountCallHistory->lastname = $request->lname;
    $accountCallHistory->account = $request->product;

    $status = Status::where('statusName', $request->leadStatus)->first();
    $accountCallHistory->statusCode = $status->statusCode;
    $accountCallHistory->statusID = $status->statusID;

    $accountCallHistory->leadsID = $account->leadsID;
    $accountCallHistory->campaignID = $request->campaignID;
    $accountCallHistory->ip = \Request::ip();
    $accountCallHistory->fullname = $request->customerName;
    $accountCallHistory->origTimestamp = $request->timestamp;
    $accountCallHistory->remark =  $request->reason;
    $accountCallHistory->callEnded = \Carbon\Carbon::now()->toDateTimeString();

    $startTime = Carbon::parse($request->timestamp);
    $endTime = Carbon::parse($accountCallHistory->callEnded);

    // $options = [
    //   'join' => ', ',
    //   'parts' => 2,
    //   'syntax' => CarbonInterface::DIFF_ABSOLUTE,
    // ];
    // $accountCallHistory->aht = $endTime->diffForHumans($startTime,$options);
    // $accountCallHistory->aht =  $startTime->diff($endTime)->format('%H:%I:%S');
    // $accountCallHistory->aht = $endTime->diffInSeconds($startTime)." Seconds";
    $accountCallHistory->aht = $endTime->diffInSeconds($startTime);



    //Account History
    $checkAccountHistory = AccountHistory::where('mobileNumber', $request->mobileNumber)->where('campaignID', $request->campaignID)->first();
    if (!empty($checkAccountHistory)) {


      if (empty($checkAccountHistory->statusCode1)) {
        $checkAccountHistory->statusCode1 = $status->statusCode;
        $checkAccountHistory->statusID1 = $status->statusID;
        $checkAccountHistory->callEnded1 = \Carbon\Carbon::now()->toDateTimeString();
        $checkAccountHistory->callRemark1 = $request->reason;
        $checkAccountHistory->callstart1 = $request->timestamp;
        $checkAccountHistory->action1 = "UPDATED ENTRY";

        $startTime = Carbon::parse($request->timestamp);
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
          'message' => 'Updated successfully.'
        ));
      }

      if (empty($checkAccountHistory->statusCode2)) {
        $checkAccountHistory->statusCode2 = $status->statusCode;
        $checkAccountHistory->statusID2 = $status->statusID;
        $checkAccountHistory->callEnded2 = \Carbon\Carbon::now()->toDateTimeString();
        $checkAccountHistory->callRemark2 = $request->reason;
        $checkAccountHistory->callstart2 = $request->timestamp;
        $checkAccountHistory->action2 = "UPDATED ENTRY";

        $startTime = Carbon::parse($request->timestamp);
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
          'message' => 'Updated successfully.'
        ));
      }

      if (empty($checkAccountHistory->statusCode3)) {
        $checkAccountHistory->statusCode3 = $status->statusCode;
        $checkAccountHistory->statusID3 = $status->statusID;
        $checkAccountHistory->callEnded3 = \Carbon\Carbon::now()->toDateTimeString();
        $checkAccountHistory->callRemark3 = $request->reason;
        $checkAccountHistory->callstart3 = $request->timestamp;
        $checkAccountHistory->action3 = "UPDATED ENTRY";

        $startTime = Carbon::parse($request->timestamp);
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
          'message' => 'Updated successfully.'
        ));
      }

      if (empty($checkAccountHistory->statusCode4)) {
        $checkAccountHistory->statusCode4 = $status->statusCode;
        $checkAccountHistory->statusID4 = $status->statusID;
        $checkAccountHistory->callEnded4 = \Carbon\Carbon::now()->toDateTimeString();
        $checkAccountHistory->callRemark4 = $request->reason;
        $checkAccountHistory->callstart4 = $request->timestamp;
        $checkAccountHistory->action4 = "UPDATED ENTRY";

        $startTime = Carbon::parse($request->timestamp);
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
          'message' => 'Updated successfully.'
        ));
      }

      if (empty($checkAccountHistory->statusCode5)) {
        $checkAccountHistory->statusCode5 = $status->statusCode;
        $checkAccountHistory->statusID5 = $status->statusID;
        $checkAccountHistory->callEnded5 = \Carbon\Carbon::now()->toDateTimeString();
        $checkAccountHistory->callRemark5 = $request->reason;
        $checkAccountHistory->callstart5 = $request->timestamp;
        $checkAccountHistory->action5 = "UPDATED ENTRY";

        $startTime = Carbon::parse($request->timestamp);
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
          'message' => 'Updated successfully.'
        ));
      }

      if (empty($checkAccountHistory->statusCode6)) {
        $checkAccountHistory->statusCode6 = $status->statusCode;
        $checkAccountHistory->statusID6 = $status->statusID;
        $checkAccountHistory->callEnded6 = \Carbon\Carbon::now()->toDateTimeString();
        $checkAccountHistory->callRemark6 = $request->reason;
        $checkAccountHistory->callstart6 = $request->timestamp;
        $checkAccountHistory->action6 = "UPDATED ENTRY";

        $startTime = Carbon::parse($request->timestamp);
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
          'message' => 'Updated successfully.'
        ));
      }

      if (empty($checkAccountHistory->statusCode7)) {
        $checkAccountHistory->statusCode7 = $status->statusCode;
        $checkAccountHistory->statusID7 = $status->statusID;
        $checkAccountHistory->callEnded7 = \Carbon\Carbon::now()->toDateTimeString();
        $checkAccountHistory->callRemark7 = $request->reason;
        $checkAccountHistory->callstart7 = $request->timestamp;
        $checkAccountHistory->action7 = "UPDATED ENTRY";
    
        $startTime = Carbon::parse($request->timestamp);
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
          'message' => 'Updated successfully.'
        ));
      }

      if (empty($checkAccountHistory->statusCode8)) {
        $checkAccountHistory->statusCode8 = $status->statusCode;
        $checkAccountHistory->statusID8 = $status->statusID;
        $checkAccountHistory->callEnded8 = \Carbon\Carbon::now()->toDateTimeString();
        $checkAccountHistory->callRemark8 = $request->reason;
        $checkAccountHistory->callstart8 = $request->timestamp;
        $checkAccountHistory->action8 = "UPDATED ENTRY";

        $startTime = Carbon::parse($request->timestamp);
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
          'message' => 'Updated successfully.'
        ));
      }

      if (empty($checkAccountHistory->statusCode9)) {
        $checkAccountHistory->statusCode9 = $status->statusCode;
        $checkAccountHistory->statusID9 = $status->statusID;
        $checkAccountHistory->callEnded9 = \Carbon\Carbon::now()->toDateTimeString();
        $checkAccountHistory->callRemark9 = $request->reason;
        $checkAccountHistory->callstart9 = $request->timestamp;
        $checkAccountHistory->action9 = "UPDATED ENTRY";
     
        $startTime = Carbon::parse($request->timestamp);
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
          'message' => 'Updated successfully.'
        ));
      }
    } else {

      $accountHistory = new AccountHistory();
      $accountHistory->agent = $request->agentName;
      $accountHistory->date = $request->timestamp;
      $accountHistory->action = "ADDED NEW ENTRY";
      $accountHistory->mobileNumber = $request->mobileNumber;

      $accountHistory->firstname = $request->fname;
      $accountHistory->lastname = $request->lname;

      $accountHistory->account = $request->product;
      $accountHistory->statusCode = $status->statusCode;
      $accountHistory->statusID = $status->statusID;

      $accountHistory->leadsID = $account->leadsID;
      $accountHistory->campaignID = $request->campaignID;
      $accountHistory->ip = \Request::ip();
      $accountHistory->fullname = $request->customerName;
      $accountHistory->origTimestamp = $request->timestamp;
      $accountHistory->remark =  $request->reason;
      $accountHistory->callEnded = \Carbon\Carbon::now()->toDateTimeString();

      $startTime = Carbon::parse($request->timestamp);
      $endTime = Carbon::parse($accountHistory->callEnded);
      // $accountHistory->aht = $endTime->diffForHumans($startTime);
      // $accountHistory->aht = $endTime->diffInSeconds($startTime)." Seconds";
      $accountHistory->aht = $endTime->diffInSeconds($startTime);
      $account->verifier = '1';
    }



    // $accountCallHistory->tapped = $lead->dl;
    $accountHistory->save();
    $account->save();
    $accountCallHistory->save();
    // $lead->save();



    return json_encode(array(
      'success' => true,
      'level' => $level,
      'message' => 'Account submitted successfully.'
    ));
  }
  // Edit lead for agent
  public function listLeadAgent(Request $request)
  {

    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: *');

    $tableColumns = array(
      'product',
      'customerName',
      'mobileNumber',
      'campaignID',
      'campaignStatus',
      'campaignTimestamp'
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


    $leadList = AccountListGlobe::where('deleted', '0')->where('agentNumber', auth()->user()->agentNum);
    $leadList = $leadList->where(function ($query) use ($search) {
      return $query->where('product', 'like', '%' . $search . '%')
        ->orWhere('origName', 'like', '%' . $search . '%')
        ->orWhere('mobileContactNumber', 'like', '%' . $search . '%')
        ->orWhere('campaignID', 'like', '%' . $search . '%')
        ->orWhere('campaignStatus', 'like', '%' . $search . '%')
        ->orWhere('campaignTimestamp', 'like', '%' . $search . '%');
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

    $accountCallHistory = AccountCallHistory::where('mobileNumber', $request->mobileNumber)->where('campaignID', $request->campaignID)->where('agent', auth()->user()->agentNum);
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
    ];

    // reponse must be in  array
    return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
  }

  public function exitAccount(Request $request)
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
      'product',
      'campaignRemark',
      'firstname',
      'middlename',
      'lastname',
      'mobileContactNumber',
      'campaignID',
      'campaignStatus'
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


    $callBackList = AccountListGlobe::where('deleted', '0')->where('agentNumber', auth()->user()->agentNum)->where(function ($query) {
      $query->where('campaignStatusID', 'like', '%INT2%')
        ->orWhere('campaignStatusID', 'like', '%CAL%')
        ->orWhere('campaignStatusID', 'like', '%HOT%')
        ->orWhere('campaignStatusID', 'like', '%CONTACTED2%')
        ->orWhere('campaignStatusID', 'like', '%CONTACTED3%')
        ->orWhere('campaignStatusID', 'like', '%CONTACTED9%')
        ->orWhere('campaignStatusID', 'like', '%CONTACTED17%')
        ->orWhere('campaignStatusID', 'like', '%CONTACTED18%')
        ->orWhere('campaignStatusID', 'like', '%FORCAL%')
        ->orWhere('campaignStatusID', 'like', '%CONTACTEDUNQ4%')
        ->orWhere('campaignStatusID', 'like', '%UNCONTACTED7%');
    });

      $callBackList = $callBackList->where(function ($query) use ($search) {
        return $query->where('id', 'like', '%' . $search . '%')
          ->orWhere('product', 'like', '%' . $search . '%')
          ->orWhere('campaignRemark', 'like', '%' . $search . '%')
          ->orWhere('firstname', 'like', '%' . $search . '%')
          ->orWhere('middlename', 'like', '%' . $search . '%')
          ->orWhere('lastname', 'like', '%' . $search . '%')
          ->orWhere('mobileContactNumber', 'like', '%' . $search . '%')
          ->orWhere('campaignID', 'like', '%' . $search . '%')
          ->orWhere('campaignStatus', 'like', '%' . $search . '%');
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

  public function listHotLead(Request $request)
  {

    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: *');

    $tableColumns = array(
      'id',
      'referenceNumber',
      'product',
      'campaignID',
      'mobileContactNumber',
      'firstname',
      'lastname',
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


    $hotLeadList = AccountListGlobe::select('referenceNumber','product','orderNumberUltima','transmittalDate','globeAccount','salutation','gender','birthday',
    'civilStatus','lastname','firstname','middlename','motherLastname','motherFirstname','motherMiddlename','NumberOfChildren','homeOwnership','howAddressWithPostal',
    'lengthOfStay','landlineContact','existingMobile','mobileContactNumber','email','tin','gsiss','citizenship','ifForeignCountry','spousename','spouseBirthday',
    'spouseContactNumber','officeName','officeAddressPostal','dateOfemployment','officeTelephoneNumber','yearsInCompany','occupation','monthlyIncome','authorizedContactPerson',
    'relation','authorizedContactNumber','homeOfficePaperless','preferredModeOfPayment','planType','planMSF','planCombo','planBooster','mandatoryArrowAddon','goSurfBundle',
    'arrowAddon','handset','cashAmount','promoPriceBulletin','valueAddedService','hbp','lockupPeriod','transmittalType','sourceOfSales','applicationMode','dcRemark',
    'salesmanID','salesmanName','agencyName','accountManager','salesChannel','projectPromo','appReceiveSource','stimestamp','typeOfPOID','poidNumber','doculink',
    'leadType','salesAgentName','appDate','gcashGui','eviaFastlane','eplanGscore','deliveryAddress','sadmin','gdfPromoTag','dateCalled','dateCompiled','qualified',
    'deliveryZipCode','andaleArea','portingNumber','projectChamomile','gadgetCareAmount','extraField1','extraField2','extraField3','campaignID','id'
    )
    ->where('deleted', '0')->where('agentNumber', auth()->user()->agentNum)->where(function ($query) {
      $query->where('campaignStatusID', 'like', '%ENDTOEND%');
    });

      $hotLeadList = $hotLeadList->where(function ($query) use ($search) {
        return $query->where('id', 'like', '%' . $search . '%')
          ->orWhere('referenceNumber', 'like', '%' . $search . '%')
          ->orWhere('product', 'like', '%' . $search . '%')
          ->orWhere('campaignID', 'like', '%' . $search . '%')
          ->orWhere('mobileContactNumber', 'like', '%' . $search . '%')
          ->orWhere('firstname', 'like', '%' . $search . '%')
          ->orWhere('lastname', 'like', '%' . $search . '%');
      })
      ->orderBy($tableColumns[$sortIndex], $sortOrder);
    $hotLeadListCount = $hotLeadList->count();
    $hotLeadList = $hotLeadList->offset($offset)
      ->limit($limit)
      ->get();

    $result = [
      'recordsTotal'    => $hotLeadListCount,
      'recordsFiltered' => $hotLeadListCount,
      'data'            => $hotLeadList,
    ];

    // reponse must be in  array
    return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
  }

  public function hotLeadExport(Request $request)
  {
    ini_set('memory_limit','-1');
    ini_set('max_execution_time','0');

    return Excel::download(new HotLeadExport($request->campaignID,$request->search), 
    "Hotlead"."_".Carbon::now()."_".'Export.csv');

  }
}
