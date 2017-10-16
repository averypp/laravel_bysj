<div class="ibox-content">
    <div class="row">
        <?php echo $form['formSearch']; ?>

    </div>
    <div class="hr-line-dashed"></div>

    <div class="table-responsive">
        
        <table class="table table-striped dataTable">
            <thead>
            <?php echo $form['formHeader']; ?>

            </thead>
            <tbody>
            <?php echo $form['formBody']; ?>

            </tbody>
        </table>

    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                <?php echo $form['page']; ?>

            </div>
        </div>
    </div>
</div>