<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Image;
use App\Models\User;
use Storage;
use App\Http\Requests\FaceRequest;

class FaceController extends Controller
{
    public function index() {

        return view('face.index');
    }

    public function face(FaceRequest $req) {

        if($req->has('face') && $req->face->isValid())
        {
            // 当前图片上传的位置
            $oldimage = $req->face->path();

            // 保存原图片
            // 获取当前日期
            $date = date('Ymd');
            $oriImg = $req->face->store('face/'.$date);  // face/20180525/oELY5MdcB2zl28il7CfDkATcwd3DEG7PVWQrCu4Y.jpeg

            // 打开要处理的图片
            $img = Image::make($oldimage);

            // 裁切图片
            $img->crop((int)$req->w,(int)$req->h,(int)$req->x,(int)$req->y);

            // 生成缩略图并保存

            // 拼出这个缩略图的名字
            $bgname = str_replace('face/'.$date.'/', 'face/'.$date.'/bg_', $oriImg);         // face/20180525/oELY5MdcB2zl28il7CfDkATcwd3DEG7PVWQrCu4Y.jpeg
            $img->resize(240,240);
            $img->save('./uploads/'.$bgname);                     // face/20180525/bg_oELY5MdcB2zl28il7CfDkATcwd3DEG7PVWQrCu4Y.jpeg

            $mdname = str_replace('face/'.$date.'/', 'face/'.$date.'/md_', $oriImg);
            $img->resize(80,80);
            $img->save('./uploads/'.$mdname);         // face/20180525/md_oELY5MdcB2zl28il7CfDkATcwd3DEG7PVWQrCu4Y.jpeg

            $smname = str_replace('face/'.$date.'/', 'face/'.$date.'/sm_', $oriImg);
            $img->resize(35,35);
            $img->save('./uploads/'.$smname);       // face/20180525/sm_oELY5MdcB2zl28il7CfDkATcwd3DEG7PVWQrCu4Y.jpeg

            // 先取出用户的信息
            $user = User::find( session('id') );
            // 删除原头像
            Storage::delete( $user->face );
            Storage::delete( $user->bgface );
            Storage::delete( $user->mdface );
            Storage::delete( $user->smface );

            // 更新新脸
            $user->face = $oriImg;
            $user->bgface = $bgname;
            $user->mdface = $mdname;
            $user->smface = $smname;

            $user->save();

            // 修改SESSION中的图片
            session([
                'smface' => $smname,
                'bgface' => $bgname,
            ]);

            return redirect()->route('face');

        }


    }
}
