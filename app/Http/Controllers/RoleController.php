<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;

class RoleController extends Controller
{
    public function index()
    {
        return view('role.role');
    }

    public function roleStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:roles'],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => 'Role Can\'t Be Duplicate!!']);
        } else {

            DB::table('roles')->insert([
                'name' => $request->name,
                'guard_name' => "web",
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ]);

            return response()->json(['status' => 'success', 'msg' => 'Role Added Successfully!!']);
        }
    }
}
