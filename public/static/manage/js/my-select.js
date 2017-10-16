/**
 * Created by shamo on 12/22/2016.
 */

function mySel(_obj,op,typeId) {
    var _html = '';
    var keyword = $(_obj).val();
    if(typeId == 1 || typeId == 2 || typeId == 3){
        $.post('/Manage/Tools/'+op,{typeId:typeId,keyword:keyword}, function (_response) {
            if (_response.status == 1){
                var data = _response.data.list;
                var _html = '';
                if (data){
                    _html += '<ul>';
                    for (var i in data){
                        _html += '<li data-key="'+data[i].key+'" class="mySelLi">'+data[i].value+'</li>'
                    }
                    _html += '</ul>';

                    $(_obj).next('.selDiv').html(_html);
                }


            }else {
                parent.layer.msg(_response.msg);
            }
        });
    }
}

$(function () {
    $('.ratio').on('click','.divBlur li',function () {
        var _obj = $(this);
        _obj.parent().parent().prev().val(_obj.html());
        _obj.parent().parent().prev().prev().val(_obj.attr('data-key'));
        _obj.parent().remove();
    });

//
    $('.ratio').on('blur','.divBlur',function () {
        $("body").click(function(event) {
            var _this = event.target;
            var _class = $(_this).attr("class");
            if(_class!='mySelLi'){
                $('.mySelLi').parent().remove();
            }
        });

    });

});