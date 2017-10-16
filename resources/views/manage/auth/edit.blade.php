@extends('manage.comm.layouts')

@section('my_css')
    <link href="{{ asset('static/manage/css/plugins/iCheck/custom.css') }}" rel="stylesheet">
@stop

@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>编辑部门权限</h5>
                    </div>
                    <div class="ibox-content">

                        <form method="post" class="form-horizontal" action="">

                            @include('manage.comm.message')
                            @include('manage.comm._formError')
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="col-sm-2 control-label">部门名称</label>

                                <div class="col-sm-10">
                                    <input type="text"
                                           value="{{ $group->group_name }}"
                                           readonly="readonly"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">权限
                                </label>

                                <div class="col-sm-8">
                                    <div class="ibox-content">

                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th>模块</th>
                                                    @foreach($authArr as $authVal)
                                                    <th>{{ $authArrName[$authVal] }}</th>
                                                    @endforeach
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($menuInfo as $val)
                                                    <tr class="text-c" style="background-color: #f9f9f9;">
                                                        <td>
                                                            {{ $val['name'] }}
                                                            <a href="javascript:void(0)" onclick="selectAll({{ $val['id'] }})">全选</a>
                                                            <a href="javascript:void(0)" onclick="invertSelect({{ $val['id'] }})">反选</a>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    @if(count($val['down']))
                                                        @foreach($val['down'] as $v)
                                                            <tr class="text-c checkbox-{{ $v['pid'] }}">
                                                                <td>
                                                                    &nbsp;&nbsp;|-{{ $v['name'] }}
                                                                    <a href="javascript:void(0)" onclick="selectAll({{ $v['id'] }})">全选</a>
                                                                    <a href="javascript:void(0)" onclick="invertSelect({{ $v['id'] }})">反选</a>
                                                                </td>
                                                                @foreach($authArr as $authVal)
                                                                <td>
                                                                    <div class="radio i-checks checkbox-{{ $v['id'] }}">
                                                                        <input type="checkbox" value="1" name="Auth[{{ $v['module'] }}][{{ $authVal }}]"
                                                                            {{ ( (isset(old('Auth')[$v['module']][$authVal])&&old('Auth')[$v['module']][$authVal]==1) || (isset($authData[$v['module']][$authVal])&&$authData[$v['module']][$authVal]==1) ) ? 'checked' : '' }}
                                                                        >


                                                                    </div>
                                                                </td>
                                                                @endforeach
                                                            </tr>
                                                        @endforeach
                                                    @endif

                                                @endforeach
                                                </tbody>
                                            </table>


                                        </div>

                                    </div>


                                </div>
                            </div>


                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <button class="btn btn-primary" type="submit">保存内容</button>
                                    <a class="btn btn-white" href="{{ url('/Manage/Auth/index') }}">取消</a>
                                </div>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>

    </div>
@stop

@section('my_js')
    <!-- 自定义js -->
    <script src="{{ asset('static/manage/js/content.js?v=1.0.0') }}"></script>

    <!-- iCheck -->
    <script src="{{ asset('static/manage/js/plugins/iCheck/icheck.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        });

        function selectAll(id){
            $(".checkbox-"+id).find("input[type='checkbox']").each( function() {
                $(this).prop('checked', true);
                $(this).parents('.icheckbox_square-green').addClass('checked');
            });
        }

        function invertSelect(id){
            $(".checkbox-"+id).find("input[type='checkbox']").each( function() {
                if($(this).prop('checked')) {
                    $(this).prop('checked', false);
                    $(this).parents('.icheckbox_square-green').removeClass('checked');
                } else {
                    $(this).prop('checked', true);
                    $(this).parents('.icheckbox_square-green').addClass('checked');
                }
            });
        }
    </script>

@stop