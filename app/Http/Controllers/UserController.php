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
    public function index()
    {
        if (Auth::User()->can('settings.view')) {
            $roles = DB::table('roles')->get();

            return view('user.user', compact(['roles']));
        } else {
            return redirect()->back();
        }
    }

    public function storeUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:permission_groups'],
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
            $html .= '<tr>
                        <td>'.$i++.'</td>
                        <td>'.$user->name.'</td>
                        <td>'.$user->email.'</td>
                        <td>Processing</td>
                        <td><button type="button" class="btn btn-info btn-sm" onclick="openEditUserModal('.$user->id.')">Edit</button></td>
                     </tr>';
        }

        return response()->json(['status' => 'success', 'msg' => $html]);
    }
}
