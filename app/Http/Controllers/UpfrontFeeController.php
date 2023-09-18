<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\UpfrontFee;
use Illuminate\Http\Request;

class UpfrontFeeController extends Controller
{
  //
  public function listUpfrontFee(Request $request)
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

    // $upfrontFee = UpfrontFee::where(function ($query) use ($search) { // where like search request
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

    // foreach ($upfrontFee as $p) {

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

    // $upfrontFeeCount = UpfrontFee::where(function ($query) use ($search) { // where like search request
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

    $upfrontFee = UpfrontFee::where('deleted', '0');
    $upfrontFee = $upfrontFee->where(function ($query) use ($search) {
      return $query->where('product', 'like', '%' . $search . '%')
        ->orWhere('statusName', 'like', '%' . $search . '%')
        ->orWhere('id', 'like', '%' . $search . '%');
    })
      ->orderBy($tableColumns[$sortIndex], $sortOrder);
    $upfrontFeeCount = $upfrontFee->count();
    $upfrontFee = $upfrontFee->offset($offset)
      ->limit($limit)
      ->get();

    foreach ($upfrontFee as $p) {

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
      'recordsTotal'    => $upfrontFeeCount,
      'recordsFiltered' => $upfrontFeeCount,
      'data'            => $upfrontFee,
    ];

    // reponse must be in  array
    return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
  }

  public function addUpfrontFee(Request $request)
  {
    $upfrontFeeName = UpfrontFee::where('statusName', $request->upfrontFeeName)->where('product', $request->product)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')
    if ($upfrontFeeName > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Upfront Fee Name already in use.'
      ));
    }

    $upfrontFee = new UpfrontFee();
    $upfrontFee->statusName = $request->upfrontFeeName;
    $upfrontFee->product = $request->product;
    $upfrontFee->statusDefinition = $request->upfrontFeeDescription;

    switch ($request->status) {
      case "DISABLED":
        // code block
        $upfrontFee->status = 0;
        break;
      case "ACTIVE":
        // code block
        $upfrontFee->status = 1;
        break;
      default:
        // code block
    }

    $upfrontFee->save();


    $auditLog = new AuditLog();
    $auditLog->agent = auth()->user()->id;
    $auditLog->action = "Added Upfront Fee";
    $auditLog->table = "upfrontFee";
    $auditLog->nID = $upfrontFee->id . " | " . $request->upfrontFeeName . " | " . $request->product . " | " . $request->upfrontFeeDescription . " | " . $request->status;
    $auditLog->ip = \Request::ip();

    $auditLog->save();

    return json_encode(array(
      'success' => true,
      'message' => 'Upfront Fee added successfully.'
    ));
  }

  public function getEditUpfrontFee(Request $request)
  {
    $getUpfrontFee = UpfrontFee::where('id', $request->id)->first();
    return json_encode($getUpfrontFee);
  }

  public function editUpfrontFee(Request $request)
  {

    $upfrontFeeName = UpfrontFee::where('statusName', $request->upfrontFeeName)->where('id', '!=', $request->id)->where('product', $request->product)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')

    // dd($productName);
    if ($upfrontFeeName > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'UpfrontFee Name already in use.'
      ));
    }


    $upfrontFee = UpfrontFee::where('id', $request->id)->first();
    if (!empty($upfrontFee) || $upfrontFee != null) {

      $upfrontFee->statusName = $request->upfrontFeeName;
      $upfrontFee->product = $request->product;
      $upfrontFee->statusDefinition = $request->upfrontFeeDescription;

      switch ($request->status) {
        case 0:
          // code block
          $upfrontFee->status = 0;
          break;
        case 1:
          // code block
          $upfrontFee->status = 1;
          break;
        default:
          // code block
      }

      $upfrontFee->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Edited ID #" . " $upfrontFee->id " . "Upfront Fee";
      $auditLog->table = "upfrontFee";
      $auditLog->nID = $upfrontFee->id . " | " . $request->upfrontFeeName . " | " . $request->product . " | " . $request->upfrontFeeDescription . " | " . $request->status;
      $auditLog->ip = \Request::ip();
      $auditLog->save();
      return json_encode(array(
        'success' => true,
        'message' => 'UpfrontFee updated successfully.'
      ));
    } else {
      return json_encode(array(
        'success' => false,
        'message' => 'UpfrontFee not found.'
      ));
    }
  }

  public function deleteUpfrontFee(Request $request)
  {
    $deleteUpfrontFee = UpfrontFee::where('id', $request->id)->first();

    if ($deleteUpfrontFee) {


      $deleteUpfrontFee->deleted = 1;
      $deleteUpfrontFee->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Deleted ID #" . " $deleteUpfrontFee->id " . "Upfront Fee";
      $auditLog->table = "upfrontFee";
      $auditLog->nID = "Deleted =" . $deleteUpfrontFee->deleted;
      $auditLog->ip = \Request::ip();
      $auditLog->save();
      return 'UpfrontFee deleted successfully.';
    } else {

      return 'UpfrontFee deleted unsuccessfully.';
    }
  }
}
