@extends('layouts.main')

@section('title','好友空间')

@section('content')

<style>
#btns {
    color: #30b930;
}
</style>

    <div class="container clearfix">
		<div class="left">
			<div class="art-list">
				<h5>好友动态</h5>
				<ul class="friends-act">
					@foreach($data as $v)
					<li>
					<a target="_blank" href="{{ route('space', [ 'id'=>$v->user->id]) }}" class="people">{{$v->mobile}}</a>
					在 <strong>{{$v->created_at}}</strong> 发表了:
                        <a target="_blank" href="{{ route('blog.content',['id'=>$v->id]) }}">{{$v->title}}</a>
                    </li>
					@endforeach
				</ul>
			</div>
		</div>
		<div class="right">
			<div class="side user-info">
				<img src="{{ Storage::url($user->face) }}" alt="" width="50px">
			<p>{{$user->mobile}}</p>
				<p id="btns"></p>
			</div>
			{{-- <div class="side">
				<h3>我的好友（100人）</h3>
				<ul class="user-act clearfix">
                @foreach($user as $ga)
                        
					<li><a href="个人主页.html"><img src="/images/face.jpg" alt=""><br>12345678</a></li>
                    @endforeach
					
				</ul>
			</div> --}}
			<div class="side">
				<h3>关注的人1</h3>
				<ul class="user-act clearfix">
					@foreach($gzs as $g )
				<li><a href="{{route('friend.space',['user_id'=>$g->id])}}"><img src="{{Storage::url($g->face)}}" id="face" alt="" width="50px"><br>{{$g->mobile}}</a></li>
					@endforeach
					
				</ul>
			</div>
			<div class="side">
				<h3>粉丝 </h3>
				<ul class="user-act clearfix">
					@foreach($fss as $f)
				<li><a href="{{route('friend.space',['user_id'=>$f->id])}}"><img src="{{Storage::url($f->face)}}" id="face" alt="" width="50px"><br>{{$f->mobile}}</a></li>
					 @endforeach
				</ul>
			</div>
		</div>
	</div>

<script src="/ueditor/third-party/jquery.min.js"></script>
<script>

		// 根据不同的关系显示不同的按钮
		// 关系
		function showBtn(gx)
		{
			var html = '';
			if(gx == 'no')
			{
				html = '<a id="btn-gz" href="#">关注</a>';
			}
			else if(gx=='hy')
			{
				html = '好友 <a id="btn-delfriend" href="#">删除好友</a>';
			}
			else if(gx == 'gz')
			{
				html = '关注 <a id="btn-qxgz" href="#">取消关注</a>';
			}
			else if(gx == 'fs')
			{
				html = '粉丝 <a id="btn-friend" href="#">加为好友</a>';
			}
			else if(gx == 'me')
			{
				html = '我是最好地';
			}
			// 放到这里
			$("#btns").html( html );
		
			// 重新绑定事件
			bindEvent();
		
		}
		// 先调用一次显示按钮
		showBtn("{{$gx}}");
		
		// 为按钮绑定事件
		function bindEvent()
		{
			// 关注
			$("#btn-gz").click(function(){
		
				$.ajax({
					type:"GET",
					url:"/gz/{{$user->id}}",
					dataType:"json",
					success:function(data)
					{
						if(data.errno == 0)
						{
							alert('操作成功！');
							// 更新按钮
							showBtn(data.gx);
						}
						else
						{
							if(data.errno == 1001)
							{
								location.href="/login";
							}
						}
					}
				});
		
			});
		
			$("#btn-qxgz").click(function(){
		
				$.ajax({
					type:"GET",
					url:"/qxgz/{{$user->id}}",
					dataType:"json",
					success:function(data)
					{
						if(data.errno == 0)
						{
							alert('操作成功！');
							// 更新按钮
							showBtn('no');
						}
						else
						{
							if(data.errno == 1001)
							{
								location.href="/login";
							}
						}
					}
				});
		
			});
		
			$("#btn-friend").click(function(){
		
				$.ajax({
					type:"GET",
					url:"/friend/{{$user->id}}",
					dataType:"json",
					success:function(data)
					{
						if(data.errno == 0)
						{
							alert('操作成功！');
							// 更新按钮
							showBtn('hy');
						}
						else
						{
							if(data.errno == 1001)
							{
								location.href="/login";
							}
						}
					}
				});
		
			});
		
			$("#btn-delfriend").click(function(){
		
				$.ajax({
					type:"GET",
					url:"/delfriend/{{$user->id}}",
					dataType:"json",
					success:function(data)
					{
						if(data.errno == 0)
						{
							alert('操作成功！');
							// 更新按钮
							showBtn('fs');
						}
						else
						{
							if(data.errno == 1001)
							{
								location.href="/login";
							}
						}
					}
				});
		
			});
		}
		
		
		</script>
 
 
 

@endsection
