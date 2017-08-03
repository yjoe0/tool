$(document).ready(function(){
    subscribe();
    setTimeout(subscribe, 5000);
    $('#send').click(function(){
        var send_text = $('#send_text');
        var content = send_text.val();
        if (content == "") {
            new Toast({context:$('body'),message:'不能发送空白消息'}).show();
            return;
        }
        send(content);
        send_text.val('');
    });



    $("body").keydown(function() {
             if (event.keyCode == "13") {//keyCode=13是回车键
                 $('#send').click();
             }
    });


});

/** 
 * 模仿android里面的Toast效果，主要是用于在不打断程序正常执行的情况下显示提示数据 
 * @param config 
 * @return 
 */  
var Toast = function(config){  
    this.context = config.context==null?$('body'):config.context;//上下文  
    this.message = config.message;//显示内容  
    this.time = config.time==null?2000:config.time;//持续时间  
    this.left = config.left;//距容器左边的距离  
    this.top = config.top;//距容器上方的距离  
    this.init();  
}  
var msgEntity;  
Toast.prototype = {  
    //初始化显示的位置内容等  
    init : function(){  
        $("#toastMessage").remove();  
        //设置消息体  
        var msgDIV = new Array();  
        msgDIV.push('<div id="toastMessage" style="border-radius:18px;-moz-opacity:0.6;opacity:0.6;">');  
        msgDIV.push('<span>'+this.message+'</span>');  
        msgDIV.push('</div>');  
        msgEntity = $(msgDIV.join('')).appendTo(this.context);  
        //设置消息样式  
        var left = this.left == null ? this.context.width()/2-msgEntity.find('span').width()/2 : this.left;  
        var top = this.top == null ? '100px' : this.top;  
        msgEntity.css({position:'absolute',bottom:top,'z-index':'99',left:left,'background-color':'black',color:'white','font-size':'15px',padding:'10px',margin:'10px'});  
        msgEntity.hide();  
    },  
    //显示动画  
    show :function(){  
        msgEntity.fadeIn(this.time/2);  
        msgEntity.fadeOut(this.time/2);  
    }  
          
}  