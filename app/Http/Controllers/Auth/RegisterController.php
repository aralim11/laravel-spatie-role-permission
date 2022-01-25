<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;

class RegisterController extends Controller
{
    use RegistersUsers;
    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('auth');
    }


    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }


    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    protected function showUser()
    {
        $users = DB::table('users')->get();

        $i = 1;
        $html = "";

        foreach($users as $user){
            $html .= '<tr>
                        <td>'.$i++.'</td>
                        <td>'.$role->name.'</td>
                        <td><button type="button" class="btn btn-info btn-sm" onclick="openEditRoleModal('.$role->id.')">Edit</button></td>
                     </tr>';
        }

        return response()->json(['status' => 'success', 'msg' => $html]);
    }
}
