# 描述 #
> Cyask是一个简单易用的问答系统，深受广告站长的喜爱。
随着Comsenz公司UCenter产品的推出，关于用UC整合网站各个系统的呼声越来越强。包括CMS,BBS,ASK,BLOG等常用的互联网产品，Cyask就是这其中的一个应用，我在Cyask3.0.3for dz 代码的基础上开发的。
# 功能 #
> 实现对Comsenz公司的UCenter1.0于Cyask的整合
> > 拟实现以下功能
  1. 同步登录
  1. 同步登出
  1. 同步删除用户
  1. 同步短信
  1. 同步用户信息
  1. FEED： A：发布提问，B：采纳提问
  1. 积分兑换
  1. 安装程序
  1. 旧数据迁移
# 新的开发 #
### 开发的想法框图 ###

![http://cyaskuc.googlecode.com/files/iask.png](http://cyaskuc.googlecode.com/files/iask.png)

# 测试站点 #

| **URL** | **说明** |
|:--------|:-------|
|http://cyask3.redflood.cn/ | CYASK 3 FOR UCERNTER 演示站点|
|http://bbs.redflood.cn/ | DZBBS6.1|
|http://uch.redflood.cn/ | UCHOME， 设置为 不与其他系统同步登录|
|http://phpcms.redflood.cn/ | PHPCMS2007 SP6 与UC的整合，后台管理部分使用官方的补丁，前台的整合摈弃了官方的补丁。|
|http://dedecms.redflood.cn/ |  DEDECMS 5.1版本与UC的整合，自动激活似乎还有点点问题，第一次访问时还需要手动再刷新一次|
|http://ask.redflood.cn/ |  这个是CYASK2的版本，已经不再更新|

# 成功案例 #
成功使用了cyask3 for uc 的站点目录
http://code.google.com/p/cyaskuc/wiki/SuccessSite
# BUG提交 #
http://code.google.com/p/cyaskuc/issues/entry