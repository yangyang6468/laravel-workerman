@foreach($infolist as $k=>$v)
    {{--右边聊天信息--}}
    @if(Auth::user()->id == $v->from_userid)
        <div class="clearfloat">
            <div class="author-name">
                <small class="chat-date">{{ date('Y-m-d H:i:s' , $v->create_time) }}</small>
            </div>
            <div class="right">
                <div class="chat-message">
                    @if($v->msg_type == 1)
                        {!!  $v->msg  !!}
                    @else
                        <img src="{{ showImage($v->msg , $v->flag , 1) }}" alt="">
                    @endif
                </div>
                <div class="chat-avatars"><img src="{{ showImage($v->headimage , $v->userflag) }}" alt="{{ $v->nickname }}" title="{{ $v->nickname }}"></div>
            </div>
        </div>
        {{--右边聊天信息--}}
    @else
        {{--左边聊天信息--}}
        <div class="clearfloat">
            <div class="author-name">
                <small class="chat-date">{{ date('Y-m-d H:i:s' , $v->create_time) }}</small>
            </div>
            <div class="left">
                <div class="chat-avatars"><img src="{{ showImage($v->headimage , $v->userflag) }}" alt="{{ $v->nickname }}" title="{{ $v->nickname }}"></div>
                <div class="chat-message">
                    @if($v->msg_type == 1)
                        {!!  $v->msg  !!}
                    @else
                        <img src="{{ showImage($v->msg , $v->flag , 1) }}" alt="">
                    @endif
                </div>
            </div>
        </div>

        {{--左边聊天信息--}}
    @endif


@endforeach
