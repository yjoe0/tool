<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html lang="zh-cn">
<head>
    <title> 我的小园子</title>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-Control" content="no-transform " /> 
    <link rel="stylesheet" type="text/css" href="/Public/bootstrap.min.css" />
    <script type="text/javascript" src="/Public/jquery.min.js"></script>
    <script type="text/javascript" src="/Public/bootstrap.min.js"></script>
</head>
<body>
<style>
    body {font-family: "微软雅黑", Arial, Helvetica, sans-serif; font-size: 14px; color: #000; } .left-menu {background-color: #555; height: 100%; } .left-menu ul li a {color: #a0a0a0; padding-left: 15px; } .left-menu ul li a:hover {color: #fff; background-color: #a0a0a0; } .text-left h4 {color: #fff; } </style>

 <div class="container-fluid" id="sitebar">
    <div class="row">
    <!-- 左侧菜单 -->
            <div class="col-xs-4 col-sm-3 col-md-2 left-menu">
                <span class="text-left"><h4>你好,欢迎光临!!!</h4></span>
                <ul class="nav nav-pills nav-stacked">
                    <li><a href="<?php echo U('/tieba');?>">贴吧签到</a></li>
                    <li><a href="<?php echo U('/WebMonitor');?>">网页监控</a></li>
                </ul>
            </div>
        <!-- 左侧菜单结束 -->

<!-- 右侧内容区域 -->
    <div class="col-xs-8 col-sm-4 col-md-5" style="height:100%;border-right: 1px solid #a0a0a0;">
        <h3>贴吧签到:</h3>
        <form method="post" action="<?php echo U('/tieba/addUser');?>">
        <div class="form-group">
            <textarea class="form-control" name="tbcookie" rows="3" placeholder="输入贴吧cookie代码" maxlength="999" required></textarea>
        </div>
        <div class="form-group">
            <input type="email" name="usermail" class="form-control" placeholder="请输入验证邮箱" required>
        </div>
          <input type="submit" class="btn btn-default" value="提交">
        </form>
    </div>
    <!-- 右侧内容结束 -->
    <!-- 最右侧说明 -->
    <div class="col-md-5">
        <h3>百度贴吧cookie获取:</h3>
        <span>
            1.打开百度贴吧主页：<a href="https://tieba.baidu.com/index.html" target="_blank">百度贴吧</a><br>
            2.打开浏览器调试模式，按F12，选Console，在下方输入: <b> document.cookie</b><br>
            3.复制上面返回的cookie信息，填入正确的邮箱地址。<br>
            4.提交。进入邮箱点击激活链接即可。<br>
        </span>
    </div>
    <!-- 说明结束 -->
</div>




</body>
</html>