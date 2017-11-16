<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:68:"/data/wwwroot/thinkSwoole/public/../application/chat/view/login.html";i:1509883947;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <title>客服系统</title>
<!--     <link rel="stylesheet" href="css/weui.min.css">
    <link rel="stylesheet" href="css/jquery-weui.min.css"> -->
    <link rel="stylesheet" href="/static/css/login.css">
</head>
<body>
    <div class="content">
        <div class="center_box">
            <div class="form_cell">
                <div class="img_title">
                   <img src="/static/image/logo.png" alt="客服系统">
                   <div class="row">
                      <span>客服系统</span>
                      <span class="bottom_tag">
                       Custom Service System
                   </span>
               </div>

           </div>
           <div class="center_content">
            <div class="cell">
                <img src="/static/image/user.png" alt="用户名">
                <input type="text" placeholder="用户名" id="userName" onblur="checkValid(event)" data-reg="phone" data-tip="11位手机号">
            </div>
            <div class="cell">
                <img src="/static/image/key.png" alt="密码">
                <input type="password" placeholder="密码" id="passWord" onblur="checkValid(event)" data-reg="password" data-tip="长度在6~18之间，只能包含字符、数字和下划线(字母开头)">
            </div>
        </div>
        <div class='rows_cell'>
            <button class="primary col_1" disabled onclick="login()">登录</button>
        </div>
    </div>
    <div class="copy_right"></div>
</div>

</div>
</body>
<script src="/static/js/jquery-1.9.1.min.js"></script>
<script src="/static/layer/layer.js"></script>
<!-- <script src="js/jquery-weui.min.js"></script> -->
<script>
        //校验输入
        function checkValid(e){
            var reg = e.target.dataset.reg,
            val = e.target.value,
            tip = e.target.dataset.tip,
            dom = $(e.target),
            parent = dom.parents('.cell');
            parent.removeClass('error');
            parent.removeClass('success');console.log(tip);
            $('.msg_tip').remove();
            if(reg == 'phone'){
                reg = /^1[34578]\d{9}$/;
            }else if(reg == 'password'){
                reg = /^[a-zA-Z]\w{5,17}$/;
            }
            if(!reg.test(val)){
                console.log('格式错误！')
                dom.parents('.cell').addClass('error');
                if(tip){
                    var body = '<span class="msg_tip">'+tip+'</span>';
                    parent.append(body);
                }
            }else{
                console.log('格式正确。。。')
                dom.parents('.cell').addClass('success');
            }
            if($('.success').length>1){
                $('button[disabled]').attr('disabled',false);
            }
        }
        // $().ready(function(){

        // })
        function login(){
                var account = $('#userName').val();
                var passWord = $('#passWord').val();
                if(!$('.error').length){
                    // 登录
                    $.post('/chat/account/checklogin', {'account':account,'password':passWord}, function(json){
                        if (json.errcode==10001 || json.errcode==2) {
                            // layer.tips('账号或密码错误', '#userName');
                            layer.tips('账号或密码错误', '#passWord', {
                              tips: [1, '#42d79f'],
                              time: 3000
                            })
                        }
                        if (json.errcode==0) {
                            window.location.href = "/chat/msg/lists";
                        }
                    });
                }
         }
    </script>
    </html>