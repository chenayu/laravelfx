<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Route;
use Storage;
use Image;
use Gregwar\Captcha\CaptchaBuilder;
use Flc\Dysms\Client;
use Flc\Dysms\Request\SendSms;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Models\Friend;
use App\Models\Blog;


class TestController extends Controller
{
    public function index() {

        phpinfo();

        return ;
        
        Redis::pubsubloop(['subscribe'=>'tom'],function($p,$m){

                var_dump($p,$m);

                return ;

        });


        return $ret;


    }

    public function testsms() {

        return Cache::get('code-13671389467');


        Cache::put('name','tom',1);


        Cache::get('name');  //

        $config = [
            'accessKeyId'    => 'LTAIfGI6uvRzJ1gJ',
            'accessKeySecret' => '8sJjVNXak3PqlloVr2LoReaqKpGMm4',
        ];

        $client  = new Client($config);
        $sendSms = new SendSms;
        $sendSms->setPhoneNumbers('13671389467');
        $sendSms->setSignName('全栈1SNS项目');
        $sendSms->setTemplateCode('SMS_128890229');
        $sendSms->setTemplateParam(['code' => rand(100000, 999999)]);

        // 发送
        print_r($client->execute($sendSms));



    }



    public function testimage() {

        // 打开要处理的图片
        $image = Image::make('./uploads/blog/20180515/9UtJcEBVuJe7ol35Y5n3T8Sjs5bPaaQQ8obvzXEc.jpeg');

        $image->resize(null, 300, function($e){

            $e->aspectRatio();


        });

        // 按比例缩放


        $image->save('./ttt.jpeg');
    }


    public function testcaptcha() {


        $b = new CaptchaBuilder;
        // 生成图片并保存到内存中
        $b->build();

        // 保存到硬盘( 当前目录 ： index.php 所在的目录)
        // $b->save('./captcha.jpeg');

        // 直接输出打印（图片二进制的数据）以HTML的方式 来解析
        // 出现乱码：浏览器以HTML的方式解析图片（二进制）
        // 解决办法：告诉浏览器这是一张图片，以图片的方式来解析
        // 原始：header('Content-Type:image/jpeg');

        //return response( $b->output() )
        //        ->header('Content-Type','image/jpeg');


        // base64格式 的字符串图片
        //return $b->inline();



    }
    // 中间被执行
    public function testmiddleware() {

        echo ' <hr> middle  '. microtime() .'<hr>';

    }


    public function test(Request $req) {


        $req->validate([
            'logo'=>'required|image|max:2048'
        ],[
            'logo.required'=>'必须上传图片',
            'logo.image'=>'只能上传jpeg, png, bmp, gif, or svg格式的图片',
            'logo.max'=>'图片最大不能超过2M',
        ]);

        if($req->hasFile('logo')&&$req->logo->isValid())
        {
            // 获取当前日期
            $date = date('Ymd');
            // 移动图片到当前日期目录下，使用配置文件中images配置的目录
            $newImage = $req->logo->store($date,'uploads');
            // 打印上传之后文件名
            dd($newImage);
        }

        // 得到路由的对象
        // dd( Route::current() ) .'<br>';

        // 获取当前路由的名字
        echo Route::currentRouteName() .'<br>';

        // 当前路由对应的控制器中的方法
        echo Route::currentRouteAction() .'<br>';

    }


    // 显示上传表单
    public function upload() {

        return view('test.upload');
    }

    // 处理上传表单
    public function doupload(Request $req) {

        /*
            $req->has(字段名)  ：判断表单中是否上传了图片
            $req->字段名     : 获取表单中图片对象
            $req->字段名->isValid()  ： 判断图片是否上传成功
            $req->字段名->store(二级目录的名字);
        */

        if($req->has('logo') && $req->logo->isValid())
        {

            // 先获取当前的日期
            $date = date('Ymd');
            // 把图片保存到当前日期目录下，返回上传之后图片的路径
            $logo = $req->logo->store('face/'.$date);
        }
        //   face/20180515/k7aoooWHVpcfDaULrvFS16n07gbMqMcoU7kAAh8S.jpeg
        return $logo;


    }

    public function delete() {
        Storage::delete('face/20180515/k7aoooWHVpcfDaULrvFS16n07gbMqMcoU7kAAh8S.jpeg');
    }
}
