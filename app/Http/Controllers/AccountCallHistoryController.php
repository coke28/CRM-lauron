<?php

namespace App\Http\Controllers;

use App\Models\AccountCallHistory;
use Illuminate\Http\Request;
use Log;

class AccountCallHistoryController extends Controller
{
  //

  public function listAccountCallHistory(Request $request)
  {

    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: *');

    $tableColumns = array(
      'id',
      'action',
      'agent',
      'fullname',
      'date',
      'mobileNumber',
      'statusID',
      'campaignID',
      'campaignName',
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
    $accountCallHistory = AccountCallHistory::where('account',$request->campaignName)->where('campaignID',$request->campaignID)->where('leadsID',$request->leadID);
    $accountCallHistory = $accountCallHistory->where(function ($query) use ($search) {
      return $query->where('id', 'like', '%' . $search . '%')
        ->orWhere('action', 'like', '%' . $search . '%')
        ->orWhere('agent', 'like', '%' . $search . '%')
        ->orWhere('fullname', 'like', '%' . $search . '%')
        ->orWhere('date', 'like', '%' . $search . '%')
        ->orWhere('mobileNumber', 'like', '%' . $search . '%')
        ->orWhere('statusID', 'like', '%' . $search . '%')
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
      'data2'            => $request->all(),
    ];

    // reponse must be in  array
    return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
  }
}
