<?php 
namespace frontend\models;
use yii\db\ActiveRecord;
use frontend\models\Order;

//程序1：建立数据表与模数据模型之间的映射；通过数据模型操作数据表；
//活动记录类名称与数据表名称一致；
class Customer extends ActiveRecord
{
	//关联查询：根据顾客信息获取订单信息
	public function getOrders()
	{
		// 客户和订单通过 Order.customerId -> id 关联建立一对多关系
		return $this->hasMany(Order::className(),['customerId'=>'id']);
	}
}