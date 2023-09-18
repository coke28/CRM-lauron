<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\CCRemark;
use Illuminate\Http\Request;

class CCRemarkController extends Controller
{
  //
  public function listCCRemark(Request $request)
  {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: *');

    $tableColumns = array(
      'id',
      'product',
      'statusName',
      'status'
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

    // $ccRemark = CCRemark::where(function ($query) use ($search) { // where like search request
    //   return $query->where('product', 'like', '%' . $search . '%')
    //     ->orWhere('statusName', 'like', '%' . $search . '%')
    //     ->orWhere('id', 'like', '%' . $search . '%');
    // })
    //   //user is not deleted
    //   ->where('deleted', '0')
    //   //by order
    //   ->orderBy($tableColumns[$sortIndex], $sortOrder)
    //   ->offset($offset)
    //   ->limit($limit)
    //   ->get();

    // foreach ($ccRemark as $p) {

    //   switch ($p->status) {
    //     case "0":
    //       // code block
    //       $p->status = "DISABLED";
    //       break;
    //     case "1":
    //       // code block
    //       $p->status = "ACTIVE";
    //       break;
    //     default:
    //       // code block
    //   }
    // }

    // $ccRemarkCount = CCRemark::where(function ($query) use ($search) { // where like search request
    //   return $query->where('product', 'like', '%' . $search . '%')
    //     ->orWhere('statusName', 'like', '%' . $search . '%')
    //     ->orWhere('id', 'like', '%' . $search . '%');
    // })
    //   //user is not deleted
    //   ->where('deleted', '0')
    //   //by order
    //   ->orderBy($tableColumns[$sortIndex], $sortOrder)
    //   ->offset($offset)
    //   ->limit($limit)
    //   ->get()
    //   ->count();

    $ccRemark = CCRemark::where('deleted', '0');
    $ccRemark = $ccRemark->where(function ($query) use ($search) {
      return $query->where('product', 'like', '%' . $search . '%')
        ->orWhere('statusName', 'like', '%' . $search . '%')
        ->orWhere('id', 'like', '%' . $search . '%');
    })
      ->orderBy($tableColumns[$sortIndex], $sortOrder);
    $ccRemarkCount = $ccRemark->count();
    $ccRemark = $ccRemark->offset($offset)
      ->limit($limit)
      ->get();

    foreach ($ccRemark as $p) {

      switch ($p->status) {
        case "0":
          // code block
          $p->status = "DISABLED";
          break;
        case "1":
          // code block
          $p->status = "ACTIVE";
          break;
        default:
          // code block
      }
    }

    $result = [
      'recordsTotal'    => $ccRemarkCount,
      'recordsFiltered' => $ccRemarkCount,
      'data'            => $ccRemark,
    ];

    // reponse must be in  array
    return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
  }

  public function addCCRemark(Request $request)
  {
    $ccRemarkName = CCRemark::where('statusName', $request->CCRemarkName)->where('product', $request->product)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')
    if ($ccRemarkName > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Application Type Name already in use.'
      ));
    }

    $ccRemark = new CCRemark();
    $ccRemark->statusName = $request->CCRemarkName;
    $ccRemark->product = $request->product;
    $ccRemark->statusDefinition = $request->CCRemarkDescription;

    switch ($request->status) {
      case "DISABLED":
        // code block
        $ccRemark->status = 0;
        break;
      case "ACTIVE":
        // code block
        $ccRemark->status = 1;
        break;
      default:
        // code block
    }

    $ccRemark->save();

    $auditLog = new AuditLog();
    $auditLog->agent = auth()->user()->id;
    $auditLog->action = "Added CC Remark";
    $auditLog->table = "ccRemark";
    $auditLog->nID = $ccRemark->id . " | " . $request->CCRemarkName . " | " . $request->product . " | " . $request->CCRemarkDescription . " | " . $request->status;
    $auditLog->ip = \Request::ip();
    $auditLog->save();

    return json_encode(array(
      'success' => true,
      'message' => 'CC Remark added successfully.'
    ));
  }

  public function getEditCCRemark(Request $request)
  {
    $getCCRemark = CCRemark::where('id', $request->id)->first();
    return json_encode($getCCRemark);
  }

  public function editCCRemark(Request $request)
  {

    $ccRemarkName = CCRemark::where('statusName', $request->CCRemarkName)->where('id', '!=', $request->id)->where('product', $request->product)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')

    // dd($productName);
    if ($ccRemarkName > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'CCRemark already in use.'
      ));
    }


    $ccRemark = CCRemark::where('id', $request->id)->first();
    if (!empty($ccRemark) || $ccRemark != null) {

      $ccRemark->statusName = $request->CCRemarkName;
      $ccRemark->product = $request->product;
      $ccRemark->statusDefinition = $request->CCRemarkDescription;

      switch ($request->status) {
        case 0:
          // code block
          $ccRemark->status = 0;
          break;
        case 1:
          // code block
          $ccRemark->status = 1;
          break;
        default:
          // code block
      }
      $ccRemark->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Edited ID #" . " $ccRemark->id " . "CC Remark";
      $auditLog->table = "ccRemark";
      $auditLog->nID = $ccRemark->id . " | " . $request->CCRemarkName . " | " . $request->product . " | " . $request->CCRemarkDescription . " | " . $request->status;
      $auditLog->ip = \Request::ip();
      $auditLog->save();
      return json_encode(array(
        'success' => true,
        'message' => 'CC Remark Type updated successfully.'
      ));
    } else {
      return json_encode(array(
        'success' => false,
        'message' => 'CC Remark Type not found.'
      ));
    }
  }

  public function deleteCCRemark(Request $request)
  {
    $deleteCCRemark = CCRemark::where('id', $request->id)->first();

    if ($deleteCCRemark) {


      $deleteCCRemark->deleted = 1;
      $deleteCCRemark->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Deleted ID #" . " $deleteCCRemark->id " . "CC Remark";
      $auditLog->table = "ccRemark";
      $auditLog->nID = "Deleted =" . $deleteCCRemark->deleted;
      $auditLog->ip = \Request::ip();
      $auditLog->save();

      return 'CC Remark deleted successfully.';
    } else {

      return 'CC Remark deleted unsuccessfully.';
    }
  }
}
