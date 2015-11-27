# csf
用于智能项圈和App的socket长链接接收和推送消息

使用步骤：
1.application/config下面配置db、nsq、ssdb以及mongo
2.确认action--->controller转发的application/config/router
3.根据你的数据格式修改system/CoreServer的process方法
4.php index.php启动服务器
5.更多帮助请参考swoole wiki
