<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Campaign;
use App\Models\CrmClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class CampaignController extends Controller
{
  //

  public function listCampaign(Request $request)
  {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: *');

    $tableColumns = array(
      'id',
      'campaignName',
      'product',
      'context',
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

    // $campaign = Campaign::where(function ($query) use ($search) { // where like search request
    //   return $query->where('campaignName', 'like', '%' . $search . '%')
    //     ->orWhere('context', 'like', '%' . $search . '%')
    //     ->orWhere('id', 'like', '%' . $search . '%');
    // })
    //   //user is not deleted
    //   ->where('deleted', '0')
    //   //by order
    //   ->orderBy($tableColumns[$sortIndex], $sortOrder)
    //   ->offset($offset)
    //   ->limit($limit)
    //   ->get();

    // foreach ($campaign as $p) {

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

    // $campaignCount = Campaign::where(function ($query) use ($search) { // where like search request
    //   return $query->where('campaignName', 'like', '%' . $search . '%')
    //     ->orWhere('context', 'like', '%' . $search . '%')
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

      $campaign = Campaign::where('deleted', '0');
      $campaign = $campaign->where(function ($query) use ($search) {
        return $query->where('campaignName', 'like', '%' . $search . '%')
        ->orWhere('context', 'like', '%' . $search . '%')
        ->orWhere('id', 'like', '%' . $search . '%');
      })
        ->orderBy($tableColumns[$sortIndex], $sortOrder);
      $campaignCount = $campaign->count();
      $campaign = $campaign->offset($offset)
        ->limit($limit)
        ->get();
  
      foreach ($campaign as $p) {
  
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
      'recordsTotal'    => $campaignCount,
      'recordsFiltered' => $campaignCount,
      'data'            => $campaign,
    ];

    // reponse must be in  array
    return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
  }

  public function addCampaign(Request $request)
  {

    $campaignName = Campaign::where('campaignName', $request->campaignName)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')
    if ($campaignName > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Campaign Name already in use.'
      ));
    }

    // $product = CrmClient::where('clientName', $request->product)->where('deleted', '0')->get();

    $campaign = new Campaign();
    $campaign->campaignName = $request->campaignName;
    $campaign->smsTemplate = $request->smsTemplate;
    $campaign->context = $request->context;
    $prod = explode("|", $request->product);
    $campaign->product = $prod[1];
    $campaign->company = $prod[0];;

    // $campaign->product = $request->



    switch ($request->status) {
      case "DISABLED":
        // code block
        $campaign->status = 0;
        break;
      case "ACTIVE":
        // code block
        $campaign->status = 1;
        break;
      default:
        // code block
    }

    switch ($request->smsAfterCall) {
      case "DISABLED":
        // code block
        $campaign->sendSMS = 0;
        break;
      case "ACTIVE":
        // code block
        $campaign->sendSMS = 1;
        break;
      default:
        // code block
    }

    $campaign->save();


    $auditLog = new AuditLog();
    $auditLog->agent = auth()->user()->id;
    $auditLog->action = "Added Campaign";
    $auditLog->table = "campaign";
    $auditLog->nID =$campaign->id . " | " . $request->campaignName . " | " . $request->smsTemplate . " | " . $request->context . " | " 
    . $prod[1] . " | " . $prod[0]. " | " . $campaign->status. " | " .$campaign->sendSMS;
    $auditLog->ip = \Request::ip();
    $auditLog->save();

    return json_encode(array(
      'success' => true,
      'message' => 'Campaign added successfully.'
    ));



    // if(!empty($product)|| $product != null){



    // }else{
    //   return json_encode(array(
    //     'success' => false,
    //     'message' => 'Product ID not found.'
    // ));

    // }
  }

  public function getEditCampaign(Request $request)
  {
    $getCampaign = Campaign::where('id', $request->id)->first();
    return json_encode($getCampaign);
  }

  public function editCampaign(Request $request)
  {

    $campaignName = Campaign::where('campaignName', $request->campaignName)->where('id', '!=', $request->id)->where('deleted', '0')->get()->count();
    if ($campaignName > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Campaign Name already in use.'
      ));
    }

    $campaign = Campaign::where('id', $request->id)->first();
    if (!empty($campaign) || $campaign != null) {

      $campaign->campaignName = $request->campaignName;
      $campaign->smsTemplate = $request->smsTemplate;
      $campaign->context = $request->context;
      $prod = explode("|", $request->product);
      $campaign->product = $prod[1];
      $campaign->company = $prod[0];;

      // $campaign->product = $request->

      // $encrypted = Crypt::encryptString($request->campaignName);

      // dd(Crypt::decryptString($encrypted));



      switch ($request->status) {
        case 0:
          // code block
          $campaign->status = 0;
          break;
        case 1:
          // code block
          $campaign->status = 1;
          break;
        default:
          // code block
      }

      switch ($request->smsAfterCall) {
        case 0:
          // code block
          $campaign->sendSMS = 0;
          break;
        case 1:
          // code block
          $campaign->sendSMS = 1;
          break;
        default:
          // code block
      }

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Edited ID #" . " $campaign->id " . "Campaign";
      $auditLog->table = "campaign";
      $auditLog->nID = $campaign->id . " | " . $request->campaignName . " | " . $request->smsTemplate . " | " . $request->context . " | " 
                      . $prod[1] . " | " . $prod[0]. " | " . $campaign->status. " | " .$campaign->sendSMS;
      $auditLog->ip = \Request::ip();
      $auditLog->save();

      $campaign->save();


      return json_encode(array(
        'success' => true,
        'message' => 'Campaign updated successfully.'
      ));
    } else {
      return json_encode(array(
        'success' => false,
        'message' => 'Campaign  not found.'
      ));
    }
  }

  public function deleteCampaign(Request $request)
  {
    $deleteCampaign = Campaign::where('id', $request->id)->first();

    if ($deleteCampaign) {


      $deleteCampaign->deleted = 1;
      $deleteCampaign->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Deleted ID #"." $deleteCampaign->id "."Campaign";
      $auditLog->table = "campaign";
      $auditLog->nID = "Deleted =".$deleteCampaign->deleted; 
      $auditLog->ip = \Request::ip();
      $auditLog->save();

      return 'Campaign deleted successfully.';
    } else {

      return 'Campaign deleted unsuccessfully.';
    }
  }
}
