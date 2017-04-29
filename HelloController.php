<?php 

namespace frontend\controllers;
use yii;
use yii\web\Controller;

//活动记录类
use frontend\models\Customer;
use frontend\models\Order;
use frontend\models\Test;

/**
 * 重要术语
 * 1. 活动查询实例：ActiveQuery 实例。保存数据表查询操作过程中的各项信息。它是 find() 和 findBySql()方法直接返回的结果。
 * 2. 活动记录实例：ActiveRecord 实例。一个ActiveRecord 实例对应于数据表中的一条记录。它是one()、findOne()方法直接返回的结果。new 活动记录类 也能创建一个活动记录实例。
 * 
 */


class HelloController extends Controller
{

	//数据模型之查询数据表数据
	//方案1：使用活动记录的findBySql()方法
	public function actionIndex()
	{
		$id = '1 or 1=1';
		$sql = 'SELECT * FROM `test` WHERE `id`=:id';
		//用法：使用活动记录的findBySql()方法，它返回一个ActiveQuery Object。方法的第一个参数是sql语句，第二个参数是填充占位符的真实数据,是一个关联数组。
		//优缺点：使用该方法的好处是可以防sql注入攻击。原理是将用户传递过来的数据用占位符表示，将用户传递过来的数据当做一个整体来处理。缺点是代码不够清晰。
		$results = Test::findBySql($sql,array(':id'=>$id))->all();
		print_r($results);
	}

	//数据模型之查询数据表数据
	//方案2：使用活动记录的find()方法
	public function actionIndex()
	{
		
		//基本用法：yii框架对sql语句进行了改装，将查询条件改装成了一个数组,并将其作为where()方法的参数。
		//find()方法返回一个 ActiveQuery Object。
		//where()方法用于添加查询条件，接受数组作为参数。
		//all()方法在返回数据的时候，会将数据表中的每条记录包装成一个个活动记录对象，然后它会把这些对象组装进一个数组，最终它会将这个数组返回出去。
		//总结一下：all()方法会返回一个数组，每个数组元素都是一个活动记录对象，而每个活动记录对象都对应于数据表中的一条记录。
		
		//方案2是支持防sql注入的，直接将查询条件中的字段值改为用户传入的值；

		//查询id=1的数据;
		$results1 = Test::find()->where(['id'=>1])->all();

		//查询id>1的数据
		$results2 = Test::find()->where(['>','id',1])->all();

		//查询id>=2，且id<=3的数据
		$results3 = Test::find()->where(['between','id',2,3])->all();

		//查询 title like %title3%
		$results4 = Test::find()->where(['like','title','title3'])->all();
	}

	//数据模型之查询数据表数据
	//方案3：使用活动记录的findOne()或findAll()方法
	//方案特点：代码更加简洁，缺点功能不全。
	public function actionIndex()
	{
		//findOne 和 findAll() 用来返回一个或者一组ActiveRecord实例。前者返回第一个匹配到的ActiveRecord实例。后者返回所有ActiveRecord实例，并将它们组装成一个数组作为最终返回的结果。
		//优缺点：优点代码更简省；缺点是查询条件带有关系运算符时，无效。
		$customer = Customer::findOne(1);

		// 返回 id 为 1 且name为'小张'的客户
		$customer = Customer::findOne([
		    'id' => 1,
		    'name' => '小张',
		]);

		// 返回id为1、2、3的一组客户
		$customers = Customer::findAll([1, 2, 3]);

		// 返回所有name为'小张'的客户
		$customer = Customer::findAll([
		    'name' => '小张'
		]);
	}

	//数据模型之查询数据表数据
	//专题：yii框架数据表查询优化
	public function actionIndex()
	{
		// 专题：yii框架查询优化  
		// 当一次查询的记录条数很多时，内存就可能存在空间不足问题，优化的手段有两种。
		// 第一是：将查询到的记录封装成关联数组，而非ActiveRecord实例，因为后者包含的信息多，占用的内存大。
		// 第二是：采用批量查询，批量处理的方式，批量查询能限制内存中存放的数据量，使其保持在一个合理的范围。
		
		//优化1：将查询结果转换为数组
		// asArray()方法会改变all()方法的行为，它会指示all()方法会将数据表中的每条记录包装成一个个关联数组（一条记录对应一个关联数组）而非ActiveRecord对象，关联数组的key和value分别对应于数据表中的字段名和值，然后all()会把这些数组统一组装进一个数组，最终将这个外围数组返回出去。
		// 总结一下：在前置asArray()方法的作用下，all()方法会返回一个数组，每个数组元素也都是一个数组，而每个数组都对应于数据表中的一条记录。
		$results5 = Test::find()->where(['between','id',1,4])->asArray()->all();

		//优化2：批量查询
		//批量查询的必要性：假设我们要从数据表中取出一万条数据，那么一次取出不仅等待时间长，而且取出的一万条数据对内存来说也有压力。如果我们采用批量查询的方式，每批查询一千条数据，那么分十批就能查询完毕。批量查询的好处就是等待时间短，能限制内存中的数据量。

		//方案1：使用batch()方法
		//batch()方法用于执行批量查询，其参数用于指定每批所取的记录条数。调用 batch()方法，直接返回 BatchQueryResult Object 。必须通过 foreach 遍历该方法返回的结果，才能取得查询的结果。
		//注意：遍历batch()，获取的每个item都是包装了一组 ActiveRecord 实例的数组。这是因为 batch()方法将每批查询到的所有 ActiveRecord 实例都组装进了一个数组。

		//batch() 与 all() 具有极大的相似性，它们都会将数据表中的每条记录包装成 ActiveRecord 实例，然后它们会把这些实例对象组装进一个数组。不同之处在于，对于 all() 来说，本次数据表查询的所有查询结果都保存在了这个数组中，而对于 batch()来说，每批查询的结果都对应于这样的一个数组。all() 直接将数组返回，而 batch() 会将全部批次查询得到的数组再次组装进一个对象，通过 foreach 遍历该对象就能获取每批查询的结果。同样，前置的 asArray() 方法对 batch() 所起的作用与其对 all() 所起的作用一样。
		foreach (Test::find()->asArray()->batch(2) as $tests) {
			print_r($tests);
		}

		//方案2：使用each()方法
		//each()方法同样用于执行批量查询，其参数用于指定每批所取的记录条数。调用 each()方法，直接返回 BatchQueryResult Object。必须通过 foreach遍历该方法返回的结果，才能取得查询的结果。前置 asArray()方法对 each()产生相同的作用。
		//区别：与batch()方法唯一不同的是，遍历each()，获取的每个 item 都是一个 ActiveRecord实例，而遍历 batch()，获取的每个 item 都是包装了一组 ActiveRecord实例 的数组。
		foreach (Test::find()->asArray()->each(2) as $tests) {
			print_r($tests);
		}

	}

	//数据模型之删除数据表数据
	public function actionIndex()
	{
		//方案1：使用ActiveRecord实例的delete()方法。
		//首先将记录查询出来，然后删除。操作成功后，重复刷新页面要报错，因为要删除的记录已不存在。
		$results1 = Test::find()->where(['id'=>1])->all();
		//等价于：$results1 = Test::findAll(1);

		//从返回的数组中找要删除的ActiveRecord实例，调用该对象的delete()方法即可删除该条记录。如果要删除的记录不止一条，使用foreach对结果集进行遍历。
		if(!empty($results1[0])){
			$results1[0]->delete();
		}

		//方案1：改进
		$results2 = Test::findOne(1);
		if(!empty($results2)){
			$results2->delete();
		}

		//方案2：快速删除数据表中的数据
		//使用活动记录的deleteAll()方法，不传任何参数就是清空整个数据表；第一个参数是要删除记录的需满足的条件；第二个参数是填充占位符的真实数据,是一个关联数组。操作成功后，重复刷新页面不会报错。
		Test::deleteAll('id>:id',array(':id'=>3));
		//等价于：Test::deleteAll('id>:id',[':id'=>3]);
	}

	//数据模型之向数据表添加数据(记录)
	public function actionIndex()
	{
		//用法：首先创建ActiveRecord实例，然后通过'对象->属性'的方式映射数据表记录中的字段，并赋值。添加之前要启动验证：验证数据是否符合验证规则。验证无误之后才能将其保存到数据表中。注意验证规则在活动记录中定义。

		//第一步：创建一个ActiveRecord实例
		$test = new Test;
		//第二步：将数据添加到记录的对应字段；
		$test->id = 1;
		$test->title = 'title1';
		//第三步：调用validate()方法启动验证器，对数据进行验证
		$test->validate();

		//验证抛出错误
		if($test->hasErrors()){
			echo 'data is error';
			exit(0);
		}
		//第四步：将ActiveRecord实例保存为数据表中的一条记录；一个ActiveRecord实例对应于数据表中记的一条录。
		$test->save();
	}

	//数据模型之修改数据表数据
	public function actionIndex()
	{	
		//目的：修改数据表中的某条记录
		//步骤：首先获取ActiveRecord实例；其次通过'对象->属性'的方式修改相应的字段；最后将对象保存到数据表中。
		//one()直接返回结果数组中第一个ActiveRecord实例。
		$test = Test::find()->where(['id'=>3])->one();
		$test->title = 'newtitle3';
		$test->save();
	}

	//数据模型之关联查询
	//获取关联数据
	public function actionIndex()
	{
		//概述：使用 ActiveRecord实例 的方法也可以查询数据表的关联数据（如：选出表A的数据，可以拉出表B的关联数据）

		//案例说明：根据顾客，获取订单数据（顾客与订单：一对多）；根据订单获取顾客信息（订单与顾客：一对一）；

		//操作步骤：
		//第一步：定义或建立关联关系。在数据模型中定义一个可以返回 yii\db\ActiveQuery 对象的 getter 方法（借助hasMany和hasOne的返回值）。因为 yii\db\ActiveQuery 对象有关联上下文的相关信息，因此可以只查询关联数据。详见Order.php中有关Order数据模型类中定义getCustomer方法和Customer.php中有关Customer数据模型类中定义getOrders方法的构建。

		//第二步：获取关联数据。首先获取目标顾客的ActiveRecord实例，然后执行相应的getter方法获取关联数据。
		$customer = Customer::findOne(1);
		//yii框架中，当要访问的属性不存在时，yii框架都会尝试将属性访问转换为方法访问，对$customer->orders的访问会转换为对$customer->getOrders()的调用。此外，yii框架还会做额外的处理，会在getOrders()方法的return语句的末尾默认调用all()方法或one()方法。
		//注意：再次使用表达式 $customer->orders将不会执行第二次 SQL 查询， SQL 查询只在该表达式第一次使用时执行。数据库访问只返回缓存在内部的前一次取回的结果集，如果你想查询新的关联数据，先要注销现有结果集：unset($customer->orders);。
		//根据顾客查询其对应的订单信息。
		$orders = $customer->orders;
		$orders = Order::find()->where(['>','price',50])->all();
		//遍历数组中的每个ActiveRecord 实例，根据订单查询其对应的顾客信息。
		foreach ($orders as $order) {
			$customer = $order->customer;
		}
	}

	//数据模型之关联查询
	//解决关联查询的性能问题 1
	public function actionIndex()
	{
		//第一个性能问题：关联查询的结果会被缓存
		$customer = Customer::findOne(['name'=>'小张']);
		$orders1 = $customer->orders; //select * from order where customerId = ...  

		//将第一次查询缓存的结果释放掉，避免数据表更新时获取的是缓存值；
		unset($customer->orders);
		//注意：unset($orders);无效！
	
		//更新id为3的订单的价格；
		$order = Order::findOne(3);
		$order->price = 656;
		$order->save();
		$orders2 = $customer->orders; //获取更新后的数据
		}
	}

	//数据模型之关联查询
	//解决关联查询的性能问题 2
	public function actionIndex()
	{
		//第二个性能问题：关联查询的多次查询问题
		//问题描述：假设有100个$customer对象，那么下面的sql查询就要执行100次

		//改进前：$customers中有多少个ActiveRecord实例，$customer->orders 就要执行相应次数的数据库查询。
		//$customers = Customer::findAll();
		//foreach ($customers as $customer) {
		// 	$orders = $customer->orders; 
		// 	print_r($orders);
		// }

		//改进后：
		//Customer::find()->with('orders')->all()
		//等价于：select * from customer;select * from order where customerId in (...); ...表示顾客id集合

		//进行关联查询，并返回结果集的数组表示
		$customers = Customer::find()->with('orders')->all();
		
		foreach ($customers as $customer) {
			$orders = $customer->orders;
			print_r($orders);
		}
	}

	//补充
	public function actionIndex()
	{
		//打印Customer::findBySql($sql)，返回一个ActiveQuery Object;
		//打印Customer::find()->where(['id'=>1])，返回一个ActiveQuery Object;
		//打印Customer::find()->where(['id'=>1])->asArray()，返回一个ActiveQuery Object;
		//打印Customer::find()->batch(2)，返回BatchQueryResult Object;
		print_r(Customer::find()->limit(1));
		exit();

		foreach (Order::find()->asArray()->batch(1) as $cus) {
			print_r($cus);
		}
	}
}
