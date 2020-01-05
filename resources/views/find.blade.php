<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>查找</title>
    <link rel="stylesheet" href="/asset/layuiv2/css/layui.css" media="all">
</head>
<body>
<div class="layui-row">
    <div class="layui-tab layui-tab-brief">
        <ul class="layui-tab-title">
            <li @if($type == 'user'  || $type == '')  class="layui-this" @endif>找人</li>
            @if($session->user_id == 10001)
            <li @if($type == 'group')  class="layui-this" @endif>找群</li>
            @endif
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item @if($type == 'user' || $type == '')  layui-show @endif">
                <div>
                    <input  style="float: left;width: 90%;" type="text" id="user-wd" required lay-verify="required" placeholder="请输入ID/昵称" autocomplete="off" class="layui-input" @if($type == 'user') value="{{ $wd }}" @endif>
                    <button onclick="findUser()" style="float: right;width: 10%"  class="layui-btn">
                        <i class="layui-icon">&#xe615;</i> 查找
                    </button>
                </div>
                <div class="layui-row">
                    @foreach($user_list as $k=>$v)
                        <div class="layui-col-xs3 layui-find-list">
                            <li>
                                <img src="{{ $v->avatar }}" onerror="javascript:this.src='/asset/images/empty.jpg'" >
                                <span>{{ $v->nickname }}</span>
                                <p>{{ $v->sign }}  {{#  if($v->sign == ''){ }}我很懒，懒得写签名{{#  } }} </p>
                                <button class="layui-btn layui-btn-xs btncolor add"  onclick="addFriend({{ $v->id }},'{{ $v->nickname }}','{{ $v->avatar }}')"><i class="layui-icon">&#xe654;</i>加好友</button>
                            </li>
                        </div>
                    @endforeach
                </div>
            </div>
            @if($session->user_id == 10001)
            <div class="layui-tab-item @if($type == 'group')  layui-show @endif">
                <div>
                    <input  style="float: left;width: 80%;" type="text" id="group-wd" required lay-verify="required" placeholder="请输入群Id/群名称" autocomplete="off" class="layui-input" @if($type == 'group') value="{{ $wd }}" @endif>
                    <button onclick="createGroup()" style="float: right;width: 10%"  class="layui-btn layui-btn-warm">
                        <i class="layui-icon">&#xe654;</i> 创建群
                    </button>
                    <button onclick="findGroup()" style="float: left;width: 10%;margin-left: 0"  class="layui-btn">
                        <i class="layui-icon">&#xe615;</i> 查找群
                    </button>
                </div>
                @foreach($group_list as $k=>$v)
                    <div class="layui-col-xs3 layui-find-list">
                        <li>
                            <img src="{{ $v->avatar }}" onerror="javascript:this.src='/asset/images/empty1.jpg'" >
                            <span>{{ $v->groupname }}</span>
                            <p>{{ $v->desc }}  {{#  if($v->desc == ''){ }}无{{#  } }} </p>
                            <button class="layui-btn layui-btn-xs btncolor add"  onclick="joinGroup({{ $v->id }})"><i class="layui-icon">&#xe654;</i>加群</button>
                        </li>
                    </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="/asset/layuiv2/layui.js"></script>

<script>
    var layer;
    layui.use('layer', function(){
        layer = layui.layer;
    });
    layui.use('element', function(){
        var element = layui.element;
    });
    function findUser() {
        wd = $('#user-wd').val();
        window.location.href="/find?type=user&wd="+wd
    }
    function findGroup() {
        wd = $('#group-wd').val();
        window.location.href="/find?type=group&wd="+wd
    }

    function addFriend(id,nickname,avatar) {
        layui.use('layim', function(layim){
            layim.add({
                type: 'friend' //friend：申请加好友、group：申请加群
                ,username: nickname //好友昵称，若申请加群，参数为：groupname
                ,avatar: avatar //头像
                ,submit: function(group, remark, index){ //一般在此执行Ajax和WS，以通知对方
                    var data = {type:"addFriend",to_user_id:id,to_friend_group_id:group,remark:remark}
                    parent.sendMessage(parent.socket,JSON.stringify(data))
                    console.log(group); //获取选择的好友分组ID，若为添加群，则不返回值
                    console.log(remark); //获取附加信息
                    layer.close(index); //关闭改面板
                }
            });
        });
    }

    function joinGroup(id) {
        $.ajax({
            url:"/join_group",
            type:"post",
            data:{groupid:id},
            dataType:"json",
            success:function (res) {
                console.log(res)
                if(res.code == 200){
                    layer.msg(res.msg)
                    parent.layui.layim.addList(res.data)
                    //加入群成功，给群内所有在线用户发送入群通知
                    var joinNotify = {type:"joinNotify","groupid":id}
                    parent.sendMessage(parent.socket,JSON.stringify(joinNotify));
                }else{
                    layer.msg(res.msg,function () {})
                }
            },
            error:function () {
                layer.msg("网络繁忙",function(){});
            }
        })
    }

    function createGroup() {
        layer.open({
            type: 2,
            title: '创建群',
            shadeClose: true,
            shade: 0.8,
            area: ['50%', '80%'],
            content: '/create_group' //iframe的url
        });
    }
</script>
</body>
</html>