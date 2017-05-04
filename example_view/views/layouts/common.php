<!-- 程序4：启用布局文件 -->

<!DOCTYPE html>
<html>
<head>
	<!-- 对应于应用程序7：在布局文件中获取视图文件定义的数据块内容 -->
	<!-- 代码说明：若数据块存在，那么显示数据块，若不存在，那么显示else从句中的内容 -->
	<?php if(isset($this->blocks['title'])): ?>
		<?= $this->blocks['title'] ?>
	<?php else: ?>
		<title>Document</title>
	<?php endif; ?>
</head>
<body>
	<!-- 对应于程序4：将content中的内容添加到布局文件 -->
	<?= $content; ?>
</body>
</html>