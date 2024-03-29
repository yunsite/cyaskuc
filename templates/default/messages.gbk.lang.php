<?php
// Message Pack for Cyask Version 1.1.0
// Created by Cyask

$lang = array
(
	'undefined_action' => '未定义操作，请返回。',
	'action_message' => '操作提示',
	'action_error'=>'非法操作指令！',
	'back_home' 	=> '返回首页',
	'go_back' 	=> '返回原处',
	'url_forward'	=> '浏览器将在3秒钟后跳转，如果您的浏览器不支持跳转，请点击此处',
	'url_error'	=> '无效访问，请返回。',
	
	'submit_seccode_invalid' => '您输入的验证码不正确，无法提交，请返回修改。',
	'submit_secqaa_invalid' => '验证问答回答错误，无法提交，请返回修改。',
	'submit_invalid' => '您的请求来路不正确或验证字串不符，无法提交。如果您安装了某种默认屏蔽来路信息的个人防火墙软件(如 Norton Internet Security)，请设置其不要禁止来路信息后再试。',
	
	'user_nologin'=>"抱歉，您还没有登录，不能进行此操作！你可以现在<a href=\"login.php?command=login&url={$url}\">登录",
	'ques_overdue'=>"抱歉，您有 {$overdue_count} 个过期问题，请先解决吧",
	
	'login_invalid' => '用户名无效',
	'login_password_error' =>'用户或密码错误',
	'login_strike' => '累计 5 次错误尝试，15 分钟内您将不能登录论坛。',
	'login_secques' => '您设置了安全提问，请输入正确的提问和回答后才能登录。',
	'login_url_error'	=> '无效访问，请返回。',
	'login_succeed' => "<b>{$cyask_user}</b> 欢迎您回来，现在将转入登录前页面。{$synlogin}",
	'login_succeed_admin' => "管理员：<b>{$cyask_user}</b>，欢迎您回来，现在将转入管理页面。",
	'login_succeed_noactive_member' => "<b>{$cyask_user}</b> : 欢迎您回来，您的帐号处于非激活状态，现在将转入控制面板。",
	'logout_succeed' => "您已退出{$site_name}，现在将以游客身份转入退出前页面。{$synlogout}",
	
	
	'regist_name_error'=>'抱歉！您输入的用户名含有非法字符，请使用英文字母或中文。',
	'regist_name_used'=>'抱歉！您输入的用户名已经被别人使用，请换用其他用户名吧。',
	'regist_email_error4'=>'Email 格式有误',
	'regist_email_error5'=>'Email 不允许注册',
	'regist_email_error6'=>'{$email} 已经被注册',
	'regist_error'	=> '抱歉！注册失败！您可以重新注册。',
	'regist_succeed'=> '新用户名注册成功！3秒钟后将自动登录。',
	'collect_succeed'=> '收藏内容采集完毕。',
	'upinfo_succeed'=>'修改个人档案完毕。',
	'uppw_succeed'=>'密码修改成功！',
	'uppw_error'=>'抱歉，密码修改失败！您输入的原密码错误！',
	'makefriends_succeed'=>'添加好友成功！',
	'makefriends_haved'=>'您已经加过这位朋友了！',
	'makefriends_self'=>'您不能加自己为友邻！',
	'delfriend_succeed'=>'删除友邻完毕！',
	'delcollect_succeed'=>'删除收藏完毕！',
	'delmessage_succeed'=>'删除信息完毕！',
	'sendmsg_succeed'=>'信息发送完毕。',
	'sendmsg_error'=>'信息发送失败',
	'username_error'=>'抱歉，没有这个用户，请确认您填写的是用户名，不能填写呢称！',
	'sendmsg_self'=>'抱歉，您不能给自己发送信息！',
	
	'ask_error'=>'抱歉，提问失败！',
	'class_error'=>'抱歉，无法知道问题类别，麻烦您重新选择正确分类！',
	'score_error'=>'抱歉，您的悬赏分不能高于总积分！',
	'answer_more'=>'抱歉，您对该问题的回答次数超过限制，多谢您的支持。',
	'answer_yourself'=>'抱歉，您不能回答自己的问题。',
	'response_more'=>'抱歉，您今天对该答案的回应次数超过限制，多谢您的支持。',
	'vote_more'=>'抱歉，您已经对该问题投过票了，多谢您的支持。',
	'collect_error'=> '抱歉，收藏内容采集失败。',
	'collect_url_error'	=> '抱歉，采集地址无法访问，可能地址有错误，请返回检查。',
	'title_null'=>'抱歉，您的问题是空的！',
	'answer_null'=>'抱歉，您的回答是空的！',
	'response_null'=>'抱歉，您的回应是空的！',
	
	'profile_username_toolong' => '对不起，您的用户名超过 18 个字符，请返回输入一个较短的用户名。',
	'profile_username_tooshort' => '对不起，您输入的用户名小于3个字符, 请返回输入一个较长的用户名。',
	'profile_passwd_notmatch' => '两次输入的密码不一致，请返回检查后重试。'
);
?>