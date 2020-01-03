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
    var WebIM = window.WebIM || {};
    WebIM.config = {
        sessionid : '{{ $sessionid }}'
    };
    layui.use(['layim', 'jquery', 'socket'], function (layim, socket) {
        var $ = layui.jquery;
        var socket = layui.socket;
        var token = $('body').data('token');
        var rykey = $('body').data('rykey');
        socket.config({
            user: token,
            pwd: rykey ,
            layim: layim
        });

        layim.config({
            init: {
                url: '/userinfo', data: {}
            },
            //获取群成员
            members: {
                url: '/group_members', data: {}
            }
            //上传图片接口
            , uploadImage: {
                url: '/upload?type=im_image&path=im' //（返回的数据格式见下文）
                , type: 'post' //默认post
            }
            //上传文件接口
            , uploadFile: {
                url: '/upload?type=im_file&path=file' //
                , type: 'post' //默认post
            }
            //查找好友总数
            ,findFriendTotal:{
                url: 'class/doAction.php?action=findFriendTotal'
                , type: 'get' //默认
            }
            //查找好友
            ,findFriend:{
                url: 'class/doAction.php?action=findFriend'
                , type: 'get' //默认
            }
            //获取好友资料
            ,getInformation:{
                url: 'class/doAction.php?action=getInformation'
                , type: 'get' //默认
            }
            //保存我的资料
            ,saveMyInformation:{
                url: 'class/doAction.php?action=saveMyInformation'
                , type: 'post' //默认
            }
            //提交建群信息
            ,commitGroupInfo:{
                url: 'class/doAction.php?action=commitGroupInfo'
                , type: 'get' //默认
            }
            //获取系统消息
            ,getMsgBox:{
                url: 'class/doAction.php?action=getMsgBox'
                , type: 'get' //默认post
            }
            //获取总的记录数
            ,getChatLogTotal:{
                url: 'class/doAction.php?action=getChatLogTotal'
                , type: 'get' //默认post
            }
            //获取历史记录
            ,getChatLog:{
                url: 'class/doAction.php?action=getChatLog'
                , type: 'get' //默认post
            }
            ,isAudio: true //开启聊天工具栏音频
            ,isVideo: true //开启聊天工具栏视频
            , groupMembers: true
            //扩展工具栏
             , tool: [{
                     alias: 'code'
                     , title: '代码'
                     , icon: '&#xe64e;'
                 }]
            ,title: '聊天室'
            ,copyright:true
            , initSkin: '1.jpg' //1-5 设置初始背景
            , notice: true //是否开启桌面消息提醒，默认false
            , msgbox: '/message_box' //消息盒子页面地址，若不开启，剔除该项即可
            , find: '/find' //发现页面地址，若不开启，剔除该项即可
            , chatLog: '/chat_log' //聊天记录页面地址，若不开启，剔除该项即可
            , createGroup: '/create_group' //创建群页面地址，若不开启，剔除该项即可
            , Information: 'css/modules/layim/html/getInformation.html' //好友群资料页面
        });
    });
</script>

</body>
</html>
