@if($data)
    <ul class="album-pictures">
        @foreach ($data as $value)
            <li class="swipeout>
                <div class="swipeout-content item-content">
                    <div class="item-media">
                        @if ($value['type'] == 1)
                            <img style="width:30px" src="{{ URL::asset('img/dir.jpeg') }}">
                        @elseif ($value['type'] == 2)
                            <img style="width:30px" src="{{ URL::asset('img/image.png') }}">
                        @endif
                    </div>
                    @if ($value['type'] == 2)
                        <div class="item-inner album-picture-inner">
                            <span class="album-picture"
                                  data-image="{{ env('IMAGE_BASE_PATH') . $value['path'] . '/' . $value['save_name'] }}"
                                  data-id="{{$value['id']}}">
                                {{$value['name']}}
                            </span>
                        </div>
                    @elseif ($value['type'] == 1)
                        <div class="item-inner">
                            <a href="{{ URL::asset("wechat/path/".$value['id']) }}"
                               class="link">{{ $value['name'] }}</a>
                        </div>
                    @endif
                </div>
                <div class="swipeout-actions-right">
                    <a href="#" class="action1 bg-red">编辑</a>
                    <a href="#" class="action2">删除</a>
                </div>
            </li>
        @endforeach
    </ul>
@endif