<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    //
    public function listTransaction(Request $request)
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

        $transaction = Transaction::where('deleted', '0');
        $transaction = $transaction->where(function ($query) use ($search) {
            return $query->where('statusName', 'like', '%' . $search . '%')
                ->orWhere('id', 'like', '%' . $search . '%');
        })
            ->orderBy($tableColumns[$sortIndex], $sortOrder);
        $transactionCount = $transaction->count();
        $transaction = $transaction->offset($offset)
            ->limit($limit)
            ->get();

        foreach ($transaction as $p) {

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
            'recordsTotal'    => $transactionCount,
            'recordsFiltered' => $transactionCount,
            'data'            => $transaction,
        ];

        // reponse must be in  array
        return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function addTransaction(Request $request)
    {
        $transaction = Transaction::where('statusName', $request->transaction)->where('deleted', '0')->get()->count();
        // ->where('deleted', '0')
        if ($transaction > 0) {
            return json_encode(array(
                'success' => false,
                'message' => 'Transaction already in use.'
            ));
        }

        $transaction = new Transaction();
        $transaction->statusName = $request->transaction;
        $transaction->product = $request->product;
        $transaction->statusDefinition = $request->transactionDescription;

        switch ($request->status) {
            case "DISABLED":
                // code block
                $transaction->status = 0;
                break;
            case "ACTIVE":
                // code block
                $transaction->status = 1;
                break;
            default:
                // code block
        }

        $transaction->save();

        $auditLog = new AuditLog();
        $auditLog->agent = auth()->user()->id;
        $auditLog->action = "Added Transaction";
        $auditLog->table = "transaction";
        $auditLog->nID = $transaction->id . " | " . $request->transaction . " | " . $request->product . " | " . $request->transactionDescription . " | " . $request->status;
        $auditLog->ip = \Request::ip();

        $auditLog->save();

        return json_encode(array(
            'success' => true,
            'message' => 'Transaction added successfully.'
        ));
    }

    public function getEditTransaction(Request $request)
    {
        $getTransaction = Transaction::where('id', $request->id)->first();
        return json_encode($getTransaction);
    }

    public function editTransaction(Request $request)
    {

        $transaction = Transaction::where('statusName', $request->transaction)->where('id', '!=', $request->id)->where('deleted', '0')->get()->count();
        // ->where('deleted', '0')

        // dd($productName);
        if ($transaction > 0) {
            return json_encode(array(
                'success' => false,
                'message' => 'Transaction already in use.'
            ));
        }


        $transaction = Transaction::where('id', $request->id)->first();
        if (!empty($transaction) || $transaction != null) {

            $transaction->statusName = $request->transaction;
            $transaction->product = $request->product;
            $transaction->statusDefinition = $request->transactionDescription;

            switch ($request->status) {
                case 0:
                    // code block
                    $transaction->status = 0;
                    break;
                case 1:
                    // code block
                    $transaction->status = 1;
                    break;
                default:
                    // code block
            }

            $transaction->save();

            $auditLog = new AuditLog();
            $auditLog->agent = auth()->user()->id;
            $auditLog->action = "Edited ID #" . " $transaction->id " . "Transaction";
            $auditLog->table = "transaction";
            $auditLog->nID = $transaction->id . " | " . $request->transaction . " | " . $request->product . " | " . $request->transactionDescription . " | " . $request->status;
            $auditLog->ip = \Request::ip();
            $auditLog->save();
            return json_encode(array(
                'success' => true,
                'message' => 'Transaction updated successfully.'
            ));
        } else {
            return json_encode(array(
                'success' => false,
                'message' => 'Transaction found.'
            ));
        }
    }

    public function deleteTransaction(Request $request)
    {
        $deleteTransaction = Transaction::where('id', $request->id)->first();

        if ($deleteTransaction) {


            $deleteTransaction->deleted = 1;
            $deleteTransaction->save();

            $auditLog = new AuditLog();
            $auditLog->agent = auth()->user()->id;
            $auditLog->action = "Deleted ID #" . " $deleteTransaction->id " . "Transaction";
            $auditLog->table = "transaction";
            $auditLog->nID = "Deleted =" . $deleteTransaction->deleted;
            $auditLog->ip = \Request::ip();
            $auditLog->save();
            return 'Transaction deleted successfully.';
        } else {

            return 'Transaction deleted unsuccessfully.';
        }
    }
}
