<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\ProductName;
use Illuminate\Http\Request;

class ProductNameController extends Controller
{
  //
  public function listProductName(Request $request)
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

    // $productName = ProductName::where(function ($query) use ($search) { // where like search request
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

    // foreach ($productName as $p) {

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

    // $productNameCount = ProductName::where(function ($query) use ($search) { // where like search request
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

    $productName = ProductName::where('deleted', '0');
    $productName = $productName->where(function ($query) use ($search) {
      return $query->where('product', 'like', '%' . $search . '%')
        ->orWhere('statusName', 'like', '%' . $search . '%')
        ->orWhere('id', 'like', '%' . $search . '%');
    })
      ->orderBy($tableColumns[$sortIndex], $sortOrder);
    $productNameCount = $productName->count();
    $productName = $productName->offset($offset)
      ->limit($limit)
      ->get();

    foreach ($productName as $p) {

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
      'recordsTotal'    => $productNameCount,
      'recordsFiltered' => $productNameCount,
      'data'            => $productName,
    ];

    // reponse must be in  array
    return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
  }

  public function addProductName(Request $request)
  {
    $productName = ProductName::where('statusName', $request->productName)->where('product', $request->product)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')
    if ($productName > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Product Name already in use.'
      ));
    }

    $productName = new ProductName();
    $productName->statusName = $request->productName;
    $productName->product = $request->product;
    $productName->statusDefinition = $request->productNameDescription;

    switch ($request->status) {
      case "DISABLED":
        // code block
        $productName->status = 0;
        break;
      case "ACTIVE":
        // code block
        $productName->status = 1;
        break;
      default:
        // code block
    }

    $productName->save();

    $auditLog = new AuditLog();
    $auditLog->agent = auth()->user()->id;
    $auditLog->action = "Added Product Name";
    $auditLog->table = "productName";
    $auditLog->nID = $productName->id . " | " . $request->productName . " | " . $request->product . " | " . $request->productNameDescription . " | " . $request->status;
    $auditLog->ip = \Request::ip();

    $auditLog->save();

    return json_encode(array(
      'success' => true,
      'message' => 'Product Name added successfully.'
    ));
  }

  public function getEditProductName(Request $request)
  {
    $getProductName = ProductName::where('id', $request->id)->first();
    return json_encode($getProductName);
  }

  public function editProductName(Request $request)
  {

    $productName = ProductName::where('statusName', $request->productName)->where('id', '!=', $request->id)->where('product', $request->product)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')

    // dd($productName);
    if ($productName > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Product Name already in use.'
      ));
    }


    $productName = ProductName::where('id', $request->id)->first();
    if (!empty($productName) || $productName != null) {

      $productName->statusName = $request->productName;
      $productName->product = $request->product;
      $productName->statusDefinition = $request->productNameDescription;

      switch ($request->status) {
        case 0:
          // code block
          $productName->status = 0;
          break;
        case 1:
          // code block
          $productName->status = 1;
          break;
        default:
          // code block
      }

      $productName->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Edited ID #" . " $productName->id " . "Product Name";
      $auditLog->table = "productName";
      $auditLog->nID =  $productName->id . " | " . $request->productName . " | " . $request->product . " | " . $request->productNameDescription . " | " . $request->status;
      $auditLog->ip = \Request::ip();
      $auditLog->save();
      return json_encode(array(
        'success' => true,
        'message' => 'Product Name updated successfully.'
      ));
    } else {
      return json_encode(array(
        'success' => false,
        'message' => 'Product Name  not found.'
      ));
    }
  }

  public function deleteProductName(Request $request)
  {
    $deleteProductName = ProductName::where('id', $request->id)->first();

    if ($deleteProductName) {


      $deleteProductName->deleted = 1;
      $deleteProductName->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Deleted ID #" . " $deleteProductName->id " . "Product Name";
      $auditLog->table = "productName";
      $auditLog->nID = "Deleted =" . $deleteProductName->deleted;
      $auditLog->ip = \Request::ip();
      $auditLog->save();
      return 'Product Name deleted successfully.';
    } else {

      return 'Product Name deleted unsuccessfully.';
    }
  }
}
