<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use App\Exports\ReportExportLauron;
use App\Models\AccountListGlobe;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel as ExcelExcel;

class AdminReportController extends Controller
{
    //
    public function generateReport(Request $request)
    {
       
        // return (new ReportExport($request->product_bd_list))->download('invoices.xlsx');
        // return json_encode(array(
        //     'success' => true,
        //     'message' => 'Application Type added successfully.'
        // ));
        ini_set('memory_limit','-1');
        ini_set('max_execution_time','0');

        // return Excel::download(new ReportExport($request->product_bd_list, $request->campaignname, $request->select_report_type, 
        // $request->filtertype, $request->groupzz, $request->select_date_type, $request->start_date, $request->end_date,$request->ahtday,
        // $request->ahtweek,$request->ahtmonth), 
        // $request->select_report_type." ".$request->select_date_type." ".Carbon::now()." ".'Reports.csv');

        return Excel::download(new ReportExportLauron($request->product_bd_list, $request->campaignname, $request->select_report_type, 
        $request->filtertype, $request->groupzz, $request->select_date_type, $request->start_date, $request->end_date), 
        $request->select_report_type." ".$request->select_date_type." ".Carbon::now()." ".'Report.csv');
    }
}
