<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\InstallationFee;
use Illuminate\Http\Request;

class InstallationFeeController extends Controller
{
  //
  public function listInstallationFee(Request $request)
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

    // $installationFee = InstallationFee::where(function ($query) use ($search) { // where like search request
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

    // foreach ($installationFee as $p) {

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

    // $installationFeeCount = InstallationFee::where(function ($query) use ($search) { // where like search request
    //   return $query->where('product', 'like', '%' . $search . '%')
    //   ->orWhere('statusName', 'like', '%' . $search . '%')
    //   ->orWhere('id', 'like', '%' . $search . '%');
    // })
    //   //user is not deleted
    //   ->where('deleted', '0')
    //   //by order
    //   ->orderBy($tableColumns[$sortIndex], $sortOrder)
    //   ->offset($offset)
    //   ->limit($limit)
    //   ->get()
    //   ->count();

    $installationFee = InstallationFee::where('deleted', '0');
    $installationFee = $installationFee->where(function ($query) use ($search) {
      return $query->where('product', 'like', '%' . $search . '%')
        ->orWhere('statusName', 'like', '%' . $search . '%')
        ->orWhere('id', 'like', '%' . $search . '%');
    })
      ->orderBy($tableColumns[$sortIndex], $sortOrder);
    $installationFeeCount = $installationFee->count();
    $installationFee = $installationFee->offset($offset)
      ->limit($limit)
      ->get();

    foreach ($installationFee as $p) {

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
      'recordsTotal'    => $installationFeeCount,
      'recordsFiltered' => $installationFeeCount,
      'data'            => $installationFee,
    ];

    // reponse must be in  array
    return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
  }

  public function addInstallationFee(Request $request)
  {
    $installationFeeName = InstallationFee::where('statusName', $request->installationFeeName)->where('product', $request->product)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')
    if ($installationFeeName > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Installation Fee Name  already in use.'
      ));
    }

    $installationFee = new InstallationFee();
    $installationFee->statusName = $request->installationFeeName;
    $installationFee->product = $request->product;
    $installationFee->statusDefinition = $request->installationFeeDescription;

    switch ($request->status) {
      case "DISABLED":
        // code block
        $installationFee->status = 0;
        break;
      case "ACTIVE":
        // code block
        $installationFee->status = 1;
        break;
      default:
        // code block
    }

    $installationFee->save();

    $auditLog = new AuditLog();
    $auditLog->agent = auth()->user()->id;
    $auditLog->action = "Added Installation Fee";
    $auditLog->table = "installationFee";
    $auditLog->nID = $installationFee->id . " | " . $request->installationFeeName . " | " . $request->product . " | " . $request->installationFeeDescription . " | " . $request->status;
    $auditLog->save();

    return json_encode(array(
      'success' => true,
      'message' => 'Installation Fee added successfully.'
    ));
  }

  public function getEditInstallationFee(Request $request)
  {
    $getInstallationFee = InstallationFee::where('id', $request->id)->first();
    return json_encode($getInstallationFee);
  }

  public function editInstallationFee(Request $request)
  {

    $installationFeeName = InstallationFee::where('statusName', $request->installationFeeName)->where('product', $request->product)->where('id', '!=', $request->id)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')

    // dd($productName);
    if ($installationFeeName > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Installation Fee Name already in use.'
      ));
    }


    $installationFee = InstallationFee::where('id', $request->id)->first();



    if (!empty($installationFee) || $installationFee != null) {

      $installationFee->statusName = $request->installationFeeName;
      $installationFee->product = $request->product;
      $installationFee->statusDefinition = $request->installationFeeDescription;


      switch ($request->status) {
        case 0:
          // code block
          $installationFee->status = 0;
          break;
        case 1:
          // code block
          $installationFee->status = 1;
          break;
        default:
          // code block
      }

      $installationFee->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Edited ID #" . " $installationFee->id " . "Installation Fee";
      $auditLog->table = "installationFee";
      $auditLog->nID = $installationFee->id . " | " . $request->installationFeeName . " | " . $request->product . " | " . $request->installationFeeDescription . " | " . $request->status;
      $auditLog->ip = \Request::ip();
      $auditLog->save();

      return json_encode(array(
        'success' => true,
        'message' => 'Installation Fee updated successfully.'
      ));
    } else {
      return json_encode(array(
        'success' => false,
        'message' => 'Installation Fee not found.'
      ));
    }
  }

  public function deleteInstallationFee(Request $request)
  {
    $deleteInstallationFee = InstallationFee::where('id', $request->id)->first();

    if ($deleteInstallationFee) {


      $deleteInstallationFee->deleted = 1;
      $deleteInstallationFee->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Deleted ID #" . " $deleteInstallationFee->id " . "Installation Fee";
      $auditLog->table = "installationFee";
      $auditLog->nID = "Deleted =" . $deleteInstallationFee->deleted;
      $auditLog->ip = \Request::ip();
      $auditLog->save();

      return 'Installation Fee deleted successfully.';
    } else {

      return 'Installation Fee deleted unsuccessfully.';
    }
  }
}
