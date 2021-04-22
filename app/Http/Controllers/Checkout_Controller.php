<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Checkout_Controller extends Controller
{
    public function __invoke(Request $req)
    {
        if($req->session()->has('login') && $req->session()->get('login'))
        {
            return view('checkout/checkout');
        }
        return redirect('/signin');
        
    }
    

}
