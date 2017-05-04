<?php 

namespace frontend\controllers;
use yii;
use yii\web\Controller;
use yii\caching\FileDependency;
use yii\caching\ExpressionDependency;
use yii\caching\DbDependency;

class HelloController extends Controller
{

	//多级缓存之数据缓存
	public function actionIndex()
	{
		//前置操作：
		//第一步：到应用组件配置文件中配置缓存组件。确定是开启文件缓存、memcache缓存，还是数据库缓存。
		// 'cache' => 'yii\caching\FileCache', //使用文件缓存
		
		//第二步：获取缓存组件
		$cache = yii::$app->cache;

		//程序1：写入缓存
		//使用$cache->add()方法：第一个参数，数据的key或名字；第二个参数，缓存的值。
		//注意事项：使用add()方法，如果key存在，并且有值，那么重复赋值无效，依旧使用原来的值。
		$cache->add('key1','hello world1');

		//程序2：修改缓存、写入缓存
		//使用$cache->set()方法：第一个参数，数据的key或名字；第二个参数，要修改的缓存值。
		$cache->set('key1','hello world update');

		//拓展：
		//1.刷新一下页面，就可以执行一遍写入缓存的操作。
		//2.$cache->set可以完全替代$cache->add。
		//3.缓存写入后要注释掉写入的代码，避免再次刷新页面时写入操作重复执行。

		//程序3：删除缓存
		//使用$cache->delete()方法：第一个参数，数据的key或名字；
		//注意：数据删除之后，$cache->get()因获取不到数据，会返回false。
		$cache->delete('key1');

		//程序4：清空缓存中的多条数据
		//使用$cache->flush()方法：把缓存中的所有数据清空掉。
		$cache->flush();


		//程序5：缓存数据有效期设置
		//一段时间内有效，一段时间后失效，失效时缓存会被释放掉
		//使用$cache->add()方法：第三个参数，数据缓存多少秒。
		//使用$cache->set()方法：第三个参数，数据缓存多少秒。
		$cache->add('key3','hello world3',15);
		$cache->set('key4','hello',15);
		
		//程序6：数据缓存之文件依赖
		//第一步：实例化与文件关联的文件依赖对象参数为一个数组。
		$dependency = new FileDependency(['fileName'=>'hw.txt']);

		//第二步：将依赖对象作为$cache->add()方法或$cache->set()方法的第四个参数。
		//影响：如果依赖对象关联的文件发生了修改，或文件的修改时间发生了变化，那么缓存就会失效。
		$cache->set('file_key','hello，数据缓存',3000,$dependency);


		//程序6：数据缓存之表达式依赖
		//第一步：实例化与表达式关联的表达式依赖对象，参数为一个数组。
		$dependency = new ExpressionDependency(['expression'=>'yii::$app->request->get("name")']);

		//第二步：将依赖对象作为$cache->add()方法或$cache->set()方法的第四个参数。
		//影响：如果依赖对象关联的表达式的值发生了修改，那么缓存失效。
		$cache->set('expression_key','hello expression',3000,$dependency);

		//程序7：数据缓存之DB依赖
		//第一步：实例化与数据库关联的数据库依赖对象
		$dependency = new DbDependency(['sql'=>'SELECT count(*) FROM blog.order']);

		//第二步：将依赖对象作为$cache->add()方法或$cache->set()方法的第四个参数。
		//影响：如果依赖对象关联的数据库数据条数发生了改变，那么缓存失效。
		$cache->set('db_key','hello db_key',3000,$dependency);

		//扩展：
		//1.缓存有效期和缓存依赖，谁先发生，谁先生效。
		//2.当带有缓存依赖的缓存写入后，一旦缓存依赖发生改变，缓存就会释放。

		//程序8：获取缓存
		//使用$cache->get()方法：第一个参数，数据的key或名字。
		print_r($cache->get('db_key'));
		
		//扩展：循环缓存
		//1.如果缓存释放了或数据不存在，那么重新写入缓存。
		if(empty($cache->get('file_key'))){
			$cache->set('db_key','hello，数据缓存之DB依赖',3000,$dependency);
		}
		print_r($cache->get('file_key'));
	}
}