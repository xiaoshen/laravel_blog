<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class deploymentController extends Controller
{
    // 出发gitCoding hook自动部署网站
	public function deployCoding() {
		error_reporting(1);
		$target = __DIR__ . '/../website.com'; // 生产环境web目录
		$token = '您在coding填写的hook令牌';
		$wwwUser = 'apache';
		$wwwGroup = 'apache';
		$json = json_decode(file_get_contents('php://input'), true);
		if (empty($json['token']) || $json['token'] !== $token) {
			exit('error request');
		}
		$repo = $json['repository']['name'];
		$dir = __DIR__ . '/repos/' . $repo;
		$cmds = array(
			"cd $dir && git pull",
			"rm -rf $target/* && cp -r $dir/* $target/",
			"chown -R {$wwwUser}:{$wwwGroup} $target/",
		);
		foreach ($cmds as $cmd) {
			shell_exec($cmd);
		}
	}

	public function deployGit(Request $request)
	{
		$commands = ['cd /var/www/laravel-ubuntu', 'git pull'];
		$signature = $request->header('X-Hub-Signature');
		$payload = file_get_contents('php://input');
		if ($this->isFromGithub($payload, $signature)) {
			foreach ($commands as $command) {
				shell_exec($command);
			}
			http_response_code(200);
		} else {
			abort(403);
		}
	}
	private function isFromGithub($payload, $signature)
	{
		return 'sha1=' . hash_hmac('sha1', $payload, env('GITHUB_DEPLOY_TOKEN'), false) === $signature;
	}
}
