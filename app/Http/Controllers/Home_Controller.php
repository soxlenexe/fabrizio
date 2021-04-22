<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Home_Controller extends Controller
{
    public function __invoke()
    {

        return view('home/Home');
    }
    
    public function About()
    {
        return view('home/about');
    } 

    public function pages($id)
    {
        if($id=='terminos'){
            return view('home/terminos');
        }
        if($id=='privacy'){
            return view('home/priv');
        }
        return 404;

    }
}
