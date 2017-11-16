<?php
namespace App;
use Swoole;

/**
 * RPC client 接口
 *
 * @author 郭志强
 * @return void
 * @throws Exception
 *
 */
class RPCClient {
	/**
	 * API
	 *
	 * @author 郭志强
	 * @return void
	 * @throws Exception
	 * @param namespace：空间名称，如"App\\Test::hello" ，hello为方法 。param传参
	 * @access public
	 */
	public static function api(array $condition) {

		$default = [
			'namespace' => "App\\RPCApi::api",
			'param' => [],
		];
		$condition = array_merge($default, $condition);
		if (!$condition['namespace'] || empty($condition['param'])) {
			return;
		}
		$client = Swoole\Client\RPC::getInstance();
		// //$client->setEncodeType(false, true);
		// // $client->putEnv('app', 'test');
		$client->auth('rpcname', 'rpc@123456'); //认证账号和密码
		$client->addServers(array('host' => '127.0.0.1', 'port' => 8888));
		$ret = $client->task($condition['namespace'], [$condition['param']]);
		$client->wait(0.5); //500ms超时
		unset($client);
		return $ret->data;
	}
}

?>