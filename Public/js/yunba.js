    // 订阅
    function subscribe(topicName) {
        yunba.subscribe({'topic': topic}, 
            function (success, msg) {
                if (success) {
                    new Toast({context:$('body'),message:'你已进入频道：'+topicName}).show();
                    send(customid+'进入频道');
                } else {
                    $('#msg_list').append(msg);
                }
            });
    }

    function onMessage() {
        yunba.set_message_cb(function (data) {
            if (alias != '') {
                if (data.msg.indexOf('<span style="color:red;font-size:1px;">私</span>') > 0) {
                    $('#msg_list').append( data.msg );
                    scrollIntoView();
                }
            } else {
                if (data.msg != mySend.val() ) {
                    $('#msg_list').append( data.msg );
                    scrollIntoView();
                }
            }
        });
    }

    // 发送消息
    function send(content) {
        if ( IsURL(content) ) {
            var images = ["jpg","png","gif","jpeg"];
            var video = ['mp4',' mov'];
            var ext = content.substr(-3);
            if (images.indexOf(ext) > -1) {
                content = '<img src='+content+'>';
            } else if (video.indexOf(ext) > -1) {
                content = '<video src="'+content+'" controls preload>您的浏览器不支持 video 标签。</video>';
            } else {
                content = '<a target="_blank" href="'+content+'">'+content.substr(0,43)+'...</a>';
            }
        }
        if (alias != '') {
            var sendContent = '<p><div class="receive"><b onclick=privateChat('+customid+')><span style="color:red;font-size:1px;">私</span>'+customid+'</b>：<span class="receive_box">'+content+"</span></div>";
        } else {
            var sendContent = '<p><div class="receive"><b onclick=privateChat('+customid+')>'+customid+'</b>：<span class="receive_box">'+content+"</span></div>";
        }
        

        mySend.val(sendContent);
        if (alias != '') {
            yunba.publish_to_alias({'alias': alias, 'msg': sendContent}, function (success, msg) {
                if (success) {
                        $('#msg_list').append('<p><span class="trangle-right"></span><div class="my_send">'+content+'</div><br/></p><br/>');
                        scrollIntoView(); 
                    } else {
                        $('#msg_list').append(msg);
                }
            });
        } else {
            yunba.publish({'topic': topic, 'msg': sendContent },
                function (success, msg) {
                    if (success) {
                        $('#msg_list').append('<p><span class="trangle-right"></span><div class="my_send">'+content+'</div><br/></p><br/>');
                        scrollIntoView(); 
                    } else {
                        $('#msg_list').append(msg);
                    }
            });
        }
    }

    function scrollIntoView() {
        C.scrollTop( M.height() );

    }

    function set_alias() {
        yunba.set_alias({'alias': customid}, function (data) {
            new Toast({context:$('body'),message:'设定成功'}).show();
        });
    }

    function IsURL(url){
        var urlRegExp=/^((https|http|ftp|rtsp|mms)?:\/\/)+[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/;
        if(urlRegExp.test(url)){
            return true;
        }else{
            return false;
        }
    }