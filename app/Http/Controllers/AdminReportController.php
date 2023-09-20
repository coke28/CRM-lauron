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
       
        ini_set('memory_limit','-1');
        ini_set('max_execution_time','0');


        return Excel::download(new ReportExportLauron($request->product_bd_list, $request->campaignname, $request->select_report_type, 
        $request->filtertype, $request->groupzz, $request->select_date_type, $request->start_date, $request->end_date), 
        $request->select_report_type." ".$request->select_date_type." ".Carbon::now()." ".'Report.csv');

        // (new ReportExportLauron(
        //     $request->product_bd_list, 
        //     $request->campaignname, 
        //     $request->select_report_type, 
        //     $request->filtertype, 
        //     $request->groupzz, 
        //     $request->select_date_type, 
        //     $request->start_date, 
        //     $request->end_date))
        // ->queue(
        //     $request->select_report_type."_".
        //     $request->select_date_type."_".
        //     Carbon::now()->format('Y-m-d_H-i-s')."_".'Report.csv'
        // );

        // return json_encode([
        //     'message' => 'Success'
        // ]);
    }
}
