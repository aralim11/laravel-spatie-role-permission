<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Facades\Auth;

class PermissionGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role_or_permission:permission.group.view|permission.group.add|permission.group.edit|permission.group.delete']);
    }

    public function index()
    {
        return view('permission-group.permission-group');
    }

    public function storePermissionGroup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:permission_groups'],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => 'Permission Group Can\'t Be Duplicate!!']);
        } else {

            DB::table('permission_groups')->insert([
                'name' => $request->name,
                'guard_name' => "web",
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ]);

            return response()->json(['status' => 'success', 'msg' => 'Permission Group Successfully!!']);
        }
    }

    public function viewPermissionGroup()
    {
        $permissionsGroups = DB::table('permission_groups')->get();
        $i = 1;
        $html = "";

        foreach($permissionsGroups as $permissionsGroup){
            $html .= '<tr>
                        <td>'.$i++.'</td>
                        <td>'.$permissionsGroup->name.'</td>';
                        if (Auth::User()->can('permission.group.edit')) {$html .= '<td><button type="button" onclick="openEditpermissionGroupModal('.$permissionsGroup->id.')" class="btn btn-info btn-sm">Edit</button>';}
                        if (Auth::User()->can('permission.group.delete')) {$html .= '&nbsp;<button type="button" onclick="deletePermissionGroup('.$permissionsGroup->id.')" class="btn btn-danger btn-sm">Delete</button>';}
                        $html .= '</td>';
                    $html .= '</tr>';
        }

        return response()->json(['status' => 'success', 'msg' => $html]);
    }

    public function editPermissionGroup($id)
    {
        $permissionGroup = DB::table('permission_groups')->where('id', $id)->first();

        $html = '<div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Permission Group</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="col-form-label">Permission Group Name</label>
                        <input type="text" class="form-control" id="edit_name" value='.$permissionGroup->name.' placeholder="Enter Permission Group Name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="updatePermissionGroup('.$id.')">Save</button>
                </div>';

        return response()->json(['status' => 'success', 'msg' => $html]);
    }

    public function updatePermissionGroup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:permission_groups,name,'. $request->id],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => 'Permission Group Can\'t Be Duplicate!!']);
        } else {

            DB::table('permission_groups')
                ->where('id', $request->id)
                ->update(['name' => $request->name]);

            return response()->json(['status' => 'success', 'msg' => 'Permission Group Updated Successfully!!']);
        }
    }

    public function deletePermissionGroup($id)
    {
        if ($id) {
            DB::table('permission_groups')->where('id', $id)->delete();
            return response()->json(['status' => 'success', 'msg' => 'Permission Group Deleted Successfully!!']);
        }
    }
}
