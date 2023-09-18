<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\PlaceOfContact;
use Illuminate\Http\Request;

class PlaceOfContactController extends Controller
{
    //
    public function listPlaceOfContact(Request $request)
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

        $placeOfContact = PlaceOfContact::where('deleted', '0');
        $placeOfContact = $placeOfContact->where(function ($query) use ($search) {
            return $query->where('statusName', 'like', '%' . $search . '%')
                ->orWhere('id', 'like', '%' . $search . '%');
        })
            ->orderBy($tableColumns[$sortIndex], $sortOrder);
        $placeOfContactCount = $placeOfContact->count();
        $placeOfContact = $placeOfContact->offset($offset)
            ->limit($limit)
            ->get();

        foreach ($placeOfContact as $p) {

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
            'recordsTotal'    => $placeOfContactCount,
            'recordsFiltered' => $placeOfContactCount,
            'data'            => $placeOfContact,
        ];

        // reponse must be in  array
        return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function addPlaceOfContact(Request $request)
    {
        $placeOfContact = PlaceOfContact::where('statusName', $request->placeOfContact)->where('deleted', '0')->get()->count();
        // ->where('deleted', '0')
        if ($placeOfContact > 0) {
            return json_encode(array(
                'success' => false,
                'message' => 'Place Of Contact already in use.'
            ));
        }

        $placeOfContact = new PlaceOfContact();
        $placeOfContact->statusName = $request->placeOfContact;
        $placeOfContact->product = $request->product;
        $placeOfContact->statusDefinition = $request->placeOfContactDescription;

        switch ($request->status) {
            case "DISABLED":
                // code block
                $placeOfContact->status = 0;
                break;
            case "ACTIVE":
                // code block
                $placeOfContact->status = 1;
                break;
            default:
                // code block
        }

        $placeOfContact->save();

        $auditLog = new AuditLog();
        $auditLog->agent = auth()->user()->id;
        $auditLog->action = "Added Place Of Contact";
        $auditLog->table = "placeOfContact";
        $auditLog->nID = $placeOfContact->id . " | " . $request->placeOfContact . " | " . $request->product . " | " . $request->placeOfContactDescription . " | " . $request->status;
        $auditLog->ip = \Request::ip();

        $auditLog->save();

        return json_encode(array(
            'success' => true,
            'message' => 'Place Of Contact added successfully.'
        ));
    }

    public function getEditPlaceOfContact(Request $request)
    {
        $getPlaceOfContact = PlaceOfContact::where('id', $request->id)->first();
        return json_encode($getPlaceOfContact);
    }

    public function editPlaceOfContact(Request $request)
    {

        $placeOfContact = PlaceOfContact::where('statusName', $request->placeOfContact)->where('id', '!=', $request->id)->where('deleted', '0')->get()->count();
        // ->where('deleted', '0')

        // dd($productName);
        if ($placeOfContact > 0) {
            return json_encode(array(
                'success' => false,
                'message' => 'Place Of Contact already in use.'
            ));
        }


        $placeOfContact = PlaceOfContact::where('id', $request->id)->first();
        if (!empty($placeOfContact) || $placeOfContact != null) {

            $placeOfContact->statusName = $request->placeOfContact;
            $placeOfContact->product = $request->product;
            $placeOfContact->statusDefinition = $request->placeOfContactDescription;

            switch ($request->status) {
                case 0:
                    // code block
                    $placeOfContact->status = 0;
                    break;
                case 1:
                    // code block
                    $placeOfContact->status = 1;
                    break;
                default:
                    // code block
            }

            $placeOfContact->save();

            $auditLog = new AuditLog();
            $auditLog->agent = auth()->user()->id;
            $auditLog->action = "Edited ID #" . " $placeOfContact->id " . "PlaceOfContact";
            $auditLog->table = "placeOfContact";
            $auditLog->nID = $placeOfContact->id . " | " . $request->placeOfContact . " | " . $request->product . " | " . $request->placeOfContactDescription . " | " . $request->status;
            $auditLog->ip = \Request::ip();
            $auditLog->save();
            return json_encode(array(
                'success' => true,
                'message' => 'Place Of Contact updated successfully.'
            ));
        } else {
            return json_encode(array(
                'success' => false,
                'message' => 'Place Of Contact found.'
            ));
        }
    }

    public function deletePlaceOfContact(Request $request)
    {
        $deleteTransaction = PlaceOfContact::where('id', $request->id)->first();

        if ($deleteTransaction) {


            $deleteTransaction->deleted = 1;
            $deleteTransaction->save();

            $auditLog = new AuditLog();
            $auditLog->agent = auth()->user()->id;
            $auditLog->action = "Deleted ID #" . " $deleteTransaction->id " . "PlaceOfContact";
            $auditLog->table = "placeOfContact";
            $auditLog->nID = "Deleted =" . $deleteTransaction->deleted;
            $auditLog->ip = \Request::ip();
            $auditLog->save();
            return 'Place Of Contact deleted successfully.';
        } else {

            return 'Place Of Contact deleted unsuccessfully.';
        }
    }
}


