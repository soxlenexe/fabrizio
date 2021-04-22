<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class Producto_Controller extends Controller
{
    public function __invoke(Request $req, $id)
    {
        $data = ['prod'=>Producto::find($id)];

        return view('producto/Single',$data);

    }

    public function tallas()
    {
        return view('producto/tallas');
    }

    public function remove(Request $req)
    {
        DB::table('Producto')->where('id',$req->input('id'))->delete();

    }
    public function editP(Request $req){

        $u = ['completado'=>$req->input('status')=='true'?true:false,'paqueteria'=>$req->input('paqueteria'),'id_rastreo'=>$req->input('id_rastreo')];
        DB::table('Pedidos')->where('id',$req->input('id'))->update($u);
      
     
        return redirect('/admin/manage/pedidos');
    }   

}
