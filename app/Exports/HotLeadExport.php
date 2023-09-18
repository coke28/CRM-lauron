<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\AccountListGlobe;
use Illuminate\Support\Facades\DB;

class HotLeadExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    use Exportable;

    public function __construct(
        $campaignID,
        $search,
    ) {
        // $this->campaignID = $request->campaignID;
        $this->campaignID = $campaignID;
        $this->search = $search;
    }
    public function collection()
    {
        $query = "";
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
                'campaignID',
            )
            ->where('campaignStatusID', 'like', '%ENDTOEND%')
            ->where('deleted', '0');
      
        if (!empty($this->campaignID) && $this->campaignID != null) {

            $query = $query->where('campaignID', $this->campaignID);
            // dd($query->get());
        
        }
        if (!empty($this->search) && $this->search != null) {
            $search = $this->search;
            $query = $query->where(function ($query) use ($search) {
                return $query->where('id', 'like', '%' . $search . '%')
                    ->orWhere('referenceNumber', 'like', '%' . $search . '%')
                    ->orWhere('product', 'like', '%' . $search . '%')
                    ->orWhere('campaignID', 'like', '%' . $search . '%')
                    ->orWhere('mobileContactNumber', 'like', '%' . $search . '%')
                    ->orWhere('firstname', 'like', '%' . $search . '%')
                    ->orWhere('lastname', 'like', '%' . $search . '%');
            });
        }
        $output = $query->get();


        return  $output;
    }

    public function headings(): array
    {

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
    }
}
