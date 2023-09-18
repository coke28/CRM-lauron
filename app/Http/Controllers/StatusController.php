<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    //
    public function listStatus(Request $request)
    {

        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: *');

        $tableColumns = array(
            'id',
            'client',
            'displayTable',
            'statusID',
            'statusCode',
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

        // $status = Status::where(function ($query) use ($search) { // where like search request
        //     return $query->where('statusName', 'like', '%' . $search . '%')
        //         ->orWhere('statusCode', 'like', '%' . $search . '%')
        //         ->orWhere('statusID', 'like', '%' . $search . '%')
        //         ->orWhere('id', 'like', '%' . $search . '%')
        //         ->orWhere('client', 'like', '%' . $search . '%');
        // })
        //     //user is not deleted
        //     ->where('deleted', '0')
        //     //by order
        //     ->orderBy($tableColumns[$sortIndex], $sortOrder)
        //     ->offset($offset)
        //     ->limit($limit)
        //     ->get();

        // foreach ($status as $p) {

        //     switch ($p->status) {
        //         case "0":
        //             // code block
        //             $p->status = "DISABLED";
        //             break;
        //         case "1":
        //             // code block
        //             $p->status = "ACTIVE";
        //             break;
        //         default:
        //             // code block
        //     }
        // }

        // $statusCount = Status::where(function ($query) use ($search) { // where like search request
        //     return $query->where('statusName', 'like', '%' . $search . '%')
        //         ->orWhere('statusCode', 'like', '%' . $search . '%')
        //         ->orWhere('statusID', 'like', '%' . $search . '%')
        //         ->orWhere('id', 'like', '%' . $search . '%')
        //         ->orWhere('client', 'like', '%' . $search . '%');
        // })
        //     //user is not deleted
        //     ->where('deleted', '0')
        //     //by order
        //     ->orderBy($tableColumns[$sortIndex], $sortOrder)
        //     ->offset($offset)
        //     ->limit($limit)
        //     ->get()
        //     ->count();

        $status = Status::where('deleted', '0');
        $status = $status->where(function ($query) use ($search) {
            return $query->where('statusName', 'like', '%' . $search . '%')
                ->orWhere('statusCode', 'like', '%' . $search . '%')
                ->orWhere('statusID', 'like', '%' . $search . '%')
                ->orWhere('id', 'like', '%' . $search . '%')
                ->orWhere('displayTable', 'like', '%' . $search . '%')
                ->orWhere('client', 'like', '%' . $search . '%');
        })
            ->orderBy($tableColumns[$sortIndex], $sortOrder);
        $statusCount = $status->count();
        $status = $status->offset($offset)
            ->limit($limit)
            ->get();

        foreach ($status as $p) {

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
            switch ($p->client) {
                case "0":
                    // code block
                    $p->client = "Agent";
                    break;
                case "1":
                    // code block
                    $p->client = "Supervisor";
                    break;
                case "2":
                    // code block
                    $p->client = "Administrator";
                    break;
                default:
                    // code block
            }
        }

        // foreach ($status as $p) {

        //     switch ($p->client) {
        //         case "0":
        //             // code block
        //             $p->client = "Agent";
        //             break;
        //         case "1":
        //             // code block
        //             $p->client = "Supervisor";
        //             break;
        //         case "2":
        //             // code block
        //             $p->client = "Administrator";
        //             break;
        //         default:
        //             // code block
        //     }
        // }

        $result = [
            'recordsTotal'    => $statusCount,
            'recordsFiltered' => $statusCount,
            'data'            => $status,
        ];

        // reponse must be in  array
        return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function addStatus(Request $request)
    {
        $statusName = Status::where('statusName', $request->statusName)->where('deleted', '0')->get()->count();
        // ->where('deleted', '0')
        if ($statusName > 0) {
            return json_encode(array(
                'success' => false,
                'message' => 'Status Name already in use.'
            ));
        }

        $statusCode = Status::where('statusCode', $request->statusCode)->where('deleted', '0')->get()->count();
        // ->where('deleted', '0')
        if ($statusCode > 0) {
            return json_encode(array(
                'success' => false,
                'message' => 'Status Code already in use.'
            ));
        }

        $statusID = Status::where('statusID', $request->statusID)->get()->count();
        // ->where('deleted', '0')
        if ($statusID > 0) {
            return json_encode(array(
                'success' => false,
                'message' => 'Status ID already in use.'
            ));
        }

        $status = new Status();
        $status->client = $request->client;
        $status->displayTable = $request->displayTableArray;
        $status->statusCode = $request->statusCode;
        $status->statusID = $request->statusID;
        $status->statusName = $request->statusName;
        $status->statusDefinition = $request->statusDescription;


        switch ($request->status) {
            case "DISABLED":
                // code block
                $status->status = 0;
                break;
            case "ACTIVE":
                // code block
                $status->status = 1;
                break;
            default:
                // code block
        }

        $status->save();


        $auditLog = new AuditLog();
        $auditLog->agent = auth()->user()->id;
        $auditLog->action = "Added Status";
        $auditLog->table = "status";
        // $auditLog->nID = $status->id . " | " . $request->client . " | " . $request->statusCode . " | " . $request->statusID . " | " . $request->statusName . " | " . $request->statusDescription . " | " . $request->status;
        $auditLog->nID = $status->id . " | " . $request->client . " | " . $status->displayTable . " | " .$request->statusCode . " | " . $request->statusID . " | " . $request->statusName . " | " . $request->statusDescription . " | " . $request->status;
        $auditLog->ip = \Request::ip();

        $auditLog->save();

        return json_encode(array(
            'success' => true,
            'message' => 'Status added successfully.'
        ));
    }

    public function getEditStatus(Request $request)
    {
        $getStatus = Status::where('id', $request->id)->first();
        return json_encode($getStatus);
    }

    public function editStatus(Request $request)
    {

        $statusName = Status::where('statusName', $request->statusName)->where('id', '!=', $request->id)->where('deleted', '0')->get()->count();
        // ->where('deleted', '0')
        if ($statusName > 0) {
            return json_encode(array(
                'success' => false,
                'message' => 'Status Name already in use.'
            ));
        }

        $statusCode = Status::where('statusCode', $request->statusCode)->where('id', '!=', $request->id)->where('deleted', '0')->get()->count();
        // ->where('deleted', '0')
        if ($statusCode > 0) {
            return json_encode(array(
                'success' => false,
                'message' => 'Status Code already in use.'
            ));
        }

        $statusID = Status::where('statusID', $request->statusID)->where('id', '!=', $request->id)->where('deleted', '0')->get()->count();
        // ->where('deleted', '0')
        if ($statusID > 0) {
            return json_encode(array(
                'success' => false,
                'message' => 'Status ID already in use.'
            ));
        }

        $status = Status::where('id', $request->id)->first();
        if (!empty($status) || $status != null) {

            $status->client = $request->client;
            $status->displayTable = $request->displayTableArray;
            $status->statusCode = $request->statusCode;
            $status->statusID = $request->statusID;
            $status->statusName = $request->statusName;
            $status->statusDefinition = $request->statusDescription;
            switch ($request->status) {
                case 0:
                    // code block
                    $status->status = 0;
                    break;
                case 1:
                    // code block
                    $status->status = 1;
                    break;
                default:
                    // code block
            }

            $status->save();

            $auditLog = new AuditLog();
            $auditLog->agent = auth()->user()->id;
            $auditLog->action = "Edited ID #" . " $status->id " . "Status";
            $auditLog->table = "status";
            // $auditLog->nID = $status->id . " | " . $request->client . " | " . $request->statusCode . " | " . $request->statusID . " | " . $request->statusName . " | " . $request->statusDescription . " | " . $request->status;
            $auditLog->nID = $status->id . " | " . $request->client . " | " . $status->displayTable . " | " .$request->statusCode . " | " . $request->statusID . " | " . $request->statusName . " | " . $request->statusDescription . " | " . $request->status;
            $auditLog->ip = \Request::ip();
            $auditLog->save();


            return json_encode(array(
                'success' => true,
                'message' => 'Status updated successfully.'
            ));
        } else {
            return json_encode(array(
                'success' => false,
                'message' => 'Status  not found.'
            ));
        }
    }

    public function deleteStatus(Request $request)
    {
        $deleteStatus = Status::where('id', $request->id)->first();

        if ($deleteStatus) {


            $deleteStatus->deleted = 1;
            $deleteStatus->save();

            $auditLog = new AuditLog();
            $auditLog->agent = auth()->user()->id;
            $auditLog->action = "Deleted ID #" . " $deleteStatus->id " . "Status";
            $auditLog->table = "status";
            $auditLog->nID = "Deleted =" . $deleteStatus->deleted;
            $auditLog->ip = \Request::ip();
            $auditLog->save();
            return 'Status deleted successfully.';
        } else {

            return 'Status deleted unsuccessfully.';
        }
    }
}
