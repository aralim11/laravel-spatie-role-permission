<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role_or_permission:permission.view|permission.add|permission.edit|permission.delete']);
    }

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
                        <td>'.$permission->name.'</td>';
                        if(Auth::User()->can('permission.edit')) {$html .= '<td><button type="button" class="btn btn-info btn-sm" onclick="openEditpermissionGroupModal('.$permission->id.')">Edit</button></td>';}
                    $html .= '</tr>';
        }

        return response()->json(['status' => 'success', 'msg' => $html]);
    }

    public function editPermission($id){
        $permission = DB::table('permissions')->where('id', $id)->first();
        $permissionGroups = DB::table('permission_groups')->get();
        $html = "";
        $selected = "";

        $html .='<div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="col-form-label">Permission Group</label>
                        <select class="form-select" id="edit_group_id" aria-label="Default select example" required>
                            <option value="">Select Permission Group</option>';
                            foreach($permissionGroups as $permissionGroup){
                            if($permissionGroup->id === $permission->group_id){$selected = 'selected';}
                            $html .= '<option value='.$permissionGroup->id.' '.$selected.'>'.$permissionGroup->name.'</option>';}
                        $html .= '</select>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="col-form-label">Permission Name</label>
                        <input type="text" class="form-control" id="edit_name" value="'.$permission->name.'" placeholder="Enter Permission Name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="updatePermission('.$id.')">Save</button>
                </div>';

        return response()->json(['status' => 'success', 'msg' => $html]);
    }

    public function updatePermission(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name,'. $request->id],
            'group_id' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => 'Permission Can\'t Be Duplicate!!']);
        } else {

            DB::table('permissions')
                ->where('id', $request->id)
                ->update(['name' => $request->name, 'group_id' => $request->group_id]);

            return response()->json(['status' => 'success', 'msg' => 'Permission Updated Successfully!!']);
        }
    }
}
