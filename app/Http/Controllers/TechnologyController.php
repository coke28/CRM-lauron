<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Technology;
use Illuminate\Http\Request;

class TechnologyController extends Controller
{
  //
  //
  public function listTechnology(Request $request)
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

    // $technology = Technology::where(function ($query) use ($search) { // where like search request
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

    // foreach ($technology as $p) {

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

    // $technologyCount = Technology::where(function ($query) use ($search) { // where like search request
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

    $technology = Technology::where('deleted', '0');
    $technology = $technology->where(function ($query) use ($search) {
      return $query->where('product', 'like', '%' . $search . '%')
        ->orWhere('statusName', 'like', '%' . $search . '%')
        ->orWhere('id', 'like', '%' . $search . '%');
    })
      ->orderBy($tableColumns[$sortIndex], $sortOrder);
    $technologyCount = $technology->count();
    $technology = $technology->offset($offset)
      ->limit($limit)
      ->get();

    foreach ($technology as $p) {

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
      'recordsTotal'    => $technologyCount,
      'recordsFiltered' => $technologyCount,
      'data'            => $technology,
    ];

    // reponse must be in  array
    return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
  }

  public function addTechnology(Request $request)
  {
    $technologyName = Technology::where('statusName', $request->technologyName)->where('product', $request->product)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')
    if ($technologyName > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Technology Name already in use.'
      ));
    }

    $technology = new Technology();
    $technology->statusName = $request->technologyName;
    $technology->product = $request->product;
    $technology->statusDefinition = $request->technologyDescription;

    switch ($request->status) {
      case "DISABLED":
        // code block
        $technology->status = 0;
        break;
      case "ACTIVE":
        // code block
        $technology->status = 1;
        break;
      default:
        // code block
    }

    $technology->save();

    $auditLog = new AuditLog();
    $auditLog->agent = auth()->user()->id;
    $auditLog->action = "Added Technology";
    $auditLog->table = "technology";
    $auditLog->nID = $technology->id . " | " . $request->technologyName . " | " . $request->product . " | " . $request->technologyDescription . " | " . $request->status;
    $auditLog->ip = \Request::ip();

    $auditLog->save();

    return json_encode(array(
      'success' => true,
      'message' => 'Technology added successfully.'
    ));
  }

  public function getEditTechnology(Request $request)
  {
    $getTechnology = Technology::where('id', $request->id)->first();
    return json_encode($getTechnology);
  }

  public function editTechnology(Request $request)
  {

    $technologyName = Technology::where('statusName', $request->technologyName)->where('id', '!=', $request->id)->where('product', $request->product)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')

    // dd($productName);
    if ($technologyName > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Technology Name already in use.'
      ));
    }


    $technology = Technology::where('id', $request->id)->first();
    if (!empty($technology) || $technology != null) {

      $technology->statusName = $request->technologyName;
      $technology->product = $request->product;
      $technology->statusDefinition = $request->technologyDescription;

      switch ($request->status) {
        case 0:
          // code block
          $technology->status = 0;
          break;
        case 1:
          // code block
          $technology->status = 1;
          break;
        default:
          // code block
      }

      $technology->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Edited ID #" . " $technology->id " . "Technology";
      $auditLog->table = "technology";
      $auditLog->nID = $technology->id . " | " . $request->technologyName . " | " . $request->product . " | " . $request->technologyDescription . " | " . $request->status;
      $auditLog->ip = \Request::ip();
      $auditLog->save();
      return json_encode(array(
        'success' => true,
        'message' => 'Technology updated successfully.'
      ));
    } else {
      return json_encode(array(
        'success' => false,
        'message' => 'Technology not found.'
      ));
    }
  }

  public function deleteTechnology(Request $request)
  {
    $deleteTechnology = Technology::where('id', $request->id)->first();

    if ($deleteTechnology) {


      $deleteTechnology->deleted = 1;
      $deleteTechnology->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Deleted ID #" . " $deleteTechnology->id " . "Technology";
      $auditLog->table = "technology";
      $auditLog->nID = "Deleted =" . $deleteTechnology->deleted;
      $auditLog->ip = \Request::ip();
      $auditLog->save();
      return 'Technology deleted successfully.';
    } else {

      return 'Technology deleted unsuccessfully.';
    }
  }
}
