<?php
namespace app\api\controller;
use app\chat\model\Friends as FriendsModel;
use app\chat\model\Users as UsersModel;
use think\Controller;

class Friends extends Controller {
	public function __construct() {
		parent::__construct();
		$this->usersModel = new UsersModel;
		$this->friendsModel = new FriendsModel;
	}

	// 主页信息
	public function lists() {
		$uid = $this->request->get('uid');
		var_dump($uid);exit;
		if (empty($uid)) {
			return json(['errcode' => 10001, 'msg' => '参数错误']);
		}
		$mine = $this->usersModel->getRowByUid($uid);
		$mine['status'] = $mine['status'] == 1 ? 'online' : 'hide';
		$group = $this->friendsModel->getGroupByUid($uid);
		$rows = $this->friendsModel->getRowByUid($uid);
		// 数据重组
		foreach ($group as $key => $value) {
			foreach ($rows as $k => $v) {
				if ($value['id'] == $v['group_id']) {
					$group[$key]['list'][] = $v;
				}
			}
		}
		$data = array(
			'mine' => $mine,
			'friend' => $group,
		);
		return json(['code' => 0, 'msg' => 'ok', 'data' => $data]);
	}

}
