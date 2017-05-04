<?php 

namespace frontend\controllers;
use yii;
use yii\web\Controller;

class HelloController extends Controller
{
	//多级缓存之片段缓存
	public function actionIndex()
	{
		//渲染视图
		return $this->renderPartial('index');
	}
}