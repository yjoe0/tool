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

<div class="container">
<style type="text/css">
    #form-middle {
        position:relative;
        top:50%;
        transform:translateY(90%);
        width: 350px;
    }

</style>
<div class="center-block" id="form-middle">
    
  <form onsubmit="return submitform();" action="<?php echo U('index/login');?>" method="post">
  <h4 class="text-center"> 管理员登录</h4>
  <div class="form-group">
    <input type="text" class="form-control" name="name" id="name" placeholder="用户名" required="required" maxlength="20" minlength="4">
  </div>
  <div class="form-group">
    <input type="password" class="form-control" name="password" id="pwd" placeholder="密码" required="required" maxlength="20" minlength="4">
  </div>
  <div class="checkbox">
    <label>
      <input type="checkbox" name="remember"> 记住密码
    </label>
  </div>
  <input  type="submit" name="submit" class="btn btn-primary btn-lg btn-block"  value="登录" ">

</form>
<div>
    <span> <a href="#">忘记密码</a></span>
</div>
</div>


</div>

<script>
    function submitform(){
        var name,pwd;
        name = $('#name').val();
        pwd = $('#pwd').val();
        if (!name || ! pwd) {
            return false;
        } 
    };
</script>

</body>
</html>