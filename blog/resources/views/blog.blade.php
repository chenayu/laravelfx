<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form action"{{route('blog.doadd')}}" method='post'>
        {{csrf_field()}}
        <div>
            标题：<input type="text" name="title">
        </div>
        <div>
            内容：<textarea name="contene" id="" cols="30" rows="10"></textarea>
        </div>
        <div><input type="radio" name="accessable" value="public" checked>公开</div>
        <div><input type="radio" name="accessable" value="protected">好友可见</div>
        <div><input type="radio" name="accessable" value="private">私有</div>
        <div>
            <input type="submit" value="发表">
        </div>
    </form>
</body>
</html>