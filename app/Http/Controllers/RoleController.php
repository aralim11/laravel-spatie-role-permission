<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $permission_groups = DB::table('permission_groups')->get();
        return view('role.role', compact(['permission_groups']));
    }

    public function roleStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:roles'],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => 'Role Can\'t Be Duplicate!!']);
        } else {

            $role = Role::create(['name' => $request->name]);
            $role->syncPermissions($request->permission);

            return response()->json(['status' => 'success', 'msg' => 'Role Added Successfully!!']);
        }
    }

    public function viewRole()
    {
        $roles = DB::table('roles')->get();
        $i = 1;
        $html = "";

        foreach($roles as $role){
            $html .= '<tr>
                        <td>'.$i++.'</td>
                        <td>'.$role->name.'</td>
                        <td><button type="button" class="btn btn-info btn-sm">Edit</button></td>
                     </tr>';
        }

        return response()->json(['status' => 'success', 'msg' => $html]);
    }
}
