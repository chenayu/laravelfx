<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Agent\Agent;

use Closure;

class Access
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $id = session('id');
        $id = isset($id) ? '---ID:'.$id : '';
        $route = $request->path();
        $date = date('Y-m');
        
        //获取设备信息
        $agent = new Agent();
        $pc=$agent->isDesktop();
        $phone = $agent->isPhone();
 
        if($pc){
            $form = $agent->platform();
            $browser = $agent->browser();
        }elseif($phone){
            $form = $agent->device();
            $browser = $agent->browser();
        }else{
         $form = 'f';
         $browser = 'b';
        }
    
     if($id)
        $path = 'my/'.$date;
     else
        $path = 'access/'.$date;
 
        $str = '['.date('Y-m-d H:i:s').']'.$request->ip().'---'.$route.$id.'---['.$form.':'.$browser.']';
         //Storage::makeDirectory($path);  这里使用到下一个月时会报错
        @mkdir($path);
        file_put_contents($path.'/contents.log',$str."\r\n",FILE_APPEND);
        return $next($request);
    }
}
