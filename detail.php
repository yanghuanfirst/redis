<?php
header("Content-Type: text/html;charset=utf-8");
include_once 'db.php';
// $store = 5;//库存数
$redis = new Redis();
$result = $redis->connect('127.0.0.1',6379);
// $res = $redis->lLen('goods_store');
// echo $res;
// $count = $store - $res;
// for($i = 0;$i < $count;$i++)
// {
//     $redis->lPush('goods_store',1);
// }
// echo $redis->lLen('goods_store');
// include_once 'index.html';
$smtp = $db->prepare("select * from goods where id = :id");
$goods_id = 1;
$smtp->execute(array('id'=>$goods_id));
$res = $smtp->fetch();
$store = $res['goods_store'];
if(!$store)
{
    exit('已经卖完了');
}
//把库存存入redis 队列
$len = $redis->lLen('goods_store:'.$goods_id);
if(!$len)
{
    for($i = 0;$i<$store;$i++)
    {
        $redis->lPush('goods_store:'.$goods_id,1);
    }
}
//print_r($redis->lrange('goods_store:'.$goods_id,0,-1));
















