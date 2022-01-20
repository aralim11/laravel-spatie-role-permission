<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;

class PermissionController extends Controller
{
    public function index()
    {
        $permissionGroups = DB::table('permission_groups')->get();
        return view('permission.permission', compact(['permissionGroups']));
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
                'group_id' => $request->group_id,
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
            $groupName = DB::table('permission_groups')->where('id', $permission->group_id)->first();
            $html .= '<tr>
                        <td>'.$i++.'</td>
                        <td>'.$groupName->name.'</td>
                        <td>'.$permission->name.'</td>
                        <td><button type="button" class="btn btn-info btn-sm">Edit</button></td>
                    </tr>';
        }

        return response()->json(['status' => 'success', 'msg' => $html]);
    }
}
