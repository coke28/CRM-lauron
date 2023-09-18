<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;


class CategoryController extends Controller
{
    //
    public function listCategory(Request $request)
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: *');

        $tableColumns = array(
            'id',
            'category',
            'mainCategory',
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

        // $categories = Category::where(function ($query) use ($search) { // where like search request
        //     return $query->where('category', 'like', '%' . $search . '%')
        //         ->orWhere('mainCategory', 'like', '%' . $search . '%')
        //         ->orWhere('status', 'like', '%' . $search . '%');
        // })
        //     //user is not deleted
        //     ->where('deleted', '0')
        //     //by order
        //     ->orderBy($tableColumns[$sortIndex], $sortOrder)
        //     ->offset($offset)
        //     ->limit($limit)
        //     ->get();

        // foreach ($categories as $p) {

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

        // $categoryCount = Category::where(function ($query) use ($search) { // where like search request
        //     return $query->where('category', 'like', '%' . $search . '%')
        //         ->orWhere('mainCategory', 'like', '%' . $search . '%')
        //         ->orWhere('status', 'like', '%' . $search . '%');
        // })
        //     //user is not deleted
        //     ->where('deleted', '0')
        //     //by order
        //     ->orderBy($tableColumns[$sortIndex], $sortOrder)
        //     ->offset($offset)
        //     ->limit($limit)
        //     ->get()
        //     ->count();


        $categories = Category::where('deleted', '0');
        $categories = $categories->where(function ($query) use ($search) {
            return $query->where('category', 'like', '%' . $search . '%')
            ->orWhere('mainCategory', 'like', '%' . $search . '%')
            ->orWhere('status', 'like', '%' . $search . '%');
        })
            ->orderBy($tableColumns[$sortIndex], $sortOrder);
        $categoryCount = $categories->count();
        $categories = $categories->offset($offset)
            ->limit($limit)
            ->get();

        foreach ($categories as $p) {

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
            'recordsTotal'    => $categoryCount,
            'recordsFiltered' => $categoryCount,
            'data'            => $categories,
        ];

        // reponse must be in  array
        return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function addCategory(Request $request)
    {

        $mainCategory = Category::where('mainCategory', $request->mainCategory)->where('deleted', '0')->get()->count();
        // ->where('deleted', '0')
        if ($mainCategory > 0) {
            return json_encode(array(
                'success' => false,
                'message' => 'Main Category already in use.'
            ));
        }

        $category = Category::where('category', $request->category)->where('deleted', '0')->get()->count();
        // ->where('deleted', '0')
        if ($category > 0) {
            return json_encode(array(
                'success' => false,
                'message' => 'Category already in use.'
            ));
        }

        $category = new Category();
        $category->mainCategory = $request->mainCategory;
        $category->category = $request->category;


        switch ($request->status) {
            case "DISABLED":
                // code block
                $category->status = 0;
                break;
            case "ACTIVE":
                // code block
                $category->status = 1;
                break;
            default:
                // code block
        }
        $category->info = "";
        $category->owner = "";
        $category->save();

        $auditLog = new AuditLog();
        $auditLog->agent = auth()->user()->id;
        $auditLog->action = "Added Category";
        $auditLog->table = "category";
        $auditLog->nID = $category->id . " | " . $request->mainCategory . " | " . $request->category . " | " . $request->status;
        $auditLog->ip = \Request::ip();
        $auditLog->save();

        return json_encode(array(
            'success' => true,
            'message' => 'Category added successfully.'
        ));
    }

    public function getEditCategory(Request $request)
    {
        $getCategory = Category::where('id', $request->id)->first();
        return json_encode($getCategory);
    }

    public function editCategory(Request $request)
    {

        $mainCategory = Category::where('mainCategory', $request->mainCategory)->where('id', '!=', $request->id)->where('deleted', '0')->get()->count();
        // dd($request->id);
        // ->where('deleted', '0')
        if ($mainCategory > 0) {
            return json_encode(array(
                'success' => false,
                'message' => 'Main Category already in use.'
            ));
        }

        $category = Category::where('category', $request->category)->where('id', '!=', $request->id)->where('deleted', '0')->get()->count();
        // ->where('deleted', '0')
        if ($category > 0) {
            return json_encode(array(
                'success' => false,
                'message' => 'Category already in use.'
            ));
        }


        $category = Category::where('id', $request->id)->first();
        if (!empty($category) || $category != null) {

            $category->mainCategory = $request->mainCategory;
            $category->category = $request->category;
            switch ($request->status) {
                case 0:
                    // code block
                    $category->status = 0;
                    break;
                case 1:
                    // code block
                    $category->status = 1;
                    break;
                default:
                    // code block
            }
            $category->info = "";
            $category->owner = "";
            $category->save();

            $auditLog = new AuditLog();
            $auditLog->agent = auth()->user()->id;
            $auditLog->action = "Edited ID #" . " $category->id " . "Category";
            $auditLog->table = "category";
            $auditLog->nID = $category->id . " | " . $request->mainCategory . " | " . $request->category . " | " . $request->status;
            $auditLog->ip = \Request::ip();
            $auditLog->save();


            return json_encode(array(
                'success' => true,
                'message' => 'Category updated successfully.'
            ));
        } else {
            return json_encode(array(
                'success' => false,
                'message' => 'Category not found.'
            ));
        }
    }

    public function deleteCategory(Request $request)
    {
        $deleteCategory = Category::where('id', $request->id)->first();

        if ($deleteCategory) {


            $deleteCategory->deleted = 1;
            $deleteCategory->save();

            $auditLog = new AuditLog();
            $auditLog->agent = auth()->user()->id;
            $auditLog->action = "Deleted ID #" . " $deleteCategory->id " . "Category";
            $auditLog->table = "category";
            $auditLog->nID = "Deleted =" . $deleteCategory->deleted;
            $auditLog->ip = \Request::ip();
            $auditLog->save();


            return 'Category deleted successfully.';
        } else {

            return 'Category deleted unsuccessfully.';
        }
    }
}
