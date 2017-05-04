<?php
	//设置缓存有效期
	$duration = 10;

	//设置缓存依赖
	$dependency = [
		//指定文件依赖类型
		'class' => 'yii\caching\FileDependency',
		'fileName' => 'hw.txt',
		//指定表达式依赖类型
		// 'class' => 'yii\caching\ExpressionDependency',
		// 'expression'=>'yii::$app->request->get("name")',
		// //指定数据库依赖类型
		// 'class' => 'yii\caching\DbDependency',
		// 'sql'=>'SELECT count(*) FROM blog.order',
	];

	//拓展：
	//改进$dependency的形式；
	//调用的时候使用数组访问
	$dependency2 = [
		//指定文件依赖类型
		[
			'class' => 'yii\caching\FileDependency',
			'fileName' => 'hw.txt',
		],
		//指定表达式依赖类型
		[
			'class' => 'yii\caching\ExpressionDependency',
			'expression'=>'yii::$app->request->get("name")',
		],
		//指定数据库依赖类型
		[
			'class' => 'yii\caching\DbDependency',
			'sql'=>'SELECT count(*) FROM blog.order',
		]
	];

	//设置缓存开关
	$enabled = true;
?>

<?php 
//多级缓存之片段缓存

//程序1：开启片段缓存
//使用if($this->beginCache('id')){...内容生成逻辑 ... $this->endCache(); }结构：如果开启缓存的条件为true,意味着缓存中无数据，那么执行内容生成逻辑。如果开启缓存的条件为false,意味着缓存中有数据，那么跳过内容生成逻辑，直接获取缓存数据。
//beginCache()方法的第一个参数：缓存的资源id值。
if($this->beginCache('cache_div1')){ ?>
	<!-- 内容生成逻辑 -->
	<div id="cache_div1">
		<p>这是要缓存的内容</p>
	</div>
<?php $this->endCache(); } ?>


<?php 
//程序2：设置缓存有效期
//$this->beginCache()第二个参数：缓存有效期，是一个数组;如果超过缓存时间，缓存失效，那么beginCache返回true,内容逻辑将被执行;
if($this->beginCache('cache_div2',['duration'=>$duration])){ ?>
	<!-- 内容生成逻辑 -->
	<div id="cache_div2">
		<p>这是要缓存的内容</p>
	</div>
<?php $this->endCache(); } ?>


<?php 
//程序3：设置缓存依赖
//$this->beginCache()第二个参数：设置缓存依赖，是一个数组;如果依赖文件发生改变，或修改时间发生变化，缓存失效，那么beginCache返回true,内容逻辑将被执行;
if($this->beginCache('cache_div3',['dependecy'=>$dependecy])){ ?>
	<!-- 内容生成逻辑 -->
	<div id="cache_div3">
		<p>这是要缓存的内容</p>
	</div>
<?php $this->endCache(); } ?>


<?php 
//程序4：设置缓存开关
//$this->beginCache()第二个参数：设置缓存开关，是一个数组。如果enabled值为false，关闭缓存，那么beginCache返回true,内容生成逻辑将被执行。
if($this->beginCache('cache_div4',['enabled'=>$enabled])){ ?>
	<!-- 内容生成逻辑 -->
	<div id="cache_div4">
		<p>这是要缓存的内容</p>
	</div>
<?php $this->endCache(); } ?>


<?php 
//拓展：
//$this->beginCache()方法第二个参数完整的配置；
//缓存规则：谁先发生，谁先生效。
if($this->beginCache('cache_div5',['duration'=>$duration,'dependency'=>$dependecy,'enabled'=>$enabled])){ ?>
	<!-- 内容生成逻辑 -->
	<div id="cache_div5">
		<p>这是要缓存的内容</p>
	</div>
<?php $this->endCache(); } ?>


<?php
//程序5：片段缓存嵌套
//注意事项：当外层的缓存有效期大于内层的缓存有效期，那么只有外层的缓存发挥作用，内层的缓存无效；当外层的缓存有效期小于内层的缓存有效期，那么内层的缓存有效期也会发挥作用。
//代码说明：每隔10秒执行一次外层内容生成逻辑，10秒内执行不执行外层内容生成逻辑；
if($this->beginCache('outer_cache_div',['duration'=>10])){ ?>
	<!-- 外层内容生成逻辑 -->
	<div id="outer_cache_div">
		<p>这是要缓存的外部div new new</p>
		<?php 
		//第一个10秒，第二个10秒，获取的依旧是缓存值；第三个10秒，执行一次内层内容生成逻辑
		if($this->beginCache('inner_cache_div',['duration'=>15])){ ?>
			<!-- 内层内容生成逻辑 -->
			<div id="inner_cache_div">
				<p>这是要缓存的内部div</p>
			</div>
		<?php $this->endCache();} ?>
	</div>
<?php $this->endCache();}?>


<!-- 对照代码 -->
<div id="no_cache_div">
	<p>这是不会缓存的div</p>
</div>*/


