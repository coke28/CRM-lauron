<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\PlanFee;
use Illuminate\Http\Request;

class PlanFeeController extends Controller
{
  //
  public function listPlanFee(Request $request)
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

    // $planFee = PlanFee::where(function ($query) use ($search) { // where like search request
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

    // foreach ($planFee as $p) {

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

    // $planFeeCount = PlanFee::where(function ($query) use ($search) { // where like search request
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

    $planFee = PlanFee::where('deleted', '0');
    $planFee = $planFee->where(function ($query) use ($search) {
      return $query->where('product', 'like', '%' . $search . '%')
        ->orWhere('statusName', 'like', '%' . $search . '%')
        ->orWhere('id', 'like', '%' . $search . '%');
    })
      ->orderBy($tableColumns[$sortIndex], $sortOrder);
    $planFeeCount = $planFee->count();
    $planFee = $planFee->offset($offset)
      ->limit($limit)
      ->get();

    foreach ($planFee as $p) {

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
      'recordsTotal'    => $planFeeCount,
      'recordsFiltered' => $planFeeCount,
      'data'            => $planFee,
    ];

    // reponse must be in  array
    return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
  }

  public function addPlanFee(Request $request)
  {
    $planFeeName = PlanFee::where('statusName', $request->planFeeName)->where('product', $request->product)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')
    if ($planFeeName > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Plan Fee already in use.'
      ));
    }

    $planFee = new PlanFee();
    $planFee->statusName = $request->planFeeName;
    $planFee->product = $request->product;
    $planFee->statusDefinition = $request->planFeeDescription;

    switch ($request->status) {
      case "DISABLED":
        // code block
        $planFee->status = 0;
        break;
      case "ACTIVE":
        // code block
        $planFee->status = 1;
        break;
      default:
        // code block
    }

    $planFee->save();

    $auditLog = new AuditLog();
    $auditLog->agent = auth()->user()->id;
    $auditLog->action = "Added Plan Fee";
    $auditLog->table = "planFee";
    $auditLog->nID = $planFee->id . " | " . $request->planFeeName . " | " . $request->product . " | " . $request->planFeeDescription . " | " . $request->status;
    $auditLog->ip = \Request::ip();

    $auditLog->save();

    return json_encode(array(
      'success' => true,
      'message' => 'Plan Fee added successfully.'
    ));
  }

  public function getEditPlanFee(Request $request)
  {
    $getPlanFee = PlanFee::where('id', $request->id)->first();
    return json_encode($getPlanFee);
  }

  public function editPlanFee(Request $request)
  {

    $planFeeName = PlanFee::where('statusName', $request->planFeeName)->where('id', '!=', $request->id)->where('product', $request->product)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')

    // dd($productName);
    if ($planFeeName > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Plan Fee Name already in use.'
      ));
    }


    $planFee = PlanFee::where('id', $request->id)->first();
    if (!empty($planFee) || $planFee != null) {

      $planFee->statusName = $request->planFeeName;
      $planFee->product = $request->product;
      $planFee->statusDefinition = $request->planFeeDescription;

      switch ($request->status) {
        case 0:
          // code block
          $planFee->status = 0;
          break;
        case 1:
          // code block
          $planFee->status = 1;
          break;
        default:
          // code block
      }

      $planFee->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Edited ID #" . " $planFee->id " . "Plan Fee";
      $auditLog->table = "planFee";
      $auditLog->nID =  $planFee->id . " | " . $request->planFeeName . " | " . $request->product . " | " . $request->planFeeDescription . " | " . $request->status;
      $auditLog->ip = \Request::ip();
      $auditLog->save();
      return json_encode(array(
        'success' => true,
        'message' => 'Plan Fee updated successfully.'
      ));
    } else {
      return json_encode(array(
        'success' => false,
        'message' => 'Plan Fee not found.'
      ));
    }
  }

  public function deletePlanFee(Request $request)
  {
    $deletePlanFee = PlanFee::where('id', $request->id)->first();

    if ($deletePlanFee) {


      $deletePlanFee->deleted = 1;
      $deletePlanFee->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Deleted ID #" . " $deletePlanFee->id " . "PlanFee";
      $auditLog->table = "planFee";
      $auditLog->nID = "Deleted =" . $deletePlanFee->deleted;
      $auditLog->ip = \Request::ip();
      $auditLog->save();
      return 'Plan Fee deleted successfully.';
    } else {

      return 'Plan Fee deleted unsuccessfully.';
    }
  }
}
