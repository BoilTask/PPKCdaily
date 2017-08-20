# PPKCdaily
PIPAKCA每日一题系统，用于展示与管理每日一题。
功能特点：
1. 界面简洁美观，支持mathjax等显示。
2. 可搭配HUSTOJ与Vjudge获取AC状态。
3. 搭建简便，维护与管理容易。
4. 搭配参数可实现更多管理员操作。

建议配合OJ使用，可提前输入数天的题目以便游刃有余。

1. 在db_info.php里填写数据库用户名和密码
2. 在数据库daily中运行daily.sql创建daily表
3. 在后台admin.php中输入题目信息（需要管理员权限$_SESSION['administrator']）
4. 参数?time=[时间]可访问指定天的问题，只有管理员才能查看未来的题目
5. 参数?show=1可忽略时间查看题目的完整信息，可搭配time写作?time=[时间]&show=1

欢迎分享每日一题！