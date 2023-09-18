<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    //
    public function listArea(Request $request)
    {
      header('Content-Type: application/json');
      header('Access-Control-Allow-Origin: *');
      header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
      header('Access-Control-Allow-Headers: *');
  
      $tableColumns = array(
        'id',
        'statusName',
        'statusDescription',
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
  
      $area = Area::where('deleted', '0');
      $area = $area->where(function ($query) use ($search) {
        return $query->where('statusName', 'like', '%' . $search . '%')
          ->orWhere('id', 'like', '%' . $search . '%');
      })
        ->orderBy($tableColumns[$sortIndex], $sortOrder);
      $areaCount = $area->count();
      $area = $area->offset($offset)
        ->limit($limit)
        ->get();
  
      foreach ($area as $p) {
  
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
        'recordsTotal'    => $areaCount,
        'recordsFiltered' => $areaCount,
        'data'            => $area,
      ];
  
      // reponse must be in  array
      return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
  
    public function addArea(Request $request)
    {
      $area = Area::where('statusName', $request->area)->where('deleted', '0')->get()->count();
      // ->where('deleted', '0')
      if ($area > 0) {
        return json_encode(array(
          'success' => false,
          'message' => 'Area already in use.'
        ));
      }
  
      $area = new Area();
      $area->statusName = $request->area;
   
      $area->statusDefinition = $request->areaDescription;
  
      switch ($request->status) {
        case "DISABLED":
          // code block
          $area->status = 0;
          break;
        case "ACTIVE":
          // code block
          $area->status = 1;
          break;
        default:
          // code block
      }
  
      $area->save();
  
      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Added Area";
      $auditLog->table = "area";
      $auditLog->nID = $area->id . " | " . $request->area . " | " . $request->product . " | " . $request->areaDescription . " | " . $request->status;
      $auditLog->ip = \Request::ip();
  
      $auditLog->save();
  
      return json_encode(array(
        'success' => true,
        'message' => 'Area added successfully.'
      ));
    }
  
    public function getEditArea(Request $request)
    {
      $getArea = Area::where('id', $request->id)->first();
      return json_encode($getArea);
    }
  
    public function editArea(Request $request)
    {
  
      $area = Area::where('statusName', $request->area)->where('id', '!=', $request->id)->where('deleted', '0')->get()->count();
      // ->where('deleted', '0')
  
      // dd($productName);
      if ($area > 0) {
        return json_encode(array(
          'success' => false,
          'message' => 'Area already in use.'
        ));
      }
  
  
      $area = Area::where('id', $request->id)->first();
      if (!empty($area) || $area != null) {
  
        $area->statusName = $request->area;
        $area->statusDefinition = $request->areaDescription;
  
        switch ($request->status) {
          case 0:
            // code block
            $area->status = 0;
            break;
          case 1:
            // code block
            $area->status = 1;
            break;
          default:
            // code block
        }
  
        $area->save();
  
        $auditLog = new AuditLog();
        $auditLog->agent = auth()->user()->id;
        $auditLog->action = "Edited ID #" . " $area->id " . "Area";
        $auditLog->table = "area";
        $auditLog->nID = $area->id . " | " . $request->area . " | " . $request->product . " | " . $request->areaDescription . " | " . $request->status;
        $auditLog->ip = \Request::ip();
        $auditLog->save();
        return json_encode(array(
          'success' => true,
          'message' => 'Area updated successfully.'
        ));
      } else {
        return json_encode(array(
          'success' => false,
          'message' => 'Area found.'
        ));
      }
    }
  
    public function deleteArea(Request $request)
    {
      $deleteArea = Area::where('id', $request->id)->first();
  
      if ($deleteArea) {
  
  
        $deleteArea->deleted = 1;
        $deleteArea->save();
  
        $auditLog = new AuditLog();
        $auditLog->agent = auth()->user()->id;
        $auditLog->action = "Deleted ID #" . " $deleteArea->id " . "Area";
        $auditLog->table = "area";
        $auditLog->nID = "Deleted =" . $deleteArea->deleted;
        $auditLog->ip = \Request::ip();
        $auditLog->save();
        return 'Area deleted successfully.';
      } else {
  
        return 'Area deleted unsuccessfully.';
      }
    }
}
