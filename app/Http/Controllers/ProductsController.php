<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use App\Models\Products;

class ProductsController extends Controller
{
  //

  public function listProducts(Request $request)
  {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: *');

    $tableColumns = array(
      'id',
      'productName',
      'category',
      'sku',
      'price',
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

    // $products = Products::where(function ($query) use ($search) { // where like search request
    //   return $query->where('productName', 'like', '%' . $search . '%')
    //     ->orWhere('category', 'like', '%' . $search . '%')
    //     ->orWhere('sku', 'like', '%' . $search . '%')
    //     ->orWhere('price', 'like', '%' . $search . '%')
    //     ->orWhere('status', 'like', '%' . $search . '%');
    // })
    //   //user is not deleted
    //   ->where('deleted', '0')
    //   //by order
    //   ->orderBy($tableColumns[$sortIndex], $sortOrder)
    //   ->offset($offset)
    //   ->limit($limit)
    //   ->get();

    // foreach ($products as $p) {

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

    // $productsCount = Products::where(function ($query) use ($search) { // where like search request
    //   return $query->where('productName', 'like', '%' . $search . '%')
    //     ->orWhere('category', 'like', '%' . $search . '%')
    //     ->orWhere('sku', 'like', '%' . $search . '%')
    //     ->orWhere('price', 'like', '%' . $search . '%')
    //     ->orWhere('status', 'like', '%' . $search . '%');
    // })
    //   //user is not deleted
    //   ->where('deleted', '0')
    //   //by order
    //   ->orderBy($tableColumns[$sortIndex], $sortOrder)
    //   ->offset($offset)
    //   ->limit($limit)
    //   ->get()
    //   ->count();

    $products = Products::where('deleted', '0');
    $products = $products->where(function ($query) use ($search) {
      return $query->where('productName', 'like', '%' . $search . '%')
      ->orWhere('category', 'like', '%' . $search . '%')
      ->orWhere('sku', 'like', '%' . $search . '%')
      ->orWhere('price', 'like', '%' . $search . '%')
      ->orWhere('status', 'like', '%' . $search . '%');
    })
      ->orderBy($tableColumns[$sortIndex], $sortOrder);
    $productsCount = $products->count();
    $products = $products->offset($offset)
      ->limit($limit)
      ->get();

    foreach ($products as $p) {

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
      'recordsTotal'    => $productsCount,
      'recordsFiltered' => $productsCount,
      'data'            => $products,
    ];

    // reponse must be in  array
    return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
  }

  public function addProducts(Request $request)
  {
    $productName = Products::where('productName', $request->productName)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')
    if ($productName > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Product name already in use.'
      ));
    }

    $products = new Products();
    $products->productName = $request->productName;
    $products->sku = $request->productSKU;
    $products->price = $request->productPrice;
    switch ($request->status) {
      case "DISABLED":
        // code block
        $products->status = 0;
        break;
      case "ACTIVE":
        // code block
        $products->status = 1;
        break;
      default:
        // code block
    }
    $products->category = $request->productCategory;
    $products->save();

    $auditLog = new AuditLog();
    $auditLog->agent = auth()->user()->id;
    $auditLog->action = "Added Product";
    $auditLog->table = "product";
    $auditLog->nID = $products->id . " | " . $request->productName . " | " . $request->productSKU . " | " . $request->productPrice . " | " . $request->status . " | " . $request->productCategory;
    $auditLog->ip = \Request::ip();

    $auditLog->save();

    return json_encode(array(
      'success' => true,
      'message' => 'Product added successfully.'
    ));
  }

  public function getEditProducts(Request $request)
  {
    $getProducts = Products::where('id', $request->id)->first();
    return json_encode($getProducts);
  }

  public function editProducts(Request $request)
  {

    $productName = Products::where('productName', $request->productName)->where('id', '!=', $request->id)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')
    if ($productName > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Product name already in use.'
      ));
    }


    $products = Products::where('id', $request->id)->first();
    if (!empty($products) || $products != null) {

      $products->productName = $request->productName;
      $products->sku = $request->productSKU;
      $products->price = $request->productPrice;
      switch ($request->status) {
        case 0:
          // code block
          $products->status = 0;
          break;
        case 1:
          // code block
          $products->status = 1;
          break;
        default:
          // code block
      }
      $products->category = $request->productCategory;
      $products->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Edited ID #" . " $products->id " . "Product";
      $auditLog->table = "product";
      $auditLog->nID =  $products->id . " | " . $request->productName . " | " . $request->productSKU . " | " . $request->productPrice . " | " . $request->status . " | " . $request->productCategory;
      $auditLog->ip = \Request::ip();
      $auditLog->save();





      return json_encode(array(
        'success' => true,
        'message' => 'Product updated successfully.'
      ));
    } else {
      return json_encode(array(
        'success' => false,
        'message' => 'Product not found.'
      ));
    }
  }

  public function deleteProducts(Request $request)
  {
    $deleteProducts = Products::where('id', $request->id)->first();

    if ($deleteProducts) {


      $deleteProducts->deleted = 1;
      $deleteProducts->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Deleted ID #" . " $deleteProducts->id " . "Product";
      $auditLog->table = "product";
      $auditLog->nID = "Deleted =" . $deleteProducts->deleted;
      $auditLog->ip = \Request::ip();
      $auditLog->save();
      return 'Product deleted successfully.';
    } else {

      return 'Product deleted unsuccessfully.';
    }
  }
}
