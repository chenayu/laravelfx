<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>Document</title>
    <style>
    .img-container {
        width: 300px;
        height: 300px;
        float:left;
    }
    .img-preview {
        float: left;
        overflow: hidden;
        margin-left: 20px;
    }

    .preview-lg {
        width: 240px;
        height: 240px;
    }

    .preview-md {
        width: 80px;
        height: 80px;
    }

    .preview-sm {
        width: 35px;
        height: 35px;
    }
    </style>
</head>
<body>
        <div class="img-container">
          <img id="image" src="/images/face.jpg" alt="Picture">
        </div>

        <input type="file" name="abc" id="img">

        <div class="docs-preview clearfix">
          <div class="img-preview preview-lg"></div>
          <div class="img-preview preview-md"></div>
          <div class="img-preview preview-sm"></div>
        </div>
        <input type="text" name="x" id="">
        <input type="text" name="y" id="">
        <input type="text" name="w" id="">
        <input type="text" name="h" id="">


</body>
</html>
<script src="/ueditor/third-party/jquery.min.js"></script>
<link rel="stylesheet" href="/cropper/cropper.min.css">
<script src="/cropper/cropper.min.js"></script>

<script>
var $image = $('#image');

var img = $("#img");

img.change(function(){


    var objUrl = getObjectURL(this.files[0]) ;
    console.log("objUrl = "+objUrl) ;
    if (objUrl)
    {
      $image.attr("src", objUrl);
    }


    var x = $("input[name=x]");
    var y = $("input[name=y]");
    var w = $("input[name=w]");
    var h = $("input[name=h]");
    // 获取图片
    // var $image = $('#image');
    // 启动 cropper 插件
    $image.cropper({
        aspectRatio: 1,         // 裁切的框形状
        preview:'.img-preview',    // 显示预览的位置
        viewMode:3,                // 显示模式：图片不能无限缩小，但可以放大
        // 裁切时触发事件
        crop: function(event) {

            x.val( event.detail.x );
            y.val( event.detail.y );
            w.val( event.detail.width );
            h.val( event.detail.height );


            console.log(    event.detail.x        );             // 裁切区域左上角x坐标
            console.log(    event.detail.y        );             // 裁切区域左上角y坐标
            console.log(    event.detail.width    );         // 裁切区域宽高
            console.log(    event.detail.height   );        // 裁切区域高度
        }
    });
});


        function getObjectURL(file) {
          var url = null;
          if (window.createObjectURL != undefined) { // basic
            url = window.createObjectURL(file);
          } else if (window.URL != undefined) { // mozilla(firefox)
            url = window.URL.createObjectURL(file);
          } else if (window.webkitURL != undefined) { // webkit or chrome
            url = window.webkitURL.createObjectURL(file);
          }
          return url;
        }



</script>
