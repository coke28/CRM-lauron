<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
  //
  public function listPaymentMethod(Request $request)
  {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: *');

    $tableColumns = array(
      'id',
      'merchant',
      'info',
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

    // $paymentMethod = PaymentMethod::where(function ($query) use ($search) { // where like search request
    //   return $query->where('merchant', 'like', '%' . $search . '%')
    //     ->orWhere('info', 'like', '%' . $search . '%')
    //     ->orWhere('id', 'like', '%' . $search . '%');
    // })
    //   //user is not deleted
    //   ->where('deleted', '0')
    //   //by order
    //   ->orderBy($tableColumns[$sortIndex], $sortOrder)
    //   ->offset($offset)
    //   ->limit($limit)
    //   ->get();

    // foreach ($paymentMethod as $p) {

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

    // $paymentMethodCount = PaymentMethod::where(function ($query) use ($search) { // where like search request
    //   return $query->where('merchant', 'like', '%' . $search . '%')
    //     ->orWhere('info', 'like', '%' . $search . '%')
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

    $paymentMethod = PaymentMethod::where('deleted', '0');
    $paymentMethod = $paymentMethod->where(function ($query) use ($search) {
      return $query->where('merchant', 'like', '%' . $search . '%')
      ->orWhere('info', 'like', '%' . $search . '%')
      ->orWhere('id', 'like', '%' . $search . '%');
    })
      ->orderBy($tableColumns[$sortIndex], $sortOrder);
    $paymentMethodCount = $paymentMethod->count();
    $paymentMethod = $paymentMethod->offset($offset)
      ->limit($limit)
      ->get();

    foreach ($paymentMethod as $p) {

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
      'recordsTotal'    => $paymentMethodCount,
      'recordsFiltered' => $paymentMethodCount,
      'data'            => $paymentMethod,
    ];

    // reponse must be in  array
    return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
  }

  public function addPaymentMethod(Request $request)
  {
    $paymentMethod = PaymentMethod::where('merchant', $request->paymentMethod)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')
    if ($paymentMethod > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Payment Method Name already in use.'
      ));
    }

    $paymentMethod = new PaymentMethod();
    $paymentMethod->merchant = $request->paymentMethod;
    $paymentMethod->info = $request->paymentMethodRemark;

    switch ($request->status) {
      case "DISABLED":
        // code block
        $paymentMethod->status = 0;
        break;
      case "ACTIVE":
        // code block
        $paymentMethod->status = 1;
        break;
      default:
        // code block
    }

    $paymentMethod->save();

    $auditLog = new AuditLog();
    $auditLog->agent = auth()->user()->id;
    $auditLog->action = "Added Payment Method";
    $auditLog->table = "paymentMethod";
    $auditLog->nID =  $paymentMethod->id . " | " . $request->paymentMethod . " | " . $request->paymentMethodRemark . " | " . $request->status;
    $auditLog->save();

    return json_encode(array(
      'success' => true,
      'message' => 'Payment Method added successfully.'
    ));
  }

  public function getEditPaymentMethod(Request $request)
  {
    $getPaymentMethod = PaymentMethod::where('id', $request->id)->first();
    return json_encode($getPaymentMethod);
  }

  public function editPaymentMethod(Request $request)
  {

    $paymentMethod = PaymentMethod::where('merchant', $request->paymentMethod)->where('id', '!=', $request->id)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')
    if ($paymentMethod > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Payment Method already in use.'
      ));
    }


    $paymentMethod = PaymentMethod::where('id', $request->id)->first();
    if (!empty($paymentMethod) || $paymentMethod != null) {

      $paymentMethod->merchant = $request->paymentMethod;
      $paymentMethod->info = $request->paymentMethodRemark;

      switch ($request->status) {
        case 0:
          // code block
          $paymentMethod->status = 0;
          break;
        case 1:
          // code block
          $paymentMethod->status = 1;
          break;
        default:
          // code block
      }

      $paymentMethod->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Edited ID #" . " $paymentMethod->id " . "Payment Method";
      $auditLog->table = "paymentMethod";
      $auditLog->nID =  $paymentMethod->id . " | " . $request->paymentMethod . " | " . $request->paymentMethodRemark . " | " . $request->status;
      $auditLog->ip = \Request::ip();
      $auditLog->save();


      return json_encode(array(
        'success' => true,
        'message' => 'Payment Method updated successfully.'
      ));
    } else {
      return json_encode(array(
        'success' => false,
        'message' => 'Payment Method not found.'
      ));
    }
  }

  public function deletePaymentMethod(Request $request)
  {
    $deletePaymentMethod = PaymentMethod::where('id', $request->id)->first();

    if ($deletePaymentMethod) {


      $deletePaymentMethod->deleted = 1;
      $deletePaymentMethod->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Deleted ID #" . " $deletePaymentMethod->id " . "Payment Method";
      $auditLog->table = "paymentMethod";
      $auditLog->nID = "Deleted =" . $deletePaymentMethod->deleted;
      $auditLog->ip = \Request::ip();
      $auditLog->save();
      return 'Payment Method deleted successfully.';
    } else {

      return 'Payment Method deleted unsuccessfully.';
    }
  }
}
