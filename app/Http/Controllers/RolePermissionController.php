<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    public function role()
    {
        return view('role.role');
    }

    public function roleStore(Request $request){
        return $request;
    }
}
