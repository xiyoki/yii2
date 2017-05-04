<html lang="en"><head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body marginheight="0"><h2>重点：数据来源</h2>
<ol>
<li>浏览器端通过发送请求将数据传递到服务器端；</li>
<li>服务器端将数据响应给浏览器端；</li>
<li>服务器端将数据储存在session中；</li>
<li>服务器端可将cookie数据存储在cookies集合，cookie数据将通过响应传递到浏览器端，并在浏览器端进行存储。</li>
<li>浏览器端发起请求时，会将本地的cookie数据一并发送到服务器端。 </li>
</ol>
<h2>控制器的功能是什么？</h2>
<p>1.通过在控制器类内部定义<strong>操作</strong>，来处理浏览器传递过来的请求。
2. 当操作对请求进行处理之后，服务器会将请求处理的结果打包成一个消息，然后将消息扔回浏览器。这个消息我们就称为响应。
3. 在操作中，借助session组件的属性和方法，往session中存放数据。
4. 在控制器里面，可以向响应里塞入cookie数据。当服务器将响应数据发送给浏览器的时候，浏览器可以将cookie数据取出，取出来之后就将其写入到浏览器中。

</p>
<p><strong>在Yii框架中专门处理请求的方法叫做操作。</strong>
操作定义在控制器类内部，是公有的，是一个函数，action+操作名，遵循驼峰命名法则；

</p>
<h2>控制器是什么？</h2>
<ol>
<li>控制器是一个.php类文件，文件里定义了控制器类。</li>
<li>控制器名+Controller：既是文件名，又是类名，首字母大写。</li>
<li>类必须要放在命名空间（namespace）之下，使用namespace声明命名空间下的特定类。使用命名空间下的类要用到use关键字；重命名类，要用到as关键字。
全局类：全局类不处于任何命名空间之下，例如Yii类。</li>
</ol>
<h2>问题：如何将请求交由控制器中的操作处理？</h2>
<p>通过向请求资源的url地址添加额外参数：?r=控制器名/操作名；

</p>
<h2>操作的功能如何实现？</h2>
<p>在操作中调用应用主体和对应的应用组件的方法或属性。

</p>
<h1>获取应用主体：</h1>
<pre><code>Yii::$app</code></pre>
<h2>应用组件：</h2>
<p>请求组件、响应组件、session组件；

</p>
<h2>请求组件</h2>
<h4>获取请求组件：</h4>
<pre><code>$req = Yii::$app-&gt;request;</code></pre>
<h4>请求组件的方法或属性：在操作中，借助请求组件的属性或方法，对请求进行处理。</h4>
<ol>
<li>get()方法： 获取GET请求传递过来的数据。其第一个参数（必选）是数据对应的key，第二个参数（可选）是当key无对应值时，该方法可以返回的默认值。</li>
<li>post() 方法：获取POST请求传递过来的数据。其第一个参数（必选）是数据对应的key，第二个参数（可选）是当key无对应值时，该方法可以返回的默认值。</li>
<li>isGet() 方法：判断发送过来的请求是否为GET请求。</li>
<li>isPost() 方法：判断发送过来的请求是否为POST请求。</li>
<li>userIp属性：获取用户的ip地址。</li>
<li>cookies集合：浏览器端通过请求，将cookie数据发送到服务器端。</li>
</ol>
<h2>响应组件</h2>
<h4>获取响应组件：</h4>
<pre><code>$res = Yii::$app-&gt;response;</code></pre>
<h4>响应组件的方法或属性：在操作中，借助响应组件的属性或方法，对响应进行设置和处理。</h4>
<ol>
<li>statusCode属性：设置响应消息的状态码。</li>
<li>headers-&gt;add()：为响应消息的头部添加一些内容。例如添加pragma，它是同浏览器缓存打交道的，当它的值为no-cache时，就是告诉浏览器一旦接收到服务器返回的响应消息时，不要将其缓存在浏览器中。当然，也可以将pragma设置为其他值。</li>
<li>headers-&gt;set()：设置或修改响应消息头部的一些内容（既有添加的功能又有修改的功能）。
//一旦接收到响应消息，就将其缓存60秒；<pre><code>$res-&gt;headers-&gt;set('pragma','max-age=60');</code></pre>
</li>
<li>headers-&gt;remove()：删除响应消息头部指定的内容。</li>
<li>cookies集合：服务器端通过响应，将cookie数据发送到浏览器端。</li>
</ol>
<p><strong>注意</strong>
刚使用$res-&gt;headers-&gt;add()或$res-&gt;headers-&gt;set()设置的内容，立马又使用headers-&gt;remove()删除，就相当于没有进行任何相关的操作。
</p>
<pre><code>$res-&gt;headers-&gt;set('pragma','max-age=60');
$res-&gt;headers-&gt;remove('pragma');</code></pre>
<h4>页面跳转</h4>
<p>方法1：$res-&gt;headers-&gt;add('location','<a href="http://www.baidu.com')：浏览器一旦接收到响应消息，页面就跳转到指定地址。">http://www.baidu.com')：浏览器一旦接收到响应消息，页面就跳转到指定地址。</a>

</p>
<p>方法2：Controller类定义了一个专门处理跳转的函数：$this-&gt;redirect() 。第一个参数是设置要跳转到的url，第二个参数是设置返回消息的状态码。
</p>
<pre><code>$this-&gt;redirect('http://www.baidu.com','302');</code></pre>
<h4>文件下载</h4>
<p>方法1：使用$res-&gt;headers-&gt;add()方法
// 浏览器一旦接收到响应消息，就以附件的形式保留服务端传递给浏览器的数据，文件名为a.txt；
</p>
<pre><code>$res-&gt;headers-&gt;add('content-disposition','attachment; filename="a.txt"');</code></pre>
<p>方法2：响应组件专门定义了sendFile()方法，用于将服务端的文件下载给浏览器。
//将服务器将服务端根目录(C:\UPUPW_AP5.5\vhosts\hyii2.com\frontend\web)下的something.txt文件发送给浏览器。
</p>
<pre><code>$res-&gt;sendFile('something.txt')</code></pre>
<h2>session组件</h2>
<h4>获取session组件：</h4>
<pre><code>$session = Yii::$app-&gt;session;</code></pre>
<h4>session组件的方法或属性：</h4>
<p>在操作中，借助session组件的属性或方法，对session进行设置和处理。
1. isActive属性：判断session是否开启。
2. open()方法：开启session。
3. set()方法：往session中存放数据。第一个参数是要存放的数据的名字；第二个参数是存放数据的值。
//例如
</p>
<pre><code>$session-&gt;set('user','zhangsan');</code></pre>
<ol>
<li>get()方法：获取session中存放的数据。第一个参数是要获取的数据的名字。</li>
<li>remove()方法：删除session中存放的数据。第一个参数是要删除的数据的名字。</li>
</ol>
<h4>查看session数据的存放位置</h4>
<p>打开php_ini文件 -&gt; 搜索session.save_path;

</p>
<h4>往session中存、取、删数据的另一种方法：</h4>
<p>将$session当作一个数组来使用。
将session当作一个数组，通过[]访问，实现数据的存取。
例如：
</p>
<pre><code>$session['user']= 'xiyoki';</code></pre>
<p>通过unset($session[''])删除数据
</p>
<pre><code>unset($session['user']);</code></pre>
<h2>补充：</h2>
<p>session应用组件因为实现了ArrayAccess接口，凡实现了ArrayAccess接口的类，它所产生的对象都可以被当成数组使用。

</p>
<h2>响应组件下的cookies集合</h2>
<h4>获取cookies集合</h4>
<pre><code>$cookies = Yii::$app-&gt;response-&gt;cookies;</code></pre>
<h2>cookies集合的属性或方法：</h2>
<p>在操作中，借助cookies集合的属性或方法，对cookies进行设置和处理。
1. add()方法：向cookies集合中写入一条cookie数据。第一个参数是一个Cookie实例，要为实例对象传入一条cookie数据。
例如：
</p>
<pre><code>//构建一条cookie数据
$cookie_data = array('name' =&gt; 'user', 'value' =&gt; 'zhangyang');
//将cookie数据保存在cookies集合中
$cookies-&gt;add(new Cookie($cookie_data));</code></pre>
<ol>
<li>remove()方法：删除cookies集合中的某条cookie数据。参数为cookie数据中name字段的值。</li>
</ol>
<h4>修改cookie数据：</h4>
<p>直接在源码相应位置上修改。

</p>
<h2>请求组件下的cookies集合</h2>
<p>当我们刷新浏览器的时候，cookie数据会被放到请求当中，然后扔到控制器中。
</p>
<h4>获取cookies集合</h4>
<pre><code>$cookies = Yii::$app-&gt;request-&gt;cookies;</code></pre>
<h4>cookies集合的属性或方法</h4>
<p>在操作中，借助cookies集合的属性或方法，对cookie数据进行处理。

</p>
<ol>
<li>getValue()方法：从请求中获取某条cookie数据值。第一个参数（必选）是cookie数据的name字段值；第二个参数（可选）是当cookie数据中不存在该值时，该方法默认返回的结果。<pre><code>//从请求中获取cookies集合
$cookies = $req-&gt;cookies;
//从cookies集合中，获取某条cookie数据的值
$cookies-&gt;getValue('user');</code></pre>
</li>
</ol>
<h4>如何配置，确保对cookie数据中的value值进行加密</h4>
<p>在项目根目录下找到config文件夹，然后打开main-local.php，找到cookieValidationKey字段，即可查看加密字段。
Edit By <a href="http://mahua.jser.me">MaHua</a></p>
</body></html>