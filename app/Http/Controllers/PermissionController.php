<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;

class PermissionController extends Controller
{
    public function index()
    {
        return view('permission.permission');
    }

    public function storePermission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:permissions'],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => 'Permission Can\'t Be Duplicate!!']);
        } else {

            DB::table('permissions')->insert([
                'name' => $request->name,
                'guard_name' => "web",
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ]);

            return response()->json(['status' => 'success', 'msg' => 'Permission Added Successfully!!']);
        }
    }

    public function viewPermission(){
        $permissions = DB::table('permissions')->get();
        $i = 1;
        $html = "";

        foreach($permissions as $permission){
            $html .= '<tr>
                        <td>'.$i++.'</td>
                        <td>'.$permission->name.'</td>
                        <td><button type="button" class="btn btn-info btn-sm">Edit</button></td>
                    </tr>';
        }

        return response()->json(['status' => 'success', 'msg' => $html]);
    }
}
