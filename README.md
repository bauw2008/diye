# diyp+骆驼IPTV管理后台


 apk 空壳

# ...

# 说明：
首先把源码这个压缩文件上传到服务器或主机解压
然后导入数据库(diypsq.sql)
更改数据库账号密码，2个php中的都要改！对接你自己的数据库

/zbhtsq/conn.php
/zbhtsq/config.php

直播管理后台登录地址            你的域名/zbhtsq/houtai123.php

直播后台的账号密码都是 admin  （密码直播后台改）


apk部分：
改最外成的类 crack
1、http://127.0.0.1/zbhtsq，替换成自己的域名(如果有端口就加上端口)，注意：后面不带斜杠
1、http://127.0.0.1/zbhtsq/images/，替换成自己的域名(如果有端口就加上端口)，注意：后面带有斜杠


后台部分:
1、epg需要填上epg的接口，比如http://diyp.112114.xyz/，后台不涉及epg绑定的网站。EPG自定义的连接，只要问号前的部分就行
2、台标部分，把png格式的台标统一放在zbhtsq\images里，每个png文件的名称就是频道名称。
