<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use Symfony\Component\Console\Input\Input;
use Illuminate\Support\Facades\DB;

class Admin_Controller extends Controller
{
    public function __invoke(Request $req)
    {
        if(!$this->checkLogin($req)){
            return redirect('/profile');
        }
        $prod = DB::table('Producto')->get();
        $cat = DB::table('Categoria')->get();

        return view('admin/admin',['prod'=>$prod,'cat'=>$cat]);
    }

    public function product(Request $req)
    {
        if(!$this->checkLogin($req)){
            return redirect('/profile');
        }
        if($req->isMethod('POST'))
        {
            $prod= new Producto;
            $prod->nombre = $req->input('nombre');
            $prod->descripcion = $req->input('descripcion');
            $prod->precio = $req->input('precio');
            $prod->inventario = $req->input('inventario');
            $prod->categoria = $req->input('categoria');
            $prod->color = $req->input('color');
            $prod->talla = $req->input('talla');
            $prod->imagen1 = $req->input('imagen1');
            $prod->imagen2 = $req->input('imagen2');
            $prod->imagen3 = $req->input('imagen3');
            $prod->imagen4 = $req->input('imagen4');
            $prod->save();
            //inicia sesion
            return redirect('/admin');
            
        }else{
            $data = [
                'cat'=>Categoria::all(),
            ];
            return view('admin/product',$data);
        }
            
        
        
    }

    public function categoria(Request $req)
    {
        if(!$this->checkLogin($req)){
            return redirect('/profile');
        }

        if($req->isMethod('POST'))
        {
            if(!Categoria::where('categoria_id','=',$req->input('categoria'))->exists()){
                $cat= new Categoria;
                $cat->categoria_id= $req->input('categoria');

                $cat->save();
                //inicia sesion
                return redirect('/admin');
            }else{
                $data = [
                    'exists' => true,
                    'name' => $req->input('categoria'),
    
                ];
                return view('admin/categoria',$data);
            }
            
        }else{
            $data = [
                'exists' => false,
                'name' => '',

            ];
            return view('admin/categoria',$data);
        }
    }
    
    public function pedidos(Request $req)
    {
        if(!$this->checkLogin($req)){
            return redirect('/profile');
        }
        $ped = DB::table('Pedidos')->get();
        

        return view('admin/pedidos',['ped'=>$ped]);

    }

    public function checkLogin(Request $req){
        if($req->session()->has('login') && $req->session()->get('login'))
        {
            if($req->session()->has('user') && $req->session()->get('user')['admin']){
                return true;
            }
            else{
                return false;
            }
            
        }
        return false;
    }

    public function completar_pedido(Request $req)
    {

    }
}
