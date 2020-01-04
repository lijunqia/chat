<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use Cache;
use DB;
class IndexController extends Controller
{
    /**
     * @author woann<304550409@qq.com>
     * @param Request $request
     * @return IndexController|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @des 登录
     */
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $post = $request->post();
            $user = DB::table('user')->where('username', $post['username'])->first();
            if (!$user) {
                return $this->json(500,'用户不存在');
            }
            if(!password_verify ( $post['password'] , $user->password)){
                return $this->json(500,'密码输入不正确!');
            };
            $user->user_id = $user->id;
            unset($user->id);
            session(['user'=>$user]);
            return $this->json(200,'登录成功');
        } else {
            return view('login');
        }
    }

    /**
     * @author woann<304550409@qq.com>
     * @param Request $request
     * @return IndexController|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @des 注册
     */
    public function register(Request $request)
    {
        if ($request->isMethod('post')) {
            $post = $request->post();
            $code_value = Cache::get('image:'.$post['key']);
            if ($code_value != $post['code']) {
                return $this->json(500,'验证码错误');
            }
            $user = DB::table('user')->where('username', $post['username'])->first();
            if ($user) {
                return $this->json(500,'用户名已存在');
            }
            !$post['avatar'] && $post['avatar'] = randIcons();
            $data = [
                'avatar' => $post['avatar'],
                'nickname' => $post['nickname'],
                'username' => $post['username'],
                'password' => password_hash($post['password'], PASSWORD_DEFAULT),
                'sign' => $post['sign'],
            ];
            $user_id = DB::table('user')->insertGetId($data);
            if (!$user_id) {
                return $this->json(500,'注册失败');
            }
            //为用户创建默认分组
            $friend_group_id = DB::table('friend_group')->insertGetId([
                'user_id' => $user_id,
                'groupname' => '默认分组'
            ]);
//            //将用户添加到所有人都在群
//            DB::table('group_member')->insert([
//                'user_id' => $user_id,
//                'group_id' => 10001
//            ]);
//            //将用户和我互相添加为好友
//            $friend_data = [
//                [
//                    'user_id'       =>  10001,
//                    'friend_id'     =>  $user_id,
//                    'friend_group_id'  =>  147
//                ],
//                [
//                    'user_id'       =>  $user_id,
//                    'friend_id'     =>  10001,
//                    'friend_group_id'  =>  $friend_group_id
//                ],
//            ];
//            DB::table('friend')->insert($friend_data);
            return $this->json(200,'注册成功');
        } else {
            $code_hash = uniqid().uniqid();
            return view('register',['code_hash' => $code_hash]);
        }
    }

    /**
     * @author woann<304550409@qq.com>
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @des首页
     */
    public function index(Request $request)
    {
    	if(isMobile())
		{
			redirect('/mobile');
//			header('location:/mobile');
			return;
		}
        $sessionid = $request->session()->getId();
        return view('index',['sessionid' => $sessionid]);
    }

    /**
     * @author woann<304550409@qq.com>
     * @param Request $request
     * @return IndexController
     * @des 文件上传
     */
    public function upload(Request $request)
    {
        $file = $request->file('file');
        $type = $request->input('type');
        $path = $request->input('path');
        $path = 'uploads/'.$path.'/'.date('Ymd').'/';
        if (!$file) {
            return $this->json(500,'请选择上传的文件');
        }
        if (!$file->isValid()) {
            return $this->json(500,'文件验证失败！');
        }
        $size = $file->getSize();
        if($size > 1024 * 1024 * 5 ){
            return $this->json(500,'图片不能大于5M！');
        }
        if ($type != 'im_path') {
            $ext = $file->getClientOriginalExtension();     // 扩展名
            if(!in_array($ext,['png','jpg','gif','jpeg','pem','ico']))
            {
                return $this->json(500,'文件类型不正确！');
            }
        }
        $filename = uniqid() . '.' . $ext;
        $res = $file->move(base_path('public/'.$path), $filename);
        if($res){
            $data = ['src'=>$path.$filename];
            if ($type == 'im_path') {
                $data['name'] = $file->getFilename();
            }
            return $this->json(0,'上传成功',$data);
        }else{
            return $this->json(500,'上传失败！');
        }
    }

    /**
     * @author woann<304550409@qq.com>
     * @param Request $request
     * @return mixed
     * @des 图片验证码
     */
    public function imageCode(Request $request)
    {
        $key = $request->input('key');
        return getValidate(210,70,$key);
    }

    public function messageBox()
    {
        $session = session('user');
        DB::table('system_message')->where('user_id',$session->user_id)->update(['read' => 1]);
        $list = DB::table('system_message as sm')
            ->leftJoin('user as f','f.id','=','sm.from_id')
            ->select('sm.id','f.id as uid','f.avatar','f.nickname','sm.remark','sm.time','sm.type','sm.group_id','sm.status')
            ->where('user_id',$session->user_id)
            ->orderBy('id', 'DESC')
            ->paginate(10);
        foreach ($list as $k => $v) {
            $list[$k]->time = time_tranx($v->time);
        }
        return view('message_box',['list' => $list]);
    }
    public function loginOut()
    {
        session(['user'=>null]);
        return redirect('/');
    }
    public function chatLog(Request $request)
    {
        $id = $request->get('id');
        $type = $request->get('type');
        return view('chat_log',['id' => $id,'type' => $type]);
    }

    public function chatRecordData(Request $request)
    {
        $session = session('user');
        $id = $request->get('id');
        $type = $request->get('type');
        if ($type == 'group') {
            $list = DB::table('chat_record as cr')
                ->leftJoin('user as u','u.id','=','cr.user_id')
                ->select('u.nickname as username','u.id','u.avatar','time as timestamp','cr.content')
                ->where('cr.group_id',$id)
                ->orderBy('time','DESC')
                ->paginate(10);
        } else {
            $list = DB::table('chat_record as cr')
                ->leftJoin('user as u','u.id','=','cr.user_id')
                ->select('u.nickname as username','u.id','u.avatar','time as timestamp','cr.content')
                ->where(function ($query) use($session, $id) {
                    $query->where('user_id', $session->user_id)
                        ->where('friend_id', $id);
                })
                ->orWhere(function ($query) use($session, $id) {
                    $query->where('friend_id', $session->user_id)
                        ->where('user_id', $id);
                })
                ->orderBy('time','DESC')
                ->paginate(10);
        }
        foreach ($list as $k=>$v){
            $list[$k]->timestamp = $v->timestamp * 1000;
        }
        return $this->json(0,'',$list);

    }
	/**
	 * @author woann<304550409@qq.com>
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 * @des首页
	 */
	public function mobile(Request $request)
	{
		if(!isMobile())
		{
			redirect('/');
//			header('location:/');
			return;
		}
		$session = session('user');
		$user = DB::table('user')->find($session->user_id);
		if (!$user) {
			return $this->json(500,"获取用户信息失败");
		}
		$groups = DB::table('group_member as gm')
			->leftJoin('group as g','g.id','=','gm.group_id')
			->select('g.id','g.groupname','g.avatar')
			->where('gm.user_id', $user->id)->get();
//        foreach ($groups as $k=>$v) {
//            $groups[$k]->groupname = $v->groupname.'('.$v->id.')';
//        }
		$friend_groups = DB::table('friend_group')->select('id','groupname')->where('user_id', $user->id)->get();
		foreach ($friend_groups as $k => $v) {
			$friend_groups[$k]->list = DB::table('friend as f')
				->leftJoin('user as u','u.id','=','f.friend_id')
				->select('u.nickname as username','u.id','u.avatar','u.sign','u.status')
				->where('f.user_id',$user->id)
				->where('f.friend_group_id',$v->id)
				->orderBy('status','DESC')
				->get();
		}
		$data = [
			'mine'      => [
				'username'  => $user->nickname,
				'id'        => $user->id,
				'status'    => $user->status,
				'sign'      => $user->sign,
				'avatar'    => $user->avatar
			],
			"friend"    => $friend_groups,
			"group"     => $groups
		];
		$sessionid = $request->session()->getId();

		return view('mobile',['sessionid' => $sessionid,'userData'=>\GuzzleHttp\json_encode($data)]);
	}
}
