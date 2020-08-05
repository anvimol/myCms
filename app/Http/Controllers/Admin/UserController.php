<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function __Construct() 
    {
        $this->middleware('auth');
        $this->middleware('user.status');
        $this->middleware('user.permissions');
        $this->middleware('isadmin');
    }

    public function getUsers($status) {
        if ($status == 'all') {
            $users = User::orderBy('id', 'desc')->paginate(8);
        }
        else {
            $users = User::where('status', $status)->orderBy('id', 'desc')->paginate(8);
        }
        $data = ['users' => $users];
        return view('admin.users.home', $data);
    }

    public function getUserEdit($id)
    {
        $user = User::findOrFail($id);
        $data = ['user' => $user];
        return view('admin.users.edit', $data);
    }

    public function postUserEdit(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->role = $request->input('user_type');
        if ($request->input('user_type') == "1") {
            if (is_null($user->permissions)) {
                $permissions = [
                    'dashboard' => true
                ];
                $permissions = json_encode($permissions);
                $user->permissions = $permissions;
            }
        } else {
            $user->permissions = null;
        }
        if ($user->save()) {
            if ($request->input('user_type') == "1") {
                return redirect('/admin/user/' . $user->id . '/permissions')
                    ->with('message', 'El rango del usuario se actualizo con exito')
                    ->with('typealert', 'success');
            } else {
                return back()
                    ->with('message', 'El rango del usuario se actualizo con exito')
                    ->with('typealert', 'success');
            }
        }
        
    }

    public function getUserBanned($id)
    {
        $user = User::findOrFail($id);
        if ($user->status == "100") {
            $user->status = "0";
            $msg = "Usuario activado con exito.";
        }
        else {
            $user->status = "100";
            $msg = "Usuario suspendido con exito.";
        }

        if ($user->save()) {
            return back()
                ->with('message', $msg)
                ->with('typealert', 'success');
        } else {
            # code...
        }
        
    }

    public function getUserPermissions($id)
    {
        $user = User::findOrFail($id);
        $data = ['user' => $user];
        return view('admin.users.permissions', $data);
    }

    public function postUserPermissions(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->permissions = $request->except(['_token']);
        if ($user->save()) {
            return back()
                ->with('message', 'Los permisos del usuario fueron actualizados con éxito')
                ->with('typealert', 'success');
        }
    }
}
