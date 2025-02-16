<!DOCTYPE html>
<html>
<head>
<!-- TABLES CSS CODE -->
<?php include"comman/code_css_datatable.php"; ?>
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo $theme_link; ?>plugins/datepicker/datepicker3.css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <!-- Left side column. contains the logo and sidebar -->

  <?php include"sidebar.php"; ?>

  <?php
      /*Total Invoices*/
      $total_invoice=$this->db->query("SELECT COUNT(*) as total FROM db_sales")->row()->total;
      /*Total Invoices Total*/
      $sal_total=$this->db->query("SELECT COALESCE(sum(grand_total),0) AS tot_sal_grand_total FROM db_sales")->row()->tot_sal_grand_total;

      /*PAID AMOUNT*/
      $tot_received_amt=$this->db->select("COALESCE(SUM(paid_amount),0) AS paid_amount")->from("db_sales")->get()->row()->paid_amount;

      $sales_due_total=$this->db->query("SELECT COALESCE(SUM(sales_due),0) AS sales_due FROM db_customers")->row()->sales_due;
      //$sales_due_total = $sal_total - $sal_return_total;

  ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?=$page_title;?>
        <small>View/Search Sold Items</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $base_url; ?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?=$page_title;?></li>
      </ol>
    </section>

    <div class="pay_now_modal">
    </div>
    <div class="view_payments_modal">
    </div>

    <!-- Main content -->
    <?= form_open('#', array('class' => '', 'id' => 'table_form')); ?>
    <input type="hidden" id='base_url' value="<?=$base_url;?>">
    <section class="content">
      <!-- Small boxes (Stat box) -->
	  <?php if(!$customerId){?>
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?= $total_invoice;?></h3>

              <p><?= $this->lang->line('total_invoices'); ?></p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="<?= base_url('reports/sales') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?= $CI->currency($sal_total,$with_comma=true);?></h3>
              <p><?= $this->lang->line('total_invoices_amount'); ?></p>
            </div>
            <div class="icon">
              <i class="fa fa-plus-square-o"></i>
            </div>
            <a href="<?= base_url('reports/sales') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?= $CI->currency($tot_received_amt,$with_comma=true);?></h3>
             <p><?= $this->lang->line('total_received_amount'); ?></p>
            </div>
            <div class="icon">
              <i class="fa fa-undo"></i>
            </div>
            <a href="<?= base_url('reports/sales_return') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?= $CI->currency($sales_due_total,$with_comma=true);?></h3>
              <p><?= $this->lang->line('total_sales_due'); ?></p>
            </div>
            <div class="icon">
              <i class="fa fa-hourglass-2 "></i>
            </div>
            <a href="<?= base_url('reports/profit_loss') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
	  <?php }?>
      <!-- /.row -->
      <div class="row">
        <!-- ********** ALERT MESSAGE START******* -->
        <?php include"comman/code_flashdata.php"; ?>
        <!-- ********** ALERT MESSAGE END******* -->
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><?=$page_title;?></h3>
              <?php if($CI->permissions('sales_add')) { ?>
              <div class="box-tools">
                <!--<a class="btn btn-block btn-info" href="<?php echo $base_url; ?>sales/add">
                <i class="fa fa-plus"></i> <?= $this->lang->line('new_sales'); ?></a>-->
              </div>
              <?php } ?>
            </div>
            <div class="col-md-4" style="margin-bottom:10px">
                <label for="warehouse_id"><?= $this->lang->line('warehouse'); ?><span class="text-danger">*</span></label> 
                <select class="form-control select2" id="warehouse_id" name="warehouse_id"  style="width: 100%;" onkeyup="shift_cursor(event,'mobile')">
                              <?php
                                  $query1 ="select * from db_warehouse where status=1";
                                  $q1=$this->db->query($query1);
                                  if($q1->num_rows($q1)>0)
                                    { 
                                        echo "<option value=''>-Select Warehouse-</option>";
                                        foreach($q1->result() as $res1)
                                    {
                                        
                                        echo "<option $selected  value='".$res1->id."'>".$res1->warehouse_name ."</option>";
                                    }
                                    }
                                    else
                                    {
                                        ?>
                              <option value="">No Records Found</option>
                              <?php
                                  }
                                  ?>
                            </select>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example2" class="table table-bordered table-striped" width="100%">
                <thead class="bg-primary ">
                <tr>
                  <th class="text-center">
                    <input type="checkbox" class="group_check checkbox" >
                  </th>
                  <th><?= $this->lang->line('sales_date'); ?></th>
                  <th><?= $this->lang->line('sales_code'); ?></th>
                  <th><?= $this->lang->line('sales_status'); ?></th>
                  <th><?= $this->lang->line('reference_no'); ?></th>
                  <th><?= $this->lang->line('customer_name'); ?></th>
                  <th>Warehouse</th>
                  <th><?= $this->lang->line('total'); ?></th>
                  <th><?= $this->lang->line('paid_amount'); ?></th>
                  <th><?= $this->lang->line('due'); ?></th>
                  <th><?= $this->lang->line('payment_status'); ?></th>
                  <th><?= $this->lang->line('created_by'); ?></th>
                  <th><?= $this->lang->line('action'); ?></th>
                </tr>
                </thead>
                <tbody>

                </tbody>
               <tfoot>
                  <tr class="bg-gray">
                      <th colspan="7" style="text-align:right">Total</th><!-- 6 -->
                      <th></th><!-- 7 -->
                      <th></th><!-- 8 -->
                      <th></th><!-- 8 -->
                      <th></th><!-- 7 -->
                      <th></th><!-- 8 -->
                      <th></th><!-- 8 -->
                  </tr>
              </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
    <?= form_close();?>
  </div>
  <!-- /.content-wrapper -->
  <?php include"footer.php"; ?>
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- SOUND CODE -->
<?php include"comman/code_js_sound.php"; ?>
<!-- TABLES CODE -->
<?php include"comman/code_js_datatable.php"; ?>
<!-- bootstrap datepicker -->
<script src="<?php echo $theme_link; ?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script type="text/javascript">
  //Date picker
    $('.datepicker').datepicker({
		autoclose: true,
		format: 'dd-mm-yyyy',
		todayHighlight: true
    });
</script>
<script type="text/javascript">
$(document).ready(function() {
    //datatables
   var table = $('#example2').DataTable({

      /* FOR EXPORT BUTTONS START*/
  dom:'<"row margin-bottom-12"<"col-sm-12"<"pull-left"l><"pull-right"fr><"pull-right margin-left-10 "B>>>tip',
 /* dom:'<"row"<"col-sm-12"<"pull-left"B><"pull-right">>> <"row margin-bottom-12"<"col-sm-12"<"pull-left"l><"pull-right"fr>>>tip',*/
      buttons: {
        buttons: [
            {
                className: 'btn bg-red color-palette btn-flat hidden delete_btn pull-left',
                text: 'Delete',
                action: function ( e, dt, node, config ) {
                    multi_delete();
                }
            },
            { extend: 'copy', className: 'btn bg-teal color-palette btn-flat',exportOptions: { columns: [2,3,4,5,6,7,8,9,10,11]} },
            { extend: 'excel', className: 'btn bg-teal color-palette btn-flat',exportOptions: { columns: [2,3,4,5,6,7,8,9,10,11]} },
            { extend: 'pdf', className: 'btn bg-teal color-palette btn-flat',exportOptions: { columns: [2,3,4,5,6,7,8,9,10,11]} },
            { extend: 'print', className: 'btn bg-teal color-palette btn-flat',exportOptions: { columns: [2,3,4,5,6,7,8,9,10,11]} },
            { extend: 'csv', className: 'btn bg-teal color-palette btn-flat',exportOptions: { columns: [2,3,4,5,6,7,8,9,10,11]} },
            { extend: 'colvis', className: 'btn bg-teal color-palette btn-flat',text:'Columns' },

            ]
        },
        /* FOR EXPORT BUTTONS END */

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        "responsive": true,
        language: {
            processing: '<div class="text-primary bg-primary" style="position: relative;z-index:100;overflow: visible;">Processing...</div>'
        },
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('sales/ajax_list').'?customerId='.$customerId?>",
            "type": "POST",
            "data": function (d) {
                d.warehouse_id = $('#warehouse_id').val(); // Add warehouse_id to the data being sent to the server
            },
            complete: function (data) {
             $('.column_checkbox').iCheck({
                checkboxClass: 'icheckbox_square-orange',
                /*uncheckedClass: 'bg-white',*/
                radioClass: 'iradio_square-orange',
                increaseArea: '10%' // optional
              });
             call_code();
              //$(".delete_btn").hide();
             },

        },

        //Set column definition initialisation properties.
        "columnDefs": [
        {
            "targets": [ 0,1,11,12], //first column / numbering column
            "orderable": false, //set not orderable
        },
        {
            "targets" :[0],
            "className": "text-center",
        },

        ],
        /*Start Footer Total*/
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
            var total = api
                .column( 7, { page: 'none'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            var paid = api
                .column( 8, { page: 'none'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            var due = api
                .column( 9, { page: 'none'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            //$( api.column( 0 ).footer() ).html('Total');
            $( api.column( 7 ).footer() ).html(app_number_format(total));
            $( api.column( 8 ).footer() ).html(app_number_format(paid));
            $( api.column( 9 ).footer() ).html(app_number_format(due));

        },
        /*End Footer Total*/
    });
    new $.fn.dataTable.FixedHeader( table );
    // Warehouse select change event
    $('#warehouse_id').on('change', function() {
        table.ajax.reload(); // Reload DataTable when warehouse_id changes
    });
});

</script>
<script src="<?php echo $theme_link; ?>js/sales.js?id=1"></script>
<script type="text/javascript">
  function print_invoice(id){
  window.open("<?= base_url();?>pos/print_invoice_pos/"+id, "_blank", "scrollbars=1,resizable=1,height=500,width=500");
}
</script>
<!-- Make sidebar menu hughlighter/selector -->
<script>$(".<?php echo basename(__FILE__,'.php');?>-active-li").addClass("active");</script>

</body>
</html>
