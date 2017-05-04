<?php 

namespace frontend\controllers;
use yii\web\Controller;
use yii;
use yii\web\Cookie;

//注意：在yii框架中，浏览器端请求都是发送给服务器端控制器，由控制器中的操作来处理。请求参数需要这样书写：?r=控制器/操作
class HelloController extends Controller
{
	//程序1：定义操作。操作以action开头，后面接操作名；
	public function actionIndex()
	{
		//综合：通过请求组件处理请求
		
		//程序1：获取请求组件
		//技术要点：通过应用主体的request属性获取
		$req = yii::$app->request;
	
		//程序2：获取请求传递过来的数据
		//技术要点：
		//使用请求组件的get方法，获取get请求传递过来的数据；
		//使用请求组件的post方法，获取post请求传递过来的数据。
		//第一个参数数据的key值。如浏览器端未传递对应数据，那么这两个方法返回null，此时可以使用方法第二个参数，设置默认值。
		$data1 = $req->get('id',20);
		$data2 = $req->post('id',3333);

		//程序3：判断发送过来的请求类型
		//技术要点：使用请求组件的isGet方法、isPost方法；
		if($req->isGet()){
			echo "this is get";
		}
		if($req->isPost()){
			echo "this is post";
		}
		
		//程序4：获取发送请求的用户IP地址
		//技术要点：使用请求组件的userIp属性
		echo $req->userIp;

		//综合：服务器会将请求处理的结果打包成一个消息。这个消息就被称为响应。在操作中，通过响应组件就可以对响应进行设置和处理。

		//程序5：获取响应组件
		//技术要点：通过应用主体的response属性获取；
		$res = yii::$app->response;

		//程序6：设置响应的状态码
		//技术要点：使用响应组件的statusCode属性；
		//服务器，默认返回200状态码
		$res->statusCode = '200';

		//程序7：在响应头中添加新的字段
		//技术要点：使用响应的组件的headers->add()方法
		//第一个参数：字段的名称；第二个参数：字段的值；
		//说明：pragma头部主要设置浏览器缓存
		$res->headers->add('pragma','no-cache'); 

		//程序8：修改响应头中的字段
		//技术要点：使用响应的组件的headers->set()方法
		//第一个参数：字段的名称；第二个参数：字段的值；
		//代码说明：将响应的数据在浏览器端缓存5秒；chrome默认是3600秒；
		$res->headers->set('pragma','max-age=5');

		//程序9：删除响应头中的字段
		//技术要点：使用响应的组件的headers->remove()方法
		//第一个参数：字段的名称。
		$res->headers->remove('pragma');


		//程序10 浏览器端页面跳转之方案1
		//方案说明：在响应头中添加能使页面跳转的location字段。
		//技术要点：使用响应的组件的headers->add()方法
		//使用效果：浏览器一旦收到响应后，就会跳转页面到指定url；
		$res->headers->add('location','http://www.baidu.com');

		//程序11:浏览器端页面跳转之方案2
		//方案说明：yii框架中有专门用于页面跳转的方法。
		//技术要点：使用Controller类的redirect方法。
		//第一个参数：跳转的url；第二个参数：指定响应的状态码。
		$this->redirect('http://www.baidu.com',302);


		//程序12：服务端资源下载之方案1
		//方案说明：在响应头添加能使服务端资源下载的content-disposition字段
		//技术要点：使用响应的组件的headers->add()方法
		//使用效果：浏览器端在收到响应的时候，会将响应数据以附件的形式保存在指定好的文件中。
		//代码说明：将响应数据保存到a.jpg文件中。
		$res->headers->add('content-disposition','attachment;filename="a.jpg"');

		//程序13：服务端资源下载之方案2
		//方案说明：yii框架中有专门用于服务端资源下载的方法
		//技术要点：使用响应组件的sendFile方法
		//第一个参数：要发生给浏览器的文件名；
		//注意：程序到入口脚本所在的目录下去寻找要发送的文件。
		//使用效果：浏览器端收到响应的时候，会提示用户将要下载的文件保存到浏览器端指定的位置。
		$res->sendFile('./robots.txt');

		//程序14：获取session组件
		//技术要点：使用应用主体的session属性；
		$session = yii::$app->session;

		//程序15:判断session是否开启，如果未开启，则开启
		//说明：若session未开启，那么就不能往里面存放数据；
		//技术要点：使用session组件的isActive属性；使用session组件的open方法；
		if(!$session->isActive){
			$session->open();
		}

		//程序16：往session中存放数据
		//技术要点：使用session组件的set方法
		//第一个参数：数据的名称；第二个参数：数据的值；
		$session->set('user','xiyoki');

		//综合：
		//查看session数据的保存位置(save_path):
		//打开php_ini文件 -> 搜索session.save_path;即可找到存放session数据的文件。

		//程序17:获取session数据
		//技术要点：使用session组件的get方法
		//第一个参数：session数据的名字；
		$session->get('user');

		//程序18:删除session数据
		//技术要点：使用session组件的remove方法
		//第一个参数：session数据的名字；
		$session->remove('user');

		//扩展：往session中存储、修改、访问、删除数据的数组方式
		//添加数据
		$session['user'] = '张扬';
		//修改数据
		$session['user'] = 'xiyoki';
		//访问数据
		echo $session['user'];
		//删除数据
		unset($session['user']);

		//拓展：不同浏览器向服务器发送请求，服务器能够识别浏览器的类型。

		//在控制器中，可以往响应里面塞入cookie数据，服务器将响应发送给浏览器后，浏览器会将cookie数据取出来显示，或存储在浏览器端。当浏览器再次向服务器发送请求的时候，浏览器会将其存储的cookie数据一并发送到服务器。
		//可在浏览器端查看cookie数据的结构。
		
		//程序18：从响应中获取cookies集合
		//技术要点：使用响应组件中的cookies集合
		$cookies = $res->cookies;

		//程序19：向响应中写入一条cookie数据
		//技术要点：cookies集合的add方法；
		//第一个参数：cookie对象；
		//实例化cookie对象时要传递一个数组；这个数组就是由将储存的cookie数据组装而成。
		$cookie_data = array('name'=>'user','value'=>'xiyoki');
		$cookies->add(new Cookie($cookie_data));
		//注意：浏览器端显示的cookie数据值是经过加密的。
		
		//程序20：修改响应cookie数据：
		//注意：修改的逻辑必须置于$cookies->add()方法前。
		//注意：再次调用add方法，再次实例化cookie对象时，若传入的数组的name字段值没有发生改变，那么就是对原数据的修改。若name字段的值发生了改变，那么就是添加一条新的cookie数据。
		//修改cookie数据的途径：可以直接在源码上修改。
		$cookie_data = array('name'=>'user','value'=>'zhangyang');
		$cookies->add(new Cookie($cookie_data));
		

		//程序21：删除响应cookie数据
		//注意：写入的cookie数据如果不进行释放，那么永远存在；
		//技术要点：使用cookies集合和remove方法
		//第一个参数：要删除的cookie数据的name值；
		$cookies->remove('user');

		//程序22：从请求中获取cookies集合
		//技术要点：使用请求组件的cookies集合
		//说明：由于从响应返回的cookie数据被浏览器添加到了请求中，因此获取cookie数据必须要使用请求组件。
		$req_cookies = $req->cookies;

		//程序23：获取cookie数据
		//技术要点：使用cookie集合的getValue方法
		//第一个参数：cookie数据的name字段值；第二个参数：未获取到值时返回的默认值；
		echo $req_cookies->getValue('user','No cookie data return!');

		//拓展
		//如何配置，确保对写入的cookie数据中的value字段值进行加密?
		//在项目根目录下找到config文件夹，然后打开main-local.php，找到cookieValidationKey字段，即可查看加密字段。
	}
}