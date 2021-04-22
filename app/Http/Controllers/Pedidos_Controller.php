<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\Producto;
use DateTime;
use Illuminate\Support\Facades\DB;


class Pedidos_Controller extends Controller
{
    public function __invoke(Request $req)
    {
        if($req->isMethod('POST')){
            $data = [
                'nombre' => $req->input('name'),
                'apellidos' => $req->input('last_name'),
                'pais' => $req->input('pais'),
                'departamento' => $req->input('departamento'),
                'departamento2' => $req->input('departamento2'),
                'distrito' => $req->input('distrito'),
                'direccion' => $req->input('direccion'),
                'direccion2' => $req->input('direccion2'),
                'username' => $req->input('username'),
                'telefono' => $req->input('telefono')
            ];

            $req->session()->put('pedido',$data);

            return view('pedidos/pagar',$data);
        }

        return redirect('/cart');
    }
    
    public function Pagar(Request $req)
    {
        if($req->isMethod('POST'))
        {
            $data = $req->session()->get('pedido');
            $ped = new Pedido;
            $ped->nombre = $data['nombre'];
            $ped->apellido = $data['apellidos'];
            $ped->username = $data['username'];
            $ped->pais = $data['pais'];
            $ped->departamento = $data['departamento'];
            $ped->departamento2 = $data['departamento2'] != null ? $data['departamento2'] : ' ' ;
            $ped->distrito = $data['distrito'];
            $ped->direccion = $data['direccion'];
            $ped->direccion2 = $data['direccion2'] != null ? $data['direccion2'] : ' ' ;
            $ped->telefono = $data['telefono'];
            $ped->productos = json_encode($req->session()->get('cart'));
            $ped->subtotal = $req->input('total');
            $ped->delivery = 13.00; 
            $ped->codigo_pago = $data['username'].'/'.$req->input('codigo_pago');
            $ped->fecha = date_format(new DateTime(),'Y-m-d H:i:s');
            
            $ped->save();

            DB::table('ProductoReserva')->where('username',$data['username'])->delete();


            $req->session()->forget('pedido');
            $req->session()->forget('cart');
            $req->session()->forget('carCant');

            return view('pedidos/thankyou',['codigo'=>$ped->codigo_pago,'id'=>$ped->id]);
        }
        return('/cart');


        
    } 
    public function RestarStock($cart)
    {
        foreach($cart as $c)
        {
            $p = Producto::find($c['id']);
            $p->inventario -= $c['cant'];
            $p->save();
        }

    }
}
