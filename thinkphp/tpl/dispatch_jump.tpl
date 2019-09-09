<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" href="__PUBLIC__admin/css/font.css">
    <link rel="stylesheet" href="__PUBLIC__admin/css/xadmin.css">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="__PUBLIC__admin/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="__PUBLIC__admin/js/xadmin.js"></script>
    <title>跳转提示</title>
</head>
<body>
    <!--
    * $msg 待提示的消息
    * $url 待跳转的链接
    * $time 弹出维持时间（单位秒）
    * icon 这里主要有两个layer的表情，5和6，代表（哭和笑）
    -->
    <script type="text/javascript">
        (function(){
            var msg = '<?php echo(strip_tags($msg));?>';
            var iurl = '<?php echo($url);?>';
            var wait = '<?php echo($wait);?>';
            var code = '<?php echo($code);?>';
            layui.use(['layer', 'form'], function(){
                var layer = layui.layer;
                if(code==0){
                    layer.msg(msg,{icon:"5",time:wait*1000});
                }else if(code==1){
                    layer.msg(msg,{icon:"6",time:wait*1000});
                }
            });
            setTimeout(function(){
                location.href=iurl;
            },2000)
            })();
    </script>
</body>
</html>
