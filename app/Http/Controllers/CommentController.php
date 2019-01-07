<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;

class CommentController extends Controller
{
    public function doadd(CommentRequest $req) {

        $comment = new Comment;
        // 填充数据（前提：模型中要有白名单）

        $comment->fill( $req->all() );

        $comment->user_id = session('id');

        // 插入数据库
        $comment->save();

        // 返回新插入的数据的 JSON 数据
        return $comment;

    }

    public function index($blog_id)
    {
        return Comment::where('blog_id',$blog_id)
                ->orderBy('id','desc')
                ->with('user')
                ->paginate(5);

    }
}
