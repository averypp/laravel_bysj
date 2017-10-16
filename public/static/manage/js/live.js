/**
 * Created by shamo on 4/27/2017.
 */
$('.message-box').animate({scrollTop: $('.message-box')[0].scrollHeight}, 800);
$('.comment-box').animate({scrollTop: $('.comment-box')[0].scrollHeight}, 800);

var socket_url = $('#socket_url').val();
var activityId = $('#activityId').val();
var serverUrl = $('#server_url').val();
var adminId = $('#adminId').val();
var token = $('#token').val();
var customerId = $('#customerId').val();
var ws = new WebSocket("ws://"+socket_url);

ws.onopen = function () {
    //连接成功，发送数据包
    var data = new Object();
    data.activityId = activityId;
    sendMsg(data);
    // listMsg("系统消息：建立连接成功");
};

ws.onclose = function () {
    ws = new WebSocket("ws://"+socket_url);
};

/**
 * 分析服务器推送信息
 *
 * msg.op : 1-图文新消息;2-评论新消息;3-图文信息撤回;4-评论信息撤回; 5-人数添加;
 * msg.activityId : 活动id
 * msg.messageId: 消息id
 */
ws.onmessage = function (e) {
    var data = JSON.parse(e.data);

    var op = data.op ? data.op : '0';
    switch (op) {
        case 1:
            //图文新消息
            getMessage(data.messageId);
            break;
        case 2:
            //评论新消息
            getComment(data.messageId);
            break;
        case 3:
            //图文信息撤回
            $('.message-id-'+data.messageId).remove();
            break;
        case 4:
            //评论信息撤回
            $('.comment-id-'+data.messageId).remove();
            break;
        case 5:
            //人数变化
            getCustomerNumInfo(activityId);
            break;
        default:
            if (data.msg){
                listMsg('系统消息 :' + data.msg);
            }
    }
    return;

};

ws.onerror = function () {
    var data = "系统消息 : 出错了,请刷新重试.";
    listMsg(data);
};
/**
 * websocket 发送数据
 * @param msg
 */
function sendMsg(msg) {
    var data = JSON.stringify(msg);
    ws.send(data);
}

//消息提示
function listMsg(data) {
    parent.layer.msg(data);
}

/**
 * 删除图文消息
 * @param activityId
 * @param id
 */
function removeMessage(id) {
    if(confirm("确定要删除该图文信息吗？")) {
        delMessage(id);
    }

}
/**
 * 删除评论消息
 * @param activityId
 * @param id
 */
function removeComment(id) {
    if(confirm("确定要删除该评论吗？")) {
        delComment(id);
    }
}

/**
 * 删除图文信息
 */
function delMessage(id){
    var url = serverUrl+'/Api/Activity/delActivityLiveGraphic';
    var customerId = $('#customerId').val();
    var postData = {
        activityId:activityId,
        token:token,
        customerId:customerId,
        messageId:id,
        sn:(Date.parse(new Date())/1000),
        pv:"web",
        v:"1.0.0"
    };
    $.post(url,postData,function (_response) {
        if (_response.status == 1){
            $('.message-id-'+id).remove();
        }else {
            listMsg(_response.msg);
        }
    });
}

/**
 * 删除评论信息
 */
function delComment(id) {
    var url = serverUrl+'/Api/Activity/delActivityLiveComment';
    var customerId = $('#customerId').val();
    var postData = {
        activityId:activityId,
        token:token,
        customerId:customerId,
        messageId:id,
        sn:(Date.parse(new Date())/1000),
        pv:"web",
        v:"1.0.0"
    };
    $.post(url,postData,function (_response) {
        if (_response.status == 1){
            $('.comment-id-'+id).remove();
        }else {
            listMsg(_response.msg);
        }
    });
}

/**
 * 获取图文信息
 */
function getMessage(id) {
    var url = serverUrl+'/Api/Activity/getActivityLiveGraphic';
    var postCustomerId = $('#customerId').val();
    var postData = {
        activityId:activityId,
        messageId:id,
        token:token,
        customerId:postCustomerId,
        sn:(Date.parse(new Date())/1000),
        pv:"web",
        v:"1.0.0"
    };
    $.post(url,postData,function (_response) {
        html = '';
        if (_response.status == 1){
            var myselfClass = '';
            if ((adminId==_response.data.adminId && _response.data.customerId==0) || (_response.data.adminId==0 && customerId==_response.data.customerId && customerId!=0)){
                myselfClass = 'myself';
            }
            html += '<div class="chat-message message-id-'+_response.data.id+'  '+myselfClass+'  ">'
            html += '<img class="message-avatar mymessage-avatar" src="' +(_response.data.headImg?_response.data.headImg:'/static/manage/img/user.png') +'" alt="">';
            html += '<div class="message mymessage">';
            html += '<a class="message-author mymessage-author" href="#"> '+_response.data.customerName+' &nbsp;<span>'+_response.data.mtime+'</span></a>';
            html += '<span class="message-content">';
            if (_response.data.content){
                html += _response.data.content;
            }
            if (_response.data.pic){
                html += '<img  src="'+_response.data.pic+'" alt="">';
            }
            if (_response.data.voice){
                html += '<audio src="'+_response.data.voice+'" controls="controls">抱歉！浏览器不支持audio标签</audio>';
            }
            if (_response.data.file){
                html += '<a href="'+_response.data.file.path+'">'+_response.data.file.name+'</a>';
            }

            html += '</span>';
            html += '<a href="javascript:void(0);" class="message-del" onclick="removeMessage('+_response.data.id+')">';
            html += '<i class="fa fa-close"></i></a></div></div>';
            $('.message-box').append(html);
            $('.message-box').animate({scrollTop: $('.message-box')[0].scrollHeight}, 300);
        }
    });

}

/**
 * 获取评论信息
 */
function getComment(id) {
    var url = serverUrl+'/Api/Activity/getActivityLiveComment';
    var customerId = $('#customerId').val();
    var postData = {
        activityId:activityId,
        token:token,
        customerId:customerId,
        messageId:id,
        sn:(Date.parse(new Date())/1000),
        pv:"web",
        v:"1.0.0"
    }
    $.post(url,postData,function (_response) {
        html = '';
        if (_response.status == 1){
            html += '<div class="social-comment my-social-comment comment-id-'+_response.data.id+'    ">'
            html += '<div class="pull-left"><img alt="image" class="comment-header-img" src="' +(_response.data.headImg?_response.data.headImg:'/static/manage/img/user.png') +'" alt=""></div>';
            html += '<div class="media-body my-media-body"><div><div href="#" class="comment-author"><a href="#" class="comment-author">'+_response.data.customerName+'<small class="text-muted mytext-muted">'+_response.data.mtime+'</small><a class="dropdown-toggle count-info op-user" data-toggle="dropdown" href="#" aria-expanded="true"><i class="fa fa-navicon"></i></a><ul class="dropdown-menu dropdown-messages my-dropdown-messages"><li><div class="text-center link-block">';
            html += '<a class="J_menuItem" href="javascript:;" onclick="removeComment('+_response.data.id+')">删除评论</a></div></li><li class="divider"></li><li><div class="text-center link-block">';
            html += '<a class="J_menuItem" href="javascript:;" onclick="setCustomerState('+_response.data.customerId+',1)">对该用户禁言</a></div></li></ul></a></div>';
            html += '<span class="comment">';
            if (_response.data.content){
                html += _response.data.content;
            }
            if (_response.data.pic){
                html += '<img  src="'+_response.data.pic+'" alt="">';
            }
            if (_response.data.voice){
                html += '<audio src="'+_response.data.voice+'" controls="controls">抱歉！浏览器不支持audio标签</audio>';
            }

            html += '</span></div></div></div>';
            $('.comment-box').append(html);
            $('.comment-box').animate({scrollTop: $('.comment-box')[0].scrollHeight}, 300);
        }
    });
}

/**
 * 发布图文信息
 */
function pushMessage(content,pic,voice,file) {
    content = content||"";
    pic = pic||"";
    voice = voice||"";
    file = file||{};
    var url = serverUrl+'/Api/Activity/pushActivityLiveGraphic';
    var customerId = $('#customerId').val();
    var postData = {
        activityId:activityId,
        token:token,
        customerId:customerId,
        content:content,
        pic:pic,
        voice:voice,
        file:file,
        sn:(Date.parse(new Date())/1000),
        pv:"web",
        v:"1.0.0"
    };
    $.post(url,postData,function (_response) {
        if (_response.status == 1){
            $('#message-box').val('');
            ue.setContent('');
        }else {
            listMsg(_response.msg);
        }
    });
}

/**
 * 发布评论信息
 */
function pushComment(content,pic,voice,file,upid) {
    content = content||"";
    pic = pic||"";
    voice = voice||"";
    file = file||{};
    upid = upid||0;
    var url = serverUrl+'/Api/Activity/activityLiveComment';
    var customerId = $('#customerId').val();
    var postData = {
        activityId:activityId,
        token:token,
        customerId:customerId,
        content:content,
        pic:pic,
        voice:voice,
        file:file,
        upid:upid,
        sn:(Date.parse(new Date())/1000),
        pv:"web",
        v:"1.0.0"
    };
    $.post(url,postData,function (_response) {
        if (_response.status == 1){
            $('#comment-box').val('');
        }else {
            listMsg(_response.msg);
        }
    });
}

/**
 * 禁言开关
 * @param _obj
 */
function gag(_obj) {
    var isGag = $(_obj).attr('data-value');
    var msg = isGag==1 ? '是否解除禁言？' : '是否全场禁言？';
    if(confirm(msg)) {
        var url = serverUrl+'/Api/Activity/setLiveState';
        var customerId = $('#customerId').val();
        var isGag = isGag==1 ? 0 : 1;
        var postData = {
            token:token,
            customerId:customerId,
            activityId:activityId,
            isGag:isGag,
            sn:(Date.parse(new Date())/1000),
            pv:"web",
            v:"1.0.0"
        };
        $.post(url,postData,function (_response) {
            if (_response.status == 1){
                $(_obj).attr('data-value',isGag);
                if(isGag == 1){
                    $(_obj).addClass('mybtn-a-hover');
                    $(_obj).attr('title','解除禁言');
                }else {
                    $(_obj).removeClass('mybtn-a-hover');
                    $(_obj).attr('title','全场禁言');
                }
            }else {
                listMsg(_response.msg);
            }
        });
    }
}

/**
 * 直播开关
 * @param _obj
 */
function living(_obj) {
    var isLiving = $(_obj).attr('data-value');
    var msg = isLiving==1 ? '是否关闭直播？' : '是否开始直播？';
    if(confirm(msg)) {
        var url = serverUrl+'/Api/Activity/setLiveState';
        var customerId = $('#customerId').val();
        var isLiving = isLiving==1 ? 0 : 1;
        var postData = {
            token:token,
            customerId:customerId,
            activityId:activityId,
            state:isLiving,
            sn:(Date.parse(new Date())/1000),
            pv:"web",
            v:"1.0.0"
        };
        $.post(url,postData,function (_response) {
            if (_response.status == 1){
                $(_obj).attr('data-value',isLiving);

                if(isLiving == 1){
                    $(_obj).addClass('mybtn-a-hover');
                    $(_obj).attr('title','关闭直播');
                }else {
                    $(_obj).removeClass('mybtn-a-hover');
                    $(_obj).attr('title','开始直播');
                }
            }else {
                listMsg(_response.msg);
            }
        });
    }
}

/**
 * 发送信息
 * @param _obj
 * @param type
 */
function push(_obj,type) {
    if(event.keyCode == 13){
        var content = $(_obj).val();
        if (type == 1){
            pushMessage(content);
        }else if (type == 2){
            pushComment(content);
        }
    }
}

function send() {
    var content = ue.getContent();
    pushMessage(content);
}
function setCustomerState(customerId,isGag) {
    if (customerId <= 0){
        listMsg('无法禁言管理');
    }else {
        var msg = '是禁言该用户？';
        if(confirm(msg)) {
            var url = serverUrl+'/Api/Activity/setCustomerState';
            var postData = {
                token:token,
                activityId:activityId,
                customerId:customerId,
                isGag:isGag,
                sn:(Date.parse(new Date())/1000),
                pv:"web",
                v:"1.0.0"
            };
            $.post(url,postData,function (_response) {
                if (_response.status == 1){
                    listMsg('已禁言');
                }else {
                    listMsg(_response.msg);
                }
            });
        }
    }

}

function getCustomerNumInfo(activityId){
    if (activityId > 0){
        var url = serverUrl+'/Api/Activity/getCustomerNumInfo';
        var postData = {
            activityId:activityId,
            sn:(Date.parse(new Date())/1000),
            pv:"web",
            v:"1.0.0"
        };
        $.post(url,postData,function (_response) {
            if (_response.status == 1){
                $('#liveWatchNum').html(_response.data.liveWatchNum);
                $('#onLineNum').html(_response.data.onLineNum);
            }
        });
    }
}
function file_select(index) {
    $('#file').attr('data-key',index);
    $('#file').trigger('click');
    return;
}

/**
 * 上传
 * @param _obj
 * @private
 */
function _upload(_obj) {
    var index = $('#file').attr('data-key');
    var url;
    if(index=='file'){
        url = serverUrl+'/Manage/Tools/upload';
    }else {
        url = serverUrl+'/Manage/Tools/uploadImg';
    }
    //通过表单对象创建 FormData
    var fd = new FormData(document.getElementById("upload_file"));
    //XMLHttpRequest 方式发送请求
    var xhr = new XMLHttpRequest();
    xhr.open("POST" ,url , true);
    xhr.send(fd);
    xhr.onload = function(e) {
        if (this.status == 200) {
            var response = $.parseJSON(this.responseText);
            if(response.status == 1){
                if (index == 'message_pic'){
                    //图文图片
                    pushMessage('',$.trim(response.data.path));
                }else if (index == 'comment_pic'){
                    //评论图片
                    pushComment('',$.trim(response.data.path));
                }else if (index == 'file'){
                    //文件
                    var file = {
                        name:response.data.name,
                        path:response.data.path
                    };
                    pushMessage('','','',file);
                }
            }else {
                listMsg(response.msg)
            }
        };
    };
}