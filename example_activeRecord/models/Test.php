<?php 
namespace frontend\models;
use yii\db\ActiveRecord;

//类名与数据表名称一致；
class Test extends ActiveRecord
{
	//验证将添加到数据表的字段的合法性
	public function rules()
	{
		return [
			['id','integer'], //id字段为整型
			['title','string','length'=>[1,20]] //title字段为字符串，长度为1-20字符；
		];
	}
}