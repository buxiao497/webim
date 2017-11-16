<?php
namespace behavior;

use think\Controller;
use think\Session as Session;

class WebController extends Controller {
	public $sessionuser = "";
	public function __construct() {
		parent::__construct();
		$this->userSession();
	}

	public function userSession() {
		//校验session
		$this->sessionuser = $row = Session::get('login');
		if (empty($row)) {
			$this->redirect('/chat/account/login');
		}
	}

	/**
	 * 交互提示
	 * @param string $message    提示语
	 * @param number $code       交互状态码（0成功，1失败）
	 * @param string $url        返回地址
	 * @param array  $data       其它参数
	 * @return \think\mixed
	 */
	public function show_message($message = '', $code = 0, $url = '', $data = []) {
		$data = array(
			'message' => $message,
			'code' => $code,
			'url' => $url,
			'data' => $data,
		);
		return json($data);
	}

}