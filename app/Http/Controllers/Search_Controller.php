<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;


class Search_Controller extends Controller
{
    public function __invoke(Request $req)
    {
        $cat = DB::table('Categoria')->get();
        $talla = config('tallas');
        $data = [
            'res'=>[],
            'talla'=>$talla,
            'cat' => $cat
        ];
        if($req->isMethod('get')){
            $color =  $req->input('co');
            $categoria =  $req->input('c');
            $query =  $req->input('q');
            $talla = $req->input('t');
            if(isset($color))
            {
                $result = Producto::where('color', 'like', '%'.$color.'%',)->get()->reverse();
                $data['res'] = $result;
                return view('search/Search',$data);
                
            }
            if(isset($categoria))
            {
                $result = Producto::where('categoria', 'like', '%'.$categoria.'%',)->get()->reverse();
                $data['res'] = $result;
                return view('search/Search',$data);
                
            }
            if(isset($query))
            {
                $result = Producto::where('nombre', 'like', '%'.$query.'%',)->get()->reverse();
                $data['res'] = $result;
                return view('search/Search',$data);
                
            }
            if(isset($talla))
            {
                $result = Producto::where('talla', '=', $talla,)->get()->reverse();
                $data['res'] = $result;
                return view('search/Search',$data);
                
            }

            

            return view('search/Search',$data);
        }





        return view('search/Search',$data);
        
    }

}
