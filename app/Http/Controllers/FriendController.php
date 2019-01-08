<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Follow;
use App\Models\Friend;
use App\Models\User;
use DB;
use App\Models\Blog;

class FriendController extends Controller
{
    // 返回值：关注之后的关系

    public function gz($user_id) {

        // 先取出两人的关系
        $gx = Friend::gx($user_id);

        if($gx == 'no')
        {

            $f = new Follow;
            // 向模型设置值，方法一、fill （前提：模型中要设置白名单）
            // 向模型设置值、方法二、直接设置属性
            $f->user_id = session('id');
            $f->follow_id = $user_id;
            // 插入到表
            $f->save();

            return [
                'errno'=>0,
                'gx'=>'gz',
            ];

        }
        else
        {
            return [
                'errno'=>1,
                'errmsg'=>'已经关注了',
            ];
        }

    }

    public function qxgz($user_id) {

        // 先取出两人的关系
        $gx = Friend::gx($user_id);

        if($gx == 'gz')
        {

            Follow::where('user_id',session('id'))
                    ->where('follow_id',$user_id)
                    ->delete();

            return [
                'errno'=>0,
                'gx'=>'gz',
            ];

        }
        else
        {
            return [
                'errno'=>1,
                'errmsg'=>'不能这样做',
            ];
        }

    }

    // 添加好友
    public function friend($user_id) {

        // 先取出两人的关系
        $gx = Friend::gx($user_id);

        if($gx == 'fs')
        {

            $myid = session('id');


            DB::transaction(function() use($user_id,$myid){

                // 先删除粉丝
                Follow::where('user_id',$user_id)
                    ->where('follow_id',$myid)
                    ->delete();

                // 向好友表中插入两条记录
                Friend::insert([
                    ['user_id'=>$user_id,'friend_id'=>$myid],
                    ['user_id'=>$myid,'friend_id'=>$user_id],
                ]);


            });

            return [
                'errno'=>0
            ];

        }
        else
        {
            return [
                'errno'=>1,
                'errmsg'=>'不能这样做',
            ];
        }

    }

    // 删除好友
    public function delfriend($user_id) {

        // 先取出两人的关系
        $gx = Friend::gx($user_id);
        if($gx == 'hy')
        {
            $myid = session('id');
            // use是将 外部变量引入到匿名函数中（否则用不了,PHP这门语言的特点：函数内不能直接使用函数外的变量）
            DB::transaction(function() use($user_id,$myid){

                // delete from xxx where (user_id=1 and friend_id=2) OR (friend_id=1 and user_id=2)
                // 从好友表中删除两条记录
                Friend::where(function($q) use($user_id,$myid) {

                    $q->where('user_id',$myid)
                        ->where('friend_id',$user_id);

                })
                ->orWhere(function($q) use($user_id,$myid) {

                    $q->where('user_id',$user_id)
                        ->where('friend_id',$myid);

                })
                ->delete();
                // 添加关注关系
                Follow::insert([
                    'user_id'=>$user_id,
                    'follow_id'=>$myid,
                ]);
            });
            return [
                'errno'=>0
            ];
        }
        else
        {
            return [
                'errno'=>1,
                'errmsg'=>'不能这样做',
            ];
        }

    }

    //好友空间
    public function friendSpace($user_id){
        //取出两个人的关系
        $gx = Friend::gx($user_id);
        //获取用户信息
        $user = User::find($user_id);

        //获取所有关注
        $sygx = new FriendController();
        $sygz = $sygx->friendGz($user_id);
        //获取所有粉丝
        $syfs =  $sygx->friendFs($user_id);
        
        //判断是不是好友,获取是否只有好友可见的日志
        $c = $gx=='hy' ? ['public','protected'] : 'public';
        $data = Blog::where('user_id',$user_id)
        ->whereIn('accessable',[$c])
        ->select('blogs.*','users.mobile')
        ->leftJoin('users','blogs.user_id','=','users.id')->get();

        return view('space.space',['gx'=>$gx,'user'=>$user,'data'=>$data,'gzs'=>$sygz,'fss'=>$syfs]);
    }

    //获取关注的好友
    public function friendGz($user_id){
        $wgz = session('id');
        // select * from test_follows a left join test_users b on a.follow_id = b.id where a.user_id = 1
        return Follow::where('user_id',$user_id)
        ->select('follows.user_id','users.*')
        ->leftJoin('users','follows.follow_id','=','users.id')->get();
          
    }

    //获取粉丝
    public function friendFs($user_id){
        //select * from test_follows a left join test_users b on a.user_id = b.id where a.follow_id = 1
        return Follow::where('follow_id',$user_id)
        ->select('follows.user_id','users.*')
        ->leftJoin('users','follows.user_id','=','users.id')->get();
        
    }
}
