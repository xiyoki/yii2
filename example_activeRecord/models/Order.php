<?php 
namespace frontend\models;
use yii\db\ActiveRecord;
use frontend\models\Customer;

//程序1：建立数据表与模数据模型之间的映射；通过数据模型操作数据表；
//活动记录类名称与数据表名称一致；
class Order extends ActiveRecord
{
	//关联查询：根据订单信息获取顾客信息
	public function getCustomer()
	{
		//订单和客户通过 Customer.id -> customerId 关联建立一对一关系
		return $this->hasOne(Customer::className(),['id'=>'customerId']);
	}

}