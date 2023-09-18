<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\CollectionEffort;
use Illuminate\Http\Request;

class CollectionEffortController extends Controller
{
    //
    public function listCollectionEffort(Request $request)
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
  
      $collectionEffort = CollectionEffort::where('deleted', '0');
      $collectionEffort = $collectionEffort->where(function ($query) use ($search) {
        return $query->where('statusName', 'like', '%' . $search . '%')
          ->orWhere('id', 'like', '%' . $search . '%');
      })
        ->orderBy($tableColumns[$sortIndex], $sortOrder);
      $collectionEffortCount = $collectionEffort->count();
      $collectionEffort = $collectionEffort->offset($offset)
        ->limit($limit)
        ->get();
  
      foreach ($collectionEffort as $p) {
  
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
        'recordsTotal'    => $collectionEffortCount,
        'recordsFiltered' => $collectionEffortCount,
        'data'            => $collectionEffort,
      ];
  
      // reponse must be in  array
      return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
  
    public function addCollectionEffort(Request $request)
    {
      $collectionEffort = CollectionEffort::where('statusName', $request->collectionEffort)->where('deleted', '0')->get()->count();
      // ->where('deleted', '0')
      if ($collectionEffort > 0) {
        return json_encode(array(
          'success' => false,
          'message' => 'Collection Effort already in use.'
        ));
      }
  
      $collectionEffort = new CollectionEffort();
      $collectionEffort->statusName = $request->collectionEffort;
      $collectionEffort->product = $request->product;
      $collectionEffort->statusDefinition = $request->collectionEffortDescription;
  
      switch ($request->status) {
        case "DISABLED":
          // code block
          $collectionEffort->status = 0;
          break;
        case "ACTIVE":
          // code block
          $collectionEffort->status = 1;
          break;
        default:
          // code block
      }
  
      $collectionEffort->save();
  
      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Added Collection Effort";
      $auditLog->table = "collectionEffort";
      $auditLog->nID = $collectionEffort->id . " | " . $request->collectionEffort . " | " . $request->product . " | " . $request->collectionEffortDescription . " | " . $request->status;
      $auditLog->ip = \Request::ip();
  
      $auditLog->save();
  
      return json_encode(array(
        'success' => true,
        'message' => 'Collection Effort added successfully.'
      ));
    }
  
    public function getEditCollectionEffort(Request $request)
    {
      $getCollectionEffort = CollectionEffort::where('id', $request->id)->first();
      return json_encode($getCollectionEffort);
    }
  
    public function editCollectionEffort(Request $request)
    {
  
      $collectionEffort = CollectionEffort::where('statusName', $request->collectionEffort)->where('id', '!=', $request->id)->where('deleted', '0')->get()->count();
      // ->where('deleted', '0')
  
      // dd($productName);
      if ($collectionEffort > 0) {
        return json_encode(array(
          'success' => false,
          'message' => 'Collection Effort already in use.'
        ));
      }
  
  
      $collectionEffort = CollectionEffort::where('id', $request->id)->first();
      if (!empty($collectionEffort) || $collectionEffort != null) {
  
        $collectionEffort->statusName = $request->collectionEffort;
        $collectionEffort->product = $request->product;
        $collectionEffort->statusDefinition = $request->collectionEffortDescription;
  
        switch ($request->status) {
          case 0:
            // code block
            $collectionEffort->status = 0;
            break;
          case 1:
            // code block
            $collectionEffort->status = 1;
            break;
          default:
            // code block
        }
  
        $collectionEffort->save();
  
        $auditLog = new AuditLog();
        $auditLog->agent = auth()->user()->id;
        $auditLog->action = "Edited ID #" . " $collectionEffort->id " . "Collection Effort";
        $auditLog->table = "collectionEffort";
        $auditLog->nID = $collectionEffort->id . " | " . $request->collectionEffort . " | " . $request->product . " | " . $request->collectionEffortDescription . " | " . $request->status;
        $auditLog->ip = \Request::ip();
        $auditLog->save();
        return json_encode(array(
          'success' => true,
          'message' => 'Collection Effort updated successfully.'
        ));
      } else {
        return json_encode(array(
          'success' => false,
          'message' => 'Collection Effort found.'
        ));
      }
    }
  
    public function deleteCollectionEffort(Request $request)
    {
      $deleteCollectionEffort = CollectionEffort::where('id', $request->id)->first();
  
      if ($deleteCollectionEffort) {
  
  
        $deleteCollectionEffort->deleted = 1;
        $deleteCollectionEffort->save();
  
        $auditLog = new AuditLog();
        $auditLog->agent = auth()->user()->id;
        $auditLog->action = "Deleted ID #" . " $deleteCollectionEffort->id " . "CollectionEffort";
        $auditLog->table = "collectionEffort";
        $auditLog->nID = "Deleted =" . $deleteCollectionEffort->deleted;
        $auditLog->ip = \Request::ip();
        $auditLog->save();
        return 'CollectionEffort deleted successfully.';
      } else {
  
        return 'CollectionEffort deleted unsuccessfully.';
      }
    }
}
