<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Contracts\Session\Session;
use App\Models\Pedido;


class User_Controller extends Controller
{
    public function  CreateUser(Request $req)
    {
        if (!$req->session()->has('login') && $req->session()->get('login') != 'true') {
            $validate = ['email' => false, 'username' => false];
            return view('User.Create',['validate'=>$validate]);
        }

        return redirect('/');
    
    }

    public function  OnCreateUser(Request $req)
    {
        $validate = ['email' => false, 'username' => false];

        if(!User::where('email', '=', $req->input('email'))->exists()) {
            if (!User::where('username', '=', $req->input('username'))->exists()) {
                $email = $req->input('email');
                $user = new User;
                $user->email = $email;
                $user->name = $req->input('name');
                $user->last_name = $req->input('last_name');
                $user->username = $req->input('dni');
                $user->telefono = $req->input('telefono');
                $user->password = $req->input('password');
                $user->signo = $req->input('signo');
                $user->save();
                if($user->id==1){
                    $user->admin = true;
                    $user->save();
                }
                //inicia sesion
                $req->session()->put('login','true');
                $req->session()->put('email',$req->input('email'));
                $req->session()->put('user',$user);
                //pasamos la sesion a la vista
                return redirect('/');
            }else{
                $validate['username']=true;
            }
        }else{
            $validate['email']=true;
        }

        return view('User.Create',['validate'=>$validate]);

    }


    private function ValidateUSer($user,$pass)
    {
        $validate = ['valido'=> false,'password' => false, 'email' => false];

        if(User::where('email', '=', $user)->exists())
        {
            if(User::where('password', '=', $pass)->exists())
            {
                $validate['valido'] = true;
            }else{
                $validate['password'] = true;
            }
        }else{
            $validate['email'] = true;
        }

        return $validate;
    }

    public function SignIn(Request $req)
    {
        $validate = ['valido'=> false,'password' => false, 'email' => false];
        if($req->session()->has('login') && $req->session()->get('login')==true)
        {
            return redirect('/profile');
        }elseif($req->isMethod('post')){
            $validate = $this->ValidateUSer($req->input('email'),$req->input('password'));
            if($validate['valido'])
            {
                
                $req->session()->put('login','true');
                $req->session()->put('email',$req->input('email'));
                $req->session()->put('user',User::where('email', '=', $req->input('email'))->get()->first());
                
                return redirect('/profile');
            }else{
                return view('User.SignIn',['validate'=>$validate]);
            }
        }
        return view('User.SignIn',['validate'=>$validate]);
    }

    public function Signoff(Request $req)
    {
        $req->session()->flush();
        return redirect('/');

    }

    public function Profile(Request $req)
    {
        if ($req->session()->has('login') && $req->session()->get('login') == 'true')
        {
            
            $user = User::where('email','=', $req->session()->get('email'))->first();
            if($user['admin']==true)
            {
                return redirect('/admin');
            }
            $pedidos = [];
            $listo = false;
            if(Pedido::where('username','=',$user['username'])->exists())
            {
                $pedidos = Pedido::where('username','=',$user['username'])->get()->reverse()->first();
                $listo = true;
            }
            
            
            return view('User.1profile',['user'=>$user, 'pedidos'=>$pedidos,'listo'=>$listo ]);

        }
        
        return redirect('/');

    }

    public function editUser(Request $req){
        if(!$req->session()->has('login') && !$req->session()->get('login') == 'true')
        {
            return redirect('/');
        }

        if($req->isMethod('POST')){
            $user = User::where('email','=',$req->session()->get('email'))->first();
            $user->name = $req->input('name');
            $user->last_name = $req->input('last_name');
            $user->email = $req->input('email');
            $user->telefono = $req->input('telefono');
            $user->save();
        }
        
        return redirect('/profile');
        
    }
}
