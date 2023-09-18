<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    //
    public function listGroup(Request $request)
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: *');

        $tableColumns = array(
            'id',
            'groupName',
            'groupDescription',
            'client',
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

        // $group = Group::where(function ($query) use ($search) { // where like search request
        //     return $query->where('groupName', 'like', '%' . $search . '%')
        //         ->orWhere('groupDescription', 'like', '%' . $search . '%')
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

        // foreach ($group as $p) {

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

        // $groupCount = Group::where(function ($query) use ($search) { // where like search request
        //     return $query->where('groupName', 'like', '%' . $search . '%')
        //         ->orWhere('groupDescription', 'like', '%' . $search . '%')
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

        $group = Group::where('deleted', '0');
        $group = $group->where(function ($query) use ($search) {
            return $query->where('groupName', 'like', '%' . $search . '%')
                ->orWhere('groupDescription', 'like', '%' . $search . '%')
                ->orWhere('id', 'like', '%' . $search . '%')
                ->orWhere('client', 'like', '%' . $search . '%');
        })
            ->orderBy($tableColumns[$sortIndex], $sortOrder);
        $groupCount = $group->count();
        $group = $group->offset($offset)
            ->limit($limit)
            ->get();

        foreach ($group as $p) {

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
            'recordsTotal'    => $groupCount,
            'recordsFiltered' => $groupCount,
            'data'            => $group,
        ];

        // reponse must be in  array
        return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }


    public function addGroup(Request $request)
    {

        $groupName = Group::where('groupName', $request->groupName)->where('deleted', '0')->get()->count();
        // ->where('deleted', '0')
        if ($groupName > 0) {
            return json_encode(array(
                'success' => false,
                'message' => 'Group Name already in use.'
            ));
        }

        $group = new Group();
        $group->groupName = $request->groupName;
        $group->groupDescription = $request->groupDescription;
        $group->client = $request->client;


        switch ($request->status) {
            case "DISABLED":
                // code block
                $group->status = 0;
                break;
            case "ACTIVE":
                // code block
                $group->status = 1;
                break;
            default:
                // code block
        }

        $group->save();

        $auditLog = new AuditLog();
        $auditLog->agent = auth()->user()->id;
        $auditLog->action = "Added Group";
        $auditLog->table = "group";
        $auditLog->nID = $group->id . " | " . $request->groupName . " | " . $request->groupDescription . " | " . $request->client . " | " . $request->status;
        $auditLog->ip = \Request::ip();
        $auditLog->save();

        return json_encode(array(
            'success' => true,
            'message' => 'Group added successfully.'
        ));
    }

    public function getEditGroup(Request $request)
    {
        $getGroup = Group::where('id', $request->id)->first();
        return json_encode($getGroup);
    }

    public function editGroup(Request $request)
    {

        $groupName = Group::where('groupName', $request->groupName)->where('id', '!=', $request->id)->where('deleted', '0')->get()->count();
        // ->where('deleted', '0')
        if ($groupName > 0) {
            return json_encode(array(
                'success' => false,
                'message' => 'Group Name already in use.'
            ));
        }

        $group = Group::where('id', $request->id)->first();
        if (!empty($group) || $group != null) {

            $group->groupName = $request->groupName;
            $group->groupDescription = $request->groupDescription;
            $group->client = $request->client;
            switch ($request->status) {
                case 0:
                    // code block
                    $group->status = 0;
                    break;
                case 1:
                    // code block
                    $group->status = 1;
                    break;
                default:
                    // code block
            }

            $group->save();

            $auditLog = new AuditLog();
            $auditLog->agent = auth()->user()->id;
            $auditLog->action = "Edited ID #" . " $group->id " . "Group";
            $auditLog->table = "group";
            $auditLog->nID = $group->id . " | " . $request->groupName . " | " . $request->groupDescription . " | " . $request->client . " | " . $request->status;
            $auditLog->ip = \Request::ip();
            $auditLog->save();


            return json_encode(array(
                'success' => true,
                'message' => 'Group updated successfully.'
            ));
        } else {
            return json_encode(array(
                'success' => false,
                'message' => 'Group  not found.'
            ));
        }
    }

    public function deleteGroup(Request $request)
    {
        $deleteGroup = Group::where('id', $request->id)->first();

        if ($deleteGroup) {


            $deleteGroup->deleted = 1;
            $deleteGroup->save();

            $auditLog = new AuditLog();
            $auditLog->agent = auth()->user()->id;
            $auditLog->action = "Deleted ID #" . " $deleteGroup->id " . "Group";
            $auditLog->table = "group";
            $auditLog->nID = "Deleted =" . $deleteGroup->deleted;
            $auditLog->ip = \Request::ip();
            $auditLog->save();

            return 'Group deleted successfully.';
        } else {

            return 'Group deleted unsuccessfully.';
        }
    }
}
