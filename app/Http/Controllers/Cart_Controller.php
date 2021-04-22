<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\ProductoReserva;
use DateTime;
use Illuminate\Support\Facades\DB;


class Cart_Controller extends Controller
{
    public function __invoke(Request $req)
    {
        if(!$this->checkLogin($req)){
            return redirect('/signin');
        }
        $cart = [];
        if(!$req->session()->has('cart')){
            $cart = $this->checkReserva($req,$cart);
            $req->session()->put('cart',$cart);
        }else if($req->session()->has('cart') && $req->session()->get('cart') == [])
        {
            $cart = $this->checkReserva($req,$cart);
            $req->session()->put('cart',$cart);
        }
       
        return view('cart/cart');
    }

    public function add(Request $req, $id)
    {
        if(!$this->checkLogin($req)){
            return redirect('/signin');
        }
        if(!Producto::find($id)->exists())
        {
            return redirect('/cart');
        }

        if(!$this->checkAddReserva($id))
        {
            return redirect('/cart');
        }

        $p = Producto::find($id);

        $cart=[];
        $count=0;
        if(!$req->session()->has('cart')){
            $this->AddReserva($req->session()->get('user'),$id);
            $cart[$id] = [
                'id' => $id,
                'nombre' => $p['nombre'],
                'cat' => $p['categoria'],
                'precio'=> $p['precio'],
                'talla' => $p['talla'],
                'cant' => 1,
                'img' => $p['imagen1']
            ];

        }else{

            $cart = $req->session()->get('cart');

            if(!isset($cart[$id])){
                $cart[$id] = [
                    'id' => $id,
                    'nombre' => $p['nombre'],
                    'cat' => $p['categoria'],
                    'precio'=> $p['precio'],
                    'talla' => $p['talla'],
                    'cant' => 1,
                    'img' => $p['imagen1']
                ];
            }else{
                $cart[$id]['cant'] += 1;
            }
            $this->AddReserva($req->session()->get('user'),$id);
        }
        foreach($cart as $c){
            $count += $c['cant'];
        }
        $req->session()->put('cartCant',$count);
        $req->session()->put('cart',$cart);
        return redirect('/cart');
    }

    public function add_ajax(Request $req, $id)
    {
        $cart=[];
        $count=0;
        if(!$this->checkLogin($req)){
            return 404;
        }
        if($req->session()->has('cart')){
            $cart = $req->session()->get('cart');
            if(!isset($cart[$id])){
                return 400;
            }else{
                $this->AddReserva($req->session()->get('user'),$id);
                $cart[$id]['cant'] += 1;
            }
        }
        foreach($cart as $c){
            $count += $c['cant'];
        }
        $req->session()->put('cartCant',$count);
        $req->session()->put('cart',$cart);
        return 200;
    }

    public function remove(Request $req){
        $req->session()->forget('cart');
        $req->session()->forget('cartCant');
        
        return redirect('/');

    }

    public function remove_one_ajax(Request $req, $id){
        $cart = $req->session()->get('cart');
        $count = 0;
        if($req->session()->has('cart')){
            if(isset($cart[$id]))
            {
                $this->Remove_one_Reserva($req,$id);
                if($cart[$id]['cant']==0){
                    unset($cart[$id]);
                }elseif($cart[$id]['cant']>0){
                    $cart[$id]['cant'] = $cart[$id]['cant'] - 1;
                    if($cart[$id]['cant']==0){
                        unset($cart[$id]);
                    }
                }
            }else{
                return 400;
            }
        }
        foreach($cart as $c){
            $count += $c['cant'];
        }
        $req->session()->put('cart',$cart);
        $req->session()->put('cartCant',$count);
        return 200;

    }

    public function remove_ajax(Request $req, $id){
        $cart = $req->session()->get('cart');
        $count = 0;
        if($req->session()->has('cart')){
            if(isset($cart[$id]))
            {
                $this->RemoveReserva($req,$id);
                unset($cart[$id]);

            }else{
                return 400;
            }
        }
        foreach($cart as $c){
            $count += $c['cant'];
        }
        $req->session()->put('cart',$cart);
        $req->session()->put('cartCant',$count);
        return 200;

    }


    public function checkReserva(Request $req,$cart)
    {
        if($req->session()->has('login') && $req->session()->get('login'))
        {
            if($req->session()->has('user'))
            {

                if(ProductoReserva::where('username','=', $req->session()->get('user')['username'])->exists())
                {

                    $prods = ProductoReserva::where('username','=', $req->session()->get('user')['username'])->get();
                    foreach($prods as $p)
                    {
                        $interval = DateTime::createFromFormat('Y-m-d H:i:s',$p['fecha'])->diff(DateTime::createFromFormat('Y-m-d H:i:s',date_format(new DateTime(),'Y-m-d H:i:s')));
                        if($interval->h > 11){
                            $prod = Producto::find($p['id']);
                            $prod->inventario += $p['cantidad'];
                            $prod->save();
                            ProductoReserva::where('username','=',$req->session()->get('user')['username'])->where('producto_id','=',$p['id'])->delete();
                        }else{
                            $pv = Producto::find($p['producto_id']);
                            $cart[$p['producto_id']] =[
                                'id' => $pv->id,
                                'nombre' => $pv['nombre'],
                                'cat' => $pv['categoria'],
                                'precio'=> $pv['precio'],
                                'talla' => $pv['talla'],
                                'cant' => $p['cantidad'],
                                'img' => $pv['imagen1']
                            ]; 
                        }
                        
                    }



                }

            }
        }
        return $cart;
    }

    public function CheckAddReserva($id){
        if(Producto::find($id)->inventario<=0){
            $pr = ProductoReserva::where('producto_id','=',$id)->get();
            $prodd = Producto::find($id);
            
            foreach($pr as $r){
                
                if(DateTime::createFromFormat('Y-m-d H:i:s',$r['fecha'])->diff(DateTime::createFromFormat('Y-m-d H:i:s',date_format(new DateTime(),'Y-m-d H:i:s')))->h>11){
                    $prodd->inventario += $r['cantidad'];
                    
                    ProductoReserva::where('username','=',$r['username'])->where('producto_id','=',$r['id'])->delete();
                }
            }
            $prodd->save();
            if($prodd->inventario<=0){
                return false;
            }
        }
        return true;
    }

    public function AddReserva($user,$id){
        $prod=Producto::find($id);
        $prod->inventario -= 1;
        $prod->save();

        if(ProductoReserva::Where('username','=',$user['username'])->where('producto_id','=',$id)->exists()){
            $p = ProductoReserva::Where('username','=',$user['username'])->where('producto_id','=',$id)->get()->first();
            $p->cantidad = $p->cantidad + 1;
            $p->save();
        }else{
            $pr = new ProductoReserva;
            $pr->cantidad = 1;
            $pr->producto_id = $id;
            $pr->nombre = $prod->nombre;
            $pr->precio = $prod->precio;
            $pr->username = $user['username'];
            $pr->categoria = $prod->categoria;
            $pr->talla = $prod->talla;
            $pr->color = $prod->color;
            $pr->save();
        }
    }
    public function checkLogin(Request $req){
        if($req->session()->has('login') && $req->session()->get('login'))
        {
            return true;
        }
        return false;
    } 

    public function Remove_one_Reserva($user,$id){

        if(DB::table('ProductoReserva')->where('producto_id', $id,'username',$user['username'])->exists()){
            $prod=Producto::find($id);
            $prod->inventario += 1;
            $prod->save();
            $p = DB::table('ProductoReserva')->where('producto_id', $id,'username',$user['username'])->get()->first();
            $p->cantidad -= 1;
            
            if($p->cantidad <= 0){
                DB::table('ProductoReserva')
                ->where('producto_id', $id,'username',$user['username'])
                ->delete();
            }
            else
            {
                DB::table('ProductoReserva')->where('producto_id', $id,'username',$user['username'])->update(['cantidad'=>$p->cantidad]);
            }
            
        }
    }

    public function RemoveReserva($user,$id){
        
        
        if(DB::table('ProductoReserva')->where('producto_id', $id,'username',$user['username'])->exists())
        {
            $prodr = DB::table('ProductoReserva')->where('producto_id', $id,'username',$user['username'])->get()->first();
            if($prodr->cantidad>0)
            {
                $prod = Producto::find($id);
                $newIn = $prodr->cantidad + $prod->inventario;
                DB::table('Producto')->where('id',$id)->update(['inventario'=>$newIn]);
                DB::table('ProductoReserva')
                ->where('producto_id', $id,'username',$user['username'])
                ->delete();

            }
            
        }

    }
}
