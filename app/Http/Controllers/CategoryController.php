<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role_or_permission:category.view|category.add|category.edit|category.delete']);
    }

    public function index()
    {
        return view('category.category');
    }

    public function storeCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:category'],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => 'Category Name Can\'t Be Duplicate!!']);
        } else {

            DB::table('category')->insert([
                'name' => $request->name,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ]);

            return response()->json(['status' => 'success', 'msg' => 'Category Added Successfully!!']);
        }
    }

    public function viewCategory()
    {
        $categories = DB::table('category')->get();
        $i = 1;
        $html = "";

        foreach($categories as $category){
            $html .= '<tr>
                        <td>'.$i++.'</td>
                        <td>'.$category->name.'</td>';
                        if (Auth::User()->can('category.edit')) {$html .= '<td><button type="button" onclick="openEditCategoryModal('.$category->id.')" class="btn btn-info btn-sm">Edit</button>';}
                        if (Auth::User()->can('category.delete')) {$html .= '&nbsp;<button type="button" onclick="deleteCategory('.$category->id.')" class="btn btn-danger btn-sm">Delete</button>';}
                        $html .= '</td>';
                    $html .= '</tr>';
        }

        return response()->json(['status' => 'success', 'msg' => $html]);
    }

    public function editCategory($id)
    {
        $category = DB::table('category')->where('id', $id)->first();
        $html = "";

        $html .= '<div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="col-form-label">Category Name</label>
                        <input type="text" class="form-control" id="edit_name" value="'.$category->name.'" placeholder="Enter Category Name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="updateCategory('.$id.')">Save</button>
                </div>';

        return response()->json(['status' => 'success', 'msg' => $html]);
    }

    public function updateCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:category,name,' . $request->id],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => 'Category Name Can\'t Be Duplicate!!']);
        } else {

            DB::table('category')
                ->where('id', $request->id)
                ->update(['name' => $request->name]);

            return response()->json(['status' => 'success', 'msg' => 'Category Updated Successfully!!']);
        }
    }

    public function deleteCategory($id)
    {
        if ($id) {
            DB::table('category')->where('id', $id)->delete();
            return response()->json(['status' => 'success', 'msg' => 'Category Deleted Successfully!!']);
        }
    }
}
