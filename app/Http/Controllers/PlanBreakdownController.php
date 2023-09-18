<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\PlanBreakdown;
use Illuminate\Http\Request;

class PlanBreakdownController extends Controller
{
  //
  public function listPlanBreakdown(Request $request)
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

    // $planBreakdown = PlanBreakdown::where(function ($query) use ($search) { // where like search request
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

    // foreach ($planBreakdown as $p) {

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

    // $planBreakdownCount = PlanBreakdown::where(function ($query) use ($search) { // where like search request
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

    $planBreakdown = PlanBreakdown::where('deleted', '0');
    $planBreakdown = $planBreakdown->where(function ($query) use ($search) {
      return $query->where('product', 'like', '%' . $search . '%')
        ->orWhere('statusName', 'like', '%' . $search . '%')
        ->orWhere('id', 'like', '%' . $search . '%');
    })
      ->orderBy($tableColumns[$sortIndex], $sortOrder);
    $planBreakdownCount = $planBreakdown->count();
    $planBreakdown = $planBreakdown->offset($offset)
      ->limit($limit)
      ->get();

    foreach ($planBreakdown as $p) {

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
      'recordsTotal'    => $planBreakdownCount,
      'recordsFiltered' => $planBreakdownCount,
      'data'            => $planBreakdown,
    ];

    // reponse must be in  array
    return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
  }

  public function addPlanBreakdown(Request $request)
  {
    $planBreakdownName = PlanBreakdown::where('statusName', $request->planBreakdownName)->where('product', $request->product)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')
    if ($planBreakdownName > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Plan Breakdown Name already in use.'
      ));
    }

    $planBreakdown = new PlanBreakdown();
    $planBreakdown->statusName = $request->planBreakdownName;
    $planBreakdown->product = $request->product;
    $planBreakdown->statusDefinition = $request->planBreakdownDescription;

    switch ($request->status) {
      case "DISABLED":
        // code block
        $planBreakdown->status = 0;
        break;
      case "ACTIVE":
        // code block
        $planBreakdown->status = 1;
        break;
      default:
        // code block
    }

    $planBreakdown->save();

    $auditLog = new AuditLog();
    $auditLog->agent = auth()->user()->id;
    $auditLog->action = "Added Plan Breakdown";
    $auditLog->table = "planBreakdown";
    $auditLog->nID = $planBreakdown->id . " | " . $request->planBreakdownName . " | " . $request->product . " | " . $request->planBreakdownDescription . " | " . $request->status;
    $auditLog->ip = \Request::ip();

    $auditLog->save();

    return json_encode(array(
      'success' => true,
      'message' => 'Plan Breakdown added successfully.'
    ));
  }

  public function getEditPlanBreakdown(Request $request)
  {
    $getPlanBreakdown = PlanBreakdown::where('id', $request->id)->first();
    return json_encode($getPlanBreakdown);
  }

  public function editPlanBreakdown(Request $request)
  {

    $planBreakdownName = PlanBreakdown::where('statusName', $request->planBreakdownName)->where('id', '!=', $request->id)->where('product', $request->product)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')
    if ($planBreakdownName > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Plan Breakdown Name already in use.'
      ));
    }


    $planBreakdown = PlanBreakdown::where('id', $request->id)->first();
    if (!empty($planBreakdown) || $planBreakdown != null) {

      $planBreakdown->statusName = $request->planBreakdownName;
      $planBreakdown->product = $request->product;
      $planBreakdown->statusDefinition = $request->planBreakdownDescription;

      switch ($request->status) {
        case 0:
          // code block
          $planBreakdown->status = 0;
          break;
        case 1:
          // code block
          $planBreakdown->status = 1;
          break;
        default:
          // code block
      }

      $planBreakdown->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Edited ID #" . " $planBreakdown->id " . "Plan Breakdown";
      $auditLog->table = "planBreakdown";
      $auditLog->nID =  $planBreakdown->id . " | " . $request->planBreakdownName . " | " . $request->product . " | " . $request->planBreakdownDescription . " | " . $request->status;
      $auditLog->ip = \Request::ip();
      $auditLog->save();
      return json_encode(array(
        'success' => true,
        'message' => 'Plan Breakdown updated successfully.'
      ));
    } else {
      return json_encode(array(
        'success' => false,
        'message' => 'Plan Breakdown not found.'
      ));
    }
  }

  public function deletePlanBreakdown(Request $request)
  {
    $deletePlanBreakdown = PlanBreakdown::where('id', $request->id)->first();

    if ($deletePlanBreakdown) {


      $deletePlanBreakdown->deleted = 1;
      $deletePlanBreakdown->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Deleted ID #" . " $deletePlanBreakdown->id " . "Plan Breakdown";
      $auditLog->table = "planBreakdown";
      $auditLog->nID = "Deleted =" . $deletePlanBreakdown->deleted;
      $auditLog->ip = \Request::ip();
      $auditLog->save();
      return 'Plan Breakdown deleted successfully.';
    } else {

      return 'Plan Breakdown deleted unsuccessfully.';
    }
  }
}
