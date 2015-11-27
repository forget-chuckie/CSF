# csf
用于智能项圈和App的socket长链接接收和推送消息

使用步骤：<br/>
1.application/config下面配置db、nsq、ssdb以及mongo<br/>
2.确认action--->controller转发的application/config/router<br/>
3.根据你的数据格式修改system/CoreServer的process方法<br/>
4.php index.php启动服务器<br/>
5.更多帮助请参考swoole wiki<br/>
