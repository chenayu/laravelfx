<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegistRequest;
use App\Models\User;
use Hash;
use Illuminate\Support\Facades\Cache;
use Flc\Dysms\Client;
use Flc\Dysms\Request\SendSms;
use Illuminate\Support\Facades\Redis;

class RegistController extends Controller
{
    // 发送短信
    public function sendmobilecode(Request $req) {


        // 生成6位随机数
        $code = rand(100000,999999);
        // 缓存时的名字
        $name = 'code-'.$req->mobile;  // code-1367777888
        // 把随机数缓存起来（1分钟）
        Cache::put($name, $code, 1);

        // 向消息队列中发消息
        Redis::lpush('sms_list',$req->mobile.'-'.$code);

        /*

        // 发短信
        $config = [
            'accessKeyId'    => 'LTAIfGI6uvRzJ1gJ',
            'accessKeySecret' => '8sJjVNXak3PqlloVr2LoReaqKpGMm4',
        ];
        $client  = new Client($config);
        $sendSms = new SendSms;
        $sendSms->setPhoneNumbers($req->mobile);
        $sendSms->setSignName('全栈1SNS项目');
        $sendSms->setTemplateCode('SMS_128890229');
        $sendSms->setTemplateParam(['code' => $code]);
        // 发送
        $client->execute($sendSms);
        */

    }

    // 显示表单
    public function regist(Request $req) {
        return view('regist');
    }
    // 使用RegistRequest类进行表验证：
    // 1. 如果失败返回上一个页面
    // 2. 如果成功才允许继续执行方法中的代码
    public function doregist(RegistRequest $req) {

        // 拼出缓存的名字
        $name = 'code-'.$req->mobile;
        // 再根据名字取出验证码
        $code = Cache::get($name);
        if(!$code || $code != $req->mobile_code)
        {
            return back()->withErrors(['mobile_code'=>'验证码错误！']);
        }

        // 密码加密
        $password = Hash::make($req->password);
        // 创建一个user对象
        $user = new User;
        // 把表单中的手机号设置到 模型
        $user->mobile = $req->mobile;
        // 把加密 之后的密码设置到模型
        $user->password = $password;

        // 上传图片
        if($req->has('face') && $req->face->isValid())
        {
            // 保存图片到 face/当前日期目录下
            $date = date('Ymd');
            // 上传并返回 上传之后的路径
            $face = $req->face->store('face/'.$date);
            // 把这个路径设置到user模型上
            $user->face = $face;
        }
        else
        {
            return back()->withErrors([
                                        'face' => '上传过程中出错，请重试！',
                                    ]);
        }

        // 保存到表中
        $user->save();

        // 跳转到 登录页
        return redirect()->route('login');
    }
}
