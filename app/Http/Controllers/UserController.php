<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
// use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Facades\Image as Image;
use Throwable;

class UserController extends Controller
{
  //
  public function listUsers(Request $request)
  {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: *');

    $tableColumns = array(
      'id',
      'username',
      'first_name',
      'last_name',
      'level',
      'groupe',
      'extension',
      'agentNum',
      'product'
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

    // $users = User::where(function ($query) use ($search) { // where like search request
    //   return $query->where('username', 'like', '%' . $search . '%')
    //     ->orWhere('first_name', 'like', '%' . $search . '%')
    //     ->orWhere('last_name', 'like', '%' . $search . '%')
    //     ->orWhere('level', 'like', '%' . $search . '%')
    //     ->orWhere('groupe', 'like', '%' . $search . '%')
    //     ->orWhere('extension', 'like', '%' . $search . '%')
    //     ->orWhere('agentNum', 'like', '%' . $search . '%');
    // })
    //   //user is not deleted
    //   ->where('deleted', '0')
    //   //by order
    //   ->orderBy($tableColumns[$sortIndex], $sortOrder)
    //   ->offset($offset)
    //   ->limit($limit)
    //   ->get();

    // foreach ($users as $p) {

    //   switch ($p->level) {
    //     case 0:
    //       // code block
    //       $p->level = "AGENT";
    //       break;
    //     case 1:
    //       // code block
    //       $p->level = "SUPERVISOR";
    //       break;
    //     case 2:
    //       // code block
    //       $p->level = "ADMIN";
    //       break;

    //     default:
    //       // code block
    //   }
    // }

    // $usersCount = User::where(function ($query) use ($search) { // where like search request
    //   return $query->where('username', 'like', '%' . $search . '%')
    //     ->orWhere('first_name', 'like', '%' . $search . '%')
    //     ->orWhere('last_name', 'like', '%' . $search . '%')
    //     ->orWhere('level', 'like', '%' . $search . '%')
    //     ->orWhere('groupe', 'like', '%' . $search . '%')
    //     ->orWhere('extension', 'like', '%' . $search . '%')
    //     ->orWhere('agentNum', 'like', '%' . $search . '%');
    // })
    //   //user is not deleted
    //   ->where('deleted', '0')
    //   //by order
    //   ->orderBy($tableColumns[$sortIndex], $sortOrder)
    //   ->offset($offset)
    //   ->limit($limit)
    //   ->get()
    //   ->count();
    $users = User::where('deleted', '0')->where('username','!=',"root");
    // $users = User::where('deleted', '0');
    $users = $users->where(function ($query) use ($search) {
      return $query->where('username', 'like', '%' . $search . '%')
        ->orWhere('first_name', 'like', '%' . $search . '%')
        ->orWhere('last_name', 'like', '%' . $search . '%')
        ->orWhere('level', 'like', '%' . $search . '%')
        ->orWhere('groupe', 'like', '%' . $search . '%')
        ->orWhere('extension', 'like', '%' . $search . '%')
        ->orWhere('agentNum', 'like', '%' . $search . '%')
        ->orWhere('product', 'like', '%' . $search . '%');
    })
      ->orderBy($tableColumns[$sortIndex], $sortOrder);
    $usersCount = $users->count();
    $users = $users->offset($offset)
      ->limit($limit)
      ->get();

    foreach ($users as $p) {

      switch ($p->level) {
        case 0:
          // code block
          $p->level = "AGENT";
          break;
        case 1:
          // code block
          $p->level = "SUPERVISOR";
          break;
        case 2:
          // code block
          $p->level = "ADMIN";
          break;

        default:
          // code block
      }
    }

    $result = [
      'recordsTotal'    => $usersCount,
      'recordsFiltered' => $usersCount,
      'data'            => $users,
    ];

    // reponse must be in  array
    return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
  }

  public function checkUser($username, $type, $id = 0)
  {
    // $toCheck = strtolower($username);
    $toCheck = $username;
    $checkExist = 0;
    // if($type == 'add'){
    //   $checkExist = CrmUser::whereRaw('LOWER(`username`) = "'.$toCheck.'"')->where('deleted', '0')->count();
    // }

    if ($type == 'add') {
      // $checkExist = User::whereRaw('LOWER(`username`) = "'.$toCheck.'"')->where('deleted', '0')->count();
      $checkExist = User::where('username', $toCheck)->where('deleted', '0')->count();
      //If deleted user has the same username
      // if ($checkExist = User::where('username', $toCheck)->where('deleted', '1')->count() > 0) {
      //   $checkExist = 2;
      // }
    }
    if ($type == 'edit') {
      // $checkExist = User::whereRaw('LOWER(`username`) = "'.$toCheck.'"')->where('id', '!=', $id)->where('deleted', '0')->count();
      $checkExist = User::where('username', $toCheck)->where('id', '!=', $id)->where('deleted', '0')->count();
      //If deleted user has the same username
      // if (User::where('username', $toCheck)->where('id', '!=', $id)->where('deleted', '1')->count() > 0) {
      //   //
      //   $checkExist = 2;
      // }
    }
    return $checkExist;
  }

  public function addUser(Request $request)
  {
    $username = trim($request->username);
    if ($this->checkUser($username, 'add') > 0) {
      if ($this->checkUser($username, 'add') == 2) {
        return json_encode(array(
          'success' => false,
          'message' => 'Username already in use by a soft deleted user. Please contact support to hard delete the user'
        ));
      } else {
        return json_encode(array(
          'success' => false,
          'message' => 'Username already in use.'
        ));
      }
    }

    // $password = User::where('password', $request->password)->get()->count();
    // ->where('deleted', '0')

    // if ($password > 0) {
    //   return json_encode(array(
    //     'success' => false,
    //     'message' => 'Password already in use.'
    //   ));
    // }

    $extension = User::where('extension', $request->phoneExtension)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')
    if ($extension > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Phone Extension Number already in use.'
      ));
    }

    $agentNum = User::where('agentNum', $request->agentNum)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')
    if ($agentNum > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Agent Number already in use.'
      ));
    }

    $salesmanID = User::where('salesmanID', $request->salesmanID)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')

    if ($salesmanID > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Salesman ID already in use.'
      ));
    }

    $user = new User();
    $user->username = $username;
    $user->password = Hash::make($request->password);
    $user->first_name = $request->firstName;
    $user->middle_name = $request->middleName;
    $user->last_name = $request->lastName;
    $user->email = "";
    $user->fromMeEmail = "";
    $user->extension = $request->phoneExtension;
    $level = (int)$request->level;
    $user->product = $request->product;

    if(!empty($request->product)){
      $user->accountID = $request->product;
    }else{
      $user->accountID = "Outbound";
    }
    $user->level = $level;
    $user->groupe = $request->groupe;
    $user->endToEnd = $request->endToEnd;

    // $user->avatar = $request->file;
  
    try{
      $avatar = $request->file;
      $filename = date('ymd') . strtoupper(Str::random(15)) . '.' . strtolower($avatar->getClientOriginalExtension());
      Image::make($avatar)->save(storage_path('app/public/avatars/' . $filename));
    }catch(Throwable $e){
      return json_encode(array(
        'success' => false,
        'message' => 'Image Type not supported.'.$e
      ));

    }
   
    $user->avatar = 'storage/avatars/' . $filename;



    $user->agentNum = $request->agentNum;
    $user->salesmanID = $request->salesmanID;
    $user->save();

    $auditLog = new AuditLog();
    $auditLog->agent = auth()->user()->id;
    $auditLog->action = "Added Users";
    $auditLog->table = "users";
    $auditLog->nID = $user->id . " | " . $username . " | " . Hash::make($request->password) . " | " . $request->firstName .
      " | " . $request->middleName . " | " . $request->lastName . " | " . $request->phoneExtension . " | " . $request->level . " | " . $request->groupe . " | "
      . $request->endToEnd . " | " . $request->agentNum . " | " . $request->salesmanID . " | ". $request->product ." | " . $request->status;
    $auditLog->ip = \Request::ip();

    $auditLog->save();


    return json_encode(array(
      'success' => true,
      'message' => 'User added successfully.'
    ));
  }

  public function getEditUser(Request $request)
  {
    $getUser = User::where('id', $request->id)->first();
    return json_encode($getUser);
  }

  public function editUser(Request $request)
  {

    if ($this->checkUser($request->username, 'edit', $request->id) > 0) {
      if ($this->checkUser($request->username, 'edit') == 2) {
        return json_encode(array(
          'success' => false,
          'message' => 'Username already in use by a soft deleted user. Please contact support to hard delete the user'
        ));
      } else {
        return json_encode(array(
          'success' => false,
          'message' => 'Username already in use.'
        ));
      }
    }
    $extension = User::where('extension', $request->phoneExtension)->where('id', '!=', $request->id)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')
    if ($extension > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Phone Extension Number already in use.'
      ));
    }

    $agentNum = User::where('agentNum', $request->agentNum)->where('id', '!=', $request->id)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')
    if ($agentNum > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Agent Number already in use.'
      ));
    }

    $salesmanID = User::where('salesmanID', $request->salesmanID)->where('id', '!=', $request->id)->where('deleted', '0')->get()->count();
    // ->where('deleted', '0')

    if ($salesmanID > 0) {
      return json_encode(array(
        'success' => false,
        'message' => 'Salesman ID already in use.'
      ));
    }

    $user = User::where('id', $request->id)->first();

    if (!empty($user) || $user != null) {
      $user->username = $request->username;
      if (!empty($request->password) && $request->password != null) {
        Log::info('password is not empty and is not null.');
        $user->password = Hash::make($request->password);
      } else {
        Log::info('password is empty and is null.');
      }

      if (!empty($request->file) && $request->file != null) {
        Log::info('File is not empty and is not null.');
        if (\File::exists(public_path($user->avatar))) {
          \File::delete(public_path($user->avatar));
        }
      
        try{
          $avatar = $request->file;
          $filename = date('ymd') . strtoupper(Str::random(15)) . '.' . strtolower($avatar->getClientOriginalExtension());
          Image::make($avatar)->save(storage_path('app/public/avatars/' . $filename));
          $user->avatar = 'storage/avatars/' . $filename;
        }catch(Throwable $e){
          return json_encode(array(
            'success' => false,
            'message' => 'Image Type not supported.'.$e
          ));
    
        }
  
      } else {
        Log::info('File is empty and is null.');
      }
      $user->first_name = $request->firstName;
      $user->middle_name = $request->middleName;
      $user->last_name = $request->lastName;
      $user->extension = $request->phoneExtension;
      // $level = (int)$request->level;
      $user->level = $request->level;
      $user->product = $request->product;
      $user->accountID = $request->product;
      $user->groupe = $request->groupe;
      $user->endToEnd = $request->endToEnd;
      $user->agentNum = $request->agentNum;
      $user->salesmanID = $request->salesmanID;
      $user->save();

      $auditLog = new AuditLog();
      $auditLog->agent = auth()->user()->id;
      $auditLog->action = "Edited ID #" . " $user->id " . "User";
      $auditLog->table = "users";
      $auditLog->nID = $user->id . " | " . $request->username . " | " . Hash::make($request->password) . " | " . $request->firstName .
      " | " . $request->middleName . " | " . $request->lastName . " | " . $request->phoneExtension . " | " . $request->level . " | " . $request->groupe . " | "
      . $request->endToEnd . " | " . $request->agentNum . " | " . $request->salesmanID . " | ". $request->product ." | " . $request->status;
      $auditLog->ip = \Request::ip();
      $auditLog->save();

      return json_encode(array(
        'success' => true,
        'message' => 'User updated successfully.'
      ));
    } else {

      return json_encode(array(
        'success' => false,
        'message' => 'User not found.'
      ));
    }
  }

  public function deleteUser(Request $request)
  {
    $deleteUser = User::where('id', $request->id)->first();
    if ($deleteUser) {

      if (\File::exists(public_path($deleteUser->avatar))) {
        \File::delete(public_path($deleteUser->avatar));
        $deleteUser->avatar = null;
        $deleteUser->deleted = 1;
        $deleteUser->save();

        $auditLog = new AuditLog();
        $auditLog->agent = auth()->user()->id;
        $auditLog->action = "Deleted ID #" . " $deleteUser->id " . "User";
        $auditLog->table = "users";
        $auditLog->nID = "Deleted =" . $deleteUser->deleted;
        $auditLog->ip = \Request::ip();
        $auditLog->save();
        return 'User deleted successfully.';
      }
      //create log
      // $savelog = new CrmLog();
      // $savelog->module_name = 'Special Announcements';
      // $savelog->action = 'Deleted an announcement. Name: '.$deleteAnnouncement->name.'';
      // $savelog->user_id = auth()->user()->id;
      // $savelog->user_name = $savelog->user_id == 0 ? 'System' : auth()->user()->first_name.' '.auth()->user()->last_name;
      // $savelog->affected_row_copy = json_encode($deleteAnnouncement);
      // $savelog->save();
    } else {
      return 'User not found.';
    }
  }
}
