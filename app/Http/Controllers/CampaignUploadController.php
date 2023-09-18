<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\CampaignUpload;
use App\Models\LauronLead;
use App\Models\Lead;
use App\Models\RejectedLeads;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use ParagonIE\Sodium\Core\Curve25519\Ge\P2;
use stdClass;
use Throwable;

class CampaignUploadController extends Controller
{
    //
    public function mainFunction(Request $request)
    {
        //check if campaign is already uploaded
        $campaignUpload = CampaignUpload::where('campaignID', $request->campaignID)->where('deleted', '0')->get()->count();
        // ->where('deleted', '0')
        if ($campaignUpload > 0) {
            return json_encode(array(
                'success' => false,
                'message' => 'Campaign ID already in-use.'
            ));
        }

        $campaignUpload = new CampaignUpload();
        $campaignUpload->campaignName = $request->campaignName;
        $campaignUpload->campaignDateUploaded = $request->campaignTimestamp;
        $campaignUpload->campaignID = $request->campaignID;
        $campaignUpload->leadUserAssignment =$request->leadsUserAssignment;
        $campaignUpload->campaignUploader = auth()->user()->id;
        // $autoDialMode = $request->autoDialMode;
        // switch ($request->selectScheduleType) {
        //     case "NOW":
        //         // code block
        //         $currentDate = $request->campaignTimestamp;
        //         $currentDate = date("Y-m-d");
        //         $campaignUpload->schedule = $currentDate;
        //         $sched_type = "0";
        //         break;
        //     case "SCHEDULED":
        //         // code block
        //         if (!empty($request->date)) {

        //             $campaignUpload->schedule = $request->date;
        //             $sched_type = "1";
        //         }

        //         break;
        //     default:
        //         // code block
        // }
        $campaignUpload->schedule = "";
        $campaignUpload->schedType = "0";
        //validate and read uploaded file 
       
        $fileContents = $this->uploadContent($request);
        //Remove first element of array/headers
        // array_shift($fileContents);
        // array_shift($fileContents);
        

        //switch case to check lead user assignment selection
        switch ($request->leadsUserAssignment) {
            case 'Same as Product':
                # code...
                $users = User::where('product', $request->campaignName)->where('level', '0')->where('deleted', '0')->get();
                $usersCount = $users->count();
                break;

            case 'By Agent':
                # code...
                $users = "";
                $usersCount = 0;
                break;

            case 'Distributed to User Base on Lead Count':
                # code...

                $users = User::where('level', '0')->where('deleted', '0')->get();
                $usersCount = $users->count();
                break;

            default:
                # code...

                break;
        }
         //process file contents
        $currentAgent = 0;
   
        $validLeads = [];
        // $rejectedData = [];
        $leadsToInsert = [];
        $totalLeads = 0;
        // $totalRejectedLeads = 0;
        // $totalDuplicateLeads = 0;
        // $totalDifferentCampaigns = 0;

        foreach ($fileContents as $dataRow) {
            
            $port = rand(1, 9);
            $uniquecode = $this->generate_uuid();

            if ($usersCount == $currentAgent) {
                $currentAgent = 0;
            }
            try {
                //code...
                if (!empty($users)) {
                    $agent = $users[$currentAgent]['agentNum'];
                } else {
                    $agent = "";
                }
            } catch (\Throwable $th) {
                //throw $th;
                return json_encode(array(
                    'success' => false,
                    'message' => "No suitable agents found to distribute lead/'s to"
                ));
            }
            
          
       

            if (!empty($fileContents)) {
                switch ($request->campaignName) {
                    // CASES HERE ARE FOR GLOBE SPECIFIC
                    case 'POSTPAID':
                    case 'BROADBAND':
                    case 'POSTPAID-LUKEWARM':

                        try {
                            //code...
                          
                            $lead = new Lead();
                            $lead->mobileNumber = $dataRow["0"];
                            $lead->leadType = $dataRow["1"];
                            $lead->referenceNumber = $dataRow["2"];
                            $lead->financialAccountID = $dataRow["3"];
                            $lead->otherContactNumber = $dataRow["4"];
                            $lead->firstname = $dataRow["5"];
                            $lead->lastname = $dataRow["6"];
                            $lead->middle = $dataRow["7"];
                            $lead->customerName = $dataRow["8"];
                            $lead->addHB = $dataRow["9"];
                            $lead->addUnit = $dataRow["10"];
                            $lead->addBuilding = $dataRow["11"];
                            $lead->addStreet = $dataRow["12"];
                            $lead->addBarangay = $dataRow["13"];
                            $lead->addCity = $dataRow["14"];
                            $lead->addProvince = $dataRow["15"];
                            $lead->addPostal = $dataRow["16"];
                            $lead->addressRegion = $dataRow["17"];
                            $lead->creditLimit = $dataRow["18"];
                            $lead->cartContent = $dataRow["19"];
                            $lead->promoToOffer = $dataRow["20"];
                            $lead->projectType = $dataRow["21"];
                            $lead->email = $dataRow["22"];
                            $lead->campaignName = $request->campaignName;
                            $lead->campaignID = $request->campaignID;
                            $lead->dateUpload = $request->campaignTimestamp;
                            $lead->uniqueCode = $uniquecode;
                            $lead->port = $port;
                            // $lead->context = $context;
                            $lead->product = $request->campaignName;
                            $lead->agent = $agent;
                            $lead->groupz = $request->campaignGroup;
                            // $lead->autodial = $request->autoDialMode;
                            $lead->ex1 = $dataRow["23"];
                            $lead->ex2 = $dataRow["24"];
                            $lead->ex3 = $dataRow["25"];
                            $lead->save();
                        } catch (\Throwable $th) {
                            return json_encode(array(
                                'success' => false,
                                'message' => "Error file column number mismatch. Please double check if the right campaign is selected or if the file has incomplete columns"
                            ));
                        }
                        break;

                    case 'MIGRATION':

                        try {
                            //code...
                        
                            $lead = new Lead();
                            $lead->mobileNumber = $dataRow["0"];
                            $lead->accountNumber = $dataRow["1"];
                            $lead->email = $dataRow["2"];
                            $lead->customerName = $dataRow["3"];
                            $lead->address = $dataRow["4"];
                            $lead->technology = $dataRow["5"];
                            $lead->oldPlan = $dataRow["6"];
                            $lead->newMpID = $dataRow["7"];
                            $lead->latitude = $dataRow["8"];
                            $lead->longitude = $dataRow["9"];
                            $lead->duo = $dataRow["10"];
                            $lead->shpNumber = $dataRow["11"];
                            $lead->campaignName = $request->campaignName;
                            $lead->campaignID = $request->campaignID;
                            $lead->dateUpload = $request->campaignTimestamp;
                            $lead->uniqueCode = $uniquecode;
                            $lead->port = $port;
                            $lead->context = "";
                            $lead->product = $request->campaignName;
                            $lead->agent = $agent;
                            $lead->groupz = $request->campaignGroup;
                            $lead->autodial = $request->autoDialMode;
                            $lead->ex1 = $dataRow["12"];
                            $lead->ex2 = $dataRow["13"];
                            $lead->ex3 = $dataRow["14"];
                            $lead->save();
                        } catch (Throwable $e) {
                            //throw $th;
                            return json_encode(array(
                                'success' => false,
                                'message' =>"Error file column number mismatch. Please double check if the right campaign is selected or if the file has incomplete columns"
                            ));
                        }
                        break;
                    case 'BROADBAND-LUKEWARM':
                        try {
                            //code...
                            $lead = new Lead();
                            $lead->allocDate = $dataRow["0"];
                            $lead->leadMonth = $dataRow["1"];
                            $lead->globeProduct = $dataRow["2"];
                            $lead->customerName = $dataRow["3"];
                            $lead->appPeriod = $dataRow["4"];
                            $lead->appDate = $dataRow["5"];
                            $lead->referenceNumber = $dataRow["6"];
                            $lead->dateModified = $dataRow["7"];
                            $lead->firstname = $dataRow["8"];
                            $lead->middle = $dataRow["9"];
                            $lead->lastname = $dataRow["10"];
                            $lead->email = $dataRow["11"];
                            $lead->mobileNumber = $dataRow["12"];
                            $lead->optinaBandon = $dataRow["13"];
                            $lead->cartContent = $dataRow["14"];
                            $lead->campaignName = $request->campaignName;
                            $lead->campaignID = $request->campaignID;
                            $lead->dateUpload = $request->campaignTimestamp;;
                            $lead->uniqueCode = $uniquecode;
                            $lead->port = $port;
                            $lead->context = "";
                            $lead->product = $request->campaignName;
                            $lead->agent = $agent;
                            $lead->groupz = $request->campaignGroup;
                            $lead->autodial = $request->autoDialMode;
                            $lead->ex1 = $dataRow["15"];
                            $lead->ex2 = $dataRow["16"];
                            $lead->ex3 = $dataRow["17"];
                         
                            $lead->save();
                        } catch (Throwable $e) {
                            //throw $th;
                            return json_encode(array(
                                'success' => false,
                                'message' => "Error file column number mismatch. Please double check if the right campaign is selected or if the file has incomplete columns"
                            ));
                        }
                        break;
                    case 'BROADBAND-NOTSERVICEABLE':

                        try {
                            //code...
                            $lead = new Lead();

                            $lead->dateExtract = $dataRow["0"];
                            $lead->dateEdit = $dataRow["1"];
                            $lead->timeEdit = $dataRow["2"];
                            $lead->referenceID = $dataRow["3"];
                            $lead->customerName = $dataRow["4"];
                            $lead->email = $dataRow["5"];
                            $lead->address = $dataRow["6"];
                            $lead->addCity = $dataRow["7"];
                            $lead->addProvince = $dataRow["8"];
                            $lead->mobileNumber = $dataRow["9"];
                            $lead->desiredPlan = $dataRow["10"];
                            $lead->dateAdded = $dataRow["11"];
                            $lead->globeStatus = $dataRow["12"];
                            $lead->sourceAttribution = $dataRow["13"];
                            $lead->documentReference = $dataRow["14"];

                            $lead->campaignName = $request->campaignName;
                            $lead->campaignID = $request->campaignID;
                            $lead->dateUpload = $request->campaignTimestamp;
                            $lead->uniqueCode = $uniquecode;
                            $lead->port = $port;
                            $lead->context = "";
                            $lead->product = $request->campaignName;
                            $lead->agent = $agent;
                            $lead->groupz = $request->campaignGroup;
                            $lead->autodial = $request->autoDialMode;

                            $lead->ex1 = $dataRow["15"];
                            $lead->ex2 = $dataRow["16"];
                            $lead->ex3 = $dataRow["17"];
                            $lead->save();
                        } catch (Throwable $e) {
                            //throw $th;
                            return json_encode(array(
                                'success' => false,
                                'message' => "Error file column number mismatch. Please double check if the right campaign is selected or if the file has incomplete columns"
                            ));
                        }
                        break;
                    case 'BROADBAND-ASSIST':

                        try {
                            //code...
                            $lead = new Lead();

                            $lead->dateExtract = $dataRow["0"];
                            $lead->dateEdit = $dataRow["1"];
                            $lead->timeEdit = $dataRow["2"];
                            $lead->referenceID = $dataRow["3"];
                            $lead->customerName = $dataRow["4"];
                            $lead->mobileNumber = $dataRow["5"];
                            $lead->email = $dataRow["6"];
                            $lead->globeProduct = $dataRow["7"];
                            $lead->salesInquiry = $dataRow["8"];
                            $lead->renewalReference = $dataRow["9"];
                            $lead->dataPrivacy = $dataRow["10"];
                            $lead->newsletter = $dataRow["11"];
                            $lead->dateAdded = $dataRow["12"];
                            $lead->globeStatus = $dataRow["13"];
                            $lead->sourceAttribution = $dataRow["14"];
                            $lead->documentReference = $dataRow["15"];
                            $lead->team = $dataRow["16"];

                            $lead->campaignName = $request->campaignName;
                            $lead->campaignID = $request->campaignID;
                            $lead->dateUpload = $request->campaignTimestamp;
                            $lead->uniqueCode = $uniquecode;
                            $lead->port = $port;
                            $lead->context = "";
                            $lead->product = $request->campaignName;
                            $lead->agent = $agent;
                            $lead->groupz = $request->campaignGroup;
                            $lead->autodial = $request->autoDialMode;

                            $lead->ex1 = $dataRow["17"];
                            $lead->ex2 = $dataRow["18"];
                            $lead->ex3 = $dataRow["19"];
                            $lead->save();
                        } catch (Throwable $e) {
                            //throw $th;
                            return json_encode(array(
                                'success' => false,
                                'message' => "Error file column number mismatch. Please double check if the right campaign is selected or if the file has incomplete columns"
                            ));
                        }
                        break;
                    case 'BROADBAND-SCHEDULEREQUEST':
                        try {
                            //code...
                            $lead = new Lead();
                            $lead->referenceNumber = $dataRow["0"];
                            $lead->allocDate = $dataRow["1"];
                            $lead->appDate = $dataRow["2"];
                            $lead->appTime = $dataRow["3"];
                            $lead->referenceID = $dataRow["4"];
                            $lead->globeProduct = $dataRow["5"];
                            $lead->customerName = $dataRow["6"];
                            $lead->mobileNumber = $dataRow["7"];
                            $lead->email = $dataRow["8"];
                            $lead->address = $dataRow["9"];
                            $lead->addBarangay = $dataRow["10"];
                            $lead->addCity = $dataRow["11"];
                            $lead->addProvince = $dataRow["12"];
                            $lead->prefCallDate = $dataRow["13"];
                            $lead->dateAdded = $dataRow["14"];
                            $lead->globeStatus = $dataRow["15"];
                            $lead->sourceAttribution = $dataRow["16"];
                            $lead->documentReference = $dataRow["17"];

                            $lead->campaignName = $request->campaignName;
                            $lead->campaignID = $request->campaignID;
                            $lead->dateUpload = $request->campaignTimestamp;
                            $lead->uniqueCode = $uniquecode;
                            $lead->port = $port;
                            $lead->context = "";
                            $lead->product = $request->campaignName;
                            $lead->agent = $agent;

                            $lead->groupz = $request->campaignGroup;
                            $lead->autodial = $request->autoDialMode;

                            $lead->ex1 = $dataRow["18"];
                            $lead->ex2 = $dataRow["19"];
                            $lead->ex3 = $dataRow["20"];

                           
                            $lead->save();
                        } catch (Throwable $e) {
                            //throw $th;
                            return json_encode(array(
                                'success' => false,
                                'message' => "Error file column number mismatch. Please double check if the right campaign is selected or if the file has incomplete columns"
                            ));
                        }



                        break;


                    default:
                        # code...
                        // THIS CODE WILL RUN FOR LAURON CAMPAIGNS
                        try {
                            if($request->campaignName != $dataRow["0"]){
                                // $string = str_replace($dataRow["0"], '<b>'.$dataRow["0"].'</b>', $dataRow["0"]);
                                // $original_string = 'Upload failed. Lead with wrong campaign detected. 
                                // Campaign detected: '.$dataRow["0"].' 
                                // Current campaign filter selected: '.$request->campaignName;
                                // $return_string = wrap_text_with_tags( $original_string , 'PHP' , "<strong>" ,"</strong>");
                                // $string = "This is some text that needs to be bold.";
                                // $bold_string = str_replace("bold", html_entity_decode("&lt;strong&gt;bold&lt;/strong&gt;"), $dataRow["0"]);
                                return json_encode(array(
                                    'success' => false,
                                    'message' => 'Upload failed. Lead with wrong campaign detected. 
                                    Campaign detected: '.$dataRow["0"].' 
                                    Current campaign filter selected: '.$request->campaignName
                                ));
                                // $rejectedData[] = $dataRow;
                                // $totalDifferentCampaigns++;
                                // continue 2;
                            }
                            $lead = new LauronLead();
                            $lead->campaignName = $dataRow["0"];
                            $lead->segment = $dataRow["1"];
                            $lead->endoDate = $dataRow["2"];
                            $lead->pullOutDate = $dataRow["3"];
                            $lead->writeOffDate = $dataRow["4"];
                            $lead->activationDate = $dataRow["5"];
                            $lead->accountNumber = $dataRow["6"];
                            $lead->lastname = $dataRow["7"];
                            $lead->firstname = $dataRow["8"];
                            $lead->middlename = $dataRow["9"];
                            $lead->originalBalance = $dataRow["10"];
                            $lead->principalBalance = $dataRow["11"];
                            $lead->penalties = $dataRow["12"];
                            $lead->totalAmountDue = $dataRow["13"];
                            $lead->lastPaymentDate = $dataRow["14"];
                            $lead->lastPaymentAmount = $dataRow["15"];
                            $lead->dateOfBirth = $dataRow["16"];
                            $lead->civilStatus = $dataRow["17"];
                            $lead->motherMaidenname = $dataRow["18"];
                            $lead->autoloanCarInfo = $dataRow["19"];
                            $lead->homeAddress = $dataRow["20"];
                            $lead->companyName = $dataRow["21"];
                            $lead->CEAddressBusinessAddress = $dataRow["22"];
                            $lead->otherAddress1 = $dataRow["23"];
                            $lead->otherAddress2 = $dataRow["24"];
                            $lead->emailAddress = $dataRow["25"];

                            $lead->homeNumber = $dataRow["27"];
                            $lead->officeNumber = $dataRow["28"];
                            $lead->otherContact1 = $dataRow["29"];
                            $lead->otherContact2 = $dataRow["30"];
                            $lead->otherContact3 = $dataRow["31"];
                            
                            $lead->customerName = $lead->firstname." ".$lead->middlename." ".$lead->lastname;
                            $lead->campaignID = $request->campaignID;
                            $lead->dateUpload = $request->campaignTimestamp;
                            $lead->uniqueCode = $uniquecode;
                            $lead->port = $port;
                            $lead->context = "";
                            if($request->leadsUserAssignment == "By Agent"){
                                if(User::where('agentNum',$dataRow["32"])->where('deleted','0')->count() == 0){
                                    return json_encode(array(
                                        'success' => false,
                                        'message' => 'Upload failed. Agent number does not exist: '.$dataRow["32"]
                                    ));
                                }

                                $lead->agent = $dataRow["32"];
                            }else{
                                $lead->agent = $agent;
                            }
                           
                            $lead->groupz = $request->campaignGroup;
                            // $lead->autodial = $request->autoDialMode;

                            if (isset($dataRow["26"]) && !empty($dataRow["26"])){
                                if (preg_match('/(^0?9[0-9]{9}$)|(^\+?639[0-9]{9}$)|(^[0-9]{5,12}$)/', $dataRow['26']) == 0){
                                    
                                    return json_encode(array(
                                        'success' => false,
                                        'message' => 'Upload failed. Lead with invalid mobile number detected 
                                        Invalid mobile number detected: '.$dataRow["26"]
                                    ));
                                    // $rejectedData[] = $dataRow;
                                    // $totalRejectedLeads++;
                                    // continue 2;
                                }

                                if(preg_match('/^(09)\d{9}$/', $dataRow['26'] == 0)){
                                    
                                    return json_encode(array(
                                        'success' => false,
                                        'message' => 'Upload failed. Lead with invalid mobile number detected 
                                        Invalid mobile number detected: '.$dataRow["26"]
                                    ));
                                    // $rejectedData[] = $dataRow;
                                    // $totalRejectedLeads++;
                                    // continue 2;

                                }
                            }
                            $number = $dataRow["26"];
                            if (stripos($number, '+630') === 0){
                                $number = substr_replace($number, '0', 0, 4);
                            }
                            else if (stripos($number, '+63') === 0){
                                $number = substr_replace($number, '0', 0, 3);
                            }
                            else if (stripos($number, '630') === 0){
                                $number = substr_replace($number, '0', 0, 3);
                            }
                            else if (stripos($number, '63') === 0){
                                $number = substr_replace($number, '0', 0, 2);
                            }
                            else if (stripos($number, '9') === 0){
                                $number = substr_replace($number, '0', 0, 0);
                            }

                            $lead->mobileNumber =  $number;
                            
                            if(strlen($number) != 11){
                                return json_encode(array(
                                    'success' => false,
                                    'message' => 'Upload failed. Lead with invalid mobile number detected 
                                    Invalid mobile number detected: '.$dataRow["26"]
                                ));
                                // $rejectedData[] = $dataRow;
                                // $totalRejectedLeads++;
                                // continue 2;

                            }
                            $leadToCheck = $dataRow["0"]."%".$number."%".$dataRow["6"];
                            if(in_array($leadToCheck,$validLeads)){

                                return json_encode(array(
                                    'success' => false,
                                    'message' => 'Upload failed. Leads with duplicate mobile number. 
                                    Duplicate mobile number detected: '.$dataRow["26"].'
                                    Duplicate account number detected: '.$dataRow["6"]
                                ));
                                // $rejectedData[] = $dataRow;
                                // $totalDuplicateLeads++;
                                // continue 2;
                            }
                            // Check lead if existing in database
                            // $checkLead = LauronLead::where('campaignName',$dataRow["0"])
                            // ->where('mobileNumber',$number)->where('deleted',0)->count();

                            // if($checkLead > 0){
                            //     // Add lead to rejected leads
                            //     $rejectedData[] = $dataRow;
                            //     $totalDuplicateLeads++;
                            //     continue 2;
                            // }
                           
                            // $lead->save();
                            $lead = $lead->attributesToArray();
                            foreach($lead as $key => $value ){
                                $lead[$key] = utf8_encode($value);
                            }
                            $leadsToInsert[] = $lead;
                            $validLeads[] = $dataRow["0"]."%".$number."%".$dataRow["6"];
                            $totalLeads++;

                        } catch (Throwable $e) {
                            //throw $th;
                            // dd($e);
                            return json_encode(array(
                                'success' => false,
                                'message' =>"Error file column number mismatch. Please confirm if the file used is correct and/or correct lead assignment is used."
                            ));
                        }
                        
                        break;
                }

         
            }
            $currentAgent++;
        }
      

        // if(!empty($rejectedData)){
        //     $rejectedLead = new RejectedLeads();
        //     $rejectedLead->action = "Upload File";
        //     $rejectedLead->campaignID = $request->campaignID;
        //     $file = $request->file('file');
        //     $filename = $file->getClientOriginalName();
        //     $rejectedLead->fileName = $filename;
        //     $temp = array(
        //         "Total uploaded leads"=> $totalLeads,
        //         "Total rejected leads"=> $totalRejectedLeads,
        //         "Total Duplicate Leads"=> $totalDuplicateLeads,
        //         "Total leads with different campaigns"=> $totalDifferentCampaigns,
        //         "Rejected raw data"=> $rejectedData,
        //     );

           
        //     $rejectedLead->otherdata = json_encode($temp);
        //     $rejectedLead->save();

          
        //     if($totalLeads == 0){
        //         return json_encode(array(
        //             'success' => false,
        //             'message' => 'Upload failed. 
        //             Total leads with invalid mobile numbers: '.$totalRejectedLeads.
        //             ', Total leads with different campaigns: '.$totalDifferentCampaigns.
        //             ', Total leads with duplicate mobile # and campaign ID in file: '.$totalDuplicateLeads
        //         ));

        //     }
        //     $campaignUpload->save();
        //     //Save to log
        //     $auditLog = new AuditLog();
        //     $auditLog->agent = auth()->user()->id;
        //     $auditLog->action = "Uploaded Campaign";
        //     $auditLog->table = "campaignUpload";
        //     $auditLog->nID = $campaignUpload->id." | ".$request->campaignName ." | ".$request->campaignTimestamp." 
        //     | ".$request->campaignID." | ".auth()->user()->id." | ". $request->leadsUserAssignment. " | ".$request->autoDialMode." | ".$request->selectScheduleType." | ". $campaignUpload->schedule; 
        //     $auditLog->ip = \Request::ip();
        //     $auditLog->save();
        //     return json_encode(array(
        //         'success' => true,
        //         'message' => 'Invalid mobile numbers detected. Some leads were uploaded unsuccessfully.'
        //     ));

        // }
        // !!!!!!!!! DONT FORGET TO UNCOMMENT LATER !!!!!!!!!!!!
        $chunks = array_chunk($leadsToInsert, 500);

        foreach ($chunks as $chunk) {
            LauronLead::insert($chunk);
            // Do insert here
        }
        $campaignUpload->save();
        //Save to log
        $auditLog = new AuditLog();
        $auditLog->agent = auth()->user()->id;
        $auditLog->action = "Uploaded Campaign";
        $auditLog->table = "campaignUpload";
        $auditLog->nID = $campaignUpload->id." | ".$request->campaignName ." | ".$request->campaignTimestamp." 
        | ".$request->campaignID." | ".auth()->user()->id." | ". $request->leadsUserAssignment. " | ".$request->autoDialMode." | ".$request->selectScheduleType." | ". $campaignUpload->schedule; 
        $auditLog->ip = \Request::ip();
        $auditLog->save();
        return json_encode(array(
            'success' => true,
            'message' => $totalLeads.' Leads Uploaded successfully.'
        ));
    }

    // function wrap_text_with_tags( $haystack, $needle , $beginning_tag, $end_tag ) {
    //     $needle_start = stripos($haystack, $needle);
    //     $needle_end = $needle_start + strlen($needle);
    //     $return_string = substr($haystack, 0, $needle_start) . $beginning_tag . $needle . $end_tag . substr($haystack, $needle_end);
    //     return $return_string;
    // }

    public function uploadContent($request)
    {

        $file = $request->file('file');
        if ($file) {
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension(); //Get extension of uploaded file
            $tempPath = $file->getRealPath();
            $fileSize = $file->getSize(); //Get size of uploaded file in bytes
            //Check for file extension and size
            $this->checkUploadedFileProperties($extension, $fileSize);
          
            // $importData_arr = Excel::toArray(new stdClass(), $request->file('file'));
              //Where uploaded file will be stored on the server 
            $location = 'storage/files/'; //Created an "uploads" folder for that
            // Upload file
            $file->move($location, $filename);
            // In case the uploaded file path is to be stored in the database 
            $filepath = public_path($location . "/" . $filename);
            // Reading file
            $file = fopen($filepath, "r");
            $importData_arr = array(); // Read through the file and store the contents as an array
            $i = 0;
            //Read the contents of the uploaded file 
            while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
                $num = count($filedata);
                // Skip first row (Remove below comment if you want to skip the first row)
                if ($i == 0) {
                    $i++;
                    continue;
                }
                for ($c = 0; $c < $num; $c++) {
                    $importData_arr[$i][] = $filedata[$c];
                }
                $i++;
            }
            fclose($file); //Close after reading
            return $importData_arr;
        } else {
            //no file was uploaded
            // throw new \Exception('No file was uploaded', Response::HTTP_BAD_REQUEST);
            return json_encode(array(
                'success' => false,
                'message' => 'No file was uploaded.'
            ));
        }
    }
    public function checkUploadedFileProperties($extension, $fileSize)
    {
        // $valid_extension = array("csv", "xlsx"); //Only want csv and excel files
        $valid_extension = array("xlsx");
        $maxFileSize = 2097152; // Uploaded file size limit is 2mb
        if (in_array(strtolower($extension), $valid_extension)) {
            // if ($fileSize <= $maxFileSize) {
            if ($fileSize <= $maxFileSize) {
            } else {
                // throw new \Exception('No file size too large ', Response::HTTP_REQUEST_ENTITY_TOO_LARGE); //413 error
                return json_encode(array(
                    'success' => false,
                    'message' => 'file size too large. File size limit = 2MB'
                ));
            }
        } else {
            // throw new \Exception('Invalid file extension', Response::HTTP_UNSUPPORTED_MEDIA_TYPE); //415 error
            return json_encode(array(
                'success' => false,
                'message' => 'Invalid file extension.'
            ));
        }
    }

    function generate_uuid()
    {
        return sprintf(
            '%04x%04x%04x%04x%04x%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0C2f) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0x2Aff),
            mt_rand(0, 0xffD3),
            mt_rand(0, 0xff4B)
        );
    }

    public function listCampaignUpload(Request $request)
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: *');

        $tableColumns = array(
            'id',
            'campaignID',
            'campaignName',
            'campaignUploader',
            'leadUserAssignment',
            'campaignDateUploaded',
            'schedType',
            'schedule'
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

        $campaignUpload = CampaignUpload::where('deleted', '0');
        $campaignUpload = $campaignUpload->where(function ($query) use ($search) {
            return $query->where('id', 'like', '%' . $search . '%')
            ->orWhere('campaignID', 'like', '%' . $search . '%')
            ->orWhere('campaignName', 'like', '%' . $search . '%')
            ->orWhere('campaignUploader', 'like', '%' . $search . '%')
            ->orWhere('leadUserAssignment', 'like', '%' . $search . '%')
            ->orWhere('campaignDateUploaded', 'like', '%' . $search . '%')
            ->orWhere('schedType', 'like', '%' . $search . '%')
            ->orWhere('schedule', 'like', '%' . $search . '%');
        })
            ->orderBy($tableColumns[$sortIndex], $sortOrder);
        $campaignUploadCount = $campaignUpload->count();
        $campaignUpload = $campaignUpload->offset($offset)
            ->limit($limit)
            ->get();


        $result = [
            'recordsTotal'    => $campaignUploadCount,
            'recordsFiltered' => $campaignUploadCount,
            'data'            => $campaignUpload,
        ];

        // reponse must be in  array
        return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function deleteCampaignUpload(Request $request)
    {
    //   $deleteLeads = LauronLead::where('campaignName', $request->campaignName)->where('campaignID', $request->campaignID)->get();
    //   $deleteCampaignUpload = CampaignUpload::where('id', $request->id)->first();
    
        $deleteLeads = LauronLead::where('campaignName', $request->campaignName)->where('campaignID', $request->campaignID)->get();
        $deleteCampaignUpload = CampaignUpload::where('campaignID', $request->campaignID)->where('deleted', '0')->first();
  
      if ($deleteCampaignUpload) {

        foreach ($deleteLeads as $deleteLead) {

            $deleteLead->deleted = 1;
            $deleteLead->save();
        }
  
  
        $deleteCampaignUpload->deleted = 1;
        $deleteCampaignUpload->save();
  
        $auditLog = new AuditLog();
        $auditLog->agent = auth()->user()->id;
        $auditLog->action = "Deleted ID #"." $deleteCampaignUpload->id "."Campaign";
        $auditLog->table = "campaignUpload";
        $auditLog->nID = "Deleted =".$deleteCampaignUpload->deleted; 
        $auditLog->ip = \Request::ip();
        $auditLog->save();
  
        return 'Campaign Upload deleted successfully.';
      } else {
  
        return 'Campaign Upload deleted unsuccessfully.';
      }
    }
}
