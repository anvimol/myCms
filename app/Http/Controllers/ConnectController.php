<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, Hash, Auth, Mail, Str;
use App\Mail\UserSendRecover;
use App\Mail\UserSendNewPassword;
use App\User;

class ConnectController extends Controller
{
    public function __construct() 
    {
        $this->middleware('guest')->except(['getLogout']);
    }
    
    public function getLogin()
    {
        return view('connect.login');
    }

    public function postLogin(Request $request) 
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:8'
        ];

        $messages = [
            'email.required' => 'El correo electrónico es requerido.',
            'email.email' => 'El formato del correo no es valido.',
            'password.required' => 'Por favor escriba una contraseña.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.'
        ]; 

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()
                    ->withErrors($validator)
                    ->with('message', 'Se ha producido un error')
                    ->with('typealert', 'danger');
        } 
        else {
            if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')], true)) {
                if (Auth::user()->status == "100") {
                    return redirect('/logout');
                }
                else {
                    return redirect('/');
                }
            } else {
                return back()
                    ->with('message', 'Correo o contraseña incorrectos')
                    ->with('typealert', 'danger');
            }
            
        }
    }

    public function getRegister() 
    {
        return view('connect.register');
    }

    public function postRegister(Request $request) 
    {
        $rules = [
            'name' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'cpassword' => 'required|min:8|same:password'
        ];

        $messages = [
            'name.required' => 'Su nombre es requerido.',
            'lastname.required' => 'Los apellidos son requeridos.',
            'email.required' => 'El correo electrónico es requerido.',
            'email.email' => 'El formato del correo no es valido.',
            'email.unique' => 'Este correo ya esta registrado.',
            'password.required' => 'Por favor escriba una contraseña.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'cpassword.required' => 'Es necesario confirmar la contraseña.',
            'cpassword.min' => 'La confirmación de la contraseña debe tener al menos 8 caracteres.',
            'cpassword.same' => 'Las contraseñas no coinciden.'
        ]; 

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) 
        {
            return back()
                    ->withErrors($validator)
                    ->with('message', 'Se ha producido un error')
                    ->with('typealert', 'danger');
        } 
        else {
            $user = new User();
            $user->name = e($request->input('name')); // e es para no guardar scripts dañinos en la BD
            $user->lastname = e($request->input('lastname')); 
            $user->email = e($request->input('email')); 
            $user->password = Hash::make($request->input('password')); //Hash Encriptar

            if ($user->save()) {
                return redirect('/login')
                    ->with('message', 'Su usuario ha sido creado con exito')
                    ->with('typealert', 'success');
            }
        }
        
    }

    public function getLogout()
    {
        $status = Auth::user()->status;
        Auth::logout();
        if ($status == "100") {
            return redirect('/login')
                ->with('message', 'Su usuario esta suspendido')
                ->with('typealert', 'danger');
        }
        else {
            return redirect('/');
        }
    }

    public function getRecover()
    {
        return view('connect.recover');
    }

    public function postRecover(Request $request)
    {
        $rules = [
            'email' => 'required|email'
        ];

        $messages = [
            'email.required' => 'El correo electrónico es requerido.',
            'email.email' => 'El formato del correo no es valido.'
        ]; 

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()
                    ->withErrors($validator)
                    ->with('message', 'Se ha producido un error')
                    ->with('typealert', 'danger');
        } 
        else {
            $user = User::where('email', $request->input('email'))->count();
            if ($user == 1) {
                $user = User::where('email', $request->input('email'))->first();
                $code = rand(100000, 999999);
                $data = ['name' => $user->name, 'email' => $user->email, 'code' => $code];
                $u = User::find($user->id);
                $u->password_code = $code;
                if($u->save()) {
                    Mail::to($user->email)->send(new UserSendRecover($data));
                    return redirect('/reset?email=' . $user->email)
                        ->with('message', 'Ingrese el código que le hemos enviado a su correo')
                        ->with('typealert', 'success');
                    //return view('emails.user_pass_recover', $data);
                }
                
            } else {
                return back()
                    ->with('message', 'Este correo electrónico no existe')
                    ->with('typealert', 'danger');
            }
        }
    }

    public function getReset(Request $request) 
    {
        $data = ['email' => $request->get('email')];
        return view('connect.reset', $data);
    }

    public function postReset(Request $request) 
    {
        $rules = [
            'email' => 'required|email',
            'code' => 'required'
        ];

        $messages = [
            'email.required' => 'El correo electrónico es requerido.',
            'email.email' => 'El formato del correo no es valido.',
            'code.required' => 'Debe ingresar el código de recuperación que se ha enviado a su correo.'
        ]; 

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()
                    ->withErrors($validator)
                    ->with('message', 'Se ha producido un error')
                    ->with('typealert', 'danger');
        } 
        else {
            $user = User::where('email', $request->input('email'))
                ->where('password_code', $request->input('code'))    
                ->count();
            if ($user == 1) {
                $user = User::where('email', $request->input('email'))
                    ->where('password_code', $request->input('code'))    
                    ->first();
                $new_password = Str::random(8);
                $user->password = Hash::make($new_password);
                $user->password_code = null;
                if ($user->save()) {
                    $data = ['name' => $user->name, 'password' => $new_password];
                    Mail::to($user->email)->send(new UserSendNewPassword($data));
                    return redirect('/login')
                        ->with('message', 'La contraseña fue restablecida con éxito, le hemos enviado un correo electronico con su nueva contraseña para que pueda iniciar sesión')
                        ->with('typealert', 'success');
                }
            }
            else {
                return back()
                    ->with('message', 'El correo electrónico o el código de recuperación son erroneos')
                    ->with('typealert', 'danger');
            }
        }
    }
    
}
