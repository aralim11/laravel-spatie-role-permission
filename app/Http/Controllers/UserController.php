<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role_or_permission:user.view|user.add|user.edit|user.delete']);
    }

    public function index()
    {
        $roles = DB::table('roles')->get();

        return view('user.user', compact(['roles']));
    }

    public function storeUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => 'Email Can\'t Be Duplicate!!']);
        } else {

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make('1234');
            $user->save();

            if ($request->role_id) {
                $user->assignRole($request->role_id);
            }

            return response()->json(['status' => 'success', 'msg' => 'Permission Group Successfully!!']);
        }
    }

    public function showUser()
    {
        $users = DB::table('users')->get();
        $i = 1;
        $html = "";

        foreach($users as $user){
            $role = DB::table('model_has_roles')
                ->join('roles','roles.id','=','model_has_roles.role_id')
                ->select('roles.name')
                ->where(['model_has_roles.model_id' => $user->id])
                ->first();
            
                if (!empty($role)){$assignedRole = "<span class=\"badge bg-success\">$role->name</span>";} else {$assignedRole = "<span class=\"badge bg-warning text-dark\">Not Assigned Yet</span>";}
                
            $html .= '<tr>
                        <td>'.$i++.'</td>
                        <td>'.$user->name.'</td>
                        <td>'.$user->email.'</td>
                        <td>'.$assignedRole.'</td>';
                        if (Auth::User()->can('user.edit')) {$html .= '<td><button type="button" class="btn btn-info btn-sm" onclick="openEditUserModal('.$user->id.')">Edit</button></td>';}
                    $html .= '</tr>';
        }

        return response()->json(['status' => 'success', 'msg' => $html]);
    }

    public function editUser($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        $roles = DB::table('roles')->get();

        $html = '<div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="col-form-label">User Name <span class="req">*</span></label>
                        <input type="text" class="form-control" id="edit_name" value="'.$user->name.'" placeholder="Enter User Name" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="edit_email" class="col-form-label">Email Address <span class="req">*</span></label>
                        <input type="email" class="form-control" id="edit_email" value="'.$user->email.'" placeholder="Enter User Email" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="edit_role_id" class="col-form-label">User Role</label>
                        <select class="form-select" id="edit_role_id" aria-label="Default select example">
                            <option value="">Select User Role</option>';
                            foreach($roles as $role){
                                $roleCheck = DB::table('model_has_roles')->where('role_id', $role->id)->where('model_id', $user->id)->first();
                                if (!empty($roleCheck)){$selected = "selected";} else {$selected = "";}
                                $html .= '<option value="'.$role->id.'" '.$selected.'>'.$role->name.'</option>';
                            }
                        $html .= '</select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="updateUser('.$user->id.')">Save</button>
                </div>';

        return response()->json(['status' => 'success', 'msg' => $html]);
    }

    public function updateUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $request->id],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => 'Email Can\'t Be Duplicate!!']);
        } else {

            $user = User::find($request->id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

            $user->roles()->detach();
            if ($request->role_id) {
                $user->assignRole($request->role_id);
            }

            return response()->json(['status' => 'success', 'msg' => 'User Updated Successfully!!']);
        }
    }
}
