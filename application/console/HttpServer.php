<?php
namespace app\Console;

use think\console\Command;
use think\console\Input;
use think\console\Output;

class HttpServer extends Command
{
    protected $server;

    // 命令行配置函数
    protected function configure()
    {
        // setName 设置命令行名称 && setDescription 设置命令行描述
        $this->setName('http:server')->setDescription('Start Http Server!');
    }

    // 设置命令返回信息
    protected function execute(Input $input, Output $output)
    {

        $this->server = new \swoole_http_server("0.0.0.0", 9502);

        $this->server->on('Request', [$this, 'onRequest']);

        $this->server->start();

        // $output->writeln("HttpServer: Start.\n");
    }

    public function onRequest(\swoole_http_request $request, \swoole_http_response $response)
    {
        $data = isset($request->get) ? $request->get : '';

        $response->end(serialize($data));
    }
}