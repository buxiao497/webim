<?php
namespace app\api\controller;
use app\chat\model\Friends as FriendsModel;
use app\chat\model\Users as UsersModel;
use think\Controller;

class Msg extends Controller {
	public function __construct() {
		parent::__construct();
		$this->usersModel = new UsersModel;
		$this->friendsModel = new FriendsModel;
	}

	// 客户端
	public function client() {
		// 建立长连接
		$client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC); //异步非阻塞
		$client->on("connect", function ($cli) {
			$cli->send("hello world\n");
		});

		$client->on("receive", function ($cli, $data = "") {
			$data = $cli->recv(); //1.6.10+ 不需要
			if (empty($data)) {
				$cli->close();
				echo "closed\n";
			} else {
				echo "received: $data\n";
				sleep(1);
				$cli->send("hello\n");
			}
		});

		$client->on("close", function ($cli) {
			$cli->close(); // 1.6.10+ 不需要
			echo "close\n";
		});

		$client->on("error", function ($cli) {
			exit("error\n");
		});

		$client->connect('119.29.208.229', 10000, 0.5);

	}

	// 上传文件
	public function put() {
		// 获取表单上传文件 例如上传了001.jpg
		$file = request()->file('file');
		if (!$file) {
			return json(['errcode' => 10001, 'msg' => '参数错误']);
		}
		// 移动到框架应用根目录/public/uploads/ 目录下
		if ($file) {
			$info = $file->move(ROOT_PATH . 'public' . DS . 'static/chat/image');
			if ($info) {
				return json(['code' => 0, 'msg' => 'ok', 'data' => array('src' => '/static/chat/image/' . $info->getSaveName())]);
			} else {
				// 上传失败获取错误信息
				return json(['code' => 0, 'msg' => 'ok', 'data' => $file->getError()]);
			}
		}

	}

}
