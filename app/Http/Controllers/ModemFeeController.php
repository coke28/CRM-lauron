<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\ModemFee;
use Illuminate\Http\Request;

class ModemFeeController extends Controller
{
  //
  public function listModemFee(Request $request)
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

    // $modemFee = ModemFee::where(function ($query) use ($search) { // where like search request
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

    // foreach ($modemFee as $p) {

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

    // $modemFeeCount = ModemFee::where(function ($query) use ($search) { // where like search request
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

    $modemFee = ModemFee::where('deleted', '0');
    $modemFee = $modemFee->where(function ($query) use ($search) {
      return $query->where('product', 'like', '%' . $search . '%')
        ->orWhere('statusName', 'like', '%' . $search . '%')
        ->orWhere('id', 'like', '%' . $search . '%');
    })
      ->orderBy($tableColumns[$sortIndex], $sortOrder);
    $modemFeeCount = $modemFee->count();
    $modemFee = $modemFee->offset($offset)
      ->limit($limit)
      ->get();

    foreach ($modemFee as $p) {

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
      'recordsTotal'    => $modemFeeCount,
      'recordsFiltered' => $modemFeeCount,
      'data'            => $modemFee,
    ];



    // reponse must be in  array
    return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
  }

  public function addModemFee(Request $request)
  {
    $modemFeeName = ModemFee::where('statusName', $request->modemFeeName)->where('product', $request->product)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')
    if ($modemFeeName > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Modem Fee Name already in use.'
      ));
    }

    $modemFee = new ModemFee();
    $modemFee->statusName = $request->modemFeeName;
    $modemFee->product = $request->product;
    $modemFee->statusDefinition = $request->modemFeeDescription;

    switch ($request->status) {
      case "DISABLED":
        // code block
        $modemFee->status = 0;
        break;
      case "ACTIVE":
        // code block
        $modemFee->status = 1;
        break;
      default:
        // code block
    }

    $modemFee->save();

    $auditLog = new AuditLog();
    $auditLog->agent = auth()->user()->id;
    $auditLog->action = "Added Modem Fee";
    $auditLog->table = "modemFee";
    $auditLog->nID =  $modemFee->id . " | " . $request->lockupName . " | " . $request->product . " | " . $request->lockupDescription . " | " . $request->status;
    $auditLog->save();

    return json_encode(array(
      'success' => true,
      'message' => 'Modem Fee added successfully.'
    ));
  }

  public function getEditModemFee(Request $request)
  {
    $getModemFee = ModemFee::where('id', $request->id)->first();
    return json_encode($getModemFee);
  }

  public function editModemFee(Request $request)
  {

    $modemFeeName = ModemFee::where('statusName', $request->modemFeeName)->where('product', $request->product)->where('id', '!=', $request->id)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')

    // dd($productName);
    if ($modemFeeName > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Modem Fee Name already in use.'
      ));
    }


    $modemFee = ModemFee::where('id', $request->id)->first();
    if (!empty($modemFee) || $modemFee != null) {

      $modemFee->statusName = $request->modemFeeName;
      $modemFee->product = $request->product;
      $modemFee->statusDefinition = $request->modemFeeDescription;

      switch ($request->status) {
        case 0:
          // code block
          $modemFee->status = 0;
          break;
        case 1:
          // code block
          $modemFee->status = 1;
          break;
        default:
          // code block
      }

      $modemFee->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Edited ID #" . " $modemFee->id " . "Modem Fee";
      $auditLog->table = "modemFee";
      $auditLog->nID =  $modemFee->id . " | " . $request->lockupName . " | " . $request->product . " | " . $request->lockupDescription . " | " . $request->status;
      $auditLog->ip = \Request::ip();
      $auditLog->save();
      return json_encode(array(
        'success' => true,
        'message' => 'Modem Fee updated successfully.'
      ));
    } else {
      return json_encode(array(
        'success' => false,
        'message' => 'Modem Fee not found.'
      ));
    }
  }

  public function deleteModemFee(Request $request)
  {
    $deleteModemFee = ModemFee::where('id', $request->id)->first();

    if ($deleteModemFee) {


      $deleteModemFee->deleted = 1;
      $deleteModemFee->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Deleted ID #" . " $deleteModemFee->id " . "Modem Fee";
      $auditLog->table = "modemFee";
      $auditLog->nID = "Deleted =" . $deleteModemFee->deleted;
      $auditLog->ip = \Request::ip();
      $auditLog->save();
      return 'Modem Fee deleted successfully.';
    } else {

      return 'Modem Fee deleted unsuccessfully.';
    }
  }
}
