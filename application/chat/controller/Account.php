<?php
namespace app\chat\controller;
use app\chat\model\Users as UsersModel;
use think\Controller;
use think\Session;

class Account extends Controller {
	public function __construct() {
		parent::__construct();
		$this->usersModel = new UsersModel;

	}

	//获取验证码
	public function get_captcha() {
		//设置session
		$captcha = new Captcha(100, 38, 4);
		echo $captcha->showImg();
		Session::set('code', $captcha->getCaptcha());
		exit;

	}
	// 登录视图
	public function login() {
		return $this->fetch('/login');
	}
	// 校验登录
	public function checklogin() {
		$account = $this->request->post('account');
		$password = $this->request->post('password');
		// 校验参数
		if (!ctype_digit($account) || empty($password)) {
			return json(['errcode' => 10001, 'msg' => '参数错误']);
		}
		$row = $this->usersModel->getRowByAccount($account, $password);
		if (empty($row)) {
			return json(['errcode' => 2, 'msg' => '账号或密码错误']);
		}
		// 添加session
		unset($row['password']);
		session('login', $row);
		return json([
			'errcode' => 0,
			'msg' => 'ok',
		]);
	}
	// 退出登录
	public function out() {
		session::delete('login');
		$this->redirect('/chat/account/login');
	}
}
