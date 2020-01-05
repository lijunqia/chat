<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use Cache;
use DB;
class FriendController extends Controller
{
	/**
	 * @author woann<304550409@qq.com>
	 * @param Request $request
	 * @return GroupController
	 * @des 删除好友
	 */
	public function deleteFriend(Request $request)
	{
		$session = session('user');
		$id = $request->post('friendid');
		$isIn = DB::table('friend')->where('user_id', $session->user_id)->first();
		if ($isIn) {
			DB::table('friend')->where('user_id', $session->user_id)->where('friend_id', $id)->delete();
		}
		return $this->json(200,"删除成功");
	}

	/**
     * @author woann<304550409@qq.com>
     * @param Request $request
     * @return GroupController
     * @des 获取用户组成员
     */
    public function friendGroup(Request $request)
    {
        $id = $request->get('id');
        $list = DB::table('friend_group as gm')
            ->leftJoin('user as u','u.id','=','gm.friend_id')
            ->select('u.username','u.id','u.avatar','u.sign')
            ->where('friend_group_id', $id)
            ->get();
        if (!count($list)) {
            return $this->json(500,"获取用户组成员失败");
        }
        return $this->json(0,"",['list' => $list]);
    }


    /**
     * @author woann<304550409@qq.com>
     * @param Request $request
     * @return GroupController|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @des 创建群
     */
    public function createGroup(Request $request)
    {
        if($request->isMethod("POST")){
            $session = session('user');
            $post = $request->post();
            $data = [
                'groupname' => $post['groupname'],
                'user_id'   => $session->user_id,
            ];
            DB::beginTransaction();

            $group_id = DB::table('friend_group')->insertGetId($data);
            if ($group_id) {
                DB::commit();
                $data = [
                    "type" => "createFriendGroup",
                    "groupname" => $post['groupname'],
                    "id"        => $group_id
                ];
                return $this->json(200,"创建成功！",$data);
            } else {
                DB::callback();
                return $this->json(500,"创建失败！");
            }
        }else{
           return view('friend_group');
        }
    }

	/**
	 * @author woann<304550409@qq.com>
	 * @param Request $request
	 * @return GroupController
	 * @des 删除群
	 */
	public function deleteGroup(Request $request)
	{
		$session = session('user');
		$id = $request->post('groupid');
		$isIn = DB::table('friend')->where('friend_group_id',$id)->where('user_id', $session->user_id)->first();
		if ($isIn) {
			$default_group = DB::table('friend_group')->where('user_id', $session->user_id)->order('is_default desc')->first();
			if($default_group->is_default && $id == $default_group->id)
				return $this->json(500,"默认组不能删除");
			$res = DB::table('friend')->where('user_id', $session->user_id)->where('friend_group_id', $id)->update(['friend_group_id' => $default_group['id']]);
			DB::table('friend_group')->where('user_id', $session->user_id)->where('id', $id)->delete();
		}
		DB::table('friend_group')->where('user_id', $session->user_id)->where('id', $id)->delete();
		return $this->json(200,"删除成功");
	}

	/**
	 * @author woann<304550409@qq.com>
	 * @param Request $request
	 * @return GroupController
	 * @des 移到用户到该组
	 */
	public function moveGroup(Request $request)
	{
		$session = session('user');
		$friendid = $request->post('friendid');
		$id = $request->post('groupid');
		$isIn = DB::table('friend')->where('friend_id',$friendid)->where('user_id', $session->user_id)->first();
		if (!$isIn) {
			return $this->json(500,"还不是好友");
		}
		$group = DB::table('friend_group')->where('user_id', $session->user_id)->find($id);
		if (!$group) {
			return $this->json(500,"先建立用户组");
		}
		$res = DB::table('friend')->where('user_id', $session->user_id)->where('friend_id', $friendid)->update(['friend_group_id' => $group['id']]);
		if (!$res) {
			return $this->json(500,"移入失败");
		}
		$data = [
			"type" => "moveGroup",
			"groupname" =>$group->groupname,
			"id"        =>$group->id,
			'friendid'=>$friendid,
		];
		return $this->json(200,"移入成功",$data);
	}

}
