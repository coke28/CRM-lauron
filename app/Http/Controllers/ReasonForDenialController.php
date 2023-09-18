<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\ReasonForDenial;
use Illuminate\Http\Request;

class ReasonForDenialController extends Controller
{
    //
    public function listReasonForDenial(Request $request)
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

        $reasonForDenial = ReasonForDenial::where('deleted', '0');
        $reasonForDenial = $reasonForDenial->where(function ($query) use ($search) {
            return $query->where('statusName', 'like', '%' . $search . '%')
                ->orWhere('id', 'like', '%' . $search . '%');
        })
            ->orderBy($tableColumns[$sortIndex], $sortOrder);
        $reasonForDenialCount = $reasonForDenial->count();
        $reasonForDenial = $reasonForDenial->offset($offset)
            ->limit($limit)
            ->get();

        foreach ($reasonForDenial as $p) {

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
            'recordsTotal'    => $reasonForDenialCount,
            'recordsFiltered' => $reasonForDenialCount,
            'data'            => $reasonForDenial,
        ];

        // reponse must be in  array
        return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function addReasonForDenial(Request $request)
    {
        $reasonForDenial = ReasonForDenial::where('statusName', $request->reasonForDenial)->where('deleted', '0')->get()->count();
        // ->where('deleted', '0')
        if ($reasonForDenial > 0) {
            return json_encode(array(
                'success' => false,
                'message' => 'Reason For Denial already in use.'
            ));
        }

        $reasonForDenial = new ReasonForDenial();
        $reasonForDenial->statusName = $request->reasonForDenial;
        $reasonForDenial->product = $request->product;
        $reasonForDenial->statusDefinition = $request->reasonForDenialDescription;

        switch ($request->status) {
            case "DISABLED":
                // code block
                $reasonForDenial->status = 0;
                break;
            case "ACTIVE":
                // code block
                $reasonForDenial->status = 1;
                break;
            default:
                // code block
        }

        $reasonForDenial->save();

        $auditLog = new AuditLog();
        $auditLog->agent = auth()->user()->id;
        $auditLog->action = "Added ReasonForDenial";
        $auditLog->table = "reasonForDenial";
        $auditLog->nID = $reasonForDenial->id . " | " . $request->reasonForDenial . " | " . $request->product . " | " . $request->reasonForDenialDescription . " | " . $request->status;
        $auditLog->ip = \Request::ip();

        $auditLog->save();

        return json_encode(array(
            'success' => true,
            'message' => 'Reason For Denial added successfully.'
        ));
    }

    public function getEditReasonForDenial(Request $request)
    {
        $getReasonForDenial = ReasonForDenial::where('id', $request->id)->first();
        return json_encode($getReasonForDenial);
    }

    public function editReasonForDenial(Request $request)
    {

        $reasonForDenial = ReasonForDenial::where('statusName', $request->reasonForDenial)->where('id', '!=', $request->id)->where('deleted', '0')->get()->count();
        // ->where('deleted', '0')

        // dd($productName);
        if ($reasonForDenial > 0) {
            return json_encode(array(
                'success' => false,
                'message' => 'Reason For Denial already in use.'
            ));
        }


        $reasonForDenial = ReasonForDenial::where('id', $request->id)->first();
        if (!empty($reasonForDenial) || $reasonForDenial != null) {

            $reasonForDenial->statusName = $request->reasonForDenial;
            $reasonForDenial->product = $request->product;
            $reasonForDenial->statusDefinition = $request->reasonForDenialDescription;

            switch ($request->status) {
                case 0:
                    // code block
                    $reasonForDenial->status = 0;
                    break;
                case 1:
                    // code block
                    $reasonForDenial->status = 1;
                    break;
                default:
                    // code block
            }

            $reasonForDenial->save();

            $auditLog = new AuditLog();
            $auditLog->agent = auth()->user()->id;
            $auditLog->action = "Edited ID #" . " $reasonForDenial->id " . "ReasonForDenial";
            $auditLog->table = "reasonForDenial";
            $auditLog->nID = $reasonForDenial->id . " | " . $request->reasonForDenial . " | " . $request->product . " | " . $request->reasonForDenialDescription . " | " . $request->status;
            $auditLog->ip = \Request::ip();
            $auditLog->save();
            return json_encode(array(
                'success' => true,
                'message' => 'Reason For Denial updated successfully.'
            ));
        } else {
            return json_encode(array(
                'success' => false,
                'message' => 'Reason For Denial found.'
            ));
        }
    }

    public function deleteReasonForDenial(Request $request)
    {
        $deleteReasonForDenial = ReasonForDenial::where('id', $request->id)->first();

        if ($deleteReasonForDenial) {


            $deleteReasonForDenial->deleted = 1;
            $deleteReasonForDenial->save();

            $auditLog = new AuditLog();
            $auditLog->agent = auth()->user()->id;
            $auditLog->action = "Deleted ID #" . " $deleteReasonForDenial->id " . "ReasonForDenial";
            $auditLog->table = "reasonForDenial";
            $auditLog->nID = "Deleted =" . $deleteReasonForDenial->deleted;
            $auditLog->ip = \Request::ip();
            $auditLog->save();
            return 'Reason For Denial deleted successfully.';
        } else {

            return 'Reason For Denial deleted unsuccessfully.';
        }
    }
}
