<?php
namespace app\api\controller;

use think\Controller;

class Client extends Controller {
	public function send() {
		// 实例化同步阻塞 TCP 客户端
		$client = new \swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_SYNC);

		// 建立连接，连接失败时停止程序
		$client->connect('119.29.208.229', 10000) or die("connect failed\n");
		// // // 向 TCP 服务器发送数据
        $client->send(json_encode(array('type'=>1,'uid'=>2,'content'=>"登录")));

  //       // 接收数据的最大长度为700，不等待所有数据到达后返回
  //       $data = $client->recv(700, 0) or die("recv failed\n");
  //       echo "recv: {$data} \n";
	}
}