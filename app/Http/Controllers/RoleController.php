<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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
                        <td><button type="button" class="btn btn-info btn-sm" onclick="openEditRoleModal('.$role->id.')">Edit</button></td>
                     </tr>';
        }

        return response()->json(['status' => 'success', 'msg' => $html]);
    }


    public function editRole($id)
    {
        $role = DB::table('roles')->where('id', $id)->first();
        $permission_groups = DB::table('permission_groups')->get();

        $html = '<div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body edit_role_add_form">
                    <div class="mb-3">
                        <label for="name" class="col-form-label">Role Name</label>
                        <input type="text" class="form-control" value="'.$role->name.'" id="edit_name" placeholder="Enter Role Name" required>
                    </div>

                    <h5>All Permissions</h5><hr style="background-color: black!important;">
                    <table class="hundred_percent">
                        <tbody>';
                            foreach($permission_groups as $permission_group){
                                $permissions = DB::table('permissions')->where('group_id', $permission_group->id)->get();
                                $selectedCount = 0;
                                foreach($permissions as $permission_chek){
                                    $checkHasPermission = DB::table('role_has_permissions')
                                        ->where('permission_id', $permission_chek->id)
                                        ->where('role_id', $role->id)
                                        ->first();
                                    if (!empty($checkHasPermission)) {$selectedCount = $selectedCount + 1;}
                                }

                                if ($selectedCount === count($permissions)) {$groupChecked = 'Checked';}else{$groupChecked = '';}

                                $html .= '<tr class="table_bottom_border">
                                    <td class="fifty_percent">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" '.$groupChecked.' value="'.$permission_group->id.'" id="edit_permission_group_'. $permission_group->id.'" onclick="edit_checkAllPermissionByGroup('.$permission_group->id.')">
                                            <label class="form-check-label" for="edit_permission_group_'.$permission_group->id.'">'.$permission_group->name .'</label>
                                        </div>
                                    </td>';
                                    $html .= '<td class="fifty_percent">';
                                        foreach($permissions as $permission){

                                            $hasPermission = DB::table('role_has_permissions')
                                                            ->where('permission_id', $permission->id)
                                                            ->where('role_id', $role->id)
                                                            ->first();

                                            if (!empty($hasPermission)) {$selected = "checked";} else {$selected = "";}

                                            $html .= '<div class="form-check">
                                                <input class="form-check-input edit-fom-check edit_checkAllPermissionByGroup_'.$permission_group->id.'" '.$selected.' onclick="edit_checkGroupByPermission('. $permission_group->id .' , '. count($permissions) .')" name="edit_checkPermission" type="checkbox" value="'.$permission->name.'" id="edit_checkPermission'.$permission->id.'">
                                                <label class="form-check-label" for="edit_checkPermission'.$permission->id.'">'. $permission->name .'</label>
                                            </div>';
                                        }
                                    $html .= '</td>
                                </tr>';
                            }
                        $html .= '</tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="updateRole('.$role->id.')">Save</button>
                </div>';

        return response()->json(['status' => 'success', 'msg' => $html]);
    }

    public function editUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,' . $request->id],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => 'Role Can\'t Be Duplicate!!']);
        } else {

            $role = Role::findById($request->id);
            $role->syncPermissions($request->permission);

            DB::table('roles')->where('id', $request->id)->update(['name' => $request->name]);

            return response()->json(['status' => 'success', 'msg' => 'Role Added Successfully!!']);
        }
    }
}
