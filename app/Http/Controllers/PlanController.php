<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
  //
  public function listPlan(Request $request)
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

    // $plan = Plan::where(function ($query) use ($search) { // where like search request
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

    // foreach ($plan as $p) {

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

    // $planCount = Plan::where(function ($query) use ($search) { // where like search request
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


    $plan = Plan::where('deleted', '0');
    $plan = $plan->where(function ($query) use ($search) {
      return $query->where('product', 'like', '%' . $search . '%')
        ->orWhere('statusName', 'like', '%' . $search . '%')
        ->orWhere('id', 'like', '%' . $search . '%');
    })
      ->orderBy($tableColumns[$sortIndex], $sortOrder);
    $planCount = $plan->count();
    $plan = $plan->offset($offset)
      ->limit($limit)
      ->get();

    foreach ($plan as $p) {

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
      'recordsTotal'    => $planCount,
      'recordsFiltered' => $planCount,
      'data'            => $plan,
    ];

    // reponse must be in  array
    return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
  }

  public function addPlan(Request $request)
  {
    $productName = Plan::where('statusName', $request->planName)->where('product', $request->product)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')
    if ($productName > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Plan Name already in use.'
      ));
    }

    $plan = new Plan();
    $plan->statusName = $request->planName;
    $plan->product = $request->product;
    $plan->statusDefinition = $request->planDescription;

    switch ($request->status) {
      case "DISABLED":
        // code block
        $plan->status = 0;
        break;
      case "ACTIVE":
        // code block
        $plan->status = 1;
        break;
      default:
        // code block
    }

    $plan->save();

    $auditLog = new AuditLog();
    $auditLog->agent = auth()->user()->id;
    $auditLog->action = "Added Plan";
    $auditLog->table = "plan";
    $auditLog->nID = $plan->id . " | " . $request->planName . " | " . $request->product . " | " . $request->planDescription . " | " . $request->status;
    $auditLog->ip = \Request::ip();

    $auditLog->save();

    return json_encode(array(
      'success' => true,
      'message' => 'Plan added successfully.'
    ));
  }

  public function getEditPlan(Request $request)
  {
    $getPlan = Plan::where('id', $request->id)->first();
    return json_encode($getPlan);
  }

  public function editPlan(Request $request)
  {

    $productName = Plan::where('statusName', $request->planName)->where('id', '!=', $request->id)->where('product', $request->product)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')

    // dd($productName);
    if ($productName > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Plan Name already in use.'
      ));
    }


    $plan = Plan::where('id', $request->id)->first();
    if (!empty($plan) || $plan != null) {

      $plan->statusName = $request->planName;
      $plan->product = $request->product;
      $plan->statusDefinition = $request->planDescription;

      switch ($request->status) {
        case 0:
          // code block
          $plan->status = 0;
          break;
        case 1:
          // code block
          $plan->status = 1;
          break;
        default:
          // code block
      }

      $plan->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Edited ID #" . " $plan->id " . "Plan Breakdown";
      $auditLog->table = "planBreakdown";
      $auditLog->nID =  $plan->id . " | " . $request->planBreakdownName . " | " . $request->product . " | " . $request->planBreakdownDescription . " | " . $request->status;
      $auditLog->ip = \Request::ip();
      $auditLog->save();
      return json_encode(array(
        'success' => true,
        'message' => 'Plan updated successfully.'
      ));
    } else {
      return json_encode(array(
        'success' => false,
        'message' => 'Plan not found.'
      ));
    }
  }

  public function deletePlan(Request $request)
  {
    $deletePlan = Plan::where('id', $request->id)->first();

    if ($deletePlan) {


      $deletePlan->deleted = 1;
      $deletePlan->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Deleted ID #" . " $deletePlan->id " . "Plan";
      $auditLog->table = "plan";
      $auditLog->nID = "Deleted =" . $deletePlan->deleted;
      $auditLog->ip = \Request::ip();
      $auditLog->save();
      return 'Plan deleted successfully.';
    } else {

      return 'Plan deleted unsuccessfully.';
    }
  }
}
