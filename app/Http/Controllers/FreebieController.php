<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Freebie;
use Illuminate\Http\Request;

class FreebieController extends Controller
{
  //
  public function listFreebie(Request $request)
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

    // $freebie = Freebie::where(function ($query) use ($search) { // where like search request
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

    // foreach ($freebie as $p) {

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

    // $freebieCount = Freebie::where(function ($query) use ($search) { // where like search request
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

    $freebie = Freebie::where('deleted', '0');
    $freebie = $freebie->where(function ($query) use ($search) {
      return $query->where('product', 'like', '%' . $search . '%')
      ->orWhere('statusName', 'like', '%' . $search . '%')
      ->orWhere('id', 'like', '%' . $search . '%');
    })
      ->orderBy($tableColumns[$sortIndex], $sortOrder);
    $freebieCount = $freebie->count();
    $freebie = $freebie->offset($offset)
      ->limit($limit)
      ->get();

    foreach ($freebie as $p) {

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
      'recordsTotal'    => $freebieCount,
      'recordsFiltered' => $freebieCount,
      'data'            => $freebie,
    ];

    // reponse must be in  array
    return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
  }

  public function addFreebie(Request $request)
  {
    $freebieName = Freebie::where('statusName', $request->freebieName)->where('product', $request->product)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')
    if ($freebieName > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Freebie Name already in use.'
      ));
    }

    $freebie = new Freebie();
    $freebie->statusName = $request->freebieName;
    $freebie->product = $request->product;
    $freebie->statusDefinition = $request->freebieDescription;

    switch ($request->status) {
      case "DISABLED":
        // code block
        $freebie->status = 0;
        break;
      case "ACTIVE":
        // code block
        $freebie->status = 1;
        break;
      default:
        // code block
    }

    $freebie->save();

    $auditLog = new AuditLog();
    $auditLog->agent = auth()->user()->id;
    $auditLog->action = "Added Freebie";
    $auditLog->table = "freebie";
    $auditLog->nID = $freebie->id . " | " . $request->freebieName . " | " . $request->product . " | " . $request->freebieDescription . " | " . $request->status;
    $auditLog->ip = \Request::ip();
    $auditLog->save();

    return json_encode(array(
      'success' => true,
      'message' => 'Freebie added successfully.'
    ));
  }

  public function getEditFreebie(Request $request)
  {
    $getFreebie = Freebie::where('id', $request->id)->first();
    return json_encode($getFreebie);
  }

  public function editFreebie(Request $request)
  {

    $freebieName = Freebie::where('statusName', $request->freebieName)->where('id', '!=', $request->id)->where('product', $request->product)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')

    // dd($productName);
    if ($freebieName > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Freebie Name already in use.'
      ));
    }


    $freebie = Freebie::where('id', $request->id)->first();
    if (!empty($technology) || $freebie != null) {

      $freebie->statusName = $request->freebieName;
      $freebie->product = $request->product;
      $freebie->statusDefinition = $request->freebieDescription;

      switch ($request->status) {
        case 0:
          // code block
          $freebie->status = 0;
          break;
        case 1:
          // code block
          $freebie->status = 1;
          break;
        default:
          // code block
      }

      $freebie->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Edited ID #" . " $freebie->id " . "Freebie";
      $auditLog->table = "freebie";
      $auditLog->nID = $freebie->id . " | " . $request->freebieName . " | " . $request->product . " | " . $request->freebieDescription . " | " . $request->status;
      $auditLog->ip = \Request::ip();
      $auditLog->save();

      return json_encode(array(
        'success' => true,
        'message' => 'Freebie updated successfully.'
      ));
    } else {
      return json_encode(array(
        'success' => false,
        'message' => 'Freebie not found.'
      ));
    }
  }

  public function deleteFreebie(Request $request)
  {
    $deleteFreebie = Freebie::where('id', $request->id)->first();

    if ($deleteFreebie) {


      $deleteFreebie->deleted = 1;
      $deleteFreebie->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Deleted ID #" . " $deleteFreebie->id " . "Freebie";
      $auditLog->table = "freebie";
      $auditLog->nID = $deleteFreebie->deleted;
      $auditLog->ip = \Request::ip();
      $auditLog->save();
      return 'Freebie deleted successfully.';
    } else {

      return 'Freebie deleted unsuccessfully.';
    }
  }
}
