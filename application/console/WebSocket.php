<?php

namespace app\Console;

use app\chat\model\Users as UsersModel;
use helper\RedisDriver;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class WebSocket extends Command {
	// 构造函数
	public function __construct() {
		parent::__construct();
		$this->redisDriver = new RedisDriver();
		$this->usersModel = new UsersModel;
	}
	// Server 实例
	protected $server;

	protected function configure() {
		$this->setName('websocket:start')->setDescription('Start Web Socket Server!');
	}

	protected function execute(Input $input, Output $output) {
		// 监听所有地址，监听 10000 端口
		$this->server = new \swoole_websocket_server('0.0.0.0', 10000);

		// 设置 server 运行前各项参数
		// 调试的时候把守护进程关闭，部署到生产环境时再把注释取消
		// $this->server->set([
		//     'daemonize' => true,
		// ]);

		// 设置回调函数
		$this->server->on('Open', [$this, 'onOpen']);
		$this->server->on('Message', [$this, 'onMessage']);
		$this->server->on('Close', [$this, 'onClose']);

		$this->server->start();
		// $output->writeln("WebSocket: Start.\n");
	}

	// 建立连接时回调函数
	public function onOpen(\swoole_websocket_server $server, \swoole_http_request $request) {
		echo "server: handshake success with fd{$request->fd}\n";
	}

	// 收到数据时回调函数
	public function onMessage(\swoole_websocket_server $server, \swoole_websocket_frame $frame) {
		//解析数据
		$this->redisDriver->connect();
		$data = json_decode($frame->data, true);
		// 登录时绑定用户uid与客户端id的关系
		if ($data['type'] == 1) {
			$this->redisDriver->setStr($data['uid'], $frame->fd);
			echo "我是1";
		}
		// 退出时解除用户uid与客户端id的关系
		if ($data['type'] == 2) {
			$this->redisDriver->delStr($data['uid']);
			echo "我是2";
		}
		// 处理普通聊天信息/文字
		if ($data['type'] == 3) {
			$fd = $this->redisDriver->getStr($data['uid']);
			$server->push($fd, json_encode(array( 'username' => $data['username'],'avatar' => $data['avatar'], 'id' => $data['id'],'type' => $data['cat'], 'content' => $data['content'])));
			// $server->push($fd, json_encode(array('avatar' => $data['avatar'], 'username' => $data['username'], 'type' => $data['cat'],'mine'=>false, 'cid' => $data['cid'], 'id' => $data['id'], 'content' => $data['content'], 'fromid' => $data['fromid'], 'timestamp' => $data['timestamp'])));
			// echo json_encode(array('avatar' => $data['avatar'], 'username' => $data['username'], 'type' => $data['cat'], 'cid' => $data['cid'], 'id' => $data['id'], 'content' => $data['content'], 'fromid' => $data['fromid'], 'mine' => $data['mine'], 'timestamp' => $data['timestamp']));
		}
		echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
	}

	// 连接关闭时回调函数
	public function onClose($server, $fd) {
		echo "client {$fd} closed\n";
	}
}