<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\PromoName;
use Illuminate\Http\Request;

class PromoNameController extends Controller
{
  //
  //
  public function listPromoName(Request $request)
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

    // $promoName = PromoName::where(function ($query) use ($search) { // where like search request
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

    // foreach ($promoName as $p) {

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

    // $promoNameCount = PromoName::where(function ($query) use ($search) { // where like search request
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

    $promoName = PromoName::where('deleted', '0');
    $promoName = $promoName->where(function ($query) use ($search) {
      return $query->where('product', 'like', '%' . $search . '%')
        ->orWhere('statusName', 'like', '%' . $search . '%')
        ->orWhere('id', 'like', '%' . $search . '%');
    })
      ->orderBy($tableColumns[$sortIndex], $sortOrder);
    $promoNameCount = $promoName->count();
    $promoName = $promoName->offset($offset)
      ->limit($limit)
      ->get();

    foreach ($promoName as $p) {

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
      'recordsTotal'    => $promoNameCount,
      'recordsFiltered' => $promoNameCount,
      'data'            => $promoName,
    ];

    // reponse must be in  array
    return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
  }

  public function addPromoName(Request $request)
  {
    $promoName = PromoName::where('statusName', $request->promoName)->where('product', $request->product)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')
    if ($promoName > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Promo Name already in use.'
      ));
    }

    $promoName = new PromoName();
    $promoName->statusName = $request->promoName;
    $promoName->product = $request->product;
    $promoName->statusDefinition = $request->promoDescription;

    switch ($request->status) {
      case "DISABLED":
        // code block
        $promoName->status = 0;
        break;
      case "ACTIVE":
        // code block
        $promoName->status = 1;
        break;
      default:
        // code block
    }

    $promoName->save();

    $auditLog = new AuditLog();
    $auditLog->agent = auth()->user()->id;
    $auditLog->action = "Added Promo Name";
    $auditLog->table = "promoName";
    $auditLog->nID = $promoName->id . " | " . $request->productName . " | " . $request->product . " | " . $request->promoDescription . " | " . $request->status;
    $auditLog->ip = \Request::ip();

    $auditLog->save();

    return json_encode(array(
      'success' => true,
      'message' => 'Promo Name added successfully.'
    ));
  }

  public function getEditPromoName(Request $request)
  {
    $getPromoName = PromoName::where('id', $request->id)->first();
    return json_encode($getPromoName);
  }

  public function editPromoName(Request $request)
  {

    $promoName = PromoName::where('statusName', $request->promoName)->where('id', '!=', $request->id)->where('product', $request->product)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')

    // dd($productName);
    if ($promoName > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Promo Name already in use.'
      ));
    }


    $promoName = PromoName::where('id', $request->id)->first();
    if (!empty($promoName) || $promoName != null) {

      $promoName->statusName = $request->promoName;
      $promoName->product = $request->product;
      $promoName->statusDefinition = $request->promoDescription;

      switch ($request->status) {
        case 0:
          // code block
          $promoName->status = 0;
          break;
        case 1:
          // code block
          $promoName->status = 1;
          break;
        default:
          // code block
      }

      $promoName->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Edited ID #" . " $promoName->id " . "Promo Name";
      $auditLog->table = "promoName";
      $auditLog->nID = $promoName->id . " | " . $request->productName . " | " . $request->product . " | " . $request->promoDescription . " | " . $request->status;
      $auditLog->ip = \Request::ip();
      $auditLog->save();
      return json_encode(array(
        'success' => true,
        'message' => 'Promo Name updated successfully.'
      ));
    } else {
      return json_encode(array(
        'success' => false,
        'message' => 'Promo Name found.'
      ));
    }
  }

  public function deletePromoName(Request $request)
  {
    $deletePromoName = PromoName::where('id', $request->id)->first();

    if ($deletePromoName) {


      $deletePromoName->deleted = 1;
      $deletePromoName->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Deleted ID #" . " $deletePromoName->id " . "Promo Name";
      $auditLog->table = "promoName";
      $auditLog->nID = "Deleted =" . $deletePromoName->deleted;
      $auditLog->ip = \Request::ip();
      $auditLog->save();
      return 'Promo Name deleted successfully.';
    } else {

      return 'Promo Name deleted unsuccessfully.';
    }
  }
}
