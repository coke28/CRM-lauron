<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Segment;
use Illuminate\Http\Request;

class SegmentController extends Controller
{
  //
  public function listSegment(Request $request)
  {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: *');

    $tableColumns = array(
      'id',
      'product',
      'statusName',
      'statusDefinition',
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

    $segment = Segment::where('deleted', '0');
    $segment = $segment->where(function ($query) use ($search) {
      return $query->where('statusName', 'like', '%' . $search . '%')
        ->orWhere('id', 'like', '%' . $search . '%');
    })
      ->orderBy($tableColumns[$sortIndex], $sortOrder);
    $segmentCount = $segment->count();
    $segment = $segment->offset($offset)
      ->limit($limit)
      ->get();

    foreach ($segment as $p) {

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
      'recordsTotal'    => $segmentCount,
      'recordsFiltered' => $segmentCount,
      'data'            => $segment,
    ];

    // reponse must be in  array
    return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
  }

  public function addSegment(Request $request)
  {
    $segment = Segment::where('statusName', $request->segmentName)->where('product', $request->product)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')
    if ($segment > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Segment already in use.'
      ));
    }

    $segment = new Segment();
    $segment->statusName = $request->segmentName;
    $segment->product = $request->product;
    $segment->statusDefinition = $request->segmentDescription;

    switch ($request->status) {
      case "DISABLED":
        // code block
        $segment->status = 0;
        break;
      case "ACTIVE":
        // code block
        $segment->status = 1;
        break;
      default:
        // code block
    }

    $segment->save();

    $auditLog = new AuditLog();
    $auditLog->agent = auth()->user()->id;
    $auditLog->action = "Added Segment";
    $auditLog->table = "segment";
    $auditLog->nID = $segment->id . " | " . $request->segmentName . " | " . $request->product . " | " . $request->segmentDescription . " | " . $request->status;
    $auditLog->ip = \Request::ip();

    $auditLog->save();

    return json_encode(array(
      'success' => true,
      'message' => 'Segment added successfully.'
    ));
  }

  public function getEditSegment(Request $request)
  {
    $getSegment = Segment::where('id', $request->id)->first();
    return json_encode($getSegment);
  }

  public function editSegment(Request $request)
  {

    $segment = Segment::where('statusName', $request->segmentName)->where('id', '!=', $request->id)->where('product', $request->product)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')

    // dd($productName);
    if ($segment > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Segment Name already in use.'
      ));
    }


    $segment = Segment::where('id', $request->id)->first();
    if (!empty($segment) || $segment != null) {

      $segment->statusName = $request->segmentName;
      $segment->product = $request->product;
      $segment->statusDefinition = $request->segmentDescription;

      switch ($request->status) {
        case 0:
          // code block
          $segment->status = 0;
          break;
        case 1:
          // code block
          $segment->status = 1;
          break;
        default:
          // code block
      }

      $segment->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Edited ID #" . " $segment->id " . "Segment";
      $auditLog->table = "segment";
      $auditLog->nID = $segment->id . " | " . $request->segmentName . " | " . $request->product . " | " . $request->segmentDescription . " | " . $request->status;
      $auditLog->ip = \Request::ip();
      $auditLog->save();
      return json_encode(array(
        'success' => true,
        'message' => 'Segment updated successfully.'
      ));
    } else {
      return json_encode(array(
        'success' => false,
        'message' => 'Segment found.'
      ));
    }
  }

  public function deleteSegment(Request $request)
  {
    $deleteSegment = Segment::where('id', $request->id)->first();

    if ($deleteSegment) {


      $deleteSegment->deleted = 1;
      $deleteSegment->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Deleted ID #" . " $deleteSegment->id " . "Segment ";
      $auditLog->table = "Segment";
      $auditLog->nID = "Deleted =" . $deleteSegment->deleted;
      $auditLog->ip = \Request::ip();
      $auditLog->save();
      return 'Segment deleted successfully.';
    } else {

      return 'Segment deleted unsuccessfully.';
    }
  }
}
