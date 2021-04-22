<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class Shop_Controller extends Controller
{
    public function __invoke()
    {
        $prod = Producto::all();
        $cat = DB::table('Categoria')->get();
        $talla = config('tallas');
        $data = [
            'prod' => $prod,
            'cat' => $cat,
            'talla' => $talla
        ];

        return view('shop/Shop',$data);
    }
    

}
