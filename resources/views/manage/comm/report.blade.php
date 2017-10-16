<div class="ibox-content">
    <div class="row">
        {!! $form['formSearch'] !!}
    </div>
    <div class="hr-line-dashed"></div>

    <div class="table-responsive">
        {{--table-striped table-bordered table-hover  dataTable" id="editable" aria-describedby="editable_info"--}}
        <table class="table table-striped dataTable">
            <thead>
            {!! $form['formHeader'] !!}
            </thead>
            <tbody>
            {!! $form['formBody'] !!}
            </tbody>
        </table>

    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                {!! $form['page'] !!}
            </div>
        </div>
    </div>
</div>