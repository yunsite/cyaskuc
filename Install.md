#安装.

# 简介 #

本文档用于说明如何安装程序.


# 正文 #

安装前，请确认你的系统是否支持有一下支持：
  * php4.3.5及其以上
  * mysql 3.2.3 以上，以及数据库账号是否支持create,update,select,insert等操作
  * 请务必让系统/config.inc.php 以及/askdata/目录及其子目录，/attachments/目录及其子目录可写

安装过程
  1. 首先要确认您已经成功的安装了UCenter 1.0版本，并记住您的 UCENTER的安装地址和创建人密码
  1. **将你使用的UCENTER对应版本的client复制到根目录下并命名为uc\_client**
  1. 将Cyask所有文件上传到服务器空间后，输入安装地址进行自动安装，例如：http://www.xxx.com/install.php  在安装向导的帮助下，您只需要填写相关设置即可完成安装。
软件安装完毕后，建议您即时删除 install.php文件和install目录，防止您的数据被再次覆盖和损坏。