<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\PointOfContact;
use Illuminate\Http\Request;

class PointOfContactController extends Controller
{
    //
    public function listPointOfContact(Request $request)
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

        $pointOfContact = PointOfContact::where('deleted', '0');
        $pointOfContact = $pointOfContact->where(function ($query) use ($search) {
            return $query->where('statusName', 'like', '%' . $search . '%')
                ->orWhere('id', 'like', '%' . $search . '%');
        })
            ->orderBy($tableColumns[$sortIndex], $sortOrder);
        $pointOfContactCount = $pointOfContact->count();
        $pointOfContact = $pointOfContact->offset($offset)
            ->limit($limit)
            ->get();

        foreach ($pointOfContact as $p) {

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
            'recordsTotal'    => $pointOfContactCount,
            'recordsFiltered' => $pointOfContactCount,
            'data'            => $pointOfContact,
        ];

        // reponse must be in  array
        return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function addPointOfContact(Request $request)
    {
        $pointOfContact = PointOfContact::where('statusName', $request->pointOfContact)->where('deleted', '0')->get()->count();
        // ->where('deleted', '0')
        if ($pointOfContact > 0) {
            return json_encode(array(
                'success' => false,
                'message' => 'Point Of Contact already in use.'
            ));
        }

        $pointOfContact = new PointOfContact();
        $pointOfContact->statusName = $request->pointOfContact;
        $pointOfContact->product = $request->product;
        $pointOfContact->statusDefinition = $request->pointOfContactDescription;

        switch ($request->status) {
            case "DISABLED":
                // code block
                $pointOfContact->status = 0;
                break;
            case "ACTIVE":
                // code block
                $pointOfContact->status = 1;
                break;
            default:
                // code block
        }

        $pointOfContact->save();

        $auditLog = new AuditLog();
        $auditLog->agent = auth()->user()->id;
        $auditLog->action = "Added PointOfContact";
        $auditLog->table = "pointOfContact";
        $auditLog->nID = $pointOfContact->id . " | " . $request->pointOfContact . " | " . $request->product . " | " . $request->pointOfContactDescription . " | " . $request->status;
        $auditLog->ip = \Request::ip();

        $auditLog->save();

        return json_encode(array(
            'success' => true,
            'message' => 'Point Of Contact added successfully.'
        ));
    }

    public function getEditPointOfContact(Request $request)
    {
        $getPointOfContact = PointOfContact::where('id', $request->id)->first();
        return json_encode($getPointOfContact);
    }

    public function editPointOfContact(Request $request)
    {

        $pointOfContact = PointOfContact::where('statusName', $request->pointOfContact)->where('id', '!=', $request->id)->where('deleted', '0')->get()->count();
        // ->where('deleted', '0')

        // dd($productName);
        if ($pointOfContact > 0) {
            return json_encode(array(
                'success' => false,
                'message' => 'Point Of Contact already in use.'
            ));
        }


        $pointOfContact = PointOfContact::where('id', $request->id)->first();
        if (!empty($pointOfContact) || $pointOfContact != null) {

            $pointOfContact->statusName = $request->pointOfContact;
            $pointOfContact->product = $request->product;
            $pointOfContact->statusDefinition = $request->pointOfContactDescription;

            switch ($request->status) {
                case 0:
                    // code block
                    $pointOfContact->status = 0;
                    break;
                case 1:
                    // code block
                    $pointOfContact->status = 1;
                    break;
                default:
                    // code block
            }

            $pointOfContact->save();

            $auditLog = new AuditLog();
            $auditLog->agent = auth()->user()->id;
            $auditLog->action = "Edited ID #" . " $pointOfContact->id " . "PointOfContact";
            $auditLog->table = "pointOfContact";
            $auditLog->nID = $pointOfContact->id . " | " . $request->pointOfContact . " | " . $request->product . " | " . $request->pointOfContactDescription . " | " . $request->status;
            $auditLog->ip = \Request::ip();
            $auditLog->save();
            return json_encode(array(
                'success' => true,
                'message' => 'Point Of Contact updated successfully.'
            ));
        } else {
            return json_encode(array(
                'success' => false,
                'message' => 'Point Of Contact found.'
            ));
        }
    }

    public function deletePointOfContact(Request $request)
    {
        $deletePointOfContact = PointOfContact::where('id', $request->id)->first();

        if ($deletePointOfContact) {


            $deletePointOfContact->deleted = 1;
            $deletePointOfContact->save();

            $auditLog = new AuditLog();
            $auditLog->agent = auth()->user()->id;
            $auditLog->action = "Deleted ID #" . " $deletePointOfContact->id " . "PointOfContact";
            $auditLog->table = "pointOfContact";
            $auditLog->nID = "Deleted =" . $deletePointOfContact->deleted;
            $auditLog->ip = \Request::ip();
            $auditLog->save();
            return 'PointOfContact deleted successfully.';
        } else {

            return 'PointOfContact deleted unsuccessfully.';
        }
    }
}
