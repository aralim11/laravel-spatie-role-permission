<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class BlogPostController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role_or_permission:blog.view|blog.add|blog.edit|blog.delete']);
    }

    public function index()
    {
        $categories = DB::table('category')->get();

        return view('blog.blog', compact(['categories']));
    }

    public function storeBlog(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255', 'unique:blog'],
            'category_id' => ['required', 'string', 'max:255'],
            'description' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => 'Fill All Field\'s Perfectly!!']);
        } else {

            DB::table('blog')->insert([
                'title' => $request->title,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ]);

            return response()->json(['status' => 'success', 'msg' => 'Blog Added Successfully!!']);
        }
    }

    public function viewBlog()
    {
        $blogs = DB::table('blog')
                    ->join('category','category.id','=','blog.category_id')
                    ->select('blog.*','category.name')
                    ->get();
        $i = 1;
        $html = "";

        foreach($blogs as $blog){
            $html .= '<tr>
                        <td>'.$i++.'</td>
                        <td>'.$blog->name.'</td>
                        <td>'.$blog->title.'</td>';
                        if (Auth::User()->can('blog.edit') || Auth::User()->can('blog.delete')) {$html .= '<td>';
                        if (Auth::User()->can('blog.edit')) {$html .= '<button type="button" onclick="openEditBlogModal('.$blog->id.')" class="btn btn-info btn-sm">Edit</button>';}
                        if (Auth::User()->can('blog.delete')) {$html .= '&nbsp;<button type="button" onclick="deleteBlog('.$blog->id.')" class="btn btn-danger btn-sm">Delete</button>';}
                        $html .= '</td>';}
                    $html .= '</tr>';
        }

        return response()->json(['status' => 'success', 'msg' => $html]);
    }

    public function editBlog($id)
    {
        $blog = DB::table('blog')->where('id', $id)->first();
        $categories = DB::table('category')->get();
        $html = "";

        $html .= '<div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Blog</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_title" class="col-form-label">Blog Title <span class="req">*</span></label>
                        <input type="text" class="form-control" value="'.$blog->title.'" id="edit_title" placeholder="Enter Blog Title" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_category_id" class="col-form-label">Category Name <span class="req">*</span></label>
                        <select class="form-select" id="edit_category_id" aria-label="Default select example" required>
                            <option value="">Select Category</option>';
                            foreach($categories as $category){
                                if($category->id === $blog->category_id){$selected = "selected";}else{$selected = "";}
                                $html .= '<option value="'.$category->id.'" '.$selected.'>'.$category->name.'</option>';
                            }
                        $html .= '</select>
                    </div>

                    <div class="mb-3">
                        <label for="edit_description" class="col-form-label">Blog Description <span class="req">*</span></label>
                        <textarea name="description" id="edit_description" class="form-control" cols="30" rows="10" required>'.$blog->description.'</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="updateBlog('.$blog->id.')">Save</button>
                </div>';

        return response()->json(['status' => 'success', 'msg' => $html]);
    }

    public function updateBlog(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255', 'unique:blog,title,' . $request->id],
            'category_id' => ['required', 'string', 'max:255'],
            'description' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => 'Fill All Field\'s Perfectly!!']);
        } else {

            DB::table('blog')
                ->where('id', $request->id)
                ->update(['title' => $request->title, 'category_id' => $request->category_id, 'description' => $request->description, 'updated_at' => date("Y-m-d H:i:s")]);

            return response()->json(['status' => 'success', 'msg' => 'Blog Added Successfully!!']);
        }
    }

    public function deleteBlog($id)
    {
        if ($id) {
            DB::table('blog')->where('id', $id)->delete();
            return response()->json(['status' => 'success', 'msg' => 'Blog Deleted Successfully!!']);
        }
    }
}
