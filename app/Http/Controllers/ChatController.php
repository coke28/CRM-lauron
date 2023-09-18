<?php

namespace App\Http\Controllers;

use App\Models\chat;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    //
    public function getChatList(Request $request)
    {

        // $chatList = chat::leftJoin('crm_user_levels', 'crm_users.user_level_id', '=', 'crm_user_levels.id')
        //     ->selectRaw('crm_users.*, crm_user_levels.name as user_level_name')
        //     ->where('username', '!=', 'root')
        //     ->where('crm_users.deleted', '0');

        $chatList = User::where('username', '!=', 'user')
            ->where('deleted', '0');

        if ($request->sender_table == 'user') {
            $chatList = $chatList->where('id', '!=', $request->sender_id);
        }

        // if ($request->isagent == 0) {
        // } else {
        //     $chatList = $chatList->whereIn('user_level_id', [2, 3]);
        // }

        $chatList = $chatList->get();


        for ($i = 0; $i < count($chatList); $i++) {
            $chatList[$i]->recipienttable = 'user';
            $chatList[$i]->chatname = '' . $chatList[$i]->last_name . ', ' . $chatList[$i]->first_name . '';
            $chatList[$i]->badgeCount = chat::where('recipient_id', $request->sender_id)
                ->where('recipient_table', $request->sender_table)
                ->where('sender_id', $chatList[$i]->id)
                ->where('sender_table', $chatList[$i]->recipienttable)
                ->where('seen', 0)
                ->count();
        }

        $chatList = $chatList->toArray();

        // $chatListEcom = [];
        // if ($request->isagent == 1) {
        //     $chatListEcom = User::leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
        //         ->selectRaw('users.*, model_has_roles.role_id')
        //         ->whereIn('model_has_roles.role_id', [2, 3, 5])
        //         ->get();

        //     for ($i = 0; $i < count($chatListEcom); $i++) {
        //         $chatListEcom[$i]->recipienttable = 'users';
        //         $chatListEcom[$i]->chatname = $chatListEcom[$i]->name;
        //         if ($chatListEcom[$i]->role_id == 3) {
        //             $chatListEcom[$i]->user_level_name = 'Distributor';
        //         } else if ($chatListEcom[$i]->role_id == 5) {
        //             $chatListEcom[$i]->user_level_name = 'Showroom';
        //         } else {
        //             $chatListEcom[$i]->user_level_name = 'Rider';
        //         }
        //         $chatListEcom[$i]->badgeCount = chat::where('recipient_id', $request->sender_id)
        //             ->where('recipient_table', $request->sender_table)
        //             ->where('sender_id', $chatListEcom[$i]->id)
        //             ->where('sender_table', $chatListEcom[$i]->recipienttable)
        //             ->where('seen', 0)
        //             ->count();
        //     }

        //     $chatListEcom = $chatListEcom->toArray();
        // }

        // $chatList = array_merge($chatList, $chatListEcom);
        // usort($chatList, function ($a, $b) {
        //     return strcmp($a['chatname'], $b['chatname']);
        // });

        usort($chatList, function ($a, $b) {
            return $b['badgeCount'] - $a['badgeCount'];
        });



        $allUnseenCount = chat::where('recipient_id', $request->sender_id)
            ->where('recipient_table', $request->sender_table)
            ->where('seen', 0)
            ->count();

        return array(
            'chatlist' => $chatList,
            'sender_id' => $request->sender_id,
            'all_unseen_count' => $allUnseenCount
        );
    }

    public function getChatMessageData(Request $request)
    {
        $limit = 100;
        $offset = ((int)$request->page - 1) * $limit;
        $getMessages = chat::where(function($query)use($request) {
          return $query->where('recipient_id', $request->recipient_id)
          ->where('recipient_table', $request->recipient_table)
          ->where('sender_id', $request->sender_id)
          ->where('sender_table', $request->sender_table);
        })
        ->orWhere(function($query)use($request) {
          return $query->where('recipient_id', $request->sender_id)
          ->where('recipient_table', $request->sender_table)
          ->where('sender_id', $request->recipient_id)
          ->where('sender_table', $request->recipient_table);
        })
        ->orderBy('created_at', 'desc')
        ->offset($offset)
        ->limit($limit)
        ->get()
        ->toArray();

        //update to seen
        $updateToSeen = chat::where('recipient_id', $request->sender_id)
            ->where('recipient_table', $request->sender_table)
            ->where('sender_id', $request->recipient_id)
            ->where('sender_table', $request->recipient_table)
            ->where('seen', 0)
            ->update([
                'seen' => 1,
                'seen_at' => date('Y-m-d H:i:s')
            ]);
        //end of update to seen

        for ($i = 0; $i < count($getMessages); $i++) {
            $getMessages[$i]['created_date'] = date("Y-m-d H:i:s", strtotime($getMessages[$i]['created_at']));
            $getMessages[$i]['seen_date'] = $getMessages[$i]['seen_at'] ? date("Y-m-d H:i:s", strtotime($getMessages[$i]['seen_at'])) : '';
        }

        usort($getMessages, function ($a, $b) {
            return strtotime($a['created_date']) - strtotime($b['created_date']);
        });

        return array(
            'messages' => $getMessages,
        );
    }

    public function sendChatMessage(Request $request)
    {
        $sendChat = new chat();
        $sendChat->recipient_id = $request->recipient_id;
        $sendChat->recipient_table = $request->recipient_table;
        $sendChat->sender_id = $request->sender_id;
        $sendChat->sender_table = $request->sender_table;
        $sendChat->message = $request->message;
        $sendChat->save();

        return $this->getChatMessageData($request);
    }
}
