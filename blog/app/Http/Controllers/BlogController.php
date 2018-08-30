<?php

namespace App\Http\Controllers;
use App\Models\blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function add(){
        return view('blog');
    }

    public function doadd(Request $req){
        $data = $req->all();
        $blog = Blog::create($data);
    }
}
