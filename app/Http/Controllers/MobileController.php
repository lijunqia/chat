<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use Cache;
use DB;
class MobileController extends Controller
{
    /**
     * @author woann<304550409@qq.com>
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @des首页
     */
    public function index(Request $request)
    {
        $sessionid = $request->session()->getId();
        return view('mobile',['sessionid' => $sessionid]);
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

}
