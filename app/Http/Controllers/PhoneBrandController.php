<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\PhoneBrand;
use Illuminate\Http\Request;

class PhoneBrandController extends Controller
{
  //
  public function listPhoneBrand(Request $request)
  {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: *');

    $tableColumns = array(
      'id',
      'phoneBrandName',
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

    // $phoneBrand = PhoneBrand::where(function ($query) use ($search) { // where like search request
    //   return $query->where('phoneBrandName', 'like', '%' . $search . '%')
    //     ->orWhere('status', 'like', '%' . $search . '%')
    //     ->orWhere('id', 'like', '%' . $search . '%');
    // })
    //   //user is not deleted
    //   ->where('deleted', '0')
    //   //by order
    //   ->orderBy($tableColumns[$sortIndex], $sortOrder)
    //   ->offset($offset)
    //   ->limit($limit)
    //   ->get();

    // foreach ($phoneBrand as $p) {

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

    // $phoneBrandCount = PhoneBrand::where(function ($query) use ($search) { // where like search request
    //   return $query->where('phoneBrandName', 'like', '%' . $search . '%')
    //     ->orWhere('status', 'like', '%' . $search . '%')
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

    $phoneBrand = PhoneBrand::where('deleted', '0');
    $phoneBrand = $phoneBrand->where(function ($query) use ($search) {
      return $query->where('phoneBrandName', 'like', '%' . $search . '%')
      ->orWhere('status', 'like', '%' . $search . '%')
      ->orWhere('id', 'like', '%' . $search . '%');
    })
      ->orderBy($tableColumns[$sortIndex], $sortOrder);
    $phoneBrandCount = $phoneBrand->count();
    $phoneBrand = $phoneBrand->offset($offset)
      ->limit($limit)
      ->get();

    foreach ($phoneBrand as $p) {

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
      'recordsTotal'    => $phoneBrandCount,
      'recordsFiltered' => $phoneBrandCount,
      'data'            => $phoneBrand,
    ];

    // reponse must be in  array
    return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
  }

  public function addPhoneBrand(Request $request)
  {

    $phoneBrand = PhoneBrand::where('phoneBrandName', $request->phoneBrandName)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')
    if ($phoneBrand > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Phone Brand already in use.'
      ));
    }

    $phoneBrand = new PhoneBrand();
    $phoneBrand->phoneBrandName = $request->phoneBrandName;

    switch ($request->status) {
      case "DISABLED":
        // code block
        $phoneBrand->status = 0;
        break;
      case "ACTIVE":
        // code block
        $phoneBrand->status = 1;
        break;
      default:
        // code block
    }
    $phoneBrand->save();


    $auditLog = new AuditLog();
    $auditLog->agent = auth()->user()->id;
    $auditLog->action = "Added Phone Brand";
    $auditLog->table = "phoneBrand";
    $auditLog->nID =   $phoneBrand->id . " | " . $request->phoneBrandName . " | " . $request->status;
    $auditLog->save();

    return json_encode(array(
      'success' => true,
      'message' => 'Phone Brand Name added successfully.'
    ));
  }

  public function getEditPhoneBrand(Request $request)
  {
    $getPhoneBrand = PhoneBrand::where('id', $request->id)->first();
    return json_encode($getPhoneBrand);
  }

  public function editPhoneBrand(Request $request)
  {

    $phoneBrand = PhoneBrand::where('phoneBrandName', $request->phoneBrandName)->where('id', '!=', $request->id)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')
    if ($phoneBrand > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Phone Brand already in use.'
      ));
    }



    $phoneBrand = PhoneBrand::where('id', $request->id)->first();
    if (!empty($phoneBrand) || $phoneBrand != null) {

      $phoneBrand->phoneBrandName = $request->phoneBrandName;
      switch ($request->status) {
        case 0:
          // code block
          $phoneBrand->status = 0;
          break;
        case 1:
          // code block
          $phoneBrand->status = 1;
          break;
        default:
          // code block
      }

      $phoneBrand->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Edited ID #" . " $phoneBrand->id " . "Phone Brand";
      $auditLog->table = "phoneBrand";
      $auditLog->nID =  $phoneBrand->id . " | " . $request->phoneBrandName . " | " . $request->status;
      $auditLog->ip = \Request::ip();
      $auditLog->save();


      return json_encode(array(
        'success' => true,
        'message' => 'Phone Brand updated successfully.'
      ));
    } else {
      return json_encode(array(
        'success' => false,
        'message' => 'Phone Brand not found.'
      ));
    }
  }

  public function deletePhoneBrand(Request $request)
  {
    $deletePhoneBrand = PhoneBrand::where('id', $request->id)->first();

    if ($deletePhoneBrand) {


      $deletePhoneBrand->deleted = 1;
      $deletePhoneBrand->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Deleted ID #" . " $deletePhoneBrand->id " . "Phone Brand";
      $auditLog->table = "phoneBrand";
      $auditLog->nID = "Deleted =" . $deletePhoneBrand->deleted;
      $auditLog->ip = \Request::ip();
      $auditLog->save();
      return 'Phone Brand deleted successfully.';
    } else {

      return 'Phone Brand deleted unsuccessfully.';
    }
  }
}
