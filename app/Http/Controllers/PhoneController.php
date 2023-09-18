<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Phone;
use Auth;
use Illuminate\Http\Request;

class PhoneController extends Controller
{
    //
    public function listPhone(Request $request)
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: *');

        $tableColumns = array(
            'id',
            'phoneBrand',
            'phoneModel',
            'phonePrice',
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

        // $phone = Phone::where(function ($query) use ($search) { // where like search request
        //     return $query->where('phoneBrand', 'like', '%' . $search . '%')
        //         ->orWhere('phoneModel', 'like', '%' . $search . '%')
        //         ->orWhere('phonePrice', 'like', '%' . $search . '%')
        //         ->orWhere('id', 'like', '%' . $search . '%');
        // })
        //     //user is not deleted
        //     ->where('deleted', '0')
        //     //by order
        //     ->orderBy($tableColumns[$sortIndex], $sortOrder)
        //     ->offset($offset)
        //     ->limit($limit)
        //     ->get();

        // foreach ($phone as $p) {

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

        // $phoneCount = Phone::where(function ($query) use ($search) { // where like search request
        //     return $query->where('phoneBrand', 'like', '%' . $search . '%')
        //         ->orWhere('phoneModel', 'like', '%' . $search . '%')
        //         ->orWhere('phonePrice', 'like', '%' . $search . '%')
        //         ->orWhere('id', 'like', '%' . $search . '%');
        // })
        //     //user is not deleted
        //     ->where('deleted', '0')
        //     //by order
        //     ->orderBy($tableColumns[$sortIndex], $sortOrder)
        //     ->offset($offset)
        //     ->limit($limit)
        //     ->get()
        //     ->count();

        $phone = Phone::where('deleted', '0');
        $phone = $phone->where(function ($query) use ($search) {
            return $query->where('phoneBrand', 'like', '%' . $search . '%')
                ->orWhere('phoneModel', 'like', '%' . $search . '%')
                ->orWhere('phonePrice', 'like', '%' . $search . '%')
                ->orWhere('id', 'like', '%' . $search . '%');
        })
            ->orderBy($tableColumns[$sortIndex], $sortOrder);
        $phoneCount = $phone->count();
        $phone = $phone->offset($offset)
            ->limit($limit)
            ->get();

        foreach ($phone as $p) {

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
            'recordsTotal'    => $phoneCount,
            'recordsFiltered' => $phoneCount,
            'data'            => $phone,
        ];

        // reponse must be in  array
        return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function addPhone(Request $request)
    {
        $phoneModel = Phone::where('phoneModel', $request->phoneModel)->where('deleted', '0')->get()->count();
        // ->where('deleted', '0')
        if ($phoneModel > 0) {
            return json_encode(array(
                'success' => false,
                'message' => 'Phone Model already in use.'
            ));
        }

        $phone = new Phone();
        $phone->phoneBrand = $request->phoneBrand;
        $phone->phoneModel = $request->phoneModel;
        $phone->phonePrice = $request->phonePrice;

        switch ($request->status) {
            case "DISABLED":
                // code block
                $phone->status = 0;
                break;
            case "ACTIVE":
                // code block
                $phone->status = 1;
                break;
            default:
                // code block
        }

        $phone->save();

        $auditLog = new AuditLog();
        $auditLog->agent = auth()->user()->id;
        $auditLog->action = "Added Phone";
        $auditLog->table = "phones";
        $auditLog->nID = $phone->id . " | " . $request->phoneBrand . " | " . $request->phoneModel . " | " . $request->phonePrice . " | " . $request->status;
        $auditLog->ip = \Request::ip();

        $auditLog->save();

        return json_encode(array(
            'success' => true,
            'message' => 'Phone Model added successfully.'
        ));
    }

    public function getEditPhone(Request $request)
    {
        $getPhone = Phone::where('id', $request->id)->first();
        return json_encode($getPhone);
    }

    public function editPhone(Request $request)
    {

        $phoneModel = Phone::where('phoneModel', $request->phoneModel)->where('id', '!=', $request->id)->where('deleted', '0')->get()->count();
        // ->where('deleted', '0')
        if ($phoneModel > 0) {
            return json_encode(array(
                'success' => false,
                'message' => 'Phone Model already in use.'
            ));
        }


        $phone = Phone::where('id', $request->id)->first();
        if (!empty($phone) || $phone != null) {

            $phone->phoneBrand = $request->phoneBrand;
            $phone->phoneModel = $request->phoneModel;
            $phone->phonePrice = $request->phonePrice;
            switch ($request->status) {
                case 0:
                    // code block
                    $phone->status = 0;
                    break;
                case 1:
                    // code block
                    $phone->status = 1;
                    break;
                default:
                    // code block
            }
            $phone->save();

            $auditLog = new AuditLog();
            $auditLog->agent = auth()->user()->id;
            $auditLog->action = "Edited ID #" . " $phone->id " . "Phone";
            $auditLog->table = "phone";
            $auditLog->nID =   $phone->id . " | " . $request->phoneBrand . " | " . $request->phoneModel . " | " . $request->phonePrice . " | " . $request->status;
            $auditLog->ip = \Request::ip();
            $auditLog->save();





            return json_encode(array(
                'success' => true,
                'message' => 'Phone Model updated successfully.'
            ));
        } else {
            return json_encode(array(
                'success' => false,
                'message' => 'Phone Model not found.'
            ));
        }
    }

    public function deletePhone(Request $request)
    {
        $deletePhone = Phone::where('id', $request->id)->first();

        if ($deletePhone) {


            $deletePhone->deleted = 1;
            $deletePhone->save();

            $auditLog = new AuditLog();
            $auditLog->agent = auth()->user()->id;
            $auditLog->action = "Deleted ID #" . " $deletePhone->id " . "Phone";
            $auditLog->table = "phone";
            $auditLog->nID = "Deleted =" . $deletePhone->deleted;
            $auditLog->ip = \Request::ip();
            $auditLog->save();
            return 'Phone Model deleted successfully.';
        } else {

            return 'Phone Model deleted unsuccessfully.';
        }
    }
}
