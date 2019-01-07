<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    public $timestamps = false;

    // 判断我和对方的关系
    // 参数：对方的ID
    // 返回值：no  , gz   ,fs   ,hy

    public static function gx($userId) {
        // 取出我的ID
        $myid = session('id');
        // 未登录
        if(!$myid)
            return 'no';
            
        if($myid==$userId)
            return 'me';
        // 是否好友
        $f = Friend::where('user_id',$myid)
                ->where('friend_id',$userId)
                ->count();
        if($f > 0)
        {
            return 'hy';
        }
        else
        {
            // 我是否关注了对方
            $f = Follow::where('user_id',$myid)
                    ->where('follow_id',$userId)
                    ->count();
            if($f>0)
            {
                return 'gz';
            }
            else
            {
                // 他是否关注了我
                $f = Follow::where('user_id',$userId)
                    ->where('follow_id',$myid)
                    ->count();
                return $f==0?'no':'fs';
            }
        }
    }

}
