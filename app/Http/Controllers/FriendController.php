<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Follow;
use App\Models\Friend;
use DB;

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
}
