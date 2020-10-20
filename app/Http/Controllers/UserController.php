<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, Auth, Image, Config, Str, Hash;
use App\User;

class UserController extends Controller
{
    public function __constructor() {
        $this->middleware('auth');
    }

    public function getAccountEdit() {
        $birthday = (is_null(Auth::user()->birthday)) ? [null,null,null] : explode('-', Auth::user()->birthday);
        
        $data = ['birthday' => $birthday];
        return view('user.account_edit', $data);
    }

    public function postAccountAvatar(Request $request) {
        $rules = [
            'avatar' => 'required'
        ];

        $messages = [
            'avatar.required' => 'Seleccione una imagen destacada',
            'avatar.image' => 'El archivo no es una imagen'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) 
        {
            return back()
                    ->withErrors($validator)
                    ->with('message', 'Se ha producido un error')
                    ->with('typealert', 'danger')
                    ->withInput();
        } else { 
            if ($request->hasFile('avatar')) {
                $path = '/'.Auth::id();
                $fileExt = trim($request->file('avatar')->getClientOriginalExtension());
                $upload_path = Config::get('filesystems.disks.uploads_users.root');
                $name = Str::slug(str_replace($fileExt, '', $request->file('avatar')->getClientOriginalName()));
                $filename = rand(1,999).'_'.$name.'.'.$fileExt;
                $file_file = $upload_path . '/' . $path . '/' . $filename;

                $u = User::find(Auth::id());
                $actual_avatar = $u->avatar;
                $u->avatar = $filename;

                if ($u->save()) {
                    if($request->hasFile('avatar')) {
                        $fl = $request->avatar->storeAs($path, $filename, 'uploads_users');
                        $img = Image::make($file_file);
                        $img->resize(256, 256, function($constraint) {
                            $constraint->upsize();
                        }); 
                        /* $img->fit(256, 256, function($constraint) {
                            $constraint->upsize();
                        });  */ 
                        $img->save($upload_path.'/'.$path.'/av_'.$filename);
                    }
                    unlink($upload_path.'/'.$path.'/'.$actual_avatar);
                    unlink($upload_path.'/'.$path.'/av_'.$actual_avatar);
                    return back()
                        ->with('message', 'Avatar actualizado con exito')
                        ->with('typealert', 'success');
                }
            }
        }
    }

    public function postAccountPassword(Request $request) {
        $rules = [
            'apassword' => 'required|min:8',
            'password' => 'required|min:8',
            'cpassword' => 'required|min:8|same:password'
        ];

        $messages = [
            'apassword.required' => 'Escriba su contraseña actual',
            'apassword.min' => 'La contraseña actual debe tener al menos 8 caracteres',
            'password.required' => 'Escriba su nueva contraseña',
            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres',
            'cpassword.required' => 'Confirme su nueva contraseña',
            'cpassword.min' => 'La confirmación de la nueva contraseña debe tener al menos 8 caracteres',
            'cpassword.same' => 'Las contraseñas no coinciden'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) 
        {
            return back()
                    ->withErrors($validator)
                    ->with('message', 'Se ha producido un error')
                    ->with('typealert', 'danger')
                    ->withInput();
        } else {
            $user = User::findOrFail(Auth::id());
            if (Hash::check($request->input('apassword'), $user->password)) {
                $user->password = Hash::make($request->input('password'));
                if ($user->save()) {
                    return back()
                    ->with('message', 'Su contraseña se actualizo con éxito')
                    ->with('typealert', 'success');
                }
            } else {
                return back()
                    ->with('message', 'Su contraseña actual es erronea')
                    ->with('typealert', 'danger');
            }
        }   
        
    }

    public function postAccountInfo(Request $request) {
        $rules = [
            'name' => 'required',
            'lastname' => 'required',
            'phone' => 'required|min:9',
            'year' => 'required',
            'day' => 'required'
        ];

        $messages = [
            'name.required' => 'Su nombre es requerido',
            'lastname.required' => 'Sus apellidos son requeridos',
            'phone.required' => 'Su número de teléfono es requerido',
            'phone.min' => 'Su número de teléfono debe tener al menos 9 digitos',
            'year.required' => 'Su año de nacimiento es requerido',
            'day.required' => 'Su día de nacimiento es requerido',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) 
        {
            return back()
                    ->withErrors($validator)
                    ->with('message', 'Se ha producido un error')
                    ->with('typealert', 'danger')
                    ->withInput();
        } else {
            //$date = $request->input('year') . '-' . $request->input('month') . '-' . $request->input('day');
            $user = User::findOrFail(Auth::id());
            $user->name = e($request->input('name'));
            $user->lastname = e($request->input('lastname'));
            $user->phone = e($request->input('phone'));
            // $user->birthday = date("Y-m-d", strtotime($date));
            //  return date("Y-m-d", strtotime($date));
            $user->birthday = $request->input('year') . '-' . $request->input('month') . '-' . $request->input('day');
            $user->gender = $request->input('gender');
            if ($user->save()) {
                return back()
                ->with('message', 'Su información se actualizo con exito')
                ->with('typealert', 'success');
            }
        } 
    }
    
}
