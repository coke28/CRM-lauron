<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Lockup;
use Illuminate\Http\Request;

class LockupController extends Controller
{
  //
  public function listLockup(Request $request)
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

    // $lockup = Lockup::where(function ($query) use ($search) { // where like search request
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

    // foreach ($lockup as $p) {

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

    // $lockupCount = Lockup::where(function ($query) use ($search) { // where like search request
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

    $lockup = Lockup::where('deleted', '0');
    $lockup = $lockup->where(function ($query) use ($search) {
      return $query->where('product', 'like', '%' . $search . '%')
        ->orWhere('statusName', 'like', '%' . $search . '%')
        ->orWhere('id', 'like', '%' . $search . '%');
    })
      ->orderBy($tableColumns[$sortIndex], $sortOrder);
    $lockupCount = $lockup->count();
    $lockup = $lockup->offset($offset)
      ->limit($limit)
      ->get();

    foreach ($lockup as $p) {

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
      'recordsTotal'    => $lockupCount,
      'recordsFiltered' => $lockupCount,
      'data'            => $lockup,
    ];

    // reponse must be in  array
    return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
  }

  public function addLockup(Request $request)
  {
    $lockupName = Lockup::where('statusName', $request->lockupName)->where('product', $request->product)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')
    if ($lockupName > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Lockup Name already in use.'
      ));
    }

    $lockup = new Lockup();
    $lockup->statusName = $request->lockupName;
    $lockup->product = $request->product;
    $lockup->statusDefinition = $request->lockupDescription;

    switch ($request->status) {
      case "DISABLED":
        // code block
        $lockup->status = 0;
        break;
      case "ACTIVE":
        // code block
        $lockup->status = 1;
        break;
      default:
        // code block
    }

    $lockup->save();

    $auditLog = new AuditLog();
    $auditLog->agent = auth()->user()->id;
    $auditLog->action = "Added Lockup";
    $auditLog->table = "lockup";
    $auditLog->nID =  $lockup->id . " | " . $request->lockupName . " | " . $request->product . " | " . $request->lockupDescription . " | " . $request->status;
    $auditLog->save();

    return json_encode(array(
      'success' => true,
      'message' => 'Lockup added successfully.'
    ));
  }

  public function getEditLockup(Request $request)
  {
    $getLockup = Lockup::where('id', $request->id)->first();
    return json_encode($getLockup);
  }

  public function editLockup(Request $request)
  {

    $lockupName = Lockup::where('statusName', $request->lockupName)->where('id', '!=', $request->id)->where('product', $request->product)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')

    // dd($productName);
    if ($lockupName > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Lockup Name already in use.'
      ));
    }


    $lockup = Lockup::where('id', $request->id)->first();
    if (!empty($lockup) || $lockup != null) {

      $lockup->statusName = $request->lockupName;
      $lockup->product = $request->product;
      $lockup->statusDefinition = $request->lockupDescription;

      switch ($request->status) {
        case 0:
          // code block
          $lockup->status = 0;
          break;
        case 1:
          // code block
          $lockup->status = 1;
          break;
        default:
          // code block
      }

      $lockup->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Edited ID #" . " $lockup->id " . "Lockup";
      $auditLog->table = "lockup";
      $auditLog->nID =  $lockup->id . " | " . $request->lockupName . " | " . $request->product . " | " . $request->lockupDescription . " | " . $request->status;
      $auditLog->ip = \Request::ip();
      $auditLog->save();
      return json_encode(array(
        'success' => true,
        'message' => 'Lockup updated successfully.'
      ));
    } else {
      return json_encode(array(
        'success' => false,
        'message' => 'Lockup not found.'
      ));
    }
  }

  public function deleteLockup(Request $request)
  {
    $deleteLockup = Lockup::where('id', $request->id)->first();

    if ($deleteLockup) {


      $deleteLockup->deleted = 1;
      $deleteLockup->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Deleted ID #" . " $deleteLockup->id " . "Lockup";
      $auditLog->table = "lockup";
      $auditLog->nID = "Deleted =" . $deleteLockup->deleted;
      $auditLog->ip = \Request::ip();
      $auditLog->save();

      return 'Lockup deleted successfully.';
    } else {

      return 'Lockup deleted unsuccessfully.';
    }
  }
}
