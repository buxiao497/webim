<?php
namespace app\chat\controller;
// use helper\RedisDriver;
use think\Controller;

class Msg extends Controller {
	public function __construct() {
		parent::__construct();
		// $this->redisDriver = new RedisDriver();

	}

	// 好友列表
	public function lists() {
		// $this->redisDriver->connect();
		// $this->redisDriver->setStr(1, 2);
		return $this->fetch('/index');
	}

}
