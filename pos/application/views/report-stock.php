<!DOCTYPE html>
<html>
<head>
<!-- TABLES CSS CODE -->
<?php include"comman/code_css_form.php"; ?>
<!-- </copy> -->
</head>
<body class="hold-transition skin-blue sidebar-mini">


<div class="wrapper">

 <?php include"sidebar.php"; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?=$page_title;?>
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $base_url; ?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?=$page_title;?></li>
      </ol>
    </section>

    <!-- /.content -->
    <section class="content">
      <div class="row">
                  <div class="col-md-12">
                     <!-- Custom Tabs -->
                     <div class="nav-tabs-custom">

                        <ul class="nav nav-tabs">
                           <li class="active"><a href="#tab_1" data-toggle="tab"><?= $this->lang->line('item_wise'); ?></a></li>
                           <li><a href="#tab_2" data-toggle="tab"><?= $this->lang->line('brand_wise'); ?></a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                            <div class="form-group col-md-4">
                            <!-- <label for="warehouse_id"><?= $this->lang->line('warehouse'); ?><span class="text-danger">*</span></label> -->
                            <select class="form-control select2" id="warehouse_id" name="warehouse_id"  style="width: 100%;" onkeyup="shift_cursor(event,'mobile')" onchange="get_product_warehouse()">
                              <?php
                                  $query1 ="select * from db_warehouse where status=1";
                                  $q1=$this->db->query($query1);
                                  if($q1->num_rows($q1)>0)
                                    { 
                                        echo "<option value=''>-Select Warehouse-</option>";
                                        foreach($q1->result() as $res1)
                                    {
                                        $selected=($warehouse_id==$res1->id) ? 'selected' : '';
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
                              <div class="row">
                                 <!-- right column -->
                                 <div class="col-md-12">
                                    <!-- form start -->
                                       <input type="hidden" id="base_url" value="<?php echo $base_url;; ?>">
                                          <button type="button" class="btn btn-info pull-right btnExport_3" title="Download Data in Excel Format">Excel</button>
                                          <br><br>
                                          <div class="table-responsive">
                                          <table class="table table-bordered table-hover " id="report-data" >
                                            <thead>
                                            <tr class="bg-blue">
                                              <th style="">#</th>
                                              <th style=""><?= $this->lang->line('item_code'); ?></th>
                                              <th style=""><?= $this->lang->line('item_name'); ?></th>
                                              <th style=""><?= $this->lang->line('unit_price'); ?>(<?= $CI->currency(); ?>)</th>
                                              <th style=""><?= $this->lang->line('tax'); ?></th>
                                              <th style=""><?= $this->lang->line('sales_price'); ?>(<?= $CI->currency(); ?>)</th>
                                              <th style=""><?= $this->lang->line('current_stock'); ?></th>
                                              <th style=""><?= $this->lang->line('stock_value'); ?></th>
                                            </tr>
                                            </thead>
                                            <tbody id="tbodyid">

                                          </tbody>
                                          </table>
                                          </div>
                                       <!-- /.box-body -->
                                 </div>
                                 <!--/.col (right) -->
                              </div>
                              <!-- /.row -->
                           </div>
                           <!-- /.tab-pane -->

                           <div class="tab-pane" id="tab_2">
                              <div class="row">
                                 <!-- right column -->
                                 <div class="col-md-12">
                                    <!-- form start -->
                                       <input type="hidden" id="base_url" value="<?php echo $base_url;; ?>">
                                          <button type="button" class="btn btn-info pull-right btnExport_4" title="Download Data in Excel Format">Excel</button>
                                          <br><br>
                                          <div class="table-responsive">
                                          <table class="table table-bordered table-hover " id="brand_wise_stock" >
                                              <thead>
                                              <tr class="bg-blue">
                                                <th style="">#</th>
                                                <th style=""><?= $this->lang->line('brand_name'); ?></th>
                                                <th style=""><?= $this->lang->line('current_stock'); ?></th>
                                              </tr>
                                              </thead>
                                              <tbody id="">

                                              </tbody>
                                            </table>
                                          </div>
                                       <!-- /.box-body -->
                                 </div>
                                 <!--/.col (right) -->
                              </div>
                              <!-- /.row -->
                           </div>
                           <!-- /.tab-pane -->

                        </div>
                        <!-- /.tab-content -->
                     </div>
                     <!-- nav-tabs-custom -->
                  </div>
                  <!-- /.col -->


      </div>
    </section>
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
<?php include"comman/code_js_form.php"; ?>

<script src="<?php echo $theme_link; ?>js/sheetjs.js" type="text/javascript"></script>
<script>

function convert_excel(type,file_name,table_name) {
    var fn; var dl;
    var elt = document.getElementById(table_name);
    var wb = XLSX.utils.table_to_book(elt, {sheet:"Sheet JS"});
    return dl ?
        XLSX.write(wb, {bookType:type, bookSST:true, type: 'base64'}) :
        XLSX.writeFile(wb, fn || (file_name+'.' + (type || 'xlsx')));
}
$(".btnExport_3").click(function(event) {
 convert_excel('xlsx','Item-wise-stock-report-1','report-data');
});
$(".btnExport_4").click(function(event) {
 convert_excel('xlsx','Brand-wise-stock-report-1','brand_wise_stock');
});
</script>
<script type="text/javascript">
  $(document).ready(function() {

    $(".box").append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
        $.post("show_stock_report",{},function(result){
          //alert(result);
            setTimeout(function() {
             $("#tbodyid").empty().append(result);
             $(".overlay").remove();
            }, 0);
           });

     $(".box").append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
        $.post("brand_wise_stock",{},function(result){
          //alert(result);
            setTimeout(function() {
             $("#brand_wise_stock tbody").empty().append(result);
             $(".overlay").remove();
            }, 0);
           });
          });
    function get_product_warehouse(){
        let warehouse_id=$('#warehouse_id').val();
        $.post("show_stock_report",{'warehouse_id':warehouse_id},function(result){
          //alert(result);
            setTimeout(function() {
             $("#tbodyid").empty().append(result);
             $(".overlay").remove();
            }, 0);
           });
    }
</script>


<!-- Make sidebar menu hughlighter/selector -->
<script>$(".<?php echo basename(__FILE__,'.php');?>-active-li").addClass("active");</script>


</body>
</html>
