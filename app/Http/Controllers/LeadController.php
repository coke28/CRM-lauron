<?php

namespace App\Http\Controllers;

use App\Models\AccountCallHistory;
use App\Models\AccountHistory;
use App\Models\AccountListGlobe;
use App\Models\AuditLog;
use App\Models\CampaignUpload;
use App\Models\Lead;
use App\Models\Status;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Log;

class LeadController extends Controller
{
    //
    public function listLead(Request $request)
    {

        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: *');

        $tableColumns = array(
            'id',
            'product',
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

        $lead = Lead::where('deleted', '0');
        $lead = $lead->where(function ($query) use ($search) {
            return $query->where('product', 'like', '%' . $search . '%')
                ->orWhere('customerName', 'like', '%' . $search . '%')
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

    public function editLeadStatus(Request $request)
    {
        // check if account globe list has request lead

        //code...
        $lead = Lead::where('mobileNumber', $request->mobileNumber)->where('campaignID', $request->campaignID)->where('deleted', '0')->first();
        $accountListGlobe = AccountListGlobe::where('leadsID', $lead->id)->where('deleted', '0')->get()->count();
        if ($accountListGlobe > 0) {
            return json_encode(array(
                'success' => false,
                'message' => 'Lead already has a Globe Account already in use.'
            ));
        }

        $campaignUpload = CampaignUpload::where('campaignID', $request->campaignID)->where('campaignName', $request->campaignName)->where('deleted', '0')->first();
        if (empty($campaignUpload)) {
            return json_encode(array(
                'success' => false,
                'message' => 'Campaign Not Found'
            ));
        }
        //Account List Globe
        $accountListGlobe = new AccountListGlobe();

        //finish status
        $accountListGlobe->agentName = $request->agentName;
        $accountListGlobe->agentNumber = $lead->agent;
        $accountListGlobe->campaignName = $request->campaignName;
        $accountListGlobe->campaignID = $request->campaignID;
        $accountListGlobe->campaignStatus = $request->leadStatus;

        $status = Status::where('statusName', $request->leadStatus)->first();
        $accountListGlobe->campaignStatusID = $status->statusID;


        // $accountListGlobe->dcRemark = $request->reason;
        $accountListGlobe->campaignRemark = $request->remarkStatus;
        $accountListGlobe->leadsID = $lead->id;

        $accountListGlobe->extraField1 = $request->extraField1;
        $accountListGlobe->extraField2 = $request->extraField2;
        $accountListGlobe->extraField3 = $request->extraField3;

        $accountListGlobe->referenceNumber = $request->reference;

        $accountListGlobe->origName = $request->customerName;
        $names = explode(" ", $request->customerName);

        foreach ($names as $name) {
            $name = str_replace(',', ' ', $name);
            preg_replace('/[^A-Za-z0-9\-]/', '', $name);
        }


        if (count($names) == 2) {
            // if just last name and first name
            $accountListGlobe->firstname = $names[0];
            $accountListGlobe->lastname = $names[1];
        } else {
            // If full name
            $accountListGlobe->firstname = $names[0];
            $accountListGlobe->middlename = $names[1];
            $accountListGlobe->lastname = $names[2];
        }

        // $accountListGlobe->lastname = $request->lname;
        // $accountListGlobe->firstname = $request->fname;
        // $accountListGlobe->middlename = $request->mname;

        $accountListGlobe->creditLimit = $request->creditLimit;
        $accountListGlobe->product = $request->product;
        $accountListGlobe->orderNumberUltima = $request->orderNumberUltima;
        $accountListGlobe->transmittalDate = $request->CCandTransmittalDate;

        $accountListGlobe->globeAccount = $request->globeAccountNumber;
        $accountListGlobe->salutation = $request->salutation;

        // installation date 1
        // installation date 2
        // installation date 3
        // applicationType



        $accountListGlobe->doculink1 = $request->docksLink;
        // $accountListGlobe->product = $request->product;
        $accountListGlobe->gender = $request->gender;
        $accountListGlobe->birthday = $request->birthday;
        $accountListGlobe->civilStatus = $request->civilStatus;
        $accountListGlobe->campaignTimestamp = $campaignUpload->campaignDateUploaded;

        //fix name
        // $accountListGlobe->product = $request->lname;
        // $accountListGlobe->product = $request->fname;
        // $accountListGlobe->product = $request->mname;

        $accountListGlobe->motherFirstname = $request->motherFname;
        $accountListGlobe->motherMiddlename = $request->motherMname;
        $accountListGlobe->motherLastname = $request->motherLname;
        $accountListGlobe->NumberOfChildren = $request->numberOfChildren;
        $accountListGlobe->homeOwnership = $request->homeOwnership;
        $accountListGlobe->addHB = $request->houseBuilding;
        $accountListGlobe->addUnit = $request->unitNumber;
        $accountListGlobe->addBuilding = $request->buildingVillage;

        // $accountListGlobe->houseRoomFloor = $request->buildingVillage;
        $accountListGlobe->addStreet = $request->addressStreet;
        $accountListGlobe->addBarangay = $request->addressBarangay;

        $accountListGlobe->addCity = $request->addressCity;
        $accountListGlobe->addProvince = $request->addressProvince;
        $accountListGlobe->addPostal = $request->addressPostal;
        $accountListGlobe->addressRegion = $request->addressRegion;
        $accountListGlobe->addressRemark = $request->addressRemark;
        $accountListGlobe->lengthOfStay = $request->lengthOfStay;
        $accountListGlobe->landlineContact = $request->landlineContactNumber;
        $accountListGlobe->existingMobile = $request->existingMobileNumber;
        $accountListGlobe->mobileContactNumber = $request->mobileNumber;
        $accountListGlobe->email = $request->email;
        $accountListGlobe->tin = $request->tin;
        $accountListGlobe->gsiss = $request->gsiss;
        $accountListGlobe->citizenship = $request->citizenship;

        $accountListGlobe->ifForeignCountry = $request->ifForeign;
        $accountListGlobe->spousename = $request->spouseLname;
        $accountListGlobe->spouseFirstname = $request->spouseFname;
        $accountListGlobe->spouseMiddlename = $request->spouseMname;
        $accountListGlobe->spouseBirthday = $request->birthdaySpouse;
        $accountListGlobe->spouseContactNumber = $request->contactNumberSpouse;
        $accountListGlobe->officeName = $request->officeName;
        $accountListGlobe->officeAddressPostal = $request->officeAddress;
        $accountListGlobe->dateOfemployment = $request->dateOfEmployment;
        $accountListGlobe->officeTelephoneNumber = $request->officeTelephoneNumber;
        $accountListGlobe->yearsInCompany = $request->yearsInCompany;

        $accountListGlobe->occupation = $request->occupation;
        $accountListGlobe->monthlyIncome = $request->monthlyIncome;
        $accountListGlobe->authorizedContactPerson = $request->authorizedContactName;
        $accountListGlobe->authorizedContactNumber = $request->authorizedContact;
        $accountListGlobe->relation = $request->authorizedContactRelation;

        $accountListGlobe->homeOfficePaperless = $request->billingType;


        $accountListGlobe->planType = $request->planType;

        $accountListGlobe->planMSF = $request->planMSF;
        $accountListGlobe->planCombo = $request->combos;
        $accountListGlobe->planBooster = $request->addOnBooster;
        $accountListGlobe->mandatoryArrowAddon = $request->mandatoryBoosterForArrow;
        $accountListGlobe->goSurfBundle = $request->lifestyleBundle;
        $accountListGlobe->arrowAddon = $request->arrowBundleFree;
        $accountListGlobe->handset = $request->handset;

        $accountListGlobe->cashAmount = $request->cashOut;
        $accountListGlobe->promoPriceBulletin = $request->promo;
        $accountListGlobe->paymentMethod = $request->modeOfPayment;
        $accountListGlobe->valueAddedService = $request->valueAddedService;

        $accountListGlobe->hbp = $request->hbp;
        $accountListGlobe->lockupPeriod = $request->LockUpPeriod;
        $accountListGlobe->transmittalType = $request->transmittalType;
        $accountListGlobe->sourceOfSales = $request->sourceOfSales;
        $accountListGlobe->applicationMode = $request->applicationMode;
        $accountListGlobe->remark = $request->remark;
        $accountListGlobe->salesmanID = $request->salesmanID;
        $accountListGlobe->salesmanName = $request->salesmanName;
        $accountListGlobe->agencyName = $request->agencyName;
        $accountListGlobe->accountManager = $request->accountManager;
        $accountListGlobe->salesChannel = $request->salesChannel;
        $accountListGlobe->projectPromo = $request->projectPromo;
        $accountListGlobe->appReceiveSource = $request->appsReceivedSource;
        $accountListGlobe->stimestamp = $request->timestamp;
        $accountListGlobe->typeOfPOID = $request->poidType;
        $accountListGlobe->poidNumber = $request->poidNumber;
        $accountListGlobe->doculink = $request->docksLink;
        $accountListGlobe->leadType = $request->leadType;
        $accountListGlobe->salesAgentName = $request->salesAgentName;
        $accountListGlobe->appDate = $request->applicationDate;
        $accountListGlobe->gcashGui = $request->gcashGUI;

        $accountListGlobe->eviaFastlane = $request->eligablePlansFastlane;
        $accountListGlobe->eplanGscore = $request->eligablePlansGscore;
        $accountListGlobe->deliveryAddress = $request->deliveryAddress;
        $accountListGlobe->sadmin = $request->admin;
        $accountListGlobe->gdfPromoTag = $request->GDFPromo;
        $accountListGlobe->dateCalled = $request->dateCalled;
        $accountListGlobe->dateExtract = $lead->dateExtract;
        $accountListGlobe->dateAdded = $lead->dateAdded;
        $accountListGlobe->timeEdit = $lead->timeEdit;
        $accountListGlobe->dateEdit = $lead->dateEdit;
        $accountListGlobe->globeStatus = $lead->globeStatus;
        $accountListGlobe->globeProduct = $lead->globeProduct;
        $accountListGlobe->documentReference = $lead->documentReference;
        $accountListGlobe->referenceID = $request->referenceID;

        $accountListGlobe->renewalReference = $lead->renewalReference;
        $accountListGlobe->dataPrivacy = $lead->dataPrivacy;
        $accountListGlobe->newsletter = $lead->newsletter;
        $accountListGlobe->prefCallDate = $lead->prefCallDate;
        $accountListGlobe->appTime = $lead->appTime;
        $accountListGlobe->desiredPlan = $lead->desiredPlan;
        $accountListGlobe->salesInquiry = $lead->salesInquiry;

        $accountListGlobe->sourceAttribution = $lead->sourceAttribution;
        $accountListGlobe->dateCompiled = $request->dateCompiled;
        $accountListGlobe->qualified = $request->qualified;
        $accountListGlobe->deliveryZipCode = $request->deliveryZipCode;
        $accountListGlobe->andaleArea = $request->andaleArea;
        $accountListGlobe->projectChamomile = $request->projectChamomile;
        $accountListGlobe->gadgetCareAmount = $request->gadgetCareAmount;
        $accountListGlobe->extraField1 = $request->extraFieldEnd1;
        $accountListGlobe->extraField2 = $request->extraFieldEnd2;
        $accountListGlobe->extraField3 = $request->extraFieldEnd3;

        //Missing Migration fields not in edit lead
        $accountListGlobe->technology = $lead->technology;
        $accountListGlobe->oldPlanDescription = $lead->oldPlan;
        $accountListGlobe->newMpID = $lead->newMpID;
        $accountListGlobe->latlong = $lead->latitude . " " . $lead->longitude;
        $accountListGlobe->duoNumber = $lead->duo;
        $accountListGlobe->shpNumber = $lead->shpNumber;


        //Missing PP fields not in edit lead
        $accountListGlobe->alternateContactNumber = $lead->otherContactNumber;
        $accountListGlobe->cartContent = $lead->cartContent;
        // $accountListGlobe->promoToOffer = $request->promo;


        //Missing Schedule request fields not in edit lead
        $accountListGlobe->allocDate = $lead->allocDate;
        $accountListGlobe->prefCallDate = $lead->prefCallDate;
        $accountListGlobe->dateAdded = $lead->dateAdded;

        //Missing Serviceable fields not in edit lead


        //Missing ASSIST fields not in edit lead

        $accountListGlobe->team = $lead->team;

        //Missing Broadband fields not in edit lead

        $accountListGlobe->leadMonth = $lead->leadMonth;
        $accountListGlobe->appPeriod = $lead->appPeriod;
        $accountListGlobe->dateModified = $lead->dateModified;
        $accountListGlobe->optinAbandon = $lead->optinaBandon;
        $accountListGlobe->cartContent = $lead->cartContent;
        $accountListGlobe->ex1 = $lead->ex1;
        $accountListGlobe->ex2 = $lead->ex2;
        $accountListGlobe->ex3 = $lead->ex3;
        $accountListGlobe->dl = $lead->dl;
        $accountListGlobe->addressOrig = $lead->address;
        $accountListGlobe->portingNumber = $request->portingNumber;
        $accountListGlobe->verifier = "0";
        $accountListGlobe->groupz = $lead->groupz;
        $lead->status = "1";
        $lead->done = "1";

        $lead->referenceNumber = $request->reference;
        $lead->customerName = $request->customerName;
        $lead->creditLimit = $request->creditLimit;
        $lead->product = $request->product;
        $lead->accountNumber = $request->globeAccountNumber;
        $lead->birthday = $request->birthday;
        $lead->lastname = $request->lname;
        $lead->middle = $request->mname;
        $lead->firstname = $request->fname;
        $lead->addHB = $request->houseBuilding;
        $lead->addUnit = $request->unitNumber;
        $lead->addBuilding = $request->buildingVillage;
        $lead->addStreet = $request->addressStreet;
        $lead->addBarangay = $request->addressBarangay;
        $lead->addCity = $request->addressCity;
        $lead->addProvince = $request->addressProvince;
        $lead->addPostal = $request->addressPostal;
        $lead->addressRegion = $request->addressRegion;
        $lead->addressRemark = $request->addressRemark;
        $lead->landline = $request->landlineContactNumber;
        $lead->otherContactNumber = $request->existingMobileNumber;
        $lead->mobileNumber = $request->mobileNumber;
        $lead->email = $request->email;
        $lead->referenceID = $request->referenceID;
        $lead->agent = $request->agentName;

        $lead->promoToOffer = $request->promo;
        $lead->remark = $request->remark;
        $lead->callRemark = $request->remarkStatus;
        $lead->callDispo = $request->leadStatus;
        $lead->projectType = $request->projectPromo;
        $lead->leadType = $request->leadType;
        $lead->appDate = $request->applicationDate;
        $lead->port = $request->portingNumber;



        $accountListGlobe->save();
        // $accountCallHistory->save();
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
        Log::info($request->statusFilter);





        // ordering
        $sortIndex = 0;
        $sortOrder = 'desc';
        if (isset($request->order) && isset($request->order[0]) && isset($request->order[0]['column'])) {
            $sortIndex = $request->order[0]['column'];
        }
        if (isset($request->order) && isset($request->order[0]) && isset($request->order[0]['dir'])) {
            $sortOrder = $request->order[0]['dir'];
        }

        //                 SELECT 
        // id,
        // campaignName,
        // campaignID,
        // COUNT(campaignName) AS "Total Leads",
        // SUM(called) AS "Called" ,
        // SUM(case called when '0' then 1 else null end) AS "Not Called" 
        // FROM `leads` 
        // GROUP BY campaignName,campaignID;





        if ($statusFilter == "2") {
            $lead = DB::table('leads')
                ->select('campaignName', 'campaignID', 'status')
                ->selectRaw("COUNT(campaignName) AS 'totalLeads'")
                ->selectRaw("SUM(case called when '0' then 1 else null end) AS 'notCalled'")
                ->selectRaw("SUM(called) AS 'called'")
                ->where('deleted', '0')
                ->groupBy('campaignName', 'campaignID', 'status')
                ->orderBy($tableColumns[$sortIndex], $sortOrder);
        } else {
            $lead = DB::table('leads')
                ->select('campaignName', 'campaignID', 'status')
                ->selectRaw("COUNT(campaignName) AS 'totalLeads'")
                ->selectRaw("SUM(case called when '0' then 1 else null end) AS 'notCalled'")
                ->selectRaw("SUM(called) AS 'called'")
                ->where('deleted', '0')
                ->groupBy('campaignName', 'campaignID', 'status');

            // $lead = $lead->where(function ($query) use ($search) {
            //     return $query->where('status', 'like', '%' . $search . '%');
            // })
            $lead = $lead->where(function ($query) use ($search) {
                return $query->where('id', 'like', '%' . $search . '%')
                    ->orWhere('campaignName', 'like', '%' . $search . '%')
                    ->orWhere('campaignID', 'like', '%' . $search . '%');
                    // ->orWhere('status', 'like', '%' . $search . '%');
            })
                ->where('status', 'like', '%' . $statusFilter . '%')
                ->orderBy($tableColumns[$sortIndex], $sortOrder);
        }
        // $lead = DB::table('leads')
        // ->select('campaignName', 'campaignID','status')
        // ->selectRaw("COUNT(campaignName) AS 'totalLeads'")
        // ->selectRaw("SUM(case called when '0' then 1 else null end) AS 'notCalled'")
        // ->selectRaw("SUM(called) AS 'called'")
        // ->groupBy('campaignName','campaignID','status');
        // $lead = $lead->where(function ($query) use ($search) {
        //     return $query->where('status', '=', '%' . $search . '%');

        // })
        // ->orderBy($tableColumns[$sortIndex], $sortOrder);



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


        $deleteLeads = Lead::where('campaignName', $request->campaignName)->where('campaignID', $request->campaignID)->get();
        $deleteCampaignUpload = CampaignUpload::where('campaignName', $request->campaignName)->where('campaignID', $request->campaignID)->where('deleted', '0')->first();


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

    public function listCampaignDashboard(Request $request)
    {
        $campaign = DB::table('leads')
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

    public function listManualCall(Request $request)
    {

        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: *');

        $tableColumns = array(
            'id',
            'product',
            'customerName',
            'mobileNumber',
            'campaignID',
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

        $manualCall = Lead::where('done', '0')->where('agent', auth()->user()->agentNum)->where('deleted', '0');
        $manualCall = $manualCall->where(function ($query) use ($search) {
            return $query->where('id', 'like', '%' . $search . '%')
                ->orWhere('product', 'like', '%' . $search . '%')
                ->orWhere('customerName', 'like', '%' . $search . '%')
                ->orWhere('mobileNumber', 'like', '%' . $search . '%')
                ->orWhere('campaignID', 'like', '%' . $search . '%')
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
}
