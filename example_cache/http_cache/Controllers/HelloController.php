<?php 

namespace frontend\controllers;
use yii;
use yii\web\Controller;


class HelloController extends Controller
{
	//php多级缓存之HTTP缓存
	//last-modified基本原理：
	//配置lastModified后，服务器第一次响应浏览器端请求时，会将服务端资源的最后修改时间添加到响应头部，连同资源一并发送给浏览器。待浏览器端再次请求相同地址的资源时，服务器会校验服务端和浏览器端传递过来的last-modified是否一致。如果一致，那么返回304状态码和空响应体。
	public function behaviors()
	{
		return [
			[ 
				'class' => 'yii\filters\HttpCache',
				'lastModified' => function(){
					//问题：如何获取服务端资源最后修改时间对应的时间戳？
					//使用filemtime()方法，获取文件最新的修改时间，返回时间戳。第一个参数是文件名。
					return filemtime('hw.txt');
				},
				//etagSeed：判断服务端和浏览端资源的内容是否一样
				//如果一致，返回304状态码和空响应体
				//服务器在响应前，将服务端资源的修改时间添加到响应头部
				'etagSeed' => function(){
					//以只读的方式打开'hw.txt'文件
					$fp = fopen('hw.txt','r');
					//fgets()方法；读取文件的内容；第一个参数：文件句柄；只传第一个参数，表示从头开始，读取文件的第一行。
					$title = fgets($fp);
					//关闭文件
					fclose($fp);
					//将新闻标题作为etagSeed函数的返回值；
					//说明：如果文件第一行的内容未发生任何更改，那么$title的值不变；
					return $title;
				}
			]
		];
	}

	//index操作
	public function actionIndex()
	{
		return $this->render('index');
	}
}