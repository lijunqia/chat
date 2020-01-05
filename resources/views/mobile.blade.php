<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>聊天室</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link rel="stylesheet" href="/asset/layuiv2/css/layui.mobile.css" media="all">
</head>
<body>

<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="/asset/layuiv2/layui.js"></script>
<script src="/asset/layuiv2/lay/modules/upload.js"></script>

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
    layui.config({
        version: '20171011'
    }).use('mobile', function(){
        var mobile = layui.mobile
            ,layim = mobile.layim
            ,layer = mobile.layer;

        var userData = JSON.parse('{!! $userData !!}');

        //基础配置
        layim.config({
            init: {//我的信息
                mine: userData.mine
                //好友列表数据
                ,friend: userData.friend
                ,group: userData.group
            }
            // //获取群员接口（返回的数据格式见下文）
            // ,members: {
            //     url: '/group_members' //接口地址（返回的数据格式见下文）
            //     ,type: 'get' //默认get，一般可不填
            //     ,data: {} //额外参数
            // }
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
            // //扩展工具栏，下文会做进一步介绍（如果无需扩展，剔除该项即可）
            // ,tool: [{
            //     alias: 'code' //工具别名
            //     ,title: '代码' //工具名称
            //     ,icon: '&#xe64e;' //工具图标，参考图标文档
            // }]
            // ,msgbox: '/message_box' //消息盒子页面地址，若不开启，剔除该项即可
            // ,find: '/find'//发现页面地址，若不开启，剔除该项即可
            // ,chatLog: '/chat_log' //聊天记录页面地址，若不开启，剔除该项即可

            //扩展更多列表
//            ,moreList: [{
//                alias: 'find'
//                ,title: '发现'
//                ,iconUnicode: '&#xe628;' //图标字体的unicode，可不填
//                ,iconClass: '' //图标字体的class类名
//            },{
//                alias: 'share'
//                ,title: '分享与邀请'
//                ,iconUnicode: '&#xe641;' //图标字体的unicode，可不填
//                ,iconClass: '' //图标字体的class类名
//            }]
            ,isNewFriend: true //是否开启“新的朋友”
            ,isgroup: true //是否开启“群聊”
//            ,chatTitleColor: '#c00' //顶部Bar颜色
            ,title: '聊天室' //应用名，默认：我的IM
            ,notice:true
//            ,voice: 'default.mp3' //声音提醒，默认开启，声音文件为：default.mp3
            ,isAudio: true //开启聊天工具栏音频
            ,isVideo: true //开启聊天工具栏视频
            //扩展工具栏，下文会做进一步介绍（如果无需扩展，剔除该项即可）
//            ,tool: [{
//                alias: 'code' //工具别名
//                ,title: '代码' //工具名称
//                ,iconUnicode: '&#xe64e;' //工具图标，参考图标文档，可不填
//                ,iconClass: '' //图标字体的class类名
//            }]
        })
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
            switch (data.type) {
                case "friend":
                case "group":
                    console.log(data)
                    layim.getMessage(data); //res.data即你发送消息传递的数据（阅读：监听发送的消息）
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
//                    setTimeout(function(){
//                        if(data.count > 0){
//                            layim.msgbox(data.count);
//                        }
//                    },1000);
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
        }
        //监听发送消息
        layim.on('sendMessage', function(res){
            var mine = res.mine; //包含我发送的消息及我的信息
            var to = res.to; //对方的信息
            sendMessage(socket,JSON.stringify({
                type: 'chatMessage' //随便定义，用于在服务端区分消息类型
                ,data: res
            }));
        });


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
//        layim.on('tool(code)', function(insert, send, obj){ //事件中的tool为固定字符，而code则为过滤器，对应的是工具别名（alias）
//            layer.prompt({
//                title: '插入代码'
//                ,formType: 2
//                ,shade: 0
//            }, function(text, index){
//                layer.close(index);
//                insert('[pre class=layui-code]' + text + '[/pre]'); //将内容插入到编辑器，主要由insert完成
//                //send(); //自动发送
//            });
//        });
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






        //创建一个会话
        /*
        layim.chat({
          id: 111111
          ,name: '许闲心'
          ,type: 'kefu' //friend、group等字符，如果是group，则创建的是群聊
          ,avatar: '//tp1.sinaimg.cn/1571889140/180/40030060651/1'
        });
        */

        //监听点击“新的朋友”
        layim.on('newFriend', function(){
            layim.panel({
                title: '新的朋友' //标题
                ,tpl: '<div style="padding: 10px;">自定义模版，</div>' //模版
                ,data: { //数据
                    test: '么么哒'
                }
            });
        });

        //查看聊天信息
        layim.on('detail', function(data){
            console.log(data); //获取当前会话对象
            var title=data.name + ' 聊天信息';
            //以查看群组信息（如成员）为例
            $.get('/chat_log', {id: data.fromid ,type:data.type}, function(res){
                console.log('res');
                console.log(res);
                //弹出面板
                layim.panel({
                    title: title //标题
                    ,tpl: res //模版，基于laytpl语法
                    ,data: { //数据
                    }
                });
            });
        });

        //监听点击更多列表
//        layim.on('moreList', function(obj){
//            switch(obj.alias){
//                case 'find':
//                    layer.msg('自定义发现动作');
//
//                    //模拟标记“发现新动态”为已读
//                    layim.showNew('More', false);
//                    layim.showNew('find', false);
//                    break;
//                case 'share':
//                    layim.panel({
//                        title: '邀请好友' //标题
//                        ,tpl: '<div style="padding: 10px;">自定义模版，</div>' //模版
//                        ,data: { //数据
//                            test: '么么哒'
//                        }
//                    });
//                    break;
//            }
//        });


        //监听查看更多记录
        layim.on('chatlog', function(data, ul){
            console.log(data);
            layim.panel({
                title: '与 '+ data.username +' 的聊天记录' //标题
                ,tpl: '<div style="padding: 10px;">这里是模版，</div>' //模版
                ,data: { //数据
                    test: 'Hello'
                }
            });
        });



        //模拟"更多"有新动态
//        layim.showNew('More', true);
//        layim.showNew('find', true);
    });

</script>
</body>
</html>
