<?php

namespace App\Http\Controllers;

use App\Models\ApplicationType;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class ApplicationTypeController extends Controller
{
  //
  public function listApplicationType(Request $request)
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


    $applicationType = ApplicationType::where('deleted', '0');
    $applicationType = $applicationType->where(function ($query) use ($search) {
      return $query->where('product', 'like', '%' . $search . '%')
        ->orWhere('statusName', 'like', '%' . $search . '%')
        ->orWhere('id', 'like', '%' . $search . '%');
    })
      ->orderBy($tableColumns[$sortIndex], $sortOrder);
    $applicationTypeCount = $applicationType->count();
    $applicationType = $applicationType->offset($offset)
      ->limit($limit)
      ->get();

    foreach ($applicationType as $p) {

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
      'recordsTotal'    => $applicationTypeCount,
      'recordsFiltered' => $applicationTypeCount,
      'data'            => $applicationType,
    ];

    // reponse must be in  array
    return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
  }

  public function addApplicationType(Request $request)
  {
    $applicationTypeName = ApplicationType::where('statusName', $request->applicationTypeName)->where('product', $request->product)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')
    if ($applicationTypeName > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Application Type Name already in use.'
      ));
    }

    $applicationType = new ApplicationType();
    $applicationType->statusName = $request->applicationTypeName;
    $applicationType->product = $request->product;
    $applicationType->statusDefinition = $request->applicationTypeDescription;

    switch ($request->status) {
      case "DISABLED":
        // code block
        $applicationType->status = 0;
        break;
      case "ACTIVE":
        // code block
        $applicationType->status = 1;
        break;
      default:
        // code block
    }

    $applicationType->save();

    $auditLog = new AuditLog();
    $auditLog->agent = auth()->user()->id;
    $auditLog->action = "Added Application Type";
    $auditLog->table = "applicationType";
    $auditLog->nID = $applicationType->id . " | " . $request->product . " | " . $request->applicationTypeName . " | " . $request->applicationTypeDescription;
    $auditLog->ip = \Request::ip();
    $auditLog->save();

    return json_encode(array(
      'success' => true,
      'message' => 'Application Type added successfully.'
    ));
  }

  public function getEditApplicationType(Request $request)
  {
    $getApplicationType = ApplicationType::where('id', $request->id)->first();
    return json_encode($getApplicationType);
  }

  public function editApplicationType(Request $request)
  {

    $applicationTypeName = ApplicationType::where('statusName', $request->applicationTypeName)->where('id', '!=', $request->id)->where('product', $request->product)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')

    // dd($productName);
    if ($applicationTypeName > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Application Type Name already in use.'
      ));
    }


    $applicationType = ApplicationType::where('id', $request->id)->first();
    if (!empty($applicationType) || $applicationType != null) {

      $applicationType->statusName = $request->applicationTypeName;
      $applicationType->product = $request->product;
      $applicationType->statusDefinition = $request->applicationTypeDescription;

      switch ($request->status) {
        case 0:
          // code block
          $applicationType->status = 0;
          break;
        case 1:
          // code block
          $applicationType->status = 1;
          break;
        default:
          // code block
      }

      $applicationType->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Edited ID #" . " $applicationType->id " . "Application Type";
      $auditLog->table = "applicationType";
      $auditLog->nID = $applicationType->id . " | " . $request->product . " | " . $request->applicationTypeName . " | " . $request->applicationTypeDescription;
      $auditLog->ip = \Request::ip();
      $auditLog->save();

      return json_encode(array(
        'success' => true,
        'message' => 'Application Type updated successfully.'
      ));
    } else {
      return json_encode(array(
        'success' => false,
        'message' => 'Application Type not found.'
      ));
    }
  }

  public function deleteApplicationType(Request $request)
  {
    $deleteApplicationType = ApplicationType::where('id', $request->id)->first();

    if ($deleteApplicationType) {


      $deleteApplicationType->deleted = 1;
      $deleteApplicationType->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Deleted ID #" . " $deleteApplicationType->id " . "Application Type";
      $auditLog->table = "applicationType";
      $auditLog->nID = "Deleted =" . $deleteApplicationType->deleted;
      $auditLog->ip = \Request::ip();
      $auditLog->save();

      return 'Application Type deleted successfully.';
    } else {

      return 'Application Type deleted unsuccessfully.';
    }
  }
}
