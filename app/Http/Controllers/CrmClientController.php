<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\CrmClient;
use Illuminate\Http\Request;

class CrmClientController extends Controller
{
  //
  public function listCrmClient(Request $request)
  {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: *');

    $tableColumns = array(
      'id',
      'clientName',
      'clientDescription',
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

    // $crmClient = CrmClient::where(function ($query) use ($search) { // where like search request
    //   return $query->where('clientName', 'like', '%' . $search . '%')
    //     ->orWhere('clientDescription', 'like', '%' . $search . '%')
    //     ->orWhere('id', 'like', '%' . $search . '%');
    // })
    //   //user is not deleted
    //   ->where('deleted', '0')
    //   //by order
    //   ->orderBy($tableColumns[$sortIndex], $sortOrder)
    //   ->offset($offset)
    //   ->limit($limit)
    //   ->get();

    // foreach ($crmClient as $p) {

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

    // $crmClientCount = CrmClient::where(function ($query) use ($search) { // where like search request
    //   return $query->where('clientName', 'like', '%' . $search . '%')
    //     ->orWhere('clientDescription', 'like', '%' . $search . '%')
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

    $crmClient = CrmClient::where('deleted', '0');
    $crmClient = $crmClient->where(function ($query) use ($search) {
      return $query->where('clientName', 'like', '%' . $search . '%')
        ->orWhere('clientDescription', 'like', '%' . $search . '%')
        ->orWhere('id', 'like', '%' . $search . '%');
    })
      ->orderBy($tableColumns[$sortIndex], $sortOrder);
    $crmClientCount = $crmClient->count();
    $crmClient = $crmClient->offset($offset)
      ->limit($limit)
      ->get();

    foreach ($crmClient as $p) {

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
      'recordsTotal'    => $crmClientCount,
      'recordsFiltered' => $crmClientCount,
      'data'            => $crmClient,
    ];

    // reponse must be in  array
    return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
  }

  public function addCrmClient(Request $request)
  {

    $clientName = CrmClient::where('clientName', $request->clientName)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')
    if ($clientName > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Client Name already in use.'
      ));
    }

    $crmClient = new CrmClient();
    $crmClient->clientName = $request->clientName;
    $crmClient->clientDescription = $request->clientDescription;


    switch ($request->status) {
      case "DISABLED":
        // code block
        $crmClient->status = 0;
        break;
      case "ACTIVE":
        // code block
        $crmClient->status = 1;
        break;
      default:
        // code block
    }

    $crmClient->save();

    $auditLog = new AuditLog();
    $auditLog->agent = auth()->user()->id;
    $auditLog->action = "Added Client";
    $auditLog->table = "crmClient";
    $auditLog->nID = $crmClient->id . " | " . $request->clientName . " | " . $request->clientDescription . " | " . $request->status;
    $auditLog->ip = \Request::ip();
    $auditLog->save();

    return json_encode(array(
      'success' => true,
      'message' => 'Client added successfully.'
    ));
  }

  public function getEditCrmClient(Request $request)
  {
    $getCrmClient = CrmClient::where('id', $request->id)->first();
    return json_encode($getCrmClient);
  }

  public function editCrmClient(Request $request)
  {

    $clientName = CrmClient::where('clientName', $request->clientName)->where('id', '!=', $request->id)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')
    if ($clientName > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Client Name already in use.'
      ));
    }

    $crmClient = CrmClient::where('id', $request->id)->first();
    if (!empty($crmClient) || $crmClient != null) {

      // $crmClient->clientName = $request->clientName;
      $crmClient->clientDescription = $request->clientDescription;
      switch ($request->status) {
        case 0:
          // code block
          $crmClient->status = 0;
          break;
        case 1:
          // code block
          $crmClient->status = 1;
          break;
        default:
          // code block
      }

      $crmClient->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Edited ID #" . " $crmClient->id " . "CRM Client";
      $auditLog->table = "crmClient";
      $auditLog->nID = $crmClient->id . " | " . $request->clientName . " | " . $request->clientDescription . " | " . $request->status;
      $auditLog->ip = \Request::ip();
      $auditLog->save();




      return json_encode(array(
        'success' => true,
        'message' => 'Client updated successfully.'
      ));
    } else {
      return json_encode(array(
        'success' => false,
        'message' => 'Client  not found.'
      ));
    }
  }

  public function deleteCrmClient(Request $request)
  {
    $deleteCrmClient = CrmClient::where('id', $request->id)->first();

    if ($deleteCrmClient) {


      $deleteCrmClient->deleted = 1;
      $deleteCrmClient->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Deleted ID #" . " $deleteCrmClient->id " . "CRM Client";
      $auditLog->table = "crmCLient";
      $auditLog->nID = "Deleted =" . $deleteCrmClient->deleted;
      $auditLog->ip = \Request::ip();
      $auditLog->save();
      return 'Client deleted successfully.';
    } else {

      return 'Client deleted unsuccessfully.';
    }
  }
}
