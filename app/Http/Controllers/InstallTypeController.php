<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\InstallType;
use Illuminate\Http\Request;

class InstallTypeController extends Controller
{
  //
  public function listInstallType(Request $request)
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

    // $installType = InstallType::where(function ($query) use ($search) { // where like search request
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

    // foreach ($installType as $p) {

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

    // $installTypeCount = InstallType::where(function ($query) use ($search) { // where like search request
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

    $installType = InstallType::where('deleted', '0');
    $installType = $installType->where(function ($query) use ($search) {
      return $query->where('product', 'like', '%' . $search . '%')
        ->orWhere('statusName', 'like', '%' . $search . '%')
        ->orWhere('id', 'like', '%' . $search . '%');
    })
      ->orderBy($tableColumns[$sortIndex], $sortOrder);
    $installTypeCount = $installType->count();
    $installType = $installType->offset($offset)
      ->limit($limit)
      ->get();

    foreach ($installType as $p) {

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
      'recordsTotal'    => $installTypeCount,
      'recordsFiltered' => $installTypeCount,
      'data'            => $installType,
    ];

    // reponse must be in  array
    return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
  }

  public function addInstallType(Request $request)
  {
    $installTypeName = InstallType::where('statusName', $request->installTypeName)->where('product', $request->product)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')
    if ($installTypeName > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Install Type Name already in use.'
      ));
    }

    $installType = new InstallType();
    $installType->statusName = $request->installTypeName;
    $installType->product = $request->product;
    $installType->statusDefinition = $request->installTypeDescription;

    switch ($request->status) {
      case "DISABLED":
        // code block
        $installType->status = 0;
        break;
      case "ACTIVE":
        // code block
        $installType->status = 1;
        break;
      default:
        // code block
    }

    $installType->save();

    $auditLog = new AuditLog();
    $auditLog->agent = auth()->user()->id;
    $auditLog->action = "Added Install Type";
    $auditLog->table = "installType";
    $auditLog->nID = $installType->id . " | " . $request->installTypeName . " | " . $request->product . " | " . $request->installTypeDescription . " | " . $request->status;
    $auditLog->save();

    return json_encode(array(
      'success' => true,
      'message' => 'Install Type added successfully.'
    ));
  }

  public function getEditInstallType(Request $request)
  {
    $getInstallType = InstallType::where('id', $request->id)->first();
    return json_encode($getInstallType);
  }

  public function editInstallType(Request $request)
  {

    $installTypeName = InstallType::where('statusName', $request->installTypeName)->where('product', $request->product)->where('id', '!=', $request->id)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')

    // dd($productName);
    if ($installTypeName > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Install Type already in use.'
      ));
    }


    $installType = InstallType::where('id', $request->id)->first();
    if (!empty($installType) || $installType != null) {

      $installType->statusName = $request->installTypeName;
      $installType->product = $request->product;
      $installType->statusDefinition = $request->installTypeDescription;

      switch ($request->status) {
        case 0:
          // code block
          $installType->status = 0;
          break;
        case 1:
          // code block
          $installType->status = 1;
          break;
        default:
          // code block
      }

      $installType->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Edited ID #" . " $installType->id " . "Install Type";
      $auditLog->table = "installType";
      $auditLog->nID =  $installType->id . " | " . $request->installTypeName . " | " . $request->product . " | " . $request->installTypeDescription . " | " . $request->status;
      $auditLog->ip = \Request::ip();
      $auditLog->save();
      return json_encode(array(
        'success' => true,
        'message' => 'Install Type updated successfully.'
      ));
    } else {
      return json_encode(array(
        'success' => false,
        'message' => 'Install not found.'
      ));
    }
  }

  public function deleteInstallType(Request $request)
  {
    $deleteInstallType = InstallType::where('id', $request->id)->first();

    if ($deleteInstallType) {


      $deleteInstallType->deleted = 1;
      $deleteInstallType->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Deleted ID #" . " $deleteInstallType->id " . "Install Type";
      $auditLog->table = "installType";
      $auditLog->nID = "Deleted =" . $deleteInstallType->deleted;
      $auditLog->ip = \Request::ip();
      $auditLog->save();
      return 'Install Type deleted successfully.';
    } else {

      return 'Install Type deleted unsuccessfully.';
    }
  }
}
