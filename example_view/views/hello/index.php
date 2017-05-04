<!-- 在视图文件中处理跨站脚本攻击 -->
<?php
//使用Html类的encode方法对数据进行html转义，阻止脚本文件执行。
//注意：转义后的脚本文件源码依旧会显示到浏览器中。 
use yii\helpers\Html;

//使用HtmlPurifier类的process方法过滤数据中的javascript代码，阻止脚本文件执行。
//注意：被过滤的脚本文件源码将不会显示到浏览器中。
use yii\helpers\HtmlPurifier;

?>

<!-- 对应于程序2：在视图文件中使用控制器操作传递过来的字符串-->
<div><?= Html::encode($view_hello_str); ?></div>

<!-- 对应于程序3：在视图文件中使用控制器操作传递过来的数组 -->
<div><?= HtmlPurifier::process($view_hello_arr[0]); ?></div>

<!-- 对应于程序4 -->
<div>this is index page</div>

<!-- 对应于程序5  -->
<?php 
	//程序5：在视图文件中渲染另一个视图文件
	//技术要点：使用视图组件的render方法。
	//注意：前面要加上一个echo

	//程序6：向要渲染的视图文件传递数据
	//技术要点：要传递的数据必须组装到一个关联数组中，该数组要作为render的第二个参数。
	echo $this->render('about',array('v_hello_str'=>'hello world'));
?>

<!-- 程序7：定义数据块 -->
<?php $this->beginBlock('title'); ?>
	<title><?= $view_title_arr['index'] ?></title>
<?php $this->endBlock(); ?>
