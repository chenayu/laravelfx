<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Hash;
use Socialite;

class LoginController extends Controller
{
    // 生成接口参数并跳转到QQ网站
    public function qq() {
        return Socialite::with('qq')->redirect();
    }

    // 当用户登录成功之后会回到这里，在这里我们就可以获取QQ登录的信息
    public function qqlogin() {
        // 获取QQ的信息
        $qq = Socialite::driver('qq')->user();
        // 根据openid查找数据库是否有对应的账号
        $user = User::where('openid',$qq->id)->first();
        // 如果有关联的账号直接登录
        if($user)
        {
            // 把用户常用的数据保存到SESSION（标记一下、打卡）
            session([
                'id' => $user->id,
                'mobile' => $user->mobile,
                'smface' => $user->smface,
                'bgface' => $user->bgface,
            ]);
            // 跳转
            return redirect()->route('blog.list');
        }
        else
        {
            // 把openid保存到SESSION中，后续通过判断SESSION中是否有openid来判断是否是使用的QQ登录
            session('openid',$qq->id);

            return redirect()->route('login');
        }
    }

    //显示表单
    public function login() {
        return view('login');
    }
    // 处理表单的方法
    public function dologin(LoginRequest $req) {


        // 从SESSION中取出验证码（一个验证码只能用一次)
        $captcha = $req->session()->pull('captcha');

        // 判断验证码和SESSION中是否一致
        if($req->captcha == '' || $req->captcha != $captcha)
        {
            return back()->withErrors(['captcha'=>'验证码不正确！']);
        }


        // 先通过手机号到数据库中查询用户的信息
        // select * from users where mobile=$req->mobile limit 1
        $user = User::where('mobile',$req->mobile)->first();

        // 判断是否有这个账号
        if($user)
        {
            // 判断密码
            // 表单中的密码：$req->password   （原始）
            // 数据库的密码：$user->password （哈希之后 ）
            // laravel中 Hash::check(原始，哈希之后)判断是否一致
            if(  !Hash::check(  $req->password   ,   $user->password   )  )
            {
                // 把用户常用的数据保存到SESSION（标记一下、打卡）
                session([
                    'id' => $user->id,
                    'mobile' => $user->mobile,
                    'smface' => $user->smface,
                    'bgface' => $user->bgface,
                ]);
                // 取出QQID并从SESSION中删除
                $openid = $req->session()->pull('openid');
                if($openid)
                {
                    // 把openid设置到模型上
                    $user->openid = $openid;
                    // 更新数据库
                    $user->save();
                }

                // 跳转
                return redirect()->route('blog.list');
            }
            else
            {
                // 密码错误
                return back()->withErrors('密码错误！');
            }
        }
        else
        {
            // 账号不存在
            // 返回上一个页面，并把错误信保存到SESSION中，返回，在下一个页面中就可以使用 $errors 获取这个错误信息了w
            return back()->withErrors('手机号不存在！');
        }
    }

    public function logout(Request $req) {

        //  清空SESSION
        $req->session()->flush();


        return redirect()->route('blog.list');
    }
}
