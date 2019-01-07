@extends('layouts.main')
@section('title','登录')
@section('content')

    @if(session('openid'))
    <h5>
        第一次使用QQ登录需要先绑定一个本地账号：<br>
            1. 如果有账号直接登录<br>
            2. 如果没有账号请先注册一个
    </h5>
    @endif

    <div class="container">
		<h1>用户登录</h1>
		<form action="{{ route('dologin') }}" method="POST">
            {{ csrf_field() }}
			<div class="form-div"><input type="text" name="mobile" value="15555555555" placeholder="输入手机号码"></div>
			<div class="form-div"><input type="password" name="password" value="123456" placeholder="输入密码"></div>
			<div class="form-div">
                <img onclick="this.src='{{ route('captcha') }}?'+Math.random();" src="{{ route('captcha') }}">
				<br>
				<input type="text" name="captcha" placeholder="验证码">
			</div>
			<div class="form-div"><input type="submit" value="登录"></div>
            <a href="{{ route('qq0') }}"><img src="/images/qqlogin.png"></a>
        </form>
    </div>
@endsection
