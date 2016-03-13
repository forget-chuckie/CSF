# csf
================

---

> a TCP SERVER FRAMEWORK base on swoole

csf是一个参考了Codeigniter后基于swoole而编写的tcp框架，她定义了一套数据流规范，使得开发tcp服务像Codeigniter一样轻松简单

---

###基于ACM的数据流

为了让开发tcp开发像一般的http服务一样简单，csf参考了轻量级MVC框架Codeigniter结合自身的需求规定了一套AACM（Analysis --> Action --> Controller --> Model)的流程，其具体含义为：

* Analysis: 采用类似中间件的方式进行数据的解析操作
* Action: 对数据进行简单的处理，并分发给一个或多个Controller进行处理
* Controller: 控制层，作为业务相关逻辑的处理，与MVC中的Controller概念一致
* Model: 模型层，与MVC中的Model概念一致


