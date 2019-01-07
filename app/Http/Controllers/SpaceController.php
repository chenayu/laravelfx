<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Friend;
use App\Models\Follow;
use App\Models\User;
use App\Models\Blog;
use DB;


class SpaceController extends Controller
{


    // 个人空间
    public function index($id=0)
    {
        /**** 如果进入的是自己的空间，那么就从sessoin中取出我的id */
        if($id==0)
        {
            $myid = session('id');
            if($myid)
                $id=$myid;
            else
                return back();
        }




        // 取出个人信息
        $user = User::find($id);

        // 判断两人的关系
        $gx = Friend::gx($id);


        // 取出我好友的ID
        $friends = Friend::select('friend_id')
                    ->where('user_id',$id)
                    ->get();

        // 转成数组
        $frid = [];
        foreach($friends as $v)
        {
            $frid[] = $v->friend_id;
        }

        // 取出关注的ID
        $gz = Follow::select('follow_id')
                    ->where('user_id',$id)
                    ->get();

        // 转成数组
        $gzarr = [];
        foreach($gz as $v)
        {
            $gzarr[] = $v->follow_id;
        }

        // 即有好友、又有关注
        if($frid && $gzarr)
        {
            $blogs = Blog::where(function($q) use($frid) {
                        $q->where('accessable','!=','private')
                        ->whereIn('user_id',$frid);
                    })
                    ->orWhere(function($q) use($gzarr){
                        $q->where('accessable','public')
                            ->whereIn('user_id',$gzarr);
                    })
                    ->with('user')
                    ->orderBy('id','DESC')
                    ->paginate(10);
        }
        // 只有好友
        else if($frid)
        {
            $blogs = Blog::where(function($q) use($frid) {
                $q->where('accessable','!=','private')
                ->whereIn('user_id',$frid);
            })
            ->with('user')
            ->orderBy('id','DESC')
            ->paginate(10);
        }
        // 只有关注
        else if($gzarr)
        {
            $blogs = Blog::where(function($q) use($gzarr){
                $q->where('accessable','public')
                    ->whereIn('user_id',$gzarr);
            })
            ->with('user')
            ->orderBy('id','DESC')
            ->paginate(10);
        }
        // 都没有
        else
        {
            $blogs = [];
        }
       
        
        $gz = DB::select('select * from test_follows a left join test_users b on a.follow_id = b.id');
       
        return view('space.index',[
            'gx'=>$gx,
            'user'=>$user,
            'blogs'=>$blogs,
            'gz'=>$gz
        ]);
    }
}
