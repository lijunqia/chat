<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>登录</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=0.5, maximum-scale=2.0, user-scalable=yes" />
    <link rel="stylesheet" type="text/css" href="/asset/login/css/normalize.css" />
    <link rel="stylesheet" type="text/css" href="/asset/login/css/demo.css" />
    <!--必要样式-->
    <link rel="stylesheet" type="text/css" href="/asset/login/css/component.css" />
    <link rel="stylesheet" type="text/css" href="/asset/layuiv2/css/layui.css" />
    <script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="/asset/layuiv2/layui.js"></script>
    <style>
        @media screen and (max-width: 768px) {
            .layer-anim{
                left: 5% !important;
                min-width: 90% !important;
                top: 10% !important;
            }
        }

    </style>
</head>
<body>
<div class="container demo-1">
    <div class="content">
        <div id="large-header" class="large-header">
            <canvas id="demo-canvas"></canvas>
            <div class="logo_box">
                <h3>登录</h3>
                <form action="#" name="f" method="post">
                    <input type="password" style="position:absolute;top:-999px"/>
                    <div class="input_outer">
                        <span class="u_user"></span>
                        <input name="username" class="text" style="color: #FFFFFF !important" type="text" placeholder="请输入账户" value="">
                    </div>
                    <div class="input_outer">
                        <span class="us_uer"></span>
                        <input name="password" class="text" style="color: #FFFFFF !important; position:absolute; z-index:100;"value="" type="password" placeholder="请输入密码">
                    </div>
                    <div class="mb2"><a id = "sub" lay-filter="sub" class="act-but submit" href="javascript:;" style="color: #FFFFFF">登录</a></div>
                </form>
                <p style="text-align: center;">还没有账号？立即<a style="color: #cccccc;" href="javascript:;" onclick="register()"> 注册 </a></p>
            </div>
        </div>
    </div>
</div><!-- /container -->
<script src="/asset/login/js/TweenLite.min.js"></script>
<script src="/asset/login/js/EasePack.min.js"></script>
<script src="/asset/login/js/rAF.js"></script>
<script src="/asset/login/js/demo-1.js"></script>
</body>

<script>
    function register() {
        layer.open({
            type: 2,
            title: '注册',
            shadeClose: true,
            shade: 0.8,
            area: ['40%', '80%'],
            content: '/register' //iframe的url
        });
    }
    //加载弹出层组件
    layui.use('layer',function(){

        var layer = layui.layer;

        //登录的点击事件
        $("#sub").on("click",function(){
            login();
        })

        $("body").keydown(function(){
            if(event.keyCode == "13"){
                login();
            }
        })

        //登录函数
        function login(){
            var username = $(" input[ name='username' ] ").val();
            var password = $(" input[ name='password' ] ").val();
            $.ajax({
                url:"login",
                data:{"username":username,"password":password},
                type:"post",
                dataType:"json",
                success:function(res){
                    if(res.code == 200){
                        layer.msg(res.msg);
                        setTimeout(function(){
                            if(isMobile())
                                window.location = "/mobile";
                            else
                                window.location = "/";
                        },1500);
                    }else{
                        layer.msg(res.msg,function(){});
                    }
                }
            })
        }

        function isMobile() {
            var userAgentInfo = navigator.userAgent;

            var mobileAgents = [ "Android", "iPhone", "SymbianOS", "Windows Phone", "iPad","iPod"];

            var mobile_flag = false;

            //根据userAgent判断是否是手机
            for (var v = 0; v < mobileAgents.length; v++) {
                if (userAgentInfo.indexOf(mobileAgents[v]) > 0) {
                    mobile_flag = true;
                    break;
                }
            }
            var screen_width = window.screen.width;
            var screen_height = window.screen.height;

            //根据屏幕分辨率判断是否是手机
            if(screen_width < 500 && screen_height < 800){
                mobile_flag = true;
            }

            return mobile_flag;
        }
    })
</script>
</html>
