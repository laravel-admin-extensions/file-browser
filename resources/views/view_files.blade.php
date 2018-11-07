<style>
    .files > li {
        float: left;
        width: 150px;
        border: 1px solid #eee;
        margin-bottom: 10px;
        margin-right: 10px;
        position: relative;
    }

    .files>li>.file-select {
        position: absolute;
        top: -4px;
        left: -1px;
    }

    .file-icon {
        text-align: center;
        font-size: 65px;
        color: #666;
        display: block;
        height: 100px;
    }

    .file-info {
        text-align: center;
        padding: 10px;
        background: #f4f4f4;
    }

    .file-name {
        font-weight: bold;
        color: #666;
        display: block;
        overflow: hidden !important;
        white-space: nowrap !important;
        text-overflow: ellipsis !important;
    }

    .file-size {
        color: #999;
        font-size: 12px;
        display: block;
    }

    .files {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .file-icon.has-img {
        padding: 0;
    }

    .file-icon.has-img>img {
        max-width: 100%;
        height: auto;
        max-height: 92px;
    }

</style>
<div class="form-group {!! !$errors->has($label) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>
    <div class="col-sm-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @include('admin::form.error')

        <div class="controls">
            <a href="#file-browser-{!!$column!!}" class="mailbox-attachment-name" style="word-break:break-all;"
               data-toggle="modal" data-target="#file-browser-{!!$column!!}">
                <button class="btn btn-info" type="button">选择文件</button>
            </a>
        <!-- 模态框（Modal） -->

            <div class="modal fade" id="file-browser-{!!$column!!}" tabindex="-1" role="dialog"
                 aria-labelledby="file-browser-label" aria-hidden="true">
                <div class="modal-dialog" style="width: 90%;height: 100%;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="file-browser-label"></h4>
                        </div>
                        <div class="modal-body">
                            <ul class="files clearfix">

                                @if (empty($list))
                                    <li style="height: 200px;border: none;"></li>
                                @else
                                    @foreach($list as $item)
                                        @if(!$item['isDir'])
                                        <li>
                            <span class="file-select {{$column}}-pic">
                                <input type="checkbox" name="{!!$column!!}-pic" value="{{ $item['name'] }}"/>
                            </span>

                                            {!! $item['preview'] !!}

                                            <div class="file-info">
                                                <a href="{{ $item['link'] }}" class="file-name"
                                                   title="{{ $item['name'] }}">
                                                    {{ $item['icon'] }} {{ basename($item['name']) }}
                                                </a>
                                                <span class="file-size">
                                                {{ $item['size'] }}&nbsp;
                                                </span>
                                            </div>
                                        </li>
                                        @endif
                                    @endforeach
                                @endif
                            </ul>

                        </div>

                        <div class="modal-footer">
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal -->
            </div>
        </div>
        <input type="hidden" name="{{$name}}" id="{{$column}}" value="{{ json_encode(old($column, $value)) }}">
        <div id="preview-{{$column}}"></div>


        @include('admin::form.help-block')
    </div>
</div>

<script>
    (function() {
        window['{{$column}}'] = [];
        if ($('#{{$column}}').val() !== "null") {
            window[`{{$column}}`] = JSON.parse($('#{{$column}}').val());
            $('#preview-{{$column}}').html(preview(window[`{{$column}}`]));
            for (var n = 0; n < $('.{{$column}}-pic>input:checkbox').length; n++) {
                if (window[`{{$column}}`].indexOf($('.{{$column}}-pic>input:checkbox')[n].value) !== -1) {
                    $('.{{$column}}-pic>input:checkbox')[n].checked = true;
                }
            }
        }
        $('.{{$column}}-pic>input:checkbox').click(function () {

            if (this.checked == true) {
                var url = this.value;
                window[`{{$column}}`].push(this.value);
            } else {
                var index = window[`{{$column}}`].indexOf(this.value);
                if (index > -1) {
                    window[`{{$column}}`].splice(index, 1);
                }
            }
            $('#preview-{{$column}}').html(preview(window[`{{$column}}`]));
            $('#{{$column}}').val(JSON.stringify(window[`{{$column}}`]));
        });
        $('.modal-dialog').click(function () {
            $('#file-browser-{!!$column!!}').modal('hide');
        });
        $(".modal-content").click(function (event) {
            event.stopPropagation();
        });

        function preview(list) {
            var picItem = function (url) {
                return '<span class="file-icon has-img col-sm-2"><img src="{{$baseURL}}/' + url + '" alt="Attachment" \/><\/span>';
            }
            var picList = '';
            for (var i = 0; i < list.length; i++) {
                picList += picItem(list[i]);
            }
            return picList;
        }
    }())
</script>