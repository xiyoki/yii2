<?php 
//声明类文件所在的命名空间
namespace frontend\controllers;

use yii;
use yii\web\Controller;

//视图文件基本操作
class HelloController extends Controller
{
	//告诉render方法，去渲染'common'这个布局文件。
	public $layout = 'common';

	public function actionIndex()
	{
		//程序1：渲染视图文件
		//方案选择：使用renderPartial方法或使用render方法都可以渲染视图文件，但后者拥有前者所有的功能，并且后者还有额外的功能，因此我们选择render方法。
		//使用render方法：第一个参数，视图文件名（不需要后缀）。
		//注意：yiii框架规定，渲染视图的方法必须在return语句中调用。
		
		// return $this->render('index');

		//程序2：将操作中的字符串数据传递给视图文件
		$hello_str = "hello xiyoki!";
		//第一步：创建一个数组；
		$data = array();
		//第二步：将数据以key=>value的形式保存到数组中
		$data['view_hello_str'] = $hello_str;
		
		//程序3：将操作中的数组数据传递给视图文件
		$hello_arr = array(1,2);
		$title_arr = array('index'=>'首页','about'=>'关于');

		$data['view_hello_arr'] = $hello_arr;
		$data['view_title_arr'] = $title_arr;

		//第三步：将数组传递给render方法，作为该方法的第二个参数

		//程序4：启用布局文件
		//说明：布局文件存放在layouts目录下，作用是将多个视图文件都重复书写的代码提取到该文件中，避免这些视图文件重复书写代码。
		//第一步：将各个视图文件中公共的代码提取到布局文件中；
		//第二步：使用render方法拼接视图文件与布局文件，
		//首先，render方法会将视图文件中的内容放到一个叫做$content的变量中。
		//其次，render方法会将布局文件中的内容渲染出来。具体渲染哪个布局文件需要到操作外指定。
		//然后，将$content变量中的内容加入到布局文件中。
		//注意：render方法的一个参数依然是视图文件，而非布局文件。
		
		//程序5：在一个视图文件中显示另一个视图文件
		//技术要点：在视图文件中调用视图组件的render方法，将另一个视图文件渲染出来，注意还要使用echo输出语句。

		//程序7：在视图文件中定义数据块。
		//注意：render方法处理视图文件时，不会将数据块的内容放置到$content变量中。但在布局文件中可以访问到。
		//技术要点：
		//1、在视图文件中定义数据块；
		//2.在布局文件中通过$this->blocks['key']获取数据块。

		return $this->render('index',$data);

	}
}