@extends('layouts.app')

@section('content')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/font_Icon/iconfont.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/chat.css') }}?2018">
    <style>


    </style>

    <div class="container">

        <div class="chatBox" ref="chatBox">
            <div class="chatBox-head">
                <div class="chatBox-head-one">
                    {{ Auth::user()->nickname }}
                </div>
            </div>
            <div class="chatBox-info">
                <div class="chatBox-list" ref="chatBoxlist">

                    @foreach($user as $k=>$v)
                        <div class="chat-list-people" onclick="showInfo(this)" data-value="{{ $v['id'] }}">
                            <div><img src="{{ showImage($v['headimage'] , $v['flag']) }}" alt="{{ $v['nickname'] }}"></div>
                            <div class="chat-name">
                                <p>{{ $v['nickname'] }}</p>
                            </div>
                            <div class="message-num message-num-{{$v['id']}}"></div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>


        <div class="chatBox-infolist" style="" ref="chatBox">
            <div class="chatBox-head">

                <div class="chatBox-head-two" style="display: inline-block">
                    <div class="chat-people">
                        <div class="ChatInfoHead">
                            <img src="" alt="头像">
                        </div>
                        <div class="ChatInfoName">这是用户的名字，看看名字到底能有多长</div>
                    </div>
                    <div class="chat-close info-close">关闭</div>
                </div>
            </div>

            <div class="chatBox-info">

                <div class="chatBox-kuang" ref="chatBoxkuang" style="display: inline-block">
                    <div class="chatBox-content">
                        <input type="hidden" id="userid" value="">
                        <div class="chatBox-content-demo" id="chatBox-content-demo">

                            {{--聊天信息列表--}}

                        </div>
                    </div>
                    <div class="chatBox-send">
                        <div class="div-textarea" contenteditable="true"></div>
                        <div>
                            <button id="chat-biaoqing" class="btn-default-styles">
                                <i class="iconfont icon-biaoqing"></i>
                            </button>
                            <label id="chat-tuxiang" title="发送图片" for="inputImage" class="btn-default-styles">
                                <input type="file" onchange="selectImg(this)" accept="image/jpg,image/jpeg,image/png"
                                       name="file" id="inputImage" class="hidden">
                                <i class="iconfont icon-tuxiang"></i>
                            </label>
                            <button id="chat-fasong" class="btn-default-styles"><i class="iconfont icon-fasong"></i>
                            </button>
                        </div>
                        <div class="biaoqing-photo">
                            <ul>
                                <li><span class="emoji-picker-image" style="background-position: -9px -18px;"></span></li>
                                <li><span class="emoji-picker-image" style="background-position: -40px -18px;"></span></li>
                                <li><span class="emoji-picker-image" style="background-position: -71px -18px;"></span></li>
                                <li><span class="emoji-picker-image" style="background-position: -102px -18px;"></span></li>
                                <li><span class="emoji-picker-image" style="background-position: -133px -18px;"></span></li>
                                <li><span class="emoji-picker-image" style="background-position: -164px -18px;"></span></li>
                                <li><span class="emoji-picker-image" style="background-position: -9px -52px;"></span></li>
                                <li><span class="emoji-picker-image" style="background-position: -40px -52px;"></span></li>
                                <li><span class="emoji-picker-image" style="background-position: -71px -52px;"></span></li>
                                <li><span class="emoji-picker-image" style="background-position: -102px -52px;"></span></li>
                                <li><span class="emoji-picker-image" style="background-position: -133px -52px;"></span></li>
                                <li><span class="emoji-picker-image" style="background-position: -164px -52px;"></span></li>
                                <li><span class="emoji-picker-image" style="background-position: -9px -86px;"></span></li>
                                <li><span class="emoji-picker-image" style="background-position: -40px -86px;"></span></li>
                                <li><span class="emoji-picker-image" style="background-position: -71px -86px;"></span></li>
                                <li><span class="emoji-picker-image" style="background-position: -102px -86px;"></span></li>
                                <li><span class="emoji-picker-image" style="background-position: -133px -86px;"></span></li>
                                <li><span class="emoji-picker-image" style="background-position: -164px -86px;"></span></li>
                                <li><span class="emoji-picker-image" style="background-position: -9px -120px;"></span></li>
                                <li><span class="emoji-picker-image" style="background-position: -40px -120px;"></span></li>
                                <li><span class="emoji-picker-image" style="background-position: -71px -120px;"></span></li>
                                <li><span class="emoji-picker-image" style="background-position: -102px -120px;"></span></li>
                                <li><span class="emoji-picker-image" style="background-position: -133px -120px;"></span></li>
                                <li><span class="emoji-picker-image" style="background-position: -164px -120px;"></span></li>
                                <li><span class="emoji-picker-image" style="background-position: -9px -154px;"></span></li>
                                <li><span class="emoji-picker-image" style="background-position: -40px -154px;"></span></li>
                                <li><span class="emoji-picker-image" style="background-position: -71px -154px;"></span></li>
                                <li><span class="emoji-picker-image" style="background-position: -102px -154px;"></span></li>
                                <li><span class="emoji-picker-image" style="background-position: -133px -154px;"></span></li>
                                <li><span class="emoji-picker-image" style="background-position: -164px -154px;"></span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <script src="js/jquery.min.js"></script>
        <script>
            var user_id = '{{ Auth::user()->id }}';     //当前登录的用户id
            var user_nickname = '{{ Auth::user()->nickname }}' ; //当前登录的用户昵称
            var user_headimage = '{{ showImage(Auth::user()->headimage , Auth::user()->flag) }}' ; //当前登录的用户昵称
            var infoListUrl = "{{ url('infolist') }}";  //打开聊天页面请求数据的url
            var _token = '{{ csrf_token() }}';          //post请求提交防止csrf攻击的token


            //链接ws
            var ws = new WebSocket("ws:127.0.0.1:9011");

            //链接ws
            ws.onopen = function(){
                ws.send(JSON.stringify({'type':'login','uid':user_id}))
                console.log("已经打开了websocket连接，可以进行实时通信了");
            };

            //收到消息
            ws.onmessage = function(e) {

                var reposnse =  JSON.parse( e.data )
                console.log("接受到来自服务器端的数据:" + reposnse['msg']);
                console.log(reposnse);


                if( reposnse['type'] == 'chat'){
                    var userid = $("#userid").val();
                    if(userid == reposnse["userid"]){
                        //证明用户打开了对应的聊天框
                        if(reposnse['msg_type'] == 1){
                            var msg = reposnse['msg'];
                        }else if(reposnse['msg_type'] == 2){
                            var msg = "<img src=http://www.think.cn/upload/" + reposnse['msg'] + ">";
                        }

                        var html = "<div class=\"clearfloat\">" +
                                        "<div class=\"author-name\"><small class=\"chat-date\">"+ reposnse['create_time'] +" </small> </div> " +
                                        "<div class=\"left\"> " +
                                            "<div class=\"chat-avatars\"><img src="+ reposnse['headimage'] +" alt="+ reposnse['nickname'] +" title="+ reposnse['nickname'] +" /></div>" +
                                            "<div class=\"chat-message\"> " + msg + " </div> " +
                                        "</div> " +
                                    "</div>"

                        $(".chatBox-content-demo").append(html);

                        $(document).ready(function () {
                            $("#chatBox-content-demo").animate({scrollTop: $("#chatBox-content-demo")[0].scrollHeight}, 2000 , 'linear');
                        });

                    }else{
                        //用户关闭了聊天框
                        var ele = $(".message-num-"+reposnse["userid"]).parent();
                        $(".message-num-"+reposnse["userid"]).css("display" , "inline-block");
                        $(".chatBox-list").prepend(ele);
                    }
                }
            };

            //连接报错
            ws.onerror = function (e) {
                console.log("websocket连接错误" + e.error);
                ws.close(3000, "websocket连接异常导致的关闭");
            };

            //连接关闭
            ws.onclose = function (e) {
                console.log("websocket关闭连接:" + e.error);
            };



            screenFuc();
            function screenFuc() {
                var topHeight = $(".chatBox-head").innerHeight();//聊天头部高度
                //屏幕小于768px时候,布局change
                var winWidth = $(window).innerWidth();
                if (winWidth <= 768) {
                    var totalHeight = $(window).height(); //页面整体高度
                    $(".chatBox-info").css("height", totalHeight - topHeight);
                    var infoHeight = $(".chatBox-info").innerHeight();//聊天头部以下高度
                    //中间内容高度
                    $(".chatBox-content").css("height", infoHeight - 46);
                    $(".chatBox-content-demo").css("height", infoHeight - 46);

                    $(".chatBox-list").css("height", totalHeight - topHeight);
                    $(".chatBox-kuang").css("height", totalHeight - topHeight);
                    $(".div-textarea").css("width", winWidth - 106);
                } else {
                    $(".chatBox-info").css("height", 495);
                    $(".chatBox-content").css("height", 448);
                    $(".chatBox-content-demo").css("height", 448);
                    $(".chatBox-list").css("height", 495);
                    $(".chatBox-kuang").css("height", 495);
                    $(".div-textarea").css("width", '80%');
                }
            }
            (window.onresize = function () {
                screenFuc();
            })();
            //未读信息数量为空时


            //关闭聊天框
            $(".chat-close").click(function () {
                $("#userid").val('');
                $(".chatBox-infolist").hide(10);
            });


            //进聊天页面
            function showInfo(obj){
                //传名字
                $(".ChatInfoName").text($(obj).children(".chat-name").children("p").eq(0).html());
                //传头像
                $(".ChatInfoHead>img").attr("src", $(obj).children().eq(0).children("img").attr("src"));
                //获取当前用户的uid
                var to_uid = $(obj).data("value");

                //关闭红点
                $(".message-num-"+to_uid).hide();
                //设置用户的uid
                $("#userid").val(to_uid);

                $.post( infoListUrl , {"to_uid" : to_uid , "_token" : _token} , function (res) {
                    $("#chatBox-content-demo").html(res);

                    $(".chatBox-infolist").show();

                    $(document).ready(function () {
                        $("#chatBox-content-demo").animate({scrollTop: $("#chatBox-content-demo")[0].scrollHeight}, 2000 , 'linear');
                    });
                })

            }

            //发送信息
            $("#chat-fasong").click(function () {
                var textContent = $(".div-textarea").html().replace(/[\n\r]/g, '<br>');

                if(textContent.length == 0){
                    alert("请填写发送内容!!!");
                    return false;
                }

                var ws_data = {'type':'chat' , 'from_uid' : user_id , "to_uid" : $("#userid").val() , "msg":textContent , 'msgtype':1 , 'flag':2}
                ws.send(JSON.stringify(ws_data));

                var html = "<div class=\"clearfloat\">" +
                                "<div class=\"author-name\"><small class=\"chat-date\">"+ new Date().Format("yyyy-MM-dd HH:mm:ss") +"</small> </div> " +
                                "<div class=\"right\"> " +
                                    "<div class=\"chat-message\"> " + textContent + " </div> " +
                                    "<div class=\"chat-avatars\"><img src="+ user_headimage +" alt="+ user_nickname +" title= "+ user_nickname +" /></div> " +
                                "</div> " +
                            "</div>"

                $(".chatBox-content-demo").append(html);
                //发送后清空输入框
                $(".div-textarea").html("");
                //聊天框默认最底部
                $(document).ready(function () {
                    $("#chatBox-content-demo").animate({"scrollTop":$("#chatBox-content-demo")[0].scrollHeight} , 2000);
                });

            });

            //打开表情包
            $("#chat-biaoqing").click(function () {
                $(".biaoqing-photo").toggle();
            });

            // 发送表情
            $(".emoji-picker-image").each(function () {
                $(this).click(function () {
                    var bq = $(this).parent().html();

                    var ws_data = {'type':'chat' , 'from_uid' : user_id , "to_uid" : $("#userid").val() , "msg":bq , 'msgtype':1 , 'flag':2}
                    ws.send(JSON.stringify(ws_data));

                    var html = "<div class=\"clearfloat\">" +
                                    "<div class=\"author-name\"><small class=\"chat-date\">"+ new Date().Format("yyyy-MM-dd HH:mm:ss") +"</small> </div> " +
                                    "<div class=\"right\"> " +
                                        "<div class=\"chat-message\"> " + bq + " </div> " +
                                        "<div class=\"chat-avatars\"><img src="+ user_headimage +" alt="+ user_nickname +" title="+ user_nickname +"  /></div> " +
                                    "</div>" +
                                " </div>"

                    $(".chatBox-content-demo").append(html);
                    //发送后关闭表情框
                    $(".biaoqing-photo").toggle();
                    //聊天框默认最底部
                    $(document).ready(function () {
                        $("#chatBox-content-demo").animate({"scrollTop":$("#chatBox-content-demo")[0].scrollHeight} , 2000);
                    });
                })
            });

            //发送图片
            function selectImg(pic) {
                if (!pic.files || !pic.files[0]) {
                    return;
                }
                var reader = new FileReader();
                reader.onload = function (evt) {
                    var images = evt.target.result;
                    $(".chatBox-content-demo").append("<div class=\"clearfloat\">" +
                        "<div class=\"author-name\"><small class=\"chat-date\">"+ new Date().Format("yyyy-MM-dd HH:mm:ss") +"</small> </div> " +
                        "<div class=\"right\"> <div class=\"chat-message\"><img src=" + images + "></div> " +
                        "<div class=\"chat-avatars\"><img src=\"img/icon01.png\" alt=\"头像\" /></div> </div> </div>");
                    //聊天框默认最底部
                    $(document).ready(function () {
                        $("#chatBox-content-demo").scrollTop($("#chatBox-content-demo")[0].scrollHeight);
                    });
                };
                reader.readAsDataURL(pic.files[0]);

            }


            //设置当前时间
            Date.prototype.Format = function (fmt) {
                var o = {
                    "M+": this.getMonth() + 1, //月份
                    "d+": this.getDate(), //日
                    "H+": this.getHours(), //小时
                    "m+": this.getMinutes(), //分
                    "s+": this.getSeconds(), //秒
                    "q+": Math.floor((this.getMonth() + 3) / 3), //季度
                    "S": this.getMilliseconds() //毫秒
                };
                if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
                for (var k in o)
                    if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
                return fmt;
            }

        </script>


@endsection
