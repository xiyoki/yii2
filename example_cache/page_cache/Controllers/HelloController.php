<?php 

namespace frontend\controllers;
use yii;
use yii\web\Controller;


class HelloController extends Controller
{
	//php多级缓存之页面缓存
	//将操作的结果缓存在服务端；

	//定义behaviors()方法：该方法是yii框架中行为相关的方法；
	//当访问控制器中的任意操作时，behaviors方法先于控制器中的其他操作执行；
	//该方法必须返回一个数组，可在数组中进行一些配置，告诉yii框架，开启页面缓存；
	//该方法的用途是：可以将操作的结果缓存到页面缓存之中（默认将控制器中的所有操作都缓存到页面缓存中）；
	//由于该方法先于操作执行，因此等到下次访问操作时，就直接读取缓存中的数据，跳过操作的逻辑。
	public function behaviors()
	{
		//返回一个数组，在数组中进行一些配置，告诉yii框架，开启页面缓存
		return [
			[
				//使用页面缓存类
				'class' => 'yii\filters\PageCache',
				//设置缓存有效时间
				'duration' => 1000,
				//配置要缓存哪些操作的结果：
				//代码说明：缓存cache操作的结果
				'only' => ['index'],
				//设置缓存依赖
				'dependency' => [
					//文件依赖缓存
					'class' => 'yii\caching\FileDependency', 
					'fileName' => 'hw.txt'
				]
			]
		];	
	}

	//index操作
	public function actionIndex()
	{
		return $this->render('index');
	}

	//test操作
	public function actionTest()
	{
		echo "2";
	}
}