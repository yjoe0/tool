    // 订阅
    function subscribe(topicName) {
        yunba.subscribe({'topic': topic}, 
            function (success, msg) {
                if (success) {
                    new Toast({context:$('body'),message:'你已进入频道：'+topicName}).show();
                    send(customid+'进入频道');
                    setTimeout(refresh, Math.random()*5000);
                } else {
                    $('#msg_list').append(msg);
                }
            });
    }

    function onMessage() {
        yunba.set_message_cb(function (data) {
            // console.log(data);
            data.msg = unescape(data.msg.split('').reverse().join(''));

            if (alias != '') {
                if (data.msg.indexOf('<span style="color:red;font-size:1px;">私</span>') > 0) {
                    $('#msg_list').append( data.msg );
                    scrollIntoView();
                    notify();
                }
            } else {
                if (data.msg != mySend.val() ) {
                    $('#msg_list').append( data.msg );
                    scrollIntoView();
                    notify();
                }
            }
        });
    }
    function notify() {
        if(window.Notification && Notification.permission !== "denied") {
            Notification.requestPermission(function(status) {    // 请求权限
                if(status === 'granted') {
                    // 弹出一个通知
                    var n = new Notification('新消息', {
                        body : '您好，收到新消息'
                        // icon : './images/test1.png'
                    });
                    // 两秒后关闭通知
                    setTimeout(function() {
                        n.close();
                    }, 2000);
                }
            });
        }
    }

    function refresh() {
        var content;
        if ( parseInt( Math.random()*5 ) > 0) {
            content = '<img onclick="full(this)" src="http://pic.meituba.com/uploads/allimg/2017/03/23/70_'+parseInt( Math.random()*5300 )+'.jpg">';
        } else {
            var strs = ['有人吗，怎么都在潜水','出来冒个泡啊','发个福利','有人开车嘛','求推荐车票','资源群里还不错啊真的','营养要跟不上了'];
            content = strs[parseInt( Math.random()*strs.length )];
        }
        send(content, true);
        var userId = parseInt( Math.random()*1000000000 );
        if (userId == customid) {
            userId = parseInt( Math.random()*1000000000 );
        }
        var content = '<p><div class="receive"><b onclick=privateChat('+userId+')>未知地区'+ userId+'</b>：<span class="receive_box">'+content+"</span></div>";
        $('#msg_list').append( content );
        scrollIntoView();
    }
    // 发送消息
    function send(content, auto) {
        if ( !auto & IsURL(content) ) {
            var images = ["jpg","png","gif","peg"];
            var video = ['mp4',' mov'];
            var ext = content.substr(-3);
            if (images.indexOf(ext) > -1) {
                content = '<img onclick="full(this)" src='+content+'>';
            } else if (video.indexOf(ext) > -1) {
                content = '<video onclick="full(this)" src="'+content+'" controls preload>您的浏览器不支持 video 标签。</video>';
            } else {
                content = '<a target="_blank" href="'+content+'">'+content.substr(0,43)+'...</a>';
            }
        }
        if (alias != '') {
            var sendContent = '<p><div class="receive"><b onclick=privateChat('+customid+')><span style="color:red;font-size:1px;">私</span>'+loc_info+ customid+'</b>：<span class="receive_box">'+content+"</span></div>";
        } else {
            var sendContent = '<p><div class="receive"><b onclick=privateChat('+customid+')>'+ loc_info+ customid+'</b>：<span class="receive_box">'+content+"</span></div>";
        }
        

        mySend.val(sendContent);
        sendContent = escape(sendContent).split('').reverse().join('');
        if (alias != '') {
            yunba.publish_to_alias({'alias': alias, 'msg': sendContent,qos:1}, function (success, msg) {
                if (success) {
                        $('#msg_list').append('<p><span class="trangle-right"></span><div class="my_send">'+content+'</div><br/></p><br/>');
                        scrollIntoView(); 
                    } else {
                        $('#msg_list').append(msg);
                }
            });
        } else {
            yunba.publish({'topic': topic, 'msg': sendContent,qos:1 },
                function (success, msg) {
                    if (auto) {
                        return;
                    }
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
        var urlRegExp= /(https?|ftp|file):\/\/[-A-Za-z0-9+&@#\/%?=~_|!:,.;]+[-A-Za-z0-9+&@#\/%=~_|]/;
        if(urlRegExp.test(url)){
            return true;
        }else{
            return false;
        }
    }
    function full(e) {
        $(e).toggleClass("full");
    }