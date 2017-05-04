<!-- 对应于程序4 -->
<div>this is about page</div>
<!-- 对应于程序6: 从视图向视图传递数据 -->
<div><?= $v_hello_str; ?></div>

<!-- 程序7：定义数据块 -->
<?php $this->beginBlock('title'); ?>
	<title><?= $view_title_arr['about'] ?></title>
<?php $this->endBlock(); ?>