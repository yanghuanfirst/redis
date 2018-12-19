<?php
include_once 'db.php';
// $store = 5;//库存数
$goods_id = 1;
$redis = new Redis();
$result = $redis->connect('127.0.0.1',6379);
$count = $redis->lPop('goods_store:'.$goods_id);
if(!$count)
{
    exit('卖完了');
}
//数据库操作
$db ->beginTransaction();
$smtp = $db->prepare("update goods set goods_store = goods_store-1 where id=:id");
$res = $smtp->execute(array('id'=>$goods_id));
$smtp1 = $db->prepare("insert into `order`(user_id,goods_id,goods_num)values(1,$goods_id,1)");
$res1 = $smtp1->execute();
if($res && $res1)
{
    $db->commit();
}else{
    $redis->lPush('goods_store:'.$goods_id,1);
    $db->rollBack();
}
