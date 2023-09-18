<?php

namespace App\Exports;

use App\Models\AccountListGlobe;
use App\Models\Lead;
use Carbon\Carbon;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    use Exportable;

    public function __construct(
        $campaignName,
        $campaignID,
        $reportType,
        $filterType,
        $group,
        $dateType,
        $startDate,
        $endDate,
        $ahtday,
        $ahtweek,
        $ahtmonth
    ) {
        // $this->campaignName = $request->campaignName;
        $this->campaignName = $campaignName;
        $this->campaignID = $campaignID;
        $this->reportType = $reportType;
        $this->filterType = $filterType;
        $this->group = $group;
        $this->dateType = $dateType;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->ahtday = $ahtday;
        $this->ahtweek = $ahtweek;
        $this->ahtmonth = $ahtmonth;
    }
    public function collection()
    {
        // return AccountListGlobe::all();
        $query = "";
        $parsedCampaignName = $this->GetCampaignName($this->campaignName);
        switch ($this->reportType) {
            case 'CampaignExtract':
                # code...
                $query = DB::table('leads')
                    ->select(
                        'mobileNumber',
                        'leadType',
                        'referenceNumber',
                        'financialAccountID',
                        'otherContactNumber',
                        'firstname',
                        'lastname',
                        'middle',
                        'addHB',
                        'addUnit',
                        'addBuilding',
                        'addStreet',
                        'addBarangay',
                        'addCity',
                        'addProvince',
                        'addPostal',
                        'addressRegion',
                        'creditLimit',
                        'cartContent',
                        'promoToOffer',
                        'projectType',
                        'email',
                        'campaignName',
                        'campaignID',
                        'ex1',
                        'ex2',
                        'ex3'
                    )
                    ->where('campaignName', $parsedCampaignName)
                    ->where('deleted', '0');



                break;
            case 'CampaignReupload':
                # code...
                $query = DB::table('leads')
                    ->select(
                        'mobileNumber',
                        'leadType',
                        'referenceNumber',
                        'financialAccountID',
                        'otherContactNumber',
                        'firstname',
                        'lastname',
                        'middle',
                        'addHB',
                        'addUnit',
                        'addBuilding',
                        'addStreet',
                        'addBarangay',
                        'addCity',
                        'addProvince',
                        'addPostal',
                        'addressRegion',
                        'creditLimit',
                        'cartContent',
                        'promoToOffer',
                        'projectType',
                        'email',
                        'campaignName',
                        'campaignID',
                        'ex1',
                        'ex2',
                        'ex3'
                    )
                    ->where('campaignName', $parsedCampaignName)
                    ->where('deleted', '0');


                break;
            case 'CampaignCallNoAnswerA':
                # code...
                $query = DB::table('accountListGlobe')
                    ->select(
                        'referenceNumber',
                        'product',
                        'orderNumberUltima',
                        'transmittalDate',
                        'globeAccount',
                        'salutation',
                        'gender',
                        'birthday',
                        'civilStatus',
                        'lastname',
                        'firstname',
                        'middlename',
                        'motherFirstname',
                        'NumberOfChildren',
                        'homeOwnership',
                        'howAddressWithPostal',
                        'lengthOfStay',
                        'landlineContact',
                        'existingMobile',
                        'mobileContactNumber',
                        'email',
                        'tin',
                        'gsiss',
                        'citizenship',
                        'ifForeignCountry',
                        'spousename',
                        'spouseBirthday',
                        'spouseContactNumber',
                        'officeName',
                        'officeAddressPostal',
                        'dateOfemployment',
                        'officeTelephoneNumber',
                        'yearsInCompany',
                        'occupation',
                        'monthlyIncome',
                        'authorizedContactPerson',
                        'relation',
                        'authorizedContactNumber',
                        'homeOfficePaperless',
                        'preferredModeOfPayment',
                        'planType',
                        'planMSF',
                        'planCombo',
                        'planBooster',
                        'mandatoryArrowAddon',
                        'goSurfBundle',
                        'arrowAddon',
                        'handset',
                        'cashAmount',
                        'promoPriceBulletin',
                        'valueAddedService',
                        'hbp',
                        'lockupPeriod',
                        'transmittalType',
                        'sourceOfSales',
                        'applicationMode',
                        'dcRemark',
                        'salesmanID',
                        'salesmanName',
                        'agencyName',
                        'accountManager',
                        'salesChannel',
                        'projectPromo',
                        'appReceiveSource',
                        'stimestamp',
                        'typeOfPOID',
                        'poidNumber',
                        'doculink',
                        'leadType',
                        'salesAgentName',
                        'appDate',
                        'gcashGui',
                        'eviaFastlane',
                        'eplanGscore',
                        'deliveryAddress',
                        'sadmin',
                        'gdfPromoTag',
                        'dateCalled',
                        'dateCompiled',
                        'qualified',
                        'deliveryZipCode',
                        'andaleArea',
                        'portingNumber',
                        'projectChamomile',
                        'gadgetCareAmount',
                        'extraField1',
                        'extraField2',
                        'extraField3',
                    )
                    ->where('campaignName', $parsedCampaignName)
                    ->where('deleted', '0');


                break;
            case 'CampaignResultAllDispo':
                # code...
                $query = DB::table('accountListGlobe')
                    ->join('accountHistory', 'accountListGlobe.mobileContactNumber', '=', 'accountHistory.mobileNumber')
                    ->select(
                        'accountListGlobe.referenceNumber',
                        'accountListGlobe.mobileContactNumber',
                        'accountListGlobe.alternateContactNumber',
                        'accountListGlobe.salesAgentName',
                        'accountListGlobe.lastname',
                        'accountListGlobe.middlename',
                        'accountListGlobe.firstname',
                        'accountListGlobe.addressRemark',
                        'accountListGlobe.addHB',
                        'accountListGlobe.addUnit',
                        'accountListGlobe.addBuilding',
                        'accountListGlobe.addStreet',
                        'accountListGlobe.addBarangay',
                        'accountListGlobe.addCity',
                        'accountListGlobe.addProvince',
                        'accountListGlobe.addPostal',
                        'accountListGlobe.addressRegion',
                        'accountListGlobe.gdfPromoTag',
                        'accountListGlobe.projectPromo',
                        'accountListGlobe.remark',
                        'accountListGlobe.leadType',
                        'accountListGlobe.dateAdded',
                        'accountHistory.tapped',
                        'accountHistory.action',
                        'accountHistory.statusID',
                        'accountHistory.remark',
                        'accountHistory.action1',
                        'accountHistory.statusCode1',
                        'accountHistory.callRemark1',
                        'accountHistory.action2',
                        'accountHistory.statusCode2',
                        'accountHistory.callRemark2',
                        'accountHistory.action3',
                        'accountHistory.statusCode3',
                        'accountHistory.callRemark3',
                        'accountHistory.action4',
                        'accountHistory.statusCode4',
                        'accountHistory.callRemark4',
                        'accountHistory.action5',
                        'accountHistory.statusCode5',
                        'accountHistory.callRemark5',
                        'accountHistory.action6',
                        'accountHistory.statusCode6',
                        'accountHistory.callRemark6',
                        'accountHistory.action7',
                        'accountHistory.statusCode7',
                        'accountHistory.callRemark7',
                        'accountHistory.action8',
                        'accountHistory.statusCode8',
                        'accountHistory.callRemark8',
                        'accountHistory.action9',
                        'accountHistory.statusCode9',
                        'accountHistory.callRemark9',
                    )
                    ->where('campaignName', $parsedCampaignName)
                    ->where('deleted', '0');


                break;
            case 'CampaignResultAllTransDispoZ':
                # code...
                $query = DB::table('accountListGlobe')
                    ->join('accountHistory', 'accountListGlobe.mobileContactNumber', '=', 'accountHistory.mobileNumber')
                    ->select(
                        'accountListGlobe.mobileContactNumber', 
                        'accountListGlobe.leadType',
                        'accountListGlobe.referenceNumber',
                        'accountListGlobe.globeAccount',
                        'accountListGlobe.alternateContactNumber',
                        'accountListGlobe.salesAgentName',
                        'accountListGlobe.lastname',
                        'accountListGlobe.middlename',
                        'accountListGlobe.firstname',
                        'accountListGlobe.addHB',
                        'accountListGlobe.addUnit',
                        'accountListGlobe.addBuilding',
                        'accountListGlobe.addStreet',
                        'accountListGlobe.addBarangay',
                        'accountListGlobe.addCity',
                        'accountListGlobe.addProvince',
                        'accountListGlobe.addPostal',
                        'accountListGlobe.addressRegion',
                        'accountListGlobe.creditLimit',
                        'accountListGlobe.cartContent',
                        'accountListGlobe.gdfPromoTag',
                        'accountListGlobe.projectPromo',
                        'accountListGlobe.email',
                        'accountHistory.tapped',
                        'accountHistory.action',
                        'accountHistory.statusID',
                        'accountHistory.remark',
                        'accountHistory.action1',
                        'accountHistory.statusCode1',
                        'accountHistory.callRemark1',
                        'accountHistory.action2',
                        'accountHistory.statusCode2',
                        'accountHistory.callRemark2',
                        'accountHistory.action3',
                        'accountHistory.statusCode3',
                        'accountHistory.callRemark3',
                        'accountHistory.action4',
                        'accountHistory.statusCode4',
                        'accountHistory.callRemark4',
                        'accountHistory.action5',
                        'accountHistory.statusCode5',
                        'accountHistory.callRemark5',
                        'accountHistory.action6',
                        'accountHistory.statusCode6',
                        'accountHistory.callRemark6',
                        'accountHistory.action7',
                        'accountHistory.statusCode7',
                        'accountHistory.callRemark7',
                        'accountHistory.action8',
                        'accountHistory.statusCode8',
                        'accountHistory.callRemark8',
                        'accountHistory.action9',
                        'accountHistory.statusCode9',
                        'accountHistory.callRemark9',
                    )
                    ->where('campaignName', $parsedCampaignName)
                    ->where('deleted', '0');
                break;
            case 'CampaignResultAllDispoC':
                # code...
                if (empty($this->ahtday)) {
                    $query = DB::table('accountCallHistory')
                        ->join('users', 'users.agentNum', '=', 'accountCallHistory.agent')
                        ->select(
                            'users.agentNum',
                            'users.first_name',
                            'users.last_name',
                        )
                        ->selectRaw('AVG(accountCallHistory.aht)')
                        ->where('account', $parsedCampaignName)
                        ->where('deleted', '0');
                    if (!empty($this->group)) {
                        $query = $query->where('users.groupe', $this->group);
                    }
                    $query = $query->groupBy('users.agentNum', 'users.first_name', 'users.last_name');
                } else {
                    $query = DB::table('accountCallHistory')
                        ->join('users', 'users.agentNum', '=', 'accountCallHistory.agent')
                        ->select(
                            'users.agentNum',
                            'users.first_name',
                            'users.last_name',
                        )
                        ->selectRaw('AVG(accountCallHistory.aht)')
                        ->where('account', $parsedCampaignName)
                        ->where('deleted', '0')
                        ->whereDay('accountCallHistory.origTimestamp', $this->ahtday);
                    if (!empty($this->group)) {
                        $query = $query->where('users.groupe', $this->group);
                    }
                    $query = $query->groupBy('users.agentNum', 'users.first_name', 'users.last_name');
                    // ->groupBy('users.agentNum', 'users.first_name', 'users.last_name');
                }
                break;
            case 'CampaignResultAllDispoY':
                # code...
                if (!empty($this->ahtweek)) {
                    $splittedWeek = explode("-", $this->ahtweek);

                    $startWeek = Carbon::parse($splittedWeek[0])->format('Y/m/d');
                    $endWeek = Carbon::parse($splittedWeek[1])->format('Y/m/d');

                    // dd([$startWeek, $endWeek]);

                    $query = DB::table('accountCallHistory')
                        ->join('users', 'users.agentNum', '=', 'accountCallHistory.agent')
                        ->select(
                            'users.agentNum',
                            'users.first_name',
                            'users.last_name',
                        )
                        ->selectRaw('AVG(accountCallHistory.aht)')
                        ->where('account', $parsedCampaignName)
                        ->where('deleted', '0')
                        ->whereBetween(DB::raw('DATE(accountCallHistory.origTimestamp)'), [$startWeek, $endWeek]);
                    // where(DB::raw('DATE(users.created_at)'), '>=', DB::raw('DATE("'.$request->start_date.'")'));
                    // ->where(DB::raw('DATE(accountCallHistory.origTimestamp)'), '>=', DB::raw('DATE("'.$request->start_date.'")'));
                    // $query->whereRaw(DB::raw('DATE("accountCallHistory.origTimestamp")') . $startWeek . ' AND ' . $endWeek . '');
                    if (!empty($this->group)) {
                        $query = $query->where('users.groupe', $this->group);
                    }
                    $query = $query->groupBy('users.agentNum', 'users.first_name', 'users.last_name');

                    // dd($query->toSql());
                } else {
                    $query = DB::table('accountCallHistory')
                        ->join('users', 'users.agentNum', '=', 'accountCallHistory.agent')
                        ->select(
                            'users.agentNum',
                            'users.first_name',
                            'users.last_name',
                        )
                        ->selectRaw('AVG(accountCallHistory.aht)')
                        ->where('account', $parsedCampaignName)
                        ->where('deleted', '0');
                    if (!empty($this->group)) {
                        $query = $query->where('users.groupe', $this->group);
                    }
                    $query = $query->groupBy('users.agentNum', 'users.first_name', 'users.last_name');
                }

                break;
            case 'CampaignResultAllDispoB':
                # code...
                if (empty($this->ahtmonth)) {

                    $query = DB::table('accountCallHistory')
                        ->join('users', 'users.agentNum', '=', 'accountCallHistory.agent')
                        ->select(
                            'users.agentNum',
                            'users.first_name',
                            'users.last_name',
                        )
                        ->selectRaw('AVG(accountCallHistory.aht)')
                        ->where('account', $parsedCampaignName)
                        ->where('deleted', '0');
                    if (!empty($this->group)) {
                        $query = $query->where('users.groupe', $this->group);
                    }
                    $query = $query->groupBy('users.agentNum', 'users.first_name', 'users.last_name');
                } else {
                    $query = DB::table('accountCallHistory')
                        ->join('users', 'users.agentNum', '=', 'accountCallHistory.agent')
                        ->select(
                            'users.agentNum',
                            'users.first_name',
                            'users.last_name',
                        )
                        ->selectRaw('AVG(accountCallHistory.aht)')
                        ->where('account', $parsedCampaignName)
                        ->where('deleted', '0')
                        ->whereMonth('accountCallHistory.origTimestamp', $this->ahtmonth);
                    if (!empty($this->group)) {
                        $query = $query->where('users.groupe', $this->group);
                    }
                    $query = $query->groupBy('users.agentNum', 'users.first_name', 'users.last_name');
                }

                break;
            case 'CampaignAnsweredWithData':
                # code...
                $query = DB::table('accountCallHistory')
                    ->join('users', 'users.agentNum', '=', 'accountCallHistory.agent')
                    ->select(
                        'users.agentNum',
                        'users.first_name',
                        'users.last_name',
                    )
                    ->selectRaw('AVG(accountCallHistory.aht)')
                    ->where('account', $parsedCampaignName)
                    ->where('deleted', '0');
                if (!empty($this->group)) {
                    $query = $query->where('users.groupe', $this->group);
                }
                $query = $query->groupBy('users.agentNum', 'users.first_name', 'users.last_name');


                break;
        }



        if (!empty($this->filterType)) {

            if ($this->filterType == '1') {
            }
            if ($this->filterType == '2') {
                $query = $query->where('status', '0');
            }
            if ($this->filterType == '3') {
                $query = $query->where('status', '1');
            }
        }
        if (!empty($this->group)) {
            if ($this->reportType == "CampaignResultAllDispoC" || $this->reportType == "CampaignResultAllDispoY" || $this->reportType == "CampaignResultAllDispoB" || $this->reportType == "CampaignAnsweredWithData") {
                // $query = $query->where('users.groupe', $this->group);
                // }
            } else {
                $query = $query->where('groupz', $this->group);
            }
        }
        if (!empty($this->campaignID)) {
            if ($this->reportType == "CampaignResultAllDispo" || $this->reportType == "CampaignResultAllTransDispoZ") {
                $query = $query->where('accountListGlobe.campaignID', $this->campaignID);
            } else {
                $query = $query->where('campaignID', $this->campaignID);
            }
        }
        if ($this->dateType == 'daterange') {

            switch ($this->reportType) {
                case 'CampaignResultAllDispo':
                case 'CampaignResultAllTransDispoZ':
                    # code...
                    $query = $query->whereBetween('accountHistory.origTimeStamp', [$this->startDate, $this->endDate]);
                    break;

                case 'CampaignCallNoAnswerA':
                    $query = $query->whereBetween('stimestamp', [$this->startDate, $this->endDate]);
                    break;
                case 'CampaignAnsweredWithData':
                    $query = $query->whereBetween('accountCallHistory.origTimeStamp', [$this->startDate, $this->endDate]);
                    break;

                default:
                    # code...
                    $query = $query->whereBetween('dateUpload', [$this->startDate, $this->endDate]);
                    break;
            }

       
        }
        if ($this->dateType == 'today') {
            switch ($this->reportType) {
                case 'CampaignResultAllDispo':
                case 'CampaignResultAllTransDispoZ':
                    # code...
                    $query = $query->whereDate('accountHistory.origTimeStamp', Carbon::now());
                    break;
                case 'CampaignCallNoAnswerA':
                    $query = $query->whereDate('stimestamp', Carbon::now());
                    break;
                case 'CampaignAnsweredWithData':
                    $query = $query->whereDate('accountCallHistory.origTimeStamp', Carbon::now());
                    break;

                default:
                    # code...
                    $query = $query->whereDate('dateUpload', Carbon::now());
                    break;
            }
        }

        $output = $query->get();
        if ($this->reportType == "CampaignExtract" || $this->reportType == "CampaignReupload") {
            if (!empty($this->campaignID)) {
                foreach ($output as $entry) {
                    $find = Lead::where('mobileNumber', $entry->mobileNumber)->where('campaignID', $entry->campaignID)->where('dl', '0')->first();
                    if (!empty($find)) {
                        $find->dl = "1";
                        $find->save();
                    }
                }
            } else {
                foreach ($output as $entry) {
                    $find = Lead::where('mobileNumber', $entry->mobileNumber)->where('campaignName', $entry->campaignName)->where('dl', '0')->first();
                    if (!empty($find)) {
                        $find->dl = "1";
                        $find->save();
                    }
                }
            }
        } else {
            if (!empty($this->campaignID) && $this->reportType != "CampaignResultAllDispoC" && $this->reportType != "CampaignResultAllDispoY" && $this->reportType != "CampaignResultAllDispoB" && $this->reportType != "CampaignAnsweredWithData") {
                foreach ($output as $entry) {
                    $find = AccountListGlobe::where('mobileContactNumber', $entry->mobileContactNumber)->where('dl', '0')->first();
                    if (!empty($find)) {
                        $find->dl = "1";
                        $find->save();
                    }
                }
            }
            // else {
            //     foreach ($output as $entry) {
            //         $find = AccountListGlobe::where('mobileContactNumber', $entry->mobileContactNumber)->where('dl', '0')->first();
            //         if (!empty($find)) {
            //             $find->dl = "1";
            //             $find->save();
            //         }
            //     }
            // }
        }
        return  $output;
    }

    public function headings(): array
    {
        switch ($this->reportType) {
            case 'CampaignExtract':
                return [
                    'MOBILENUMBER',
                    'LEADTYPE',
                    'REFERENCE NO',
                    'FINANCIAL ACCOUNT ID',
                    'OTHER CONTACT NO',
                    'FIRSTNAME',
                    'LASTNAME',
                    'MIDDLENAME',
                    'ADDHB',
                    'ADDUNIT',
                    'ADDBLDG',
                    'ADDSTREET',
                    'ADDBRGY',
                    'ADDCITY',
                    'ADDPROVINCE',
                    'ADDPOSTAL',
                    'ADDREGION',
                    'CREDITLIMIT',
                    'CARTCONTENTS',
                    'PROMOTOOFFER',
                    'PROJECTTYPE',
                    'EMAIL',
                    'CAMPAIGNNAME',
                    'CAMPAIGNNAME',
                    'ex1',
                    'ex2',
                    'ex3'


                ];
                break;
            case 'CampaignReupload':
                return [
                    'MOBILENUMBER',
                    'LEADTYPE',
                    'REFERENCE NO',
                    'FINANCIAL ACCOUNT ID',
                    'OTHER CONTACT NO',
                    'FIRSTNAME',
                    'LASTNAME',
                    'MIDDLENAME',
                    'ADDHB',
                    'ADDUNIT',
                    'ADDBLDG',
                    'ADDSTREET',
                    'ADDBRGY',
                    'ADDCITY',
                    'ADDPROVINCE',
                    'ADDPOSTAL',
                    'ADDREGION',
                    'CREDITLIMIT',
                    'CARTCONTENTS',
                    'PROMOTOOFFER',
                    'PROJECTTYPE',
                    'EMAIL',
                    'CAMPAIGNNAME',
                    'CAMPAIGNID',
                    'ex1',
                    'ex2',
                    'ex3'
                ];
                break;
            case 'CampaignCallNoAnswerA':
                return [
                    'REFERENCENO',
                    'PRODUCT',
                    'ORDERNOULTIMA',
                    'TRANSMITTALDATE',
                    'GLOBEACCOUNT',
                    'SALUTATION',
                    'GENDER',
                    'BIRTHDAY',
                    'CIVILSTATUS',
                    'LASTNAME',
                    'FIRSTNAME',
                    'MIDDLENAME',
                    'MOTHERS FULLNAME',
                    'NOOFCHILDREN',
                    'HOMEOWNERSHIP',
                    'HOWADDRESSWITHPOSTAL',
                    'LENGTHOFSTAY',
                    'LANDLINECONTACT',
                    'EXISTINGMOBILE',
                    'MOBILECONTACTNUMBER',
                    'EMAILADDRESS',
                    'TIN',
                    'GSISSSS',
                    'CITIZENSHIP',
                    'IFFOREIGNCOUNTRY',
                    'SPOUSE FULLNAME',
                    'SPOUSEBDAY',
                    'SPOUSECONTACTNUMBER',
                    'OFFICENAME',
                    'OFFICEADDRESSPOSTAL',
                    'DATEOFEMPLOYMENT',
                    'OFFICETELEPHONENUMBER',
                    'YEARSINCOMPANY',
                    'OCCUPATION',
                    'MONTHLYINCOME',
                    'AUTHORIZEDCONTACTPERSONIN',
                    'RELATION',
                    'RELATIONCONTACTNUMBER',
                    'HOMEOFFICEPAPERLESS',
                    'PREFERREDMODEOFPAYMENT',
                    'PLANTYPE',
                    'PLANMSF',
                    'PLANCOMBOS',
                    'PLANBOOSTER',
                    'MANDATORYARROWADDONS',
                    'GOSURFBUNDLE',
                    'ARROWADDONS',
                    'HANDSET',
                    'CASHOUTAMOUNT',
                    'PROMOPRICEBULLETIN',
                    'VALUEADDEDSERVICE',
                    'HBP',
                    'LOCKUPPERIOD',
                    'TRANSMITTALTYPE',
                    'SOURCEOFSALES',
                    'APPLICATIONMODE',
                    'DCREMARKS',
                    'SALESMANID',
                    'SALESMANNAME',
                    'AGENCYNAME',
                    'ACCOUNTMANAGER',
                    'SALESCHANNEL',
                    'PROJECTPROMO',
                    'APPSRECEIVESOURCE',
                    'STIMESTAMP',
                    'TYPEOFPOID',
                    'POIDNUMBER',
                    'DOCULINK',
                    'LEADTYPE',
                    'SALESAGENTNAME',
                    'APPDATE',
                    'GCASHGUI',
                    'EVIAFASTLANE',
                    'EPLANGSCORE',
                    'DELIVERYADDRESS',
                    'SADMIN',
                    'GDFPROMOTAG',
                    'DATECALLED',
                    'DATECOMPLIED',
                    'QUALIFIED',
                    'DELIVERYZIPCODE',
                    'ANDALEAREA',
                    'PORTINGNUMBER',
                    'PROJECTCHAMOMILE',
                    'GADGETCAREAMOUNT',
                    'EXTRAFIELD1',
                    'EXTRAFIELD2',
                    'EXTRAFIELD3',
                ];
                break;
            case 'CampaignResultAllDispo':
                return [
                    'BATCHREFERENCENUMBER',
                    'MOBILENUMBER',
                    'OTHERCONTACTNO',
                    'SALESAGENTNAME',
                    'LASTNAME',
                    'MIDDLENAME',
                    'FIRSTNAME',
                    'ADDRESSREMARKS',
                    'ADDHB',
                    'ADDUNIT',
                    'ADDBLDG',
                    'ADDSTREET',
                    'ADDBRGY',
                    'ADDCITY',
                    'ADDPROVINCE',
                    'ADDPOSTAL',
                    'ADDRESSREGION',
                    'PROMOTOOFFER',
                    'PROJECTTYPE',
                    'REMARKS',
                    'LEADTYPE',
                    'DATEADDED',
                    'TAPPED',
                    'CALL1 ENTRY',
                    'CALL1 DISPOSITION',
                    'CALL1 REMARKS',
                    'CALL2 ENTRY',
                    'CALL2 DISPOSITION',
                    'CALL2 REMARKS',
                    'CALL3 ENTRY',
                    'CALL3 DISPOSITION',
                    'CALL3 REMARKS',
                    'CALL4 ENTRY',
                    'CALL4 DISPOSITION',
                    'CALL4 REMARKS',
                    'CALL5 ENTRY',
                    'CALL5 DISPOSITION',
                    'CALL5 REMARKS',
                    'CALL6 ENTRY',
                    'CALL6 DISPOSITION',
                    'CALL6 REMARKS',
                    'CALL7 ENTRY',
                    'CALL7 DISPOSITION',
                    'CALL7 REMARKS',
                    'CALL8 ENTRY',
                    'CALL8 DISPOSITION',
                    'CALL8 REMARKS',
                    'CALL9 ENTRY',
                    'CALL9 DISPOSITION',
                    'CALL9 REMARKS',
                    'CALL10 ENTRY',
                    'CALL10 DISPOSITION',
                    'CALL10 REMARKS',
                    'FINAL DISPOSITION',
                    'REASON'

                ];
                break;
            case 'CampaignResultAllTransDispoZ':
                return [
                    'MOBILENUMBER',
                    'LEADTYPE',
                    'BATCHREFERENCENUMBER',
                    'FINANCIALACCOUNTID',
                    'OTHERCONTACTNO',
                    'SALESAGENTNAME',
                    'LASTNAME',
                    'MIDDLENAME',
                    'FIRSTNAME',
                    'ADDHB',
                    'ADDUNIT',
                    'ADDBLDG',
                    'ADDSTREET',
                    'ADDBRGY',
                    'ADDCITY',
                    'ADDPROVINCE',
                    'ADDPOSTAL',
                    'ADDRESSREGION',
                    'CREDITLIMIT',
                    'CARTCONTENTS',
                    'PROMOTOOFFER',
                    'PROJECTTYPE',
                    'EMAIL',
                    'TAPPED',
                    'CALL1 ENTRY',
                    'CALL1 DISPOSITION',
                    'CALL1 REMARKS',
                    'CALL2 ENTRY',
                    'CALL2 DISPOSITION',
                    'CALL2 REMARKS',
                    'CALL3 ENTRY',
                    'CALL3 DISPOSITION',
                    'CALL3 REMARKS',
                    'CALL4 ENTRY',
                    'CALL4 DISPOSITION',
                    'CALL4 REMARKS',
                    'CALL5 ENTRY', 'CALL5 DISPOSITION',
                    'CALL5 REMARKS',
                    'CALL6 ENTRY',
                    'CALL6 DISPOSITION',
                    'CALL6 REMARKS',
                    'CALL7 ENTRY',
                    'CALL7 DISPOSITION',
                    'CALL7 REMARKS',
                    'CALL8 ENTRY',
                    'CALL8 DISPOSITION',
                    'CALL8 REMARKS',
                    'CALL9 ENTRY',
                    'CALL9 DISPOSITION',
                    'CALL9 REMARKS',
                    'CALL10 ENTRY',
                    'CALL10 DISPOSITION',
                    'CALL10 REMARKS',
                ];
                break;
            case 'CampaignResultAllDispoC':
                return [
                    'AGENT NUMBER',
                    'FIRSTNAME',
                    'LASTNAME',
                    'AHT (Seconds)',
                ];
                break;

            case 'CampaignResultAllDispoY':
                return [
                    'AGENT NUMBER',
                    'FIRSTNAME',
                    'LASTNAME',
                    'AHT (Seconds)',
                ];
                break;

            case 'CampaignResultAllDispoB':
                return [
                    'AGENT NUMBER',
                    'FIRSTNAME',
                    'LASTNAME',
                    'AHT (Seconds)',

                ];
                break;

            case 'CampaignAnsweredWithData':
                return [
                    'AGENT NUMBER',
                    'FIRSTNAME',
                    'LASTNAME',
                    'AHT (Seconds)',

                ];
                break;
        }
    }

    public function GetCampaignName($campaignNameNumber)
    {
        $output = "";

        switch ($campaignNameNumber) {
            case '3':
                $output = "POSTPAID";

                # code...
                break;
            case '4':
                $output = "BROADBAND";
                # code...
                break;
            case '5':
                $output = "MIGRATION";
                # code...
                break;
            case '6':

                $output = "POSTPAID-LUKEWARM";
                # code...
                break;
            case '7':

                $output = "BROADBAND-LUKEWARM";
                # code...
                break;
            case '8':
                $output = "BROADBAND-NOTSERVICEABLE";
                # code...
                break;
            case '9':
                $output = "BROADBAND-ASSIST";
                # code...
                break;
            case '10':
                $output = "BROADBAND-SCHEDULEREQUEST";
                # code...
                break;
        }

        return $output;
    }
}
