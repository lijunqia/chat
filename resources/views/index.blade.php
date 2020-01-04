<!doctype html>
<html>
<head>
    <meta charset="utf-8"><meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <title>聊天室</title>
    <link rel="stylesheet" href="/asset/layuiv2/css/layui.css" media="all">
</head>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
    <div class="layui-header">
        <div class="layui-logo">聊天室</div>
        <!-- 头部区域（可配合layui已有的水平导航） -->
        <ul class="layui-nav layui-layout-left">
            <li class="layui-nav-item"><a href="">控制台</a></li>
            <li class="layui-nav-item"><a href="">用户</a></li>
            <li class="layui-nav-item">
                <a href="javascript:;">其它</a>
                <dl class="layui-nav-child">
                    <dd><a href="">消息管理</a></dd>
                </dl>
            </li>
        </ul>
        <ul class="layui-nav layui-layout-right">
            <li class="layui-nav-item">
                <a href="javascript:;">
                    <a href="javascript:;"><img src="{{ session('user')->avatar }}" class="layui-nav-img">{{ session('user')->username }}</a>
                </a>
                <dl class="layui-nav-child">
                    <dd><a href="">基本资料</a></dd>
                    <dd><a href="">安全设置</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item"><a href="">退了</a></li>
        </ul>
    </div>

    <div class="layui-side layui-bg-black">
        <div class="layui-side-scroll">
            <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
            <ul class="layui-nav layui-nav-tree"  lay-filter="test">
                <li class="layui-nav-item layui-nav-itemed">
                    <a class="" href="javascript:;">用户</a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;">用户管理</a></dd>
                        <dd><a href="javascript:;">好友管理</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a href="javascript:;">群组</a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;">群组管理</a></dd>
                        <dd><a href="javascript:;">群组用户</a></dd>
                    </dl>
                </li>
            </ul>
        </div>
    </div>

    <div class="layui-body">
        <!-- 内容主体区域 -->
        <div style="padding: 15px;">内容主体区域</div>
    </div>

    <div class="layui-footer">
        <!-- 底部固定区域 -->
        © 2020
    </div>
</div>
<script src="/asset/layuiv2/layui.js"></script>

<script>
    var socket;
    var ping;
    function sendMessage(socket, data){
        var readyState = socket.readyState;
        console.log("连接状态码："+readyState);
        if(readyState == 3)
        {
            window.location.reload();
            return;
        }

        socket.send(data)
    }

    layui.use(['element', 'layim'], function(){
        var $ = layui.$
            ,layim = layui.layim
            ,element = layui.element
            ,router = layui.router();


        //基础配置
        layim.config({
            init: {
                url: '/userinfo' //接口地址（返回的数据格式见下文）
                ,type: 'get' //默认get，一般可不填
                ,data: {} //额外参数
            }
            //获取群员接口（返回的数据格式见下文）
            ,members: {
                url: '/group_members' //接口地址（返回的数据格式见下文）
                ,type: 'get' //默认get，一般可不填
                ,data: {} //额外参数
            }
            //上传图片接口（返回的数据格式见下文），若不开启图片上传，剔除该项即可
            ,uploadImage: {
                url: '/upload?type=im_image&path=im' //接口地址
                ,type: 'post' //默认post
            }
            //上传文件接口（返回的数据格式见下文），若不开启文件上传，剔除该项即可
            ,uploadFile: {
                url: '/upload?type=im_file&path=file' //接口地址
                ,type: 'post' //默认post
            }
            //扩展工具栏，下文会做进一步介绍（如果无需扩展，剔除该项即可）
            ,tool: [{
                alias: 'code' //工具别名
                ,title: '代码' //工具名称
                ,icon: '&#xe64e;' //工具图标，参考图标文档
            }]
            ,msgbox: '/message_box' //消息盒子页面地址，若不开启，剔除该项即可
            ,find: '/find'//发现页面地址，若不开启，剔除该项即可
            ,chatLog: '/chat_log' //聊天记录页面地址，若不开启，剔除该项即可
            ,isAudio: true //开启聊天工具栏音频
            ,isVideo: true //开启聊天工具栏视频
            ,initSkin: '3.jpg' //1-5 设置初始背景
//            ,notice: true //是否开启桌面消息提醒，默认false
//            ,voice: 'default.mp3' //声音提醒，默认开启，声音文件为：default.mp3
            //,brief: true //是否简约模式（若开启则不显示主面板）
            //,title: 'WebIM' //自定义主面板最小化时的标题
            //,right: '100px' //主面板相对浏览器右侧距离
            //,minRight: '90px' //聊天面板最小化时相对浏览器右侧距离
            //,isfriend: false //是否开启好友
            //,isgroup: false //是否开启群组
            //,min: true //是否始终最小化主面板，默认false
        });
        //监听自定义工具栏点击，以添加代码为例
        //建立websocket连接
        socket = new WebSocket('ws://'+window.location.host+'/ws?sessionid={{ $sessionid }}');
        socket.onopen = function(){
            console.log("websocket is connected")
            ping = setInterval(function () {
                sendMessage(socket,'{"type":"ping"}');
                console.log("ping...");
            },1000 * 10)
        };
        socket.onmessage = function(res){
            console.log('接收到数据'+ res.data);
            data = JSON.parse(res.data);
            console.log(data)
            switch (data.type) {
                case "friend":
                case "group":
                    if(data.type === 'friend')
                        layim.setChatStatus('<span style="color:#FF5722;">对方正在输入。。。</span>');
                    layim.getMessage(data); //res.data即你发送消息传递的数据（阅读：监听发送的消息）
                    if(data.type === 'friend')
                        layim.setChatStatus('<span style="color:#FF5722;">在线</span>');
                    break;
                //单纯的弹出
                case "layer":
                    if (data.code == 200) {
                        layer.msg(data.msg)
                    } else {
                        layer.msg(data.msg,function(){})
                    }
                    break;
                //将新好友添加到列表
                case "addList":
                    console.log(data.data)
                    layim.addList(data.data);
                    break;
                //好友上下线变更
                case "friendStatus" :
                    console.log(data.status)
                    layim.setFriendStatus(data.uid, data.status);
                    break;
                //消息盒子
                case "msgBox" :
                    //为了等待页面加载，不然找不到消息盒子图标节点
                    setTimeout(function(){
                        if(data.count > 0){
                            layim.msgbox(data.count);
                        }
                    },1000);
                    break;
                //token过期
                case "token_expire":
                    window.location.reload();
                    break;
                //加群提醒
                case "joinNotify":
                    layim.getMessage(data.data);
                    break;

            }
        };
        socket.onclose = function(){
            console.log("websocket is closed")
            clearInterval(ping)
        };


        //监听发送消息
        layim.on('sendMessage', function(res){
            var mine = res.mine; //包含我发送的消息及我的信息
            var to = res.to; //对方的信息
            sendMessage(socket,JSON.stringify({
                type: 'chatMessage' //随便定义，用于在服务端区分消息类型
                ,data: res
            }));
        });
        //监听查看群员
        layim.on('members', function(data){
            console.log(data);
        });
        //监听签名修改
        layim.on('sign', function(value){
            console.log(value); //获得新的签名
            $.ajax({
                url:"/update_sign",
                type:"post",
                data:{sign:value},
                dataType:"json",
                success:function (res) {
                    if(res.code == 200){
                        layer.msg(res.msg)
                    }else{
                        layer.msg(res.msg,function () {})
                    }
                },
                error:function () {
                    layer.msg("网络繁忙",function(){});
                }
            })
        });
        //监听自定义工具栏点击，以添加代码为例
        layim.on('tool(code)', function(insert, send, obj){ //事件中的tool为固定字符，而code则为过滤器，对应的是工具别名（alias）
            layer.prompt({
                title: '插入代码'
                ,formType: 2
                ,shade: 0
            }, function(text, index){
                layer.close(index);
                insert('[pre class=layui-code]' + text + '[/pre]'); //将内容插入到编辑器，主要由insert完成
                //send(); //自动发送
            });
        });
        //监听聊天窗口的切换
        layim.on('chatChange', function(obj){
            console.log(obj)
            var type = obj.data.type;
            if(type === 'friend'){
                if(obj.data.status == 'online'){
                    layim.setChatStatus('<span style="color:#FF5722;">在线</span>'); //模拟标注好友在线状态
                }else{
                    layim.setChatStatus('<span style="color:#666;">离线</span>'); //模拟标注好友在线状态
                }
            }
        });
        //监听在线状态的切换事件
        layim.on('online', function(status){
            sendMessage(socket,JSON.stringify({
                type: 'onlineHide' //随便定义，用于在服务端区分消息类型
                ,data: status
            }));
        });

    });

</script>
</body>
</html>
