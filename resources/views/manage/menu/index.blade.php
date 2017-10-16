@extends('manage.comm.layouts')

@section('my_css')
    <link href="{{ asset('static/manage/css/plugins/dataTables/dataTables.bootstrap.css') }}" rel="stylesheet">
@stop
@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">

        <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>菜单列表</h5>
                    @if(in_array('add',$option))
                        <div class="ibox-tools">
                            <a href="{{ url('Manage/Menu/add') }}" class="collapse-link btn btn-info my-inline">
                                <i class="fa fa-plus"></i>&nbsp;新建菜单
                            </a>
                        </div>
                    @endif
                </div>
                <div class="ibox-content">
                    <div class="row">

                        <div class="col-sm-3">
                            <form method="get" action="">
                                <div class="input-group">
                                    {{ csrf_field() }}
                                    <input type="text" placeholder="请输入关键词" name="keyword" value="{{ isset($search['keyword']) ? $search['keyword'] : '' }}" class="input-sm form-control"> <span class="input-group-btn">
                                        <button type="submit" class="btn btn-sm btn-primary"> 搜索</button> </span>

                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>显示名称</th>
                                    <th>菜单</th>
                                    <th>链接</th>
                                    <th>上级菜单</th>
                                    <th>优先级</th>
                                    <th>状态</th>
                                    <th>备注</th>
                                    <th>修改时间</th>
                                    <th>创建时间</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($menus as $v)
                                <tr class="text-c">
                                    <td>{{ $v->name }}</td>
                                    <td>{{ $v->menu }}</td>
                                    <td>{{ $v->url }}</td>
                                    <td>{{ isset($menusName[$v->pid]) ? $menusName[$v->pid] : '不存在' }}</td>
                                    <td>{{ $v->priority }}</td>
                                    <td><span class="is_lock">{{ $v->is_lock==0 ? '启用' : '锁定' }}</span></td>
                                    <td>{!! \App\Tools\Html::breviary($v->remark) !!}</td>
                                    <td>{{ date('Y-m-d H:i:s',$v->updated_at) }}</td>
                                    <td>{{ date('Y-m-d H:i:s',$v->created_at) }}</td>

                                    <td class="td-manage">
                                        {!! App\Tools\Html::operation('Menu', $v, $option) !!}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>


                    </div>


                    <div class="row">
                        <div class="col-sm-6">
                            <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                {{ $menus->render() }}
                            </div>
                        </div>
                    </div>




                </div>

            </div>
        </div>

    </div>
    </div>
@stop

@section('my_js')
    <!-- Peity -->
    <script src="{{ asset('static/manage/js/plugins/peity/jquery.peity.min.js') }}"></script>

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
    </script>
@stop