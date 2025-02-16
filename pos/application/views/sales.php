<!DOCTYPE html>
<html>

<head>
<!-- FORM CSS CODE -->
<?php include"comman/code_css_form.php"; ?>
<!-- </copy> -->
<style type="text/css">
table.table-bordered > thead > tr > th {
/* border:1px solid black;*/
text-align: center;
}
.table > tbody > tr > td,
.table > tbody > tr > th,
.table > tfoot > tr > td,
.table > tfoot > tr > th,
.table > thead > tr > td,
.table > thead > tr > th
{
padding-left: 2px;
padding-right: 2px;

}
</style>
</head>


<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">


        <?php include"sidebar.php"; ?>

        <?php
        if(!isset($sales_id)){
          $customer_id =$salesman_id  = $sales_date = $sales_status = $warehouse_id =
          $reference_no  =
          $other_charges_input          = $other_charges_tax_id =
          $discount_type  = $sales_note = '';
          $sales_date=show_date(date("d-m-Y"));
          $discount_input = $this->db->select("sales_discount")->get('db_sitesettings')->row()->sales_discount;
          $discount_input = ($discount_input==0) ? '' : $discount_input;
        }
        else{
          $q2 = $this->db->query("select * from db_sales where id=$sales_id");
          $customer_id=$q2->row()->customer_id;
          $salesman_id=$q2->row()->salesman_id;
          $sales_date=show_date($q2->row()->sales_date);
          $sales_status=$q2->row()->sales_status;
          $warehouse_id=$q2->row()->warehouse_id;
          $reference_no=$q2->row()->reference_no;
          $discount_input=$q2->row()->discount_to_all_input;
          $discount_type=$q2->row()->discount_to_all_type;
          $other_charges_input=$q2->row()->other_charges_input;
          $other_charges_tax_id=$q2->row()->other_charges_tax_id;
          $sales_note=$q2->row()->sales_note;

          $items_count = $this->db->query("select count(*) as items_count from db_salesitems where sales_id=$sales_id")->row()->items_count;
        }
    ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- **********************MODALS***************** -->
    <?php include"modals/modal_customer.php"; ?>
    <?php include"modals/modal_pos_sales_item.php"; ?>
    <!-- **********************MODALS END***************** -->
    <!-- Content Header (Page header) -->
    <section class="content-header">
         <h1>
            <?=$page_title;?>
            <small>Add/Update Sales</small>
         </h1>
         <ol class="breadcrumb">
            <li><a href="<?php echo $base_url; ?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo $base_url; ?>sales"><?= $this->lang->line('sales_list'); ?></a></li>
            <li><a href="<?php echo $base_url; ?>sales/add">New Sales</a></li>
            <li class="active"><?=$page_title;?></li>
         </ol>
      </section>

    <!-- Main content -->
     <section class="content">
       <div class="row">
        <!-- ********** ALERT MESSAGE START******* -->
       <?php include"comman/code_flashdata.php"; ?>
       <!-- ********** ALERT MESSAGE END******* -->
          <!-- right column -->
          <div class="col-md-12">
             <!-- Horizontal Form -->
             <div class="box box-info " >
                <!-- style="background: #68deac;" -->

                <!-- form start -->
                 <!-- OK START -->
                <?= form_open('#', array('class' => 'form-horizontal', 'id' => 'sales-form', 'enctype'=>'multipart/form-data', 'method'=>'POST'));?>
                   <input type="hidden" id="base_url" value="<?php echo $base_url;; ?>">
                   <input type="hidden" value='1' id="hidden_rowcount" name="hidden_rowcount">
                   <input type="hidden" value='0' id="hidden_update_rowid" name="hidden_update_rowid">


                   <div class="box-body">
                      <div class="form-group">
                         <label for="customer_id" class="col-sm-2 control-label"><?= $this->lang->line('customer_name'); ?><label class="text-danger">*</label></label>
                         <div class="col-sm-3">
                            <div class="input-group">
                               <select class="form-control select2" id="customer_id" name="customer_id"  style="width: 100%;" onkeyup="shift_cursor(event,'mobile')">
                                  <?php
                                     $query1="select * from db_customers where status=1";
                                     $q1=$this->db->query($query1);
                                     if($q1->num_rows($q1)>0){
                                         // echo "<option value=''>-Select-</option>";
                                          foreach($q1->result() as $res1){
                                          $selected=($customer_id==$res1->id) ? 'selected' : '';
                                          echo "<option $selected  value='".$res1->id."'>".$res1->customer_name." - ".$res1->mobile ."</option>";
                                        }
                                      }
                                      else{
                                    ?>
                                  <option value="">No Records Found</option>
                                  <?php } ?>
                               </select>
                               <span class="input-group-addon pointer" data-toggle="modal" data-target="#customer-modal" title="New Customer?"><i class="fa fa-user-plus text-primary fa-lg"></i></span>
                            </div>
                            <span id="customer_id_msg" style="display:none" class="text-danger"></span>
                         </div>
                         <label for="sales_date" class="col-sm-2 control-label"><?= $this->lang->line('sales_date'); ?> <label class="text-danger">*</label></label>
                         <div class="col-sm-3">
                            <div class="input-group date">
                               <div class="input-group-addon">
                                  <i class="fa fa-calendar"></i>
                               </div>
                               <input type="text" class="form-control pull-right datepicker"  id="sales_date" name="sales_date" readonly onkeyup="shift_cursor(event,'sales_status')" value="<?= $sales_date;?>">
                            </div>
                            <span id="sales_date_msg" style="display:none" class="text-danger"></span>
                         </div>
                      </div>
                      <div class="form-group">
                         <!-- <label for="salesman_id" class="col-sm-2 control-label"><?= $this->lang->line('salesman_name'); ?><label class="text-danger">*</label></label> -->
                         <input type="hidden" name="salesman_id" value="<?= $this->session->userdata('inv_userid');?>">
                         <div class="col-sm-3">
                            <!-- <div class="input-group">
                               <select class="form-control select2" id="salesman_id" name="salesman_id"  style="min-width:150px;width: 100%;" onkeyup="shift_cursor(event,'mobile')">
                                  <?php
                                     $q1=$this->db->query("select * from db_users");
                                     if($q1->num_rows($q1)>0){
                                         // echo "<option value=''>-Select-</option>";
                                          foreach($q1->result() as $res1){
                                          $selected=($salesman_id==$res1->id) ? 'selected' : '';
                                          echo "<option $selected  value='".$res1->id."'>".$res1->username  ."</option>";
                                        }
                                      }
                                      else{
                                    ?>
                                  <option value="">No Records Found</option>
                                  <?php } ?>
                               </select>
                            </div>
                            <span id="salesman_id_msg" style="display:none" class="text-danger"></span> -->
                         </div>
                      </div>
                      <div class="form-group hidden">
                         <label for="sales_status" class="col-sm-2 control-label"><?= $this->lang->line('status'); ?> <label class="text-danger">*</label></label>
                         <div class="col-sm-3">
                               <select class="form-control select2" id="sales_status" name="sales_status"  style="width: 100%;" onkeyup="shift_cursor(event,'mobile')">
                                  <?php
                                       $received_select = ($sales_status=='Final') ? 'selected' : '';
                                       $pending_select = ($sales_status=='Quotation') ? 'selected' : '';
                                  ?>
                                    <option <?= $received_select; ?> value="Final">Final</option>
                                    <option <?= $pending_select; ?> value="Quotation">Quotation</option>
                               </select>
                            <span id="sales_status_msg" style="display:none" class="text-danger"></span>
                         </div>
                         <label for="reference_no" class="col-sm-2 control-label"><?= $this->lang->line('reference_no'); ?> </label>
                            <div class="col-sm-3">
                                <input type="text" value="<?php echo  $reference_no; ?>" class="form-control " id="reference_no" name="reference_no" placeholder="" >
                                <span id="reference_no_msg" style="display:none" class="text-danger"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="warehouse_id" class="col-sm-2 control-label"><?= $this->lang->line('warehouse'); ?> <label class="text-danger">*</label></label>
                                <div class="col-sm-3">
                                    <select class="form-control select2" id="warehouse_id" name="warehouse_id"  style="width: 100%;" onkeyup="shift_cursor(event,'mobile')">
                                        <?php

                                     $query1="select * from db_warehouse where status=1";
                                     $q1=$this->db->query($query1);
                                     if($q1->num_rows($q1)>0)
                                        {
                                          
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
                            <span id="warehouse_id_msg" style="display:none" class="text-danger"></span>
                         </div>
                      </div>
                   </div>
                   <!-- /.box-body -->

                   <div class="row">
                      <div class="col-md-12">
                        <div class="col-md-12">
                          <div class="box">
                            <div class="box-info">
                              <div class="box-header">
                                <div class="col-md-8 col-md-offset-2 d-flex justify-content" >
                                  <div class="input-group">
                                        <span class="input-group-addon" title="Select Items"><i class="fa fa-barcode"></i></span>
                                         <input type="text" class="form-control " placeholder="Item name/Barcode/Itemcode" id="item_search">
                                      </div>
                                </div>
                              </div>
                              <div class="box-body">
                                <div class="table-responsive" style="width: 100%">
                                <table class="table table-hover table-bordered" style="width:100%" id="sales_table">
                                     <thead class="custom_thead">
                                        <tr class="bg-primary" >
                                           <th rowspan='2' style="width:15%"><?= $this->lang->line('item_name'); ?></th>
                                           <th rowspan='2' style="width:15%"><?= $this->lang->line('item_code'); ?></th>

                                           <th rowspan='2' style="width:10%;min-width: 180px;"><?= $this->lang->line('quantity'); ?></th>
                                           <th rowspan='2' style="width:10%"><?= $this->lang->line('unit_price'); ?></th>
                                           <th rowspan='2' style="width:10%"><?= $this->lang->line('discount'); ?>(<?= $CI->currency() ?>)</th>
                                           <th rowspan='2' style="width:10%"><?= $this->lang->line('tax_amount'); ?></th>
                                           <th rowspan='2' style="width:5%"><?= $this->lang->line('tax'); ?></th>
                                           <th rowspan='2' style="width:7.5%"><?= $this->lang->line('total_amount'); ?></th>
                                           <th rowspan='2' style="width:7.5%"><?= $this->lang->line('action'); ?></th>
                                        </tr>
                                     </thead>
                                     <tbody>

                                     </tbody>
                                  </table>
                              </div>
                              </div>
                            </div>
                          </div>


                        </div>
                      </div>


                      <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                               <div class="form-group">
                                  <label for="" class="col-sm-4 control-label"><?= $this->lang->line('quantity'); ?></label>
                                  <div class="col-sm-4">
                                     <label class="control-label total_quantity text-success" style="font-size: 15pt;">0</label>
                                  </div>
                               </div>
                            </div>
                         </div>
                         <div class="row">
                            <div class="col-md-12">
                               <div class="form-group">
                                  <label for="other_charges_input" class="col-sm-4 control-label"><?= $this->lang->line('other_charges'); ?></label>
                                  <div class="col-sm-4">
                                     <input type="text" class="form-control text-right only_currency" id="other_charges_input" name="other_charges_input" onkeyup="final_total();" value="<?php echo  $other_charges_input; ?>">
                                  </div>
                                  <div class="col-sm-4">
                                     <select class="form-control " id="other_charges_tax_id" name="other_charges_tax_id" onchange="final_total();" style="width: 100%;">
                                        <?php
                                           $q1="select * from db_tax where status=1";
                                           $q1=$this->db->query($q1);
                                            if($q1->num_rows()>0)
                                            {
                                             echo "<option>None</option>";
                                             foreach($q1->result() as $res1)
                                              {
                                                $selected=($other_charges_tax_id==$res1->id) ? 'selected' : '';
                                                echo "<option $selected data-tax='".$res1->tax."' value='".$res1->id."'>".$res1->tax_name."</option>";
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
                               </div>
                            </div>
                         </div>
                         <div class="row">
                            <div class="col-md-12">
                               <div class="form-group">
                                  <label for="discount_to_all_input" class="col-sm-4 control-label"><?= $this->lang->line('discount_on_all'); ?></label>
                                  <div class="col-sm-4">
                                     <input type="text" class="form-control  text-right only_currency" id="discount_to_all_input" name="discount_to_all_input" onkeyup="enable_or_disable_item_discount();" value="<?php echo  $discount_input; ?>">
                                  </div>
                                  <div class="col-sm-4">
                                     <select class="form-control" onchange="final_total();" id='discount_to_all_type' name="discount_to_all_type">
                                        <option value='in_percentage'>Per%</option>
                                        <option value='in_fixed'>Fixed</option>
                                     </select>
                                  </div>
                                  <!-- Dynamicaly select Supplier name -->
                                  <script type="text/javascript">
                                     <?php if($discount_type!=''){ ?>
                                         document.getElementById('discount_to_all_type').value='<?=  $discount_type; ?>';
                                     <?php }?>
                                  </script>
                                  <!-- Dynamicaly select Supplier name end-->
                               </div>
                            </div>
                         </div>
                        <div class="row">
                            <div class="col-md-12">
                               <div class="form-group">
                                  <label for="sales_note" class="col-sm-4 control-label"><?= $this->lang->line('note'); ?></label>
                                  <div class="col-sm-8">
                                     <textarea class="form-control text-left" id='sales_note' name="sales_note"><?= $sales_note; ?></textarea>
                                    <span id="sales_note_msg" style="display:none" class="text-danger"></span>
                                  </div>
                               </div>
                            </div>
                         </div>


                      </div>


                      <div class="col-md-6">
                         <div class="row">
                            <div class="col-md-12">
                               <div class="form-group">
                                  <table  class="col-md-9">
                                     <tr>
                                        <th class="text-right" style="font-size: 17px;"><?= $this->lang->line('subtotal'); ?></th>
                                        <th class="text-right" style="padding-left:10%;font-size: 17px;">
                                           <h4><b id="subtotal_amt" name="subtotal_amt">0.00</b></h4>
                                        </th>
                                     </tr>
                                     <tr>
                                        <th class="text-right" style="font-size: 17px;"><?= $this->lang->line('other_charges'); ?></th>
                                        <th class="text-right" style="padding-left:10%;font-size: 17px;">
                                           <h4><b id="other_charges_amt" name="other_charges_amt">0.00</b></h4>
                                        </th>
                                     </tr>
                                     <tr>
                                        <th class="text-right" style="font-size: 17px;"><?= $this->lang->line('discount_on_all'); ?></th>
                                        <th class="text-right" style="padding-left:10%;font-size: 17px;">
                                           <h4><b id="discount_to_all_amt" name="discount_to_all_amt">0.00</b></h4>
                                        </th>
                                     </tr>
                                     <tr style="<?= (!is_enabled_round_off()) ? 'display: none;' : '';?>">
                                        <th class="text-right" style="font-size: 17px;"><?= $this->lang->line('round_off'); ?>
                                        <i class="hover-q " data-container="body" data-toggle="popover" data-placement="top" data-content="Go to Site Settings-> Site -> Disable the Round Off(Checkbox)." data-html="true" data-trigger="hover" data-original-title="" title="Do you wants to Disable Round Off ?">
                                              <i class="fa fa-info-circle text-maroon text-black hover-q"></i>
                                            </i>

                                        </th>
                                        <th class="text-right" style="padding-left:10%;font-size: 17px;">
                                           <h4><b id="round_off_amt" name="tot_round_off_amt">0.00</b></h4>
                                        </th>
                                     </tr>
                                     <tr>
                                        <th class="text-right" style="font-size: 17px;"><?= $this->lang->line('grand_total'); ?></th>
                                        <th class="text-right" style="padding-left:10%;font-size: 17px;">
                                           <h4><b id="total_amt" name="total_amt">0.00</b></h4>
                                        </th>
                                     </tr>
                                  </table>
                               </div>
                            </div>
                         </div>
                      </div>

                      <div class="col-xs-12 ">
                         <div class="col-sm-12">
                               <div class="box-body ">
                                <div class="col-md-12">
                                  <table class="table table-hover table-bordered" style="width:100%" id="payments_table"><h4 class="box-title text-info"><?= $this->lang->line('previous_payments_information'); ?> : </h4>
                                     <thead>
                                        <tr class="bg-gray " >
                                           <th>#</th>
                                           <th><?= $this->lang->line('date'); ?></th>
                                           <th><?= $this->lang->line('payment_type'); ?></th>
                                           <th><?= $this->lang->line('payment_note'); ?></th>
                                           <th><?= $this->lang->line('payment'); ?></th>
                                           <th><?= $this->lang->line('action'); ?></th>
                                        </tr>
                                     </thead>
                                     <tbody>
                                        <?php
                                          if(isset($sales_id)){
                                            $q3 = $this->db->query("select * from db_salespayments where sales_id=$sales_id");
                                            if($q3->num_rows()>0){
                                              $i=1;
                                              $total_paid = 0;
                                              foreach ($q3->result() as $res3) {
                                                echo "<tr class='text-center text-bold' id='payment_row_".$res3->id."'>";
                                                echo "<td>".$i."</td>";
                                                echo "<td>".show_date($res3->payment_date)."</td>";
                                                echo "<td>".$res3->payment_type."</td>";
                                                echo "<td>".$res3->payment_note."</td>";
                                                echo "<td class='text-right' id='paid_amt_$i'>".$res3->payment."</td>";
                                                echo '<td><i class="fa fa-trash text-red pointer" onclick="delete_payment('.$res3->id.')"> Delete</i></td>';
                                                echo "</tr>";
                                                $total_paid +=$res3->payment;
                                                $i++;
                                              }
                                              echo "<tr class='text-right text-bold'><td colspan='4' >Total</td><td data-rowcount='$i' id='paid_amt_tot'>".number_format($total_paid,2,'.','')."</td><td></td></tr>";
                                            }
                                            else{
                                              echo "<tr><td colspan='6' class='text-center text-bold'>No Previous Payments Found!!</td></tr>";
                                            }

                                          }
                                          else{
                                            echo "<tr><td colspan='6' class='text-center text-bold'>Payments Pending!!</td></tr>";
                                          }
                                        ?>
                                     </tbody>
                                  </table>
                                </div>
                               </div>
                               <!-- /.box-body -->
                            </div>
                         <!-- /.box -->
                      </div>

                        <div class="col-xs-12 ">
                            <div class="col-sm-12">
                                <div class="box-body ">
                                    <div class="col-md-12 payments_div_">
                                        <h4 class="box-title text-info"><?= $this->lang->line('subtotal'); ?> : </h4>
                                        <div class="box box-solid bg-gray">
                                            <div class="box-body">
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <div class="payments_div">
                                                            <input type="hidden" data-var='inside_else' name="payment_row_count" id='payment_row_count' value="1">
                                                            <div class="row mt-1">
                                                                <div class="col-md-3">
                                                                    <label for="paid_amount"><?= $this->lang->line('amount'); ?></label>
                                                                    <input type="text" class="form-control text-right only_currency paid_amount" onkeyup="paid_cal(this)">
                                                                    <input type="hidden" class="form-control text-right paid_amt only_currency amount" name="amount_1" placeholder="" >
                                                                </div>

                                                                <div class="col-md-3">
                                                                  <div class="">
                                                                    <label for="payment_type"><?= $this->lang->line('payment_type'); ?></label>
                                                                    <select class="form-control select2" id='payment_type' name="payment_type_1">
                                                                      <?php
                                                                        $q1=$this->db->query("select * from db_paymenttypes where status=1");
                                                                        if($q1->num_rows()>0)
                                                                            foreach($q1->result() as $res1)
                                                                                echo "<option value='".$res1->payment_type."'>".$res1->payment_type ."</option>";
                                                                        else
                                                                            echo "<option>None</option>";
                                                                        ?>
                                                                    </select>
                                                                  </div>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <div class="">
                                                                        <label><?= $this->lang->line('payment_note'); ?></label>
                                                                        <input type="text" class="form-control" name="payment_note_1" placeholder="" />
                                                                    </div>
                                                                </div>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        </div>
                                                        <div class="row" style="margin-top:5px">
                                                            <div class="col-md-3 col-offset-6"><button type="button" class="btn btn-primary btn-block" id="add_payment_row">Add Payment Row</button></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label for="balance">Balance</label>
                                                                <input type="text" class="form-control text-right balance only_currency" id="balance">
                                                                <input type="hidden" class="form-control text-right" id="amount" placeholder="" >
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for="change_return">Change Return</label>
                                                                <input type="text" class="form-control text-right change_return only_currency" id="change_return" name="change_return">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <script>
                                                    function paid_cal(e){
                                                        var ptotal_amount=parseFloat($("#total_amt").text());
                                                        $('#change_return').val(0);
                                                        $('#balance').val(0);

                                                        var total_c=0;
                                                        $('.paid_amount').each(function(e){
                                                            total_c+=parseFloat($(this).val());
                                                            $(this).next('.amount').val($(this).val())
                                                        })
                                                        if(parseFloat(total_c)>ptotal_amount){
                                                            $('#change_return').val( total_c - ptotal_amount);
                                                            $('#amount').val(total_c);
                                                        }else if(parseFloat(total_c)<ptotal_amount){
                                                            $('#balance').val( ptotal_amount - total_c);
                                                            $('#amount').val(total_c);
                                                        }
                                                    }
                                                    function get_id_value(id){
                                                    	return $("#"+id).val().trim();
                                                    }

                                                    $('#add_payment_row').click(function (e) {
                                                    	var base_url=$("#base_url").val().trim();
                                                    	//table should not be empty
                                                    	if($(".items_table tr").length==1){
                                                        	toastr["error"]("Please Select Items from List!!");
                                                        	failed.currentTime = 0;
                                                    		failed.play();
                                                    		return;
                                                        }
                                                        else{
                                                        	/*BUTTON LOAD AND DISABLE START*/
                                                        	var this_id=this.id;
                                                        	var this_val=$(this).html();
                                                        	$("#"+this_id).html('<i class="fa fa-spinner fa-spin"></i> Please Wait..');
                                                        	$("#"+this_id).attr('disabled',true);
                                                        	/*BUTTON LOAD AND DISABLE END*/

                                                        	var payment_row_count=get_id_value("payment_row_count");
                                                        	$.post(base_url+"sales/add_payment_row",{payment_row_count:payment_row_count},function(result){
                                                        		$('.payments_div').append(result);

                                                        		$("#payment_row_count").val(parseFloat(payment_row_count)+1);

                                                        		/*BUTTON LOAD AND DISABLE START*/
                                                        		$("#"+this_id).html(this_val);
                                                        		$("#"+this_id).attr('disabled',false);
                                                        		/*BUTTON LOAD AND DISABLE END*/
                                                        		failed.currentTime = 0;
                                                    			failed.play();
                                                        		adjust_payments();
                                                        	});
                                                        }
                                                    }); //hold_invoice end
                                                    function remove_row(id){
                                                    	$(".payments_div_"+id).html('');
                                                    	failed.currentTime = 0;
                                                    	failed.play();
                                                    	adjust_payments();
                                                    }
                                                </script>
                                            </div>
                                        </div>
                                    </div><!-- col-md-12 -->
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                        </div>

                      <!-- SMS Sender while saving -->
                        <?php
                           //Change Return
                            $send_sms_checkbox='disabled';
                            if($CI->is_sms_enabled()){
                              if(!isset($sales_id)){
                                $send_sms_checkbox='checked';
                              }else{
                                $send_sms_checkbox='';
                              }
                            }
                      ?>

                      <div class="col-xs-12 ">
                         <div class="col-sm-12">
                               <div class="box-body ">
                                  <div class="col-md-12">
                                    <div class="checkbox icheck">
                              <label>
                                <input type="checkbox" <?=$send_sms_checkbox;?> class="form-control" id="send_sms" name="send_sms" > <label for="sales_discount" class=" control-label"><?= $this->lang->line('send_sms_to_customer'); ?>
                                  <i class="hover-q " data-container="body" data-toggle="popover" data-placement="top" data-content="If checkbox is Disabled! You need to enable it from SMS -> SMS API <br><b>Note:<i>Walk-in Customer will not receive SMS!</i></b>" data-html="true" data-trigger="hover" data-original-title="" title="Do you wants to send SMS ?">
                          <i class="fa fa-info-circle text-maroon text-black hover-q"></i>
                        </i>
                                </label>
                              </label>
                            </div>
                                </div><!-- col-md-12 -->
                               </div><!-- /.box-body -->
                            </div><!-- /.box -->
                      </div>
                   </div><!-- /.box-body -->
                   <div class="box-footer col-sm-12">
                      <center>
                        <?php
                        if(isset($sales_id)){
                          $btn_id='update';
                          $btn_name="Update";
                          echo '<input type="hidden" name="sales_id" id="sales_id" value="'.$sales_id.'"/>';
                        }
                        else{
                          $btn_id='save';
                          $btn_name="Save";
                        }

                        ?>
                         <div class="col-md-3 col-md-offset-3">
                            <button type="button" id="<?php echo $btn_id;?>" class="btn bg-maroon btn-block btn-flat btn-lg payments_modal" title="Save Data"><?php echo $btn_name;?></button>
                         </div>
                         <div class="col-sm-3"><a href="<?= base_url()?>dashboard">
                            <button type="button" class="btn bg-gray btn-block btn-flat btn-lg" title="Go Dashboard">Close</button>
                          </a>
                        </div>
                      </center>
                   </div>
                   <?= form_close(); ?>
                   <!-- OK END -->
             </div>
          </div>
          <!-- /.box-footer -->

       </div>
       <!-- /.box -->
     </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

 <?php include"footer.php"; ?>
<!-- SOUND CODE -->
<?php include"comman/code_js_sound.php"; ?>
<!-- GENERAL CODE -->
<?php include"comman/code_js_form.php"; ?>

<script src="<?php echo $theme_link; ?>js/modals.js"></script>
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

      <script src="<?php echo $theme_link; ?>js/sales.js"></script>
      <script>

        //Initialize Select2 Elements
        $('.select2').select2({ width: '100%' });

        //Date picker
        $('.datepicker').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
            todayHighlight: true
        });

      /*Warehouse*/
        $("#warehouse_id").change(function(){
            var warehouse_id=$(this).val();
            $("#sales_table > tbody").empty();
            final_total();
        });
        /*Warehouse end*/


        /* function update_price(row_id,item_cost){

          var sales_price=$("#sales_price_"+row_id).val().trim();
          if(sales_price!='' || sales_price==0) {sales_price = parseFloat(sales_price); }


          var item_price=parseFloat($("#tr_sales_price_temp_"+row_id).val().trim());

          if(sales_price<item_cost){

            $("#sales_price_"+row_id).parent().addClass('has-error');
          }else{
            $("#sales_price_"+row_id).parent().removeClass('has-error');
          }

          make_subtotal($("#tr_item_id_"+row_id).val(),row_id);
        }*/

        /*function set_to_original(i,purchase_price) {
                    var sales_price=parseFloat($("#td_data_"+i+"_10").val().trim());
          if(sales_price!='' || sales_price==0) {sales_price = parseFloat(sales_price); }

                    var item_price=parseFloat($("#tr_purchase_price_"+i).val().trim());

          if(sales_price<purchase_price){
            toastr["success"]("Default Price Set "+item_price);
            $("#td_data_"+i+"_10").parent().removeClass('has-error');
            $("#td_data_"+i+"_10").val(item_price);
          }
          calculate_tax(i);
        }*/

        /* ---------- CALCULATE TAX -------------*/
        function calculate_tax(i){ //i=Row
            set_tax_value(i);

            //Find the Tax type and Tax amount
            var tax_type = $("#tr_tax_type_"+i).val();
            var tax_amount = $("#td_data_"+i+"_11").val();

            var qty=$("#td_data_"+i+"_3").val().trim();
            var sales_price=parseFloat($("#td_data_"+i+"_10").val().trim());
            $("#td_data_"+i+"_4").val(sales_price);
            /*Discounr*/
            var discount_amt=$("#td_data_"+i+"_8").val().trim();
                discount_amt   =(isNaN(parseFloat(discount_amt)))    ? 0 : parseFloat(discount_amt);

            var amt=parseFloat(qty) * sales_price;//Taxable

            var total_amt=amt-discount_amt;
            total_amt = (tax_type=='Inclusive') ? total_amt : parseFloat(total_amt) + parseFloat(tax_amount);

            //Set Unit cost
            $("#td_data_"+i+"_9").val('').val(total_amt.toFixed(2));

            final_total();
        }

         /* ---------- CALCULATE GST END -------------*/


         /* ---------- Final Description of amount ------------*/
         function final_total(){


           var rowcount=$("#hidden_rowcount").val();
           var subtotal=parseFloat(0);

           var other_charges_per_amt=parseFloat(0);
           var other_charges_total_amt=0;
           var taxable=0;
          if($("#other_charges_input").val()!=null && $("#other_charges_input").val()!=''){

              other_charges_tax_id =$('option:selected', '#other_charges_tax_id').attr('data-tax');
             other_charges_input=$("#other_charges_input").val();
             if(other_charges_tax_id>0){

               other_charges_per_amt=(other_charges_tax_id * other_charges_input)/100;
             }

             taxable=parseFloat(other_charges_per_amt)+parseFloat(other_charges_input);//Other charges input
             other_charges_total_amt=parseFloat(other_charges_per_amt)+parseFloat(other_charges_input);
           }
           else{
             //$("#other_charges_amt").html('0.00');
           }


           var tax_amt=0;
           var actual_taxable=0;
           var total_quantity=0;

           for(i=1;i<=rowcount;i++){

             if(document.getElementById("td_data_"+i+"_3")){
               //customer_id must exist
               if($("#td_data_"+i+"_3").val()!=null && $("#td_data_"+i+"_3").val()!=''){
                    actual_taxable=actual_taxable+ + +(parseFloat($("#td_data_"+i+"_13").val()).toFixed(2) * parseFloat($("#td_data_"+i+"_3").val()));
                    subtotal=subtotal+ + +parseFloat($("#td_data_"+i+"_9").val()).toFixed(2);
                    if($("#td_data_"+i+"_7").val()>=0){
                      tax_amt=tax_amt+ + +$("#td_data_"+i+"_7").val();
                    }
                    total_quantity +=parseFloat($("#td_data_"+i+"_3").val().trim());
                }

             }//if end
           }//for end


          //Show total Sales Quantitys
           $(".total_quantity").html(total_quantity);

           //Apply Output on screen
           //subtotal
           if((subtotal!=null || subtotal!='') && (subtotal!=0)){

             //subtotal
             $("#subtotal_amt").html(subtotal.toFixed(2));

             //other charges total amount
             $("#other_charges_amt").html(parseFloat(other_charges_total_amt).toFixed(2));

             //other charges total amount


             taxable=taxable+subtotal;

             //discount_to_all_amt
            // if($("#discount_to_all_input").val()!=null && $("#discount_to_all_input").val()!=''){
                 var discount_input=parseFloat($("#discount_to_all_input").val());
                 discount_input = isNaN(discount_input) ? 0 : discount_input;
                 var discount=0;
                 if(discount_input>0){
                     var discount_type=$("#discount_to_all_type").val();
                     if(discount_type=='in_fixed'){
                       taxable-=discount_input;
                       discount=discount_input;
                       //Minus
                     }
                     else if(discount_type=='in_percentage'){
                         discount=(taxable*discount_input)/100;
                        taxable-=discount;

                     }
                 }
                 else{
                    //discount += $("#")
                 }
                   discount=parseFloat(discount).toFixed(2);

                    $("#discount_to_all_amt").html(discount);
                    $("#hidden_discount_to_all_amt").val(discount);
             //}
             //subtotal_round=Math.round(taxable);
             subtotal_round=round_off(taxable);//round_off() method custom defined
             subtotal_diff=subtotal_round-taxable;

             $("#round_off_amt").html(parseFloat(subtotal_diff).toFixed(2));
             $("#total_amt").html(parseFloat(subtotal_round).toFixed(2));
             $("#hidden_total_amt").val(parseFloat(subtotal_round).toFixed(2));
           }
           else{
             $("#subtotal_amt").html('0.00');
             $("#tax_amt").html('0.00');
             $("#round_off_amt").html('0.00');
             $("#total_amt").html('0.00');
             $("#amount").val('0.00');
             $("#hidden_total_amt").html('0.00');
             $("#discount_to_all_amt").html('0.00');
             $("#hidden_discount_to_all_amt").html('0.00');
             $("#subtotal_amt").html('0.00');
             $("#other_charges_amt").html('0.00');
           }

          // adjust_payments();
          //alert("final_total() end");
         }
         /* ---------- Final Description of amount end ------------*/

         function removerow(id){//id=Rowid

         $("#row_"+id).remove();
         final_total();
         failed.currentTime = 0;
        failed.play();
         }



    function enable_or_disable_item_discount(){
      /*var discount_input=parseFloat($("#discount_to_all_input").val());
      discount_input = isNaN(discount_input) ? 0 : discount_input;
      if(discount_input>0){
        $(".item_discount").attr({
          'readonly': true,
          'style': 'border-color:red;cursor:no-drop',
        });
      }
      else{
        $(".item_discount").attr({
          'readonly': false,
          'style': '',
        });
      }*/

      var rowcount=$("#hidden_rowcount").val();
      for(k=1;k<=rowcount;k++){
       if(document.getElementById("tr_item_id_"+k)){
         calculate_tax(k);
       }//if end
     }//for end

      //final_total();
    }

    //Sale Items Modal Operations Start
    function show_sales_item_modal(row_id){
      $('#sales_item').modal('toggle');
      $("#popup_tax_id").select2();

      //Find the item details
      var item_name = $("#td_data_"+row_id+"_1").html();
      var tax_type = $("#tr_tax_type_"+row_id).val();
      var tax_id = $("#tr_tax_id_"+row_id).val();
      var description = $("#description_"+row_id).val();

      /*Discount*/
      var item_discount_input = $("#item_discount_input_"+row_id).val();
      var item_discount_type = $("#item_discount_type_"+row_id).val();

      //Set to Popup
      $("#item_discount_input").val(item_discount_input);
      $("#item_discount_type").val(item_discount_type).select2();

      $("#popup_item_name").html(item_name);
      $("#popup_tax_type").val(tax_type).select2();
      $("#popup_tax_id").val(tax_id).select2();
      $("#popup_description").val(description);
      $("#popup_row_id").val(row_id);
    }

    function set_info(){
      var row_id = $("#popup_row_id").val();
      var tax_type = $("#popup_tax_type").val();
      var tax_id = $("#popup_tax_id").val();
      var description = $("#popup_description").val();
      var tax_name = ($('option:selected', "#popup_tax_id").attr('data-tax-value'));
      var tax = parseFloat($('option:selected', "#popup_tax_id").attr('data-tax'));

      /*Discounr*/
      var item_discount_input = $("#item_discount_input").val();
      var item_discount_type = $("#item_discount_type").val();

      //Set it into row
      $("#item_discount_input_"+row_id).val(item_discount_input);
      $("#item_discount_type_"+row_id).val(item_discount_type);

      $("#tr_tax_type_"+row_id).val(tax_type);
      $("#tr_tax_id_"+row_id).val(tax_id);
      $("#tr_tax_value_"+row_id).val(tax);//%
      $("#description_"+row_id).val(description);
      $("#td_data_"+row_id+"_12").html(tax_name);

      calculate_tax(row_id);
      $('#sales_item').modal('toggle');
    }
    function set_tax_value(row_id){
      //get the sales price of the item
      var tax_type = $("#tr_tax_type_"+row_id).val();
      var tax = $("#tr_tax_value_"+row_id).val(); //%
      var qty=$("#td_data_"+row_id+"_3").val().trim();
          qty = (isNaN(qty)) ? 0 :qty;
      var sales_price = parseFloat($("#td_data_"+row_id+"_10").val());
          sales_price = (isNaN(sales_price)) ? 0 :sales_price;
          sales_price = sales_price * qty;

      /*Discount*/
      var item_discount_type = $("#item_discount_type_"+row_id).val();
      var item_discount_input = parseFloat($("#item_discount_input_"+row_id).val());
          item_discount_input = (isNaN(item_discount_input)) ? 0 :item_discount_input;

      //Calculate discount
      var discount_amt=(item_discount_type=='Percentage') ? ((sales_price) * item_discount_input)/100 : item_discount_input;
      sales_price-=parseFloat(discount_amt);

      var tax_amount = (tax_type=='Inclusive') ? calculate_inclusive(sales_price,tax) : calculate_exclusive(sales_price,tax);

      $("#td_data_"+row_id+"_8").val(discount_amt);

      $("#td_data_"+row_id+"_11").val(tax_amount);
    }
    //Sale Items Modal Operations End


    function item_qty_input(i){

      var item_qty=$("#td_data_"+i+"_3").val();
      var available_qty=$("#tr_available_qty_"+i+"_13").val();
      if(parseFloat(item_qty)>parseFloat(available_qty)){
        $("#td_data_"+i+"_3").val(available_qty);
        toastr["warning"]("Oops! You have only "+available_qty+" items in Stock");
      }
      calculate_tax(i);
    }

      </script>


      <!-- UPDATE OPERATIONS -->
      <script type="text/javascript">
         <?php if(isset($sales_id)){ ?>
             $(document).ready(function(){
                var base_url='<?= base_url();?>';
                var sales_id='<?= $sales_id;?>';
                $(".box").append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
                $.post(base_url+"sales/return_sales_list/"+sales_id,{},function(result){
                  //alert(result);
                  $('#sales_table tbody').append(result);
                  $("#hidden_rowcount").val(parseFloat(<?=$items_count;?>)+1);
                  success.currentTime = 0;
                  success.play();
                  enable_or_disable_item_discount();
                  $(".overlay").remove();
              });
             });
         <?php }?>
      </script>
      <!-- UPDATE OPERATIONS end-->

      <!-- Make sidebar menu hughlighter/selector -->
      <script>$(".<?php echo basename(__FILE__,'.php');?>-active-li").addClass("active");</script>
</body>
</html>
