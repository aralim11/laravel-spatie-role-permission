<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class HomeController extends Controller
{
    public function index()
    {
        $blogs = DB::table('blog')->get();

        return view('home', compact(['blogs']));
    }

    public function readMore($id)
    {
        $blog = DB::table('blog')->where('id', $id)->first();
        $html = "";

        $html .='<div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Read Blog</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4 class="card-title">'.$blog->title.'</h4>
                    <small class="text-muted cat">
                        <i class="far fa-clock text-info"></i> ' .getTime($blog->created_at)
                        .'<i class="fas fa-users text-info"></i> ' .getBlogCategory($blog->category_id)
                    .'</small>
                    <p class="card-text">'.$blog->description.'</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>';

        return response()->json(['status' => 'success', 'msg' => $html]);
    }
}
