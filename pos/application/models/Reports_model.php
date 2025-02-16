<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_model extends CI_Model {

	public function show_sales_report(){
		extract($_POST);

		$from_date=date("Y-m-d",strtotime($from_date));
		$to_date=date("Y-m-d",strtotime($to_date));

		$this->db->select("a.id,a.sales_code,a.sales_date,b.customer_name,b.customer_code,a.grand_total,a.paid_amount,(select username from db_users where a.salesman_id=db_users.id) as username");

		if($customer_id!='')
			$this->db->where("a.customer_id=$customer_id");

		if($salesman_id!='')
			$this->db->where("a.salesman_id=$salesman_id");

		if($view_all=="no")
			$this->db->where("(a.sales_date>='$from_date' and a.sales_date<='$to_date')");

		$this->db->where("b.`id`= a.`customer_id`");
		$this->db->from("db_sales as a");
		$this->db->where("a.`sales_status`= 'Final'");
		$this->db->from("db_customers as b");

		//echo $this->db->get_compiled_select();exit();

		$q1=$this->db->get();
		if($q1->num_rows()>0){
			$i=0;
			$tot_grand_total=0;
			$tot_paid_amount=0;
			$tot_due_amount=0;
			foreach ($q1->result() as $res1) {
				echo "<tr>";
				echo "<td>".++$i."</td>";
				echo "<td><a title='View Invoice' href='".base_url("sales/invoice/$res1->id")."'>".$res1->sales_code."</a></td>";
				echo "<td>".show_date($res1->sales_date)."</td>";
				echo "<td>".$res1->username."</td>";
				echo "<td>".$res1->customer_code."</td>";
				echo "<td>".$res1->customer_name."</td>";
				echo "<td class='text-right'>".app_number_format($res1->grand_total)."</td>";
				echo "<td class='text-right'>".app_number_format($res1->paid_amount)."</td>";
				echo "<td class='text-right'>".app_number_format($res1->grand_total-$res1->paid_amount)."</td>";
				echo "</tr>";
				$tot_grand_total+=$res1->grand_total;
				$tot_paid_amount+=$res1->paid_amount;
				$tot_due_amount+=($res1->grand_total-$res1->paid_amount);

			}

			echo "<tr>
					  <td class='text-right text-bold' colspan='5'><b>Total :</b></td>
					  <td class='text-right text-bold'>".app_number_format($tot_grand_total)."</td>
					  <td class='text-right text-bold'>".app_number_format($tot_paid_amount)."</td>
					  <td class='text-right text-bold'>".app_number_format($tot_due_amount)."</td>
				  </tr>";
		}
		else{
			echo "<tr>";
			echo "<td class='text-center text-danger' colspan=13>No Records Found</td>";
			echo "</tr>";
		}

	    exit;
	}
	
	public function show_due_report(){
	    $this->db->select("b.customer_name, b.customer_code, 
                   SUM(a.grand_total) AS total_grand_total, 
                   SUM(a.paid_amount) AS total_paid_amount, 
                   (SUM(a.grand_total) - SUM(a.paid_amount)) AS total_due,
                   (SELECT username FROM db_users WHERE a.salesman_id = db_users.id) AS username");
        $this->db->from("db_sales as a");
        $this->db->join("db_customers as b", "b.id = a.customer_id", "inner");
        $this->db->where("a.sales_status", "Final");
        $this->db->group_by("b.customer_name, b.customer_code");
        $this->db->having("total_due >", 0);
        $this->db->order_by("total_due", "DESC");
        
        $q1 = $this->db->get();
    	if($q1->num_rows()>0){
			$i=0;
			$tot_due_amount=0;
				foreach ($q1->result() as $res1) {
				echo "<tr>";
				echo "<td>".++$i."</td>";
				echo "<td>".$res1->customer_name."</td>";
				echo "<td>".$res1->total_grand_total."</td>";
				echo "<td>".$res1->total_paid_amount."</td>";
				echo "<td>".$res1->total_due."</td>";
				echo "</tr>";
				$tot_due_amount+=$res1->total_due;
			}
			echo "<tr>
				  <td class='text-right text-bold' colspan='4'><b>Total :</b></td>
				  <td class='text-bold'>".app_number_format($tot_due_amount)."</td>
			  </tr>";
    	}else{
			echo "<tr>";
			echo "<td class='text-center text-danger' colspan=5>No Records Found</td>";
			echo "</tr>";
		}
    	exit;
	}

	public function show_sales_return_report(){
		extract($_POST);

		$from_date=date("Y-m-d",strtotime($from_date));
		$to_date=date("Y-m-d",strtotime($to_date));

		$this->db->select("a.id,a.return_code,a.return_date,b.customer_name,b.customer_code,a.grand_total,a.paid_amount");

		if($customer_id!=''){

			$this->db->where("a.customer_id=$customer_id");
		}
		if($view_all=="no"){
			$this->db->where("(a.return_date>='$from_date' and a.return_date<='$to_date')");
		}
		$this->db->where("b.`id`= a.`customer_id`");
		$this->db->from("db_salesreturn as a");
		$this->db->from("db_customers as b");
		$this->db->select("CASE WHEN c.sales_code IS NULL THEN '' ELSE c.sales_code END AS sales_code");
		$this->db->join('db_sales as c','c.id=a.sales_id','left');


		//echo $this->db->get_compiled_select();exit();

		$q1=$this->db->get();
		if($q1->num_rows()>0){
			$i=0;
			$tot_grand_total=0;
			$tot_paid_amount=0;
			$tot_due_amount=0;
			foreach ($q1->result() as $res1) {
				echo "<tr>";
				echo "<td>".++$i."</td>";
				echo "<td><a title='View Invoice' href='".base_url("sales_return/invoice/$res1->id")."'>".$res1->return_code."</a></td>";
				echo "<td>".show_date($res1->return_date)."</td>";

				echo (!empty($res1->sales_code)) ? "<td><a title='Return Raised Against this Invoice' href='".base_url("sales/invoice/$res1->id")."'>".$res1->sales_code."</a></td>" : '<td>-NA-</td>';
				echo "<td>".$res1->customer_name."</td>";
				echo "<td class='text-right'>".app_number_format($res1->grand_total)."</td>";
				echo "<td class='text-right'>".app_number_format($res1->paid_amount)."</td>";
				echo "<td class='text-right'>".app_number_format($res1->grand_total-$res1->paid_amount)."</td>";
				echo "</tr>";
				$tot_grand_total+=$res1->grand_total;
				$tot_paid_amount+=$res1->paid_amount;
				$tot_due_amount+=($res1->grand_total-$res1->paid_amount);

			}

			echo "<tr>
					  <td class='text-right text-bold' colspan='5'><b>Total :</b></td>
					  <td class='text-right text-bold'>".app_number_format($tot_grand_total)."</td>
					  <td class='text-right text-bold'>".app_number_format($tot_paid_amount)."</td>
					  <td class='text-right text-bold'>".app_number_format($tot_due_amount)."</td>
				  </tr>";
		}
		else{
			echo "<tr>";
			echo "<td class='text-center text-danger' colspan=13>No Records Found</td>";
			echo "</tr>";
		}

	    exit;
	}

	public function show_purchase_report(){
		extract($_POST);

		$from_date=date("Y-m-d",strtotime($from_date));
		$to_date=date("Y-m-d",strtotime($to_date));

		$this->db->select("a.id,a.purchase_code,a.purchase_date,b.supplier_name,b.supplier_code,a.grand_total,a.paid_amount");

		if($supplier_id!=''){
			$this->db->where("a.supplier_id=$supplier_id");
		}
		if($view_all=="no"){
			$this->db->where("(a.purchase_date>='$from_date' and a.purchase_date<='$to_date')");
		}
		$this->db->where("b.`id`= a.`supplier_id`");
		$this->db->from("db_purchase as a");
		$this->db->where("a.`purchase_status`= 'Received'");
		$this->db->from("db_suppliers as b");

		//echo $this->db->get_compiled_select();

		$q1=$this->db->get();
		if($q1->num_rows()>0){
			$i=0;
			$tot_grand_total=0;
			$tot_paid_amount=0;
			$tot_due_amount=0;
			foreach ($q1->result() as $res1) {
				echo "<tr>";
				echo "<td>".++$i."</td>";
				echo "<td><a title='View Invoice' href='".base_url("purchase/invoice/$res1->id")."'>".$res1->purchase_code."</a></td>";
				echo "<td>".show_date($res1->purchase_date)."</td>";
				echo "<td>".$res1->supplier_code."</td>";
				echo "<td>".$res1->supplier_name."</td>";
				echo "<td class='text-right'>".app_number_format($res1->grand_total)."</td>";
				echo "<td class='text-right'>".app_number_format($res1->paid_amount)."</td>";
				echo "<td class='text-right'>".app_number_format($res1->grand_total-$res1->paid_amount)."</td>";
				echo "</tr>";
				$tot_grand_total+=$res1->grand_total;
				$tot_paid_amount+=$res1->paid_amount;
				$tot_due_amount+=($res1->grand_total-$res1->paid_amount);

			}

			echo "<tr>
					  <td class='text-right text-bold' colspan='5'><b>Total :</b></td>
					  <td class='text-right text-bold'>".app_number_format($tot_grand_total)."</td>
					  <td class='text-right text-bold'>".app_number_format($tot_paid_amount)."</td>
					  <td class='text-right text-bold'>".app_number_format($tot_due_amount)."</td>
				  </tr>";
		}
		else{
			echo "<tr>";
			echo "<td class='text-center text-danger' colspan=13>No Records Found</td>";
			echo "</tr>";
		}

	    exit;
	}

	public function show_purchase_return_report(){
		extract($_POST);

		$from_date=date("Y-m-d",strtotime($from_date));
		$to_date=date("Y-m-d",strtotime($to_date));

		$this->db->select("a.id,a.return_code,a.return_date,b.supplier_name,a.grand_total,a.paid_amount");

		if($supplier_id!=''){
			$this->db->where("a.supplier_id=$supplier_id");
		}
		if($view_all=="no"){
			$this->db->where("(a.return_date>='$from_date' and a.return_date<='$to_date')");
		}
		$this->db->where("b.`id`= a.`supplier_id`");
		$this->db->from("db_purchasereturn as a");
		$this->db->from("db_suppliers as b");
		$this->db->select("CASE WHEN c.purchase_code IS NULL THEN '' ELSE c.purchase_code END AS purchase_code");
		$this->db->join('db_purchase as c','c.id=a.purchase_id','left');

		//echo $this->db->get_compiled_select();

		$q1=$this->db->get();
		if($q1->num_rows()>0){
			$i=0;
			$tot_grand_total=0;
			$tot_paid_amount=0;
			$tot_due_amount=0;
			foreach ($q1->result() as $res1) {
				echo "<tr>";
				echo "<td>".++$i."</td>";
				echo "<td><a title='View Invoice' href='".base_url("purchase_return/invoice/$res1->id")."'>".$res1->return_code."</a></td>";
				echo "<td>".show_date($res1->return_date)."</td>";
				echo (!empty($res1->purchase_code)) ? "<td><a title='Return Raised Against this Invoice' href='".base_url("purchase/invoice/$res1->id")."'>".$res1->purchase_code."</a></td>" : '<td>-NA-</td>';

				echo "<td>".$res1->supplier_name."</td>";
				echo "<td class='text-right'>".app_number_format($res1->grand_total)."</td>";
				echo "<td class='text-right'>".app_number_format($res1->paid_amount)."</td>";
				echo "<td class='text-right'>".app_number_format($res1->grand_total-$res1->paid_amount)."</td>";
				echo "</tr>";
				$tot_grand_total+=$res1->grand_total;
				$tot_paid_amount+=$res1->paid_amount;
				$tot_due_amount+=($res1->grand_total-$res1->paid_amount);

			}

			echo "<tr>
					  <td class='text-right text-bold' colspan='5'><b>Total :</b></td>
					  <td class='text-right text-bold'>".app_number_format($tot_grand_total)."</td>
					  <td class='text-right text-bold'>".app_number_format($tot_paid_amount)."</td>
					  <td class='text-right text-bold'>".app_number_format($tot_due_amount)."</td>
				  </tr>";
		}
		else{
			echo "<tr>";
			echo "<td class='text-center text-danger' colspan=13>No Records Found</td>";
			echo "</tr>";
		}

	    exit;
	}

	public function show_expense_report(){
		extract($_POST);
		$from_date=date("Y-m-d",strtotime($from_date));
		$to_date=date("Y-m-d",strtotime($to_date));

		/*$q1=$this->db->query("SELECT a.*,b.category_name from db_expense as a,db_expense_category as b where b.id=a.category_id and a.expense_date>='$from_date' and expense_date<='$to_date'");*/

		$this->db->select("a.*,b.category_name");

		if($category_id!=''){
			$this->db->where("a.category_id=$category_id");
		}
		if($view_all=="no"){
			$this->db->where("(a.expense_date>='$from_date' and a.expense_date<='$to_date')");
		}
		$this->db->where("b.`id`= a.`category_id`");
		$this->db->from("db_expense as a");
		$this->db->from("db_expense_category as b");

		//echo $this->db->get_compiled_select();

		$q1=$this->db->get();

		if($q1->num_rows()>0){
			$i=0;
			$tot_expense_amt=0;
			foreach ($q1->result() as $res1) {
				echo "<tr>";
				echo "<td>".++$i."</td>";
				echo "<td>".$res1->expense_code."</td>";
				echo "<td>".$res1->expense_date."</td>";
				echo "<td>".$res1->category_name."</td>";
				echo "<td>".$res1->reference_no."</td>";
				echo "<td>".$res1->expense_for."</td>";
				echo "<td class='text-right'>".app_number_format($res1->expense_amt)."</td>";
				echo "<td>".$res1->note."</td>";
				echo "<td>".ucfirst($res1->created_by)."</td>";
				echo "</tr>";
				$tot_expense_amt+=$res1->expense_amt;
			}
			echo "<tr>
					  <td class='text-right text-bold' colspan='6'><b>Total Expense :</b></td>
					  <td class='text-right text-bold'>".app_number_format($tot_expense_amt)."</td>
				  </tr>";
		}
		else{
			echo "<tr>";
			echo "<td class='text-center text-danger' colspan=13>No Records Found</td>";
			echo "</tr>";
		}

	    exit;
	}

	public function show_stock_report(){
        $warehouse_id=$_POST['warehouse_id'] ?? 1;
		extract($_POST);


		$this->db->select("a.*,(select sum(qty) from db_stockentry where db_stockentry.item_id=a.id and db_stockentry.warehouse_id=$warehouse_id) as stockr,b.tax_name");
		$this->db->from("db_items as a,db_tax as b");
		$this->db->where("b.id=a.tax_id");
		$this->db->order_by("a.id");

		//echo $this->db->get_compiled_select();exit();

		$q1=$this->db->get();
		if($q1->num_rows()>0){
			$i=0;
			$tot_stock_value=0;
			$tot_purchase_price=0;
			$tot_sales_price=0;
			$tot_stock=0;
			foreach ($q1->result() as $res1) {
			   
				$tax_type = ($res1->tax_type=='Inclusive') ? 'Inc.' : 'Exc.';
				$stock_value = $res1->purchase_price * $res1->stockr;
				if($stock_value > 0){
				echo "<tr>";
				echo "<td>".++$i."</td>";
				echo "<td>".$res1->item_code."</td>";
				echo "<td>".$res1->item_name."</td>";
				echo "<td class='text-right'>".app_number_format($res1->purchase_price)."</td>";
				echo "<td>".$res1->tax_name."[".$tax_type."]</td>";
				echo "<td class='text-right'>".app_number_format($res1->sales_price)."</td>";
				echo "<td>".$res1->stockr."</td>";
				echo "<td class='text-right'>".$stock_value."</td>";
				echo "</tr>";
				$tot_purchase_price+=$res1->purchase_price;
				$tot_sales_price+=$res1->sales_price;
				$tot_stock_value+=$stock_value;
				$tot_stock+=$res1->stockr;
			    }
			}

			echo "<tr>
					  <td class='text-right text-bold' colspan='3'><b>Total :</b></td>
					  <td class='text-right text-bold'>".app_number_format($tot_purchase_price)."</td>
					  <td class='text-right text-bold'></td>
					  <td class='text-right text-bold'>".app_number_format($tot_sales_price)."</td>
					  <td class='text-bold'>".app_number_format($tot_stock)."</td>
					  <td class='text-right text-bold'>".app_number_format($tot_stock_value)."</td>
				  </tr>";
		}
		else{
			echo "<tr>";
			echo "<td class='text-center text-danger' colspan=8>No Records Found</td>";
			echo "</tr>";
		}

	    exit;
	}

	public function show_item_sales_report(){
		extract($_POST);

		$from_date=date("Y-m-d",strtotime($from_date));
		$to_date=date("Y-m-d",strtotime($to_date));

		$this->db->select("a.id,a.sales_code,a.sales_date,b.customer_name,b.customer_code,c.total_cost");
		$this->db->select("c.sales_qty,d.item_name");


		if($view_all=="no"){
			$this->db->where("(a.sales_date>='$from_date' and a.sales_date<='$to_date')");
		}
//		$this->db->group_by("c.`item_id`");
		$this->db->order_by("a.`sales_date`,a.sales_code",'asc');
		$this->db->from("db_sales as a");
		$this->db->where("a.`id`= c.`sales_id`");
		$this->db->where("a.`sales_status`= 'Final'");
		$this->db->from("db_items as d");
		$this->db->where("d.`id`= c.`item_id`");
		$this->db->from("db_customers as b");
		$this->db->where("b.`id`= a.`customer_id`");
		$this->db->from("db_salesitems as c");
		if($item_id!=''){
			$this->db->where("c.item_id=$item_id");
		}



		//echo $this->db->get_compiled_select();exit();

		$q1=$this->db->get();
		if($q1->num_rows()>0){
			$i=0;
			$tot_total_cost=0;
			$tot_paid_amount=0;
			$tot_due_amount=0;
			foreach ($q1->result() as $res1) {
				echo "<tr>";
				echo "<td>".++$i."</td>";
				echo "<td><a title='View Invoice' href='".base_url("sales/invoice/$res1->id")."'>".$res1->sales_code."</a></td>";
				echo "<td>".show_date($res1->sales_date)."</td>";
				echo "<td>".$res1->customer_name."</td>";
				echo "<td>".$res1->item_name."</td>";
				echo "<td>".$res1->sales_qty."</td>";
				echo "<td class='text-right'>".app_number_format($res1->total_cost)."</td>";

				echo "</tr>";
				$tot_total_cost+=$res1->total_cost;

			}

			echo "<tr>
					  <td class='text-right text-bold' colspan='6'><b>Total :</b></td>
					  <td class='text-right text-bold'>".app_number_format($tot_total_cost)."</td>
				  </tr>";
		}
		else{
			echo "<tr>";
			echo "<td class='text-center text-danger' colspan=13>No Records Found</td>";
			echo "</tr>";
		}

	    exit;
	}

	public function show_item_purchase_report(){
		extract($_POST);

		$from_date=date("Y-m-d",strtotime($from_date));
		$to_date=date("Y-m-d",strtotime($to_date));

		$this->db->select("a.id,a.purchase_code,a.purchase_date,b.supplier_name,b.supplier_code,c.total_cost");
		$this->db->select("c.purchase_qty,d.item_name");


		if($view_all=="no"){
			$this->db->where("(a.purchase_date>='$from_date' and a.purchase_date<='$to_date')");
		}
//		$this->db->group_by("c.`item_id`");
		$this->db->order_by("a.`purchase_date`,a.purchase_code",'asc');
		$this->db->from("db_purchase as a");
		$this->db->where("a.`id`= c.`purchase_id`");
		$this->db->where("a.`purchase_status`= 'Received'");
		$this->db->from("db_items as d");
		$this->db->where("d.`id`= c.`item_id`");
		$this->db->from("db_suppliers as b");
		$this->db->where("b.`id`= a.`supplier_id`");
		$this->db->from("db_purchaseitems as c");
		if($item_id!=''){
			$this->db->where("c.item_id=$item_id");
		}



		//echo $this->db->get_compiled_select();exit();

		$q1=$this->db->get();
		if($q1->num_rows()>0){
			$i=0;
			$tot_total_cost=0;
			$tot_paid_amount=0;
			$tot_due_amount=0;
			foreach ($q1->result() as $res1) {
				echo "<tr>";
				echo "<td>".++$i."</td>";
				echo "<td><a title='View Invoice' href='".base_url("purchase/invoice/$res1->id")."'>".$res1->purchase_code."</a></td>";
				echo "<td>".show_date($res1->purchase_date)."</td>";
				echo "<td>".$res1->supplier_name."</td>";
				echo "<td>".$res1->item_name."</td>";
				echo "<td>".$res1->purchase_qty."</td>";
				echo "<td class='text-right'>".app_number_format($res1->total_cost)."</td>";

				echo "</tr>";
				$tot_total_cost+=$res1->total_cost;

			}

			echo "<tr>
					  <td class='text-right text-bold' colspan='6'><b>Total :</b></td>
					  <td class='text-right text-bold'>".app_number_format($tot_total_cost)."</td>
				  </tr>";
		}
		else{
			echo "<tr>";
			echo "<td class='text-center text-danger' colspan=13>No Records Found</td>";
			echo "</tr>";
		}

	    exit;
	}

	public function show_purchase_payments_report(){
		extract($_POST);
		$supplier_id = $this->input->post('supplier_id');
		$from_date=date("Y-m-d",strtotime($from_date));
		$to_date=date("Y-m-d",strtotime($to_date));
		$payment_type = $this->input->post('payment_type');

		$this->db->select("c.id,c.purchase_code,a.payment_date,b.supplier_name,b.supplier_code,a.payment_type,a.payment_note,a.payment");

		if($supplier_id!=''){
			$this->db->where("c.supplier_id=$supplier_id");
		}
		if(!empty($payment_type)){
			$this->db->where("a.payment_type",$payment_type);
		}
		$this->db->where("b.id=c.`supplier_id`");
		$this->db->where("(a.payment_date>='$from_date' and a.payment_date<='$to_date')");

		$this->db->where("c.id=a.purchase_id");

		$this->db->from("db_purchasepayments as a");
		$this->db->from("db_suppliers as b");
		$this->db->from("db_purchase as c");
		$this->db->where("c.`purchase_status`= 'Received'");
		//$this->db->group_by("c.purchase_code");

		//echo $this->db->get_compiled_select();

		$q1=$this->db->get();
		if($q1->num_rows()>0){
			$i=0;
			$tot_payment=0;
			foreach ($q1->result() as $res1) {
				echo "<tr>";
				echo "<td>".++$i."</td>";
				echo "<td><a title='View Invoice' href='".base_url("purchase/invoice/$res1->id")."'>".$res1->purchase_code."</a></td>";
				echo "<td>".show_date($res1->payment_date)."</td>";
				echo "<td>".$res1->supplier_code."</td>";
				echo "<td>".$res1->supplier_name."</td>";
				echo "<td>".$res1->payment_type."</td>";
				echo "<td>".$res1->payment_note."</td>";
				echo "<td class='text-right'>".app_number_format($res1->payment)."</td>";
				echo "</tr>";
				$tot_payment+=$res1->payment;
			}

			echo "<tr>
					  <td class='text-right text-bold' colspan='7'><b>Total :</b></td>
					  <td class='text-right text-bold'>".app_number_format($tot_payment)."</td>
				  </tr>";
		}
		else{
			echo "<tr>";
			echo "<td class='text-center text-danger' colspan=8>No Records Found</td>";
			echo "</tr>";
		}

	    exit;
	}

	public function supplier_payments_report(){
		extract($_POST);

		$from_date=date("Y-m-d",strtotime($from_date));
		$to_date=date("Y-m-d",strtotime($to_date));
		$payment_type = $this->input->post('payment_type');

		$this->db->select("a.payment_date,b.supplier_name,a.payment_type,a.payment_note,a.payment");

		if($supplier_id!=''){
			$this->db->where("a.supplier_id=$supplier_id");
		}
		if(!empty($payment_type)){
			$this->db->where("a.payment_type",$payment_type);
		}
		$this->db->where("a.payment>0");
		$this->db->where("(a.payment_date>='$from_date' and a.payment_date<='$to_date')");



		$this->db->from("db_supplier_payments as a");
		$this->db->from("db_suppliers as b");
		$this->db->where("b.id=a.`supplier_id`");

		//$this->db->group_by("c.sales_code");

		//echo $this->db->get_compiled_select();

		$q1=$this->db->get();
		if($q1->num_rows()>0){
			$i=0;
			$tot_payment=0;
			foreach ($q1->result() as $res1) {
				echo "<tr>";
				echo "<td>".++$i."</td>";
				echo "<td>".show_date($res1->payment_date)."</td>";
				echo "<td>".$res1->supplier_name."</td>";
				echo "<td>".$res1->payment_type."</td>";
				echo "<td>".$res1->payment_note."</td>";
				echo "<td class='text-right'>".app_number_format($res1->payment)."</td>";
				echo "</tr>";
				$tot_payment+=$res1->payment;
			}

			echo "<tr>
					  <td class='text-right text-bold' colspan='5'><b>Total :</b></td>
					  <td class='text-right text-bold'>".app_number_format($tot_payment)."</td>
				  </tr>";
		}
		else{
			echo "<tr>";
			echo "<td class='text-center text-danger' colspan=6>No Records Found</td>";
			echo "</tr>";
		}

	    exit;
	}

	public function show_sales_payments_report(){
		extract($_POST);

		$from_date=date("Y-m-d",strtotime($from_date));
		$to_date=date("Y-m-d",strtotime($to_date));
		$payment_type = $this->input->post('payment_type');
		$this->db->select("c.id,c.sales_code,a.payment_date,b.customer_name,b.customer_code,a.payment_type,a.payment_note,a.payment");

		if($customer_id!=''){
			$this->db->where("c.customer_id=$customer_id");
		}
		if(!empty($payment_type)){
			$this->db->where("a.payment_type",$payment_type);
		}
		$this->db->where("b.id=c.`customer_id`");
		$this->db->where("a.payment>0");
		$this->db->where("(a.payment_date>='$from_date' and a.payment_date<='$to_date')");

		$this->db->where("c.id=a.sales_id");

		$this->db->from("db_salespayments as a");
		$this->db->from("db_customers as b");
		$this->db->from("db_sales as c");
		$this->db->where("c.`sales_status`= 'Final'");
		//$this->db->group_by("c.sales_code");

		//echo $this->db->get_compiled_select();

		$q1=$this->db->get();
		if($q1->num_rows()>0){
			$i=0;
			$tot_payment=0;
			foreach ($q1->result() as $res1) {
				echo "<tr>";
				echo "<td>".++$i."</td>";
				echo "<td><a title='View Invoice' href='".base_url("sales/invoice/$res1->id")."'>".$res1->sales_code."</a></td>";
				echo "<td>".show_date($res1->payment_date)."</td>";
				echo "<td>".$res1->customer_code."</td>";
				echo "<td>".$res1->customer_name."</td>";
				echo "<td>".$res1->payment_type."</td>";
				echo "<td>".$res1->payment_note."</td>";
				echo "<td class='text-right'>".app_number_format($res1->payment)."</td>";
				echo "</tr>";
				$tot_payment+=$res1->payment;
			}

			echo "<tr>
					  <td class='text-right text-bold' colspan='7'><b>Total :</b></td>
					  <td class='text-right text-bold'>".app_number_format($tot_payment)."</td>
				  </tr>";
		}
		else{
			echo "<tr>";
			echo "<td class='text-center text-danger' colspan=8>No Records Found</td>";
			echo "</tr>";
		}

	    exit;
	}

	public function customer_payments_report(){
		extract($_POST);

		$from_date=date("Y-m-d",strtotime($from_date));
		$to_date=date("Y-m-d",strtotime($to_date));
		$payment_type = $this->input->post('payment_type');
		$this->db->select("a.payment_date,b.customer_name,a.payment_type,a.payment_note,a.payment");

		if($customer_id!=''){
			$this->db->where("a.customer_id=$customer_id");
		}

		if(!empty($payment_type)){
			$this->db->where("a.payment_type",$payment_type);
		}

		$this->db->where("a.payment>0");
		$this->db->where("(a.payment_date>='$from_date' and a.payment_date<='$to_date')");



		$this->db->from("db_customer_payments as a");
		$this->db->from("db_customers as b");
		$this->db->where("b.id=a.`customer_id`");

		//$this->db->group_by("c.sales_code");

		//echo $this->db->get_compiled_select();

		$q1=$this->db->get();
		if($q1->num_rows()>0){
			$i=0;
			$tot_payment=0;
			foreach ($q1->result() as $res1) {
				echo "<tr>";
				echo "<td>".++$i."</td>";
				echo "<td>".show_date($res1->payment_date)."</td>";
				echo "<td>".$res1->customer_name."</td>";
				echo "<td>".$res1->payment_type."</td>";
				echo "<td>".$res1->payment_note."</td>";
				echo "<td class='text-right'>".app_number_format($res1->payment)."</td>";
				echo "</tr>";
				$tot_payment+=$res1->payment;
			}

			echo "<tr>
					  <td class='text-right text-bold' colspan='5'><b>Total :</b></td>
					  <td class='text-right text-bold'>".app_number_format($tot_payment)."</td>
				  </tr>";
		}
		else{
			echo "<tr>";
			echo "<td class='text-center text-danger' colspan=6>No Records Found</td>";
			echo "</tr>";
		}

	    exit;
	}
	/*Expired Items Report*/
	public function show_expired_items_report(){
		extract($_POST);
		$CI =& get_instance();


		$to_date=date("Y-m-d",strtotime($to_date));

		$this->db->select("id,item_code,item_name,expire_date,stock,lot_number");

		if($item_id!=''){

			$this->db->where("id=$item_id");
		}
		if($view_all=="no"){
			$this->db->where("(expire_date<='$to_date')");
		}
		$this->db->from("db_items");

		//echo $this->db->get_compiled_select();exit();

		$q1=$this->db->get();
		if($q1->num_rows()>0){
			$i=0;
			foreach ($q1->result() as $res1) {
				echo "<tr>";
				echo "<td>".++$i."</td>";
				echo "<td>".$res1->item_code."</td>";
				echo "<td>".$res1->item_name."</td>";
				echo "<td>".$res1->lot_number."</td>";
				echo "<td>".show_date($res1->expire_date)."</td>";
				echo "<td>".$res1->stock."</td>";

			}
		}
		else{
			echo "<tr>";
			echo "<td class='text-center text-danger' colspan=6>No Records Found</td>";
			echo "</tr>";
		}

	    exit;
	}

	public function get_profit_loss_report(){
			$from_date = $this->input->post('from_date');
			$to_date = $this->input->post('to_date');
			$from_date=date("Y-m-d",strtotime($from_date));
			$to_date=date("Y-m-d",strtotime($to_date));

			$info=array();

			//Get opening Balance
			$this->db->select("COALESCE(SUM(b.qty*a.purchase_price),0) AS  opening_stock_price");
			$this->db->from("db_items AS a , db_stockentry AS b");
			$this->db->where("a.id=b.item_id");
            $opening_stock_price=$this->db->get()->row()->opening_stock_price;
            $info['opening_stock_price']=number_format($opening_stock_price,2);

            //total purchase amt
			$this->db->select("COALESCE(SUM(a.tax_amt),0) AS tax_amt");
			$this->db->from("db_purchaseitems as a,db_purchase as b");
			$this->db->where("a.purchase_id=b.id and b.purchase_status='Received'");
			$this->db->where("(b.purchase_date>='$from_date' and b.purchase_date<='$to_date')");
            $purchase_tax_amt=$this->db->get()->row()->tax_amt;
            $info['purchase_tax_amt']=number_format($purchase_tax_amt,2);

            //total purchase amt
			$this->db->select("COALESCE(SUM(grand_total),0) AS pur_total");
			$this->db->from("db_purchase");
			$this->db->where("purchase_status='Received'");
			$this->db->where("(purchase_date>='$from_date' and purchase_date<='$to_date')");
            $pur_total=$this->db->get()->row()->pur_total;
            $pur_total-=$purchase_tax_amt;
            $info['pur_total']=number_format($pur_total,2);

            //Other Charge of Purchase entry
			$this->db->select("COALESCE(SUM(other_charges_amt),0) AS other_charges_amt");
			$this->db->from("db_purchase");
			$this->db->where("purchase_status='Received'");
			$this->db->where("(purchase_date>='$from_date' and purchase_date<='$to_date')");
            $pur_other_charges_amt=$this->db->get()->row()->other_charges_amt;
            $info['pur_other_charges_amt']=number_format($pur_other_charges_amt,2);



            //Disount purchase entry
			$this->db->select("COALESCE(SUM(a.discount_amt),0) AS discount_amt");
			$this->db->from("db_purchaseitems as a,db_purchase as b");
			$this->db->where("a.purchase_id=b.id and b.purchase_status='Received'");
			$this->db->where("(b.purchase_date>='$from_date' and b.purchase_date<='$to_date')");
            $purchase_discount_amt=$this->db->get()->row()->discount_amt;

			$this->db->select("COALESCE(SUM(tot_discount_to_all_amt),0) AS tot_discount_to_all_amt");
			$this->db->from("db_purchase");
			$this->db->where("purchase_status='Received'");
			$this->db->where("(purchase_date>='$from_date' and purchase_date<='$to_date')");
			$purchase_discount_amt=$this->db->get()->row()->tot_discount_to_all_amt;
            $info['purchase_discount_amt']=number_format($purchase_discount_amt,2);

            //purchase Paid Amount
			$this->db->select("COALESCE(SUM(paid_amount),0) AS paid_amount");
			$this->db->from("db_purchase");
			$this->db->where("(purchase_date>='$from_date' and purchase_date<='$to_date')");
            $purchase_paid_amount=$this->db->get()->row()->paid_amount;
            $info['purchase_paid_amount']=number_format($purchase_paid_amount,2);

            //total purchase return tax amt
            $this->db->select("COALESCE(SUM(a.tax_amt),0) AS tax_amt");
			$this->db->from("db_purchaseitemsreturn as a,db_purchasereturn as b");
			$this->db->where("a.return_id=b.id");
			$this->db->where("(b.return_date>='$from_date' and b.return_date<='$to_date')");
            $purchase_return_tax_amt=$this->db->get()->row()->tax_amt;
            $info['purchase_return_tax_amt']=number_format($purchase_return_tax_amt,2);

            //total purchase return amt
			$this->db->select("COALESCE(SUM(grand_total),0) AS pur_total");
			$this->db->from("db_purchasereturn");
			$this->db->where("(return_date>='$from_date' and return_date<='$to_date')");
            $pur_return_total=$this->db->get()->row()->pur_total;
            $pur_return_total-=$purchase_return_tax_amt;
            $info['pur_return_total']=(number_format($pur_return_total,2));





            //Other Charge of Purchase return entry
			$this->db->select("COALESCE(SUM(other_charges_amt),0) AS other_charges_amt");
			$this->db->from("db_purchasereturn");
			$this->db->where("(return_date>='$from_date' and return_date<='$to_date')");
            $pur_return_other_charges_amt=$this->db->get()->row()->other_charges_amt;
            $info['pur_return_other_charges_amt']=(number_format($pur_return_other_charges_amt,2));

            //Disount purchase return entry
			$this->db->select("COALESCE(SUM(a.discount_amt),0) AS discount_amt");
			$this->db->from("db_purchaseitemsreturn as a,db_purchasereturn as b");
			$this->db->where("a.return_id =b.id");
			$this->db->where("(b.return_date>='$from_date' and b.return_date<='$to_date')");
            $purchase_return_discount_amt=$this->db->get()->row()->discount_amt;

			$this->db->select("COALESCE(SUM(tot_discount_to_all_amt),0) AS tot_discount_to_all_amt");
			$this->db->from("db_purchasereturn");
			$this->db->where("(return_date>='$from_date' and return_date<='$to_date')");
			$purchase_return_discount_amt=$this->db->get()->row()->tot_discount_to_all_amt;
            $info['purchase_return_discount_amt']=(number_format($purchase_return_discount_amt,2));




            //Purchase Return Paid Amount
			$this->db->select("COALESCE(SUM(paid_amount),0) AS paid_amount");
			$this->db->from("db_purchasereturn");
			$this->db->where("(return_date>='$from_date' and return_date<='$to_date')");
            $purchase_return_paid_amount=$this->db->get()->row()->paid_amount;
            $info['purchase_return_paid_amount']=(number_format($purchase_return_paid_amount,2));


            //total sales amt
			$this->db->select("COALESCE(SUM(a.tax_amt),0) AS tax_amt");
			$this->db->from("db_salesitems as a,db_sales as b");
			$this->db->where("a.sales_id=b.id and b.sales_status='Final'");
			$this->db->where("(b.sales_date>='$from_date' and b.sales_date<='$to_date')");
            $sales_tax_amt=$this->db->get()->row()->tax_amt;
            $info['sales_tax_amt']=(number_format($sales_tax_amt,2));




            //Other Charge of Sales entry
			$this->db->select("COALESCE(SUM(other_charges_amt),0) AS other_charges_amt");
			$this->db->from("db_sales");
			$this->db->where("sales_status='Final'");
			$this->db->where("(sales_date>='$from_date' and sales_date<='$to_date')");
            $sal_other_charges_amt=$this->db->get()->row()->other_charges_amt;
            $info['sal_other_charges_amt']=(number_format($sal_other_charges_amt,2));




            //Disount sales entry

			/*$this->db->select("COALESCE(SUM(a.discount_amt),0) AS discount_amt");
			$this->db->from("db_salesitems as a,db_sales as b");
			$this->db->where(" a.sales_id=b.id and b.sales_status='Final'");
            $sales_discount_amt=$this->db->get()->row()->discount_amt;*/

			$this->db->select("COALESCE(SUM(tot_discount_to_all_amt),0) AS tot_discount_to_all_amt");
			$this->db->from("db_sales");
			$this->db->where("sales_status='Received'");
			$this->db->where("(sales_date>='$from_date' and sales_date<='$to_date')");
			$sales_discount_amt=$this->db->get()->row()->tot_discount_to_all_amt;
            $info['sales_discount_amt']=(number_format($sales_discount_amt,2));




            //Total SAles amount
			$this->db->select("COALESCE(sum(grand_total),0) AS tot_sal_grand_total");
			$this->db->from("db_sales");
			$this->db->where("sales_status='Final'");
			$this->db->where("(sales_date>='$from_date' and sales_date<='$to_date')");
            $sal_total=$this->db->get()->row()->tot_sal_grand_total;
            $sal_total-=$sales_tax_amt;
            $info['sal_total']=(number_format($sal_total,2));




            //sales Paid Amount
			$this->db->select("COALESCE(SUM(paid_amount),0) AS paid_amount");
			$this->db->from("db_sales");
			$this->db->where("(sales_date>='$from_date' and sales_date<='$to_date')");
            $sales_paid_amount=$this->db->get()->row()->paid_amount;
            $info['sales_paid_amount']=(number_format($sales_paid_amount,2));



            //total sales return amt
			$this->db->select("COALESCE(SUM(a.tax_amt),0) AS tax_amt");
			$this->db->from("db_salesitemsreturn as a");
			$this->db->from("db_salesreturn as b");
			$this->db->where("a.return_id=b.id");
			$this->db->where("(b.return_date>='$from_date' and b.return_date<='$to_date')");
            $sales_return_tax_amt=$this->db->get()->row()->tax_amt;
            $info['sales_return_tax_amt']=(number_format($sales_return_tax_amt,2));




            //total sales return amt
			$this->db->select("COALESCE(SUM(grand_total),0) AS sal_total");
			$this->db->from("db_salesreturn");
			$this->db->where("(return_date>='$from_date' and return_date<='$to_date')");
            $sal_return_total=$this->db->get()->row()->sal_total;
            $sal_return_total-=$sales_return_tax_amt;
            $info['sal_return_total']=(number_format($sal_return_total,2));



            //Other Charge of Sales return entry
			$this->db->select("COALESCE(SUM(other_charges_amt),0) AS other_charges_amt");
			$this->db->from("db_salesreturn");
			$this->db->where("(return_date>='$from_date' and return_date<='$to_date')");
            $sal_return_other_charges_amt=$this->db->get()->row()->other_charges_amt;
            $info['sal_return_other_charges_amt']=(number_format($sal_return_other_charges_amt,2));

            //Disount sales return entry
			$this->db->select("COALESCE(SUM(a.discount_amt),0) AS discount_amt");
			$this->db->from("db_salesitemsreturn as a, db_salesreturn as b ");
			$this->db->where("a.return_id = b.id");
			$this->db->where("(b.return_date>='$from_date' and b.return_date<='$to_date')");
            $sales_return_discount_amt=$this->db->get()->row()->discount_amt;

			$this->db->select("COALESCE(SUM(tot_discount_to_all_amt),0) AS tot_discount_to_all_amt");
			$this->db->from("db_salesreturn");
			$this->db->where("(return_date>='$from_date' and return_date<='$to_date')");
            $sales_return_discount_amt=$this->db->get()->row()->tot_discount_to_all_amt;
            $info['sales_return_discount_amt']=(number_format($sales_return_discount_amt,2));


            //sales Return Paid Amount
			$this->db->select("COALESCE(SUM(paid_amount),0) AS paid_amount");
			$this->db->from("db_salesreturn");
			$this->db->where("(return_date>='$from_date' and return_date<='$to_date')");
            $sales_return_paid_amount=$this->db->get()->row()->paid_amount;
            $info['sales_return_paid_amount']=(number_format($sales_return_paid_amount,2));


            //total expense amt
			$this->db->select("COALESCE(SUM(expense_amt),0) AS exp_total");
			$this->db->from("db_expense");
			$this->db->where("(expense_date>='$from_date' and expense_date<='$to_date')");
            $exp_total=$this->db->get()->row()->exp_total;
            $info['exp_total']=(number_format($exp_total,2));;

            //total purchase due
			$this->db->select("(COALESCE(SUM(grand_total),0)-COALESCE(SUM(paid_amount),0)) AS purchase_due");
			$this->db->from("db_purchase");
			$this->db->where("(purchase_date>='$from_date' and purchase_date<='$to_date')");
            $purchase_due_total=$this->db->get()->row()->purchase_due;
            $info['purchase_due_total']=(number_format($purchase_due_total,2));

            //total purchase due
			$this->db->select("(COALESCE(SUM(grand_total),0)-COALESCE(SUM(paid_amount),0)) AS purchase_due");
			$this->db->from("db_purchasereturn");
			$this->db->where("(return_date>='$from_date' and return_date<='$to_date')");
            $purchase_return_due_total=$this->db->get()->row()->purchase_due;
            $info['purchase_return_due_total']=(number_format($purchase_return_due_total,2));

            //total sales due
			$this->db->select("(COALESCE(SUM(grand_total),0)-COALESCE(SUM(paid_amount),0)) AS sales_due");
			$this->db->from("db_sales");
			$this->db->where("(sales_date>='$from_date' and sales_date<='$to_date')");
            $sales_due_total=$this->db->get()->row()->sales_due;
            $info['sales_due_total']=(number_format($sales_due_total,2));

            //total sales return due
			$this->db->select("(COALESCE(SUM(grand_total),0)-COALESCE(SUM(paid_amount),0)) AS return_due");
			$this->db->from("db_salesreturn");
			$this->db->where("(return_date>='$from_date' and return_date<='$to_date')");
            $sales_return_due_total=$this->db->get()->row()->return_due;
            $info['sales_return_due_total']=(number_format($sales_return_due_total,2));


			$this->db->select("b.tax_amt,b.item_id,a.item_name,COALESCE(sum(b.sales_qty),0) as sales_qty,a.purchase_price,
                  COALESCE(SUM(total_cost),0) as total_cost");
			$this->db->from("db_items as a, db_salesitems as b, db_sales as c");
			$this->db->where("c.id=b.sales_id and a.id=b.item_id and c.sales_status='Final'");
			$this->db->where("(c.sales_date>='$from_date' and c.sales_date<='$to_date')");

			$this->db->group_by("item_id");
			//$this->db->where("a.service_bit=0");
            $q1=$this->db->get();

            /*SELECT SUM(a.total_cost ) - SUM(a.sales_qty*b.purchase_price) AS gross FROM
			  db_salesitems a,
			  db_items b
			  WHERE b.id = a.item_id
			*/
            if($q1->num_rows()>0){
            $i=0;
            $tot_purchase_price=0;
            $tot_sales_cost=0;
            $gross_profit=0;
            $tot_purchase_return_price=0;
            $tot_sales_return_price=0;
            $tot_sales_qty=0;
            $tot_purchase_return_qty=0;
            $tot_sales_return_qty=0;
            $grand_profit=0;
            $tot_net_profit=0;
            foreach ($q1->result() as $res1) {
	              /*Purchase Return Quantity*/
	              $purchase_return_qty=$this->db->query("
	                  SELECT COALESCE(sum(a.return_qty),0) as return_qty
	                  FROM db_purchaseitemsreturn as a, db_purchasereturn as b
	                  WHERE
	                  a.return_id = b.id and
	                  b.return_date>='".$from_date."' and b.return_date<='".$to_date."' and
	                  a.item_id =".$res1->item_id)->row()->return_qty;

	              /*Sales Return Quantity*/
	              $q3=$this->db->query("
	                  SELECT COALESCE(sum(a.total_cost),0) as total_cost,COALESCE(sum(a.return_qty),0) as return_qty
	                  FROM db_salesitemsreturn as a,db_salesreturn as b
	                  WHERE
	                  a.return_id = b.id and
	                  b.return_date>='".$from_date."' and b.return_date<='".$to_date."' and
	                  a.item_id =".$res1->item_id);

	              $sales_return_total_cost=$q3->row()->total_cost;
	              $sales_return_qty=$q3->row()->return_qty;

	              $qty = $res1->sales_qty-$sales_return_qty;
	              $purchase_price = $res1->purchase_price * $qty;

	              $total_cost = ($res1->total_cost - $sales_return_total_cost);
	              //$purchase_return_price = $res1->purchase_price*$purchase_return_qty;
	              $profit = $total_cost - $purchase_price;

	              $tax_amt = $res1->tax_amt/$res1->sales_qty;

	                //$net_profit =$profit-($tax_amt*$qty);
	                $net_profit =$profit;//As Per Customer Needs

	              $gross_profit+=$profit;
	              $tot_net_profit+=$net_profit;
            	}  //for
            }//foreach
            else{
	            $gross_profit=0;
	            $tot_net_profit=0;
            }
            //$gross_profit -=$exp_total;
            $tot_net_profit -=$exp_total;
            $info['gross_profit']=(number_format($gross_profit,2));
            $info['tot_net_profit']=(number_format($tot_net_profit,2));
            return $info;
	}

	public function get_profit_by_item(){
		$CI =& get_instance();
		extract($_POST);
		$from_date=date("Y-m-d",strtotime($from_date));
		$to_date=date("Y-m-d",strtotime($to_date));

		$q1=$this->db->query("
				SELECT c.id as sales_id,b.tax_amt,b.item_id,a.item_name,COALESCE(sum(b.sales_qty),0) as sales_qty,a.purchase_price,
						COALESCE(SUM(total_cost),0) as total_cost
				FROM db_items as a, db_salesitems as b, db_sales as c
				WHERE
				c.id=b.sales_id
				and
				a.id=b.item_id
				and
				c.sales_status='Final'
				and
				( c.sales_date>='".$from_date."' and  c.sales_date<='".$to_date."')
				GROUP BY item_id
			");

		if($q1->num_rows()>0){
			$i=0;
			$tot_purchase_price=0;
			$tot_sales_cost=0;
			$gross_profit=0;
			$tot_purchase_return_price=0;
			$tot_sales_return_price=0;
			$tot_sales_qty=0;
			$tot_purchase_return_qty=0;
			$tot_sales_return_qty=0;
			$grand_profit=0;
			$tot_net_profit=0;
			foreach ($q1->result() as $res1) {
				$sales_id = $res1->sales_id;
				/*Purchase Return Quantity*/
				$purchase_return_qty=$this->db->query("
						SELECT COALESCE(sum(return_qty),0) as return_qty
						FROM db_purchaseitemsreturn
						WHERE
						item_id =".$res1->item_id)->row()->return_qty;

				/*Sales Return Quantity*/
				$q3=$this->db->query("
						SELECT COALESCE(sum(total_cost),0) as total_cost,COALESCE(sum(return_qty),0) as return_qty
						FROM db_salesitemsreturn
						WHERE
						sales_id='$sales_id' and
						item_id =".$res1->item_id);
				$sales_return_total_cost=$q3->row()->total_cost;
				$sales_return_qty=$q3->row()->return_qty;

				$qty = $res1->sales_qty-$sales_return_qty;

				$purchase_price = $res1->purchase_price * $qty;

				$total_cost = ($res1->total_cost - $sales_return_total_cost);
				//$purchase_return_price = $res1->purchase_price*$purchase_return_qty;
				$profit = $total_cost - $purchase_price;

				$tax_amt = $res1->tax_amt/$res1->sales_qty;

			    $net_profit =$profit-($tax_amt*$qty);

				echo "<tr>";
				echo "<td>".++$i."</td>";
				echo "<td>".$res1->item_name."</td>";
				echo "<td>".$qty."</td>";
				echo "<td style='text-align:right;'>".app_number_format($total_cost)."</td>";
				echo "<td style='text-align:right;'>".app_number_format($purchase_price)."</td>";
				echo "<td style='text-align:right;'>".app_number_format($profit)."</td>";
				/*echo "<td style=''>".$purchase_return_qty."</td>";
				echo "<td style='text-align:right;'>".app_number_format($purchase_return_price)."</td>";*/
				/*echo "<td style=''>".$sales_return_qty."</td>";
				echo "<td style='text-align:right;'>".app_number_format($sales_return_total_cost)."</td>";*/
				/*echo "<td style='text-align:right;'>".app_number_format($net_profit)."</td>";*/
				echo "</tr>";
				$tot_purchase_price+=$purchase_price;
				//$tot_purchase_return_price+=$purchase_return_price;
				$tot_sales_cost+=$total_cost;
				//$tot_sales_return_cost+=$sales_return_total_cost;
				//$gross_profit+=(($profit + $purchase_return_price)-$sales_return_total_cost);
				$tot_sales_qty+=($res1->sales_qty-$sales_return_qty);
				$tot_purchase_return_qty+=$purchase_return_qty;
				$tot_sales_return_qty+=$sales_return_qty;
				$gross_profit+=$profit;
				$tot_net_profit+=$net_profit;
			}
			echo "<tr>
					  <td class='text-right text-bold' colspan='2'><b>Total :</b></td>
					  <td class='text-bold'>".$tot_sales_qty."</td>
					  <td class='text-right text-bold'>".app_number_format($tot_sales_cost)."</td>
					  <td class='text-right text-bold'>".app_number_format($tot_purchase_price)."</td>

					  <td class='text-right text-bold'>".app_number_format($gross_profit)."</td>

				  </tr>";
				  /*<td class='text-bold'>".$tot_purchase_return_qty."</td>
					  <td class='text-right text-bold'>".app_number_format($tot_purchase_return_price)."</td>
					  <td class='text-bold'>".$tot_sales_return_qty."</td>
					  <td class='text-right text-bold'>".app_number_format($tot_sales_return_cost)."</td>
					  */
		}
		else{
			echo "<tr>";
			echo "<td class='text-center text-danger' colspan=6>No Records Found</td>";
			echo "</tr>";
		}

	    exit;
	}

	public function get_profit_by_invoice(){
		$CI =& get_instance();
		extract($_POST);
		$from_date=date("Y-m-d",strtotime($from_date));
		$to_date=date("Y-m-d",strtotime($to_date));
		$q1=$this->db->query("SELECT a.id,a.sales_date,a.sales_code,b.customer_name ,a.tot_discount_to_all_amt as discount from db_sales as a,db_customers as b
								where
								a.sales_status='Final'
								and
								b.id=a.customer_id
								and
								( a.sales_date>='".$from_date."' and  a.sales_date<='".$to_date."')
								");

		if($q1->num_rows()>0){
			$i=0;
			$tot_purchase_price=0;
			$tot_sales_cost=0;
			$tot_profit=0;
			$net_profit=0;
			$tot_net_profit=0;
			$tot_discount=0;

			foreach ($q1->result() as $res1) {
				$q2=$this->db->query("SELECT b.sales_qty,COALESCE(SUM(purchase_price*sales_qty),0) AS purchase_price, COALESCE(SUM(total_cost),0) AS total_cost FROM db_items AS a, db_salesitems AS b, db_sales AS c WHERE c.id=b.sales_id AND a.id=b.item_id and c.sales_status='Final'
					AND b.sales_id=".$res1->id);

				$q3=$this->db->query("SELECT COALESCE(SUM(purchase_price*return_qty),0) AS purchase_price, COALESCE(SUM(total_cost),0) AS total_cost FROM db_items AS a, db_salesitemsreturn AS b, db_salesreturn AS c WHERE c.id=b.return_id AND a.id=b.item_id and c.return_status!='Final'
					AND b.sales_id=".$res1->id);
				$purchase_return_price=$q3->row()->purchase_price;


				$tot_discount =  $res1->discount;
				$tot_discount = (empty($tot_discount)) ? 0 : $tot_discount;

				//Total price item_purchase_price * qty
				$purchase_price = ($q2->row()->purchase_price-$purchase_return_price);
				//Total price item_sales_price * qty
				$sales_price = ($q2->row()->total_cost-$q3->row()->total_cost);

				$profit = ($sales_price - $purchase_price)-($tot_discount);


				/*$sales_tax_amt =$this->db->query("select COALESCE(SUM(tax_amt),0) AS tax_amt from db_salesitems where sales_id=".$res1->id)->row()->tax_amt;

				$sales_return_tax_amt =$this->db->query("select COALESCE(SUM(tax_amt),0) AS tax_amt from db_salesitemsreturn where sales_id=".$res1->id)->row()->tax_amt;

				$net_profit = $profit + ($sales_tax_amt-$sales_return_tax_amt);*/
				echo "<tr>";
				echo "<td>".++$i."</td>";
				echo "<td>".$res1->sales_code."</td>";
				echo "<td>".show_date($res1->sales_date)."</td>";
				echo "<td>".$res1->customer_name."</td>";
				echo "<td style='text-align:right;'>".app_number_format($sales_price)."</td>";
				echo "<td style='text-align:right;'>".app_number_format($purchase_price)."</td>";
				echo "<td style='text-align:right;'>".app_number_format($tot_discount)."</td>";
				echo "<td style='text-align:right;'>".app_number_format($profit)."</td>";
				//echo "<td style='text-align:right;'>".app_number_format($net_profit)."</td>";
				echo "</tr>";
				$tot_purchase_price+=$purchase_price;
				$tot_sales_cost+=$sales_price;
				$tot_profit+=$profit;
				$tot_net_profit+=$net_profit;
			}
			echo "<tr>
					  <td class='text-right text-bold' colspan='4'><b>Total :</b></td>
					  <td class='text-right text-bold'>".app_number_format($tot_sales_cost)."</td>
					  <td class='text-right text-bold'>".app_number_format($tot_purchase_price)."</td>
					  <td class='text-right text-bold'>".app_number_format($tot_profit)."</td>

				  </tr>";
		}
		else{
			echo "<tr>";
			echo "<td class='text-center text-danger' colspan=7>No Records Found</td>";
			echo "</tr>";
		}

	    exit;
	}

	public function brand_wise_stock(){
		extract($_POST);


		$this->db->select("a.item_name,COALESCE(sum(a.stock),0) as stock");
		$this->db->select("b.brand_name");
		$this->db->from("db_items as a");
		$this->db->join('db_brands as b', 'b.id=a.brand_id', 'left');
		$this->db->order_by("b.brand_name");
		$this->db->group_by("b.brand_name");

		//echo $this->db->get_compiled_select();exit();

		$q1=$this->db->get();
		if($q1->num_rows()>0){
			$i=0;
			foreach ($q1->result() as $res1) {
				//$tax_type = ($res1->tax_type=='Inclusive') ? 'Inc.' : 'Exc.';
				echo "<tr>";
				echo "<td>".++$i."</td>";
				//echo "<td>".$res1->item_code."</td>";
				echo "<td>".$res1->brand_name."</td>";
				//echo "<td class='text-right'>".$res1->purchase_price."</td>";
				//echo "<td>".$res1->tax_name."[".$tax_type."]</td>";
				//echo "<td class='text-right'>".$res1->sales_price."</td>";
				echo "<td>".$res1->stock."</td>";
				echo "</tr>";
			}
		}
		else{
			echo "<tr>";
			echo "<td class='text-center text-danger' colspan=13>No Records Found</td>";
			echo "</tr>";
		}

	    exit;
	}

	public function statement($type,$startDate,$endDate,$paytype=false){
		if($paytype)
		    $dataty=$this->db->query("SELECT GROUP_CONCAT(payment_type) as datatype,GROUP_CONCAT(id) as dataid FROM `db_paymenttypes` where `bank_cash`='$type' and payment_type='$paytype' GROUP by `bank_cash`")->row();
	    else
		    $dataty=$this->db->query("SELECT GROUP_CONCAT(payment_type) as datatype,GROUP_CONCAT(id) as dataid FROM `db_paymenttypes` where `bank_cash`='$type' GROUP by `bank_cash`")->row();

		$datatype=explode(',',$dataty->datatype);
		$datatypeid=explode(',',$dataty->dataid);
		$dataty=$dataty->dataid;
		//echo "<pre>";print_r($data);echo "<pre/>";exit;
		$result = [
			'order_payments'=>[],
			'sales_payments'=>[],
			'sales_paymentsreturn'=>[],
			'purchase_payments'=>[],
			'purchase_paymentsreturn'=>[],
			'expenses'=>[],
			'transactions'=>[],
			'last_openning_balance'=>0
		];
		$this->db->select('db_salespayments.id,db_salespayments.payment_date,db_salespayments.payment,db_sales.sales_code');
		$this->db->from('db_salespayments');
		$this->db->join('db_sales', 'db_sales.id = db_salespayments.sales_id');
		$this->db->where_in('db_salespayments.payment_type',$datatype);
		$this->db->where("db_salespayments.payment_date BETWEEN '$startDate' and  '$endDate'");
		$sales_payments = $this->db->get();
		if($sales_payments->num_rows()>0){
			$result['sales_payments'] = $sales_payments->result();
		}
		$this->db->select('order_payments.id,order_payments.payment_date,order_payments.payment,orders.invoice_no');
		$this->db->from('order_payments');
		$this->db->join('orders', 'orders.id = order_payments.order_id');
		$this->db->where_in('order_payments.payment_type',$datatypeid);
		$this->db->where("order_payments.payment_date BETWEEN '$startDate' and  '$endDate'");
		$order_payments = $this->db->get();
		if($order_payments->num_rows()>0){
			$result['order_payments'] = $order_payments->result();
		}

		// getting sales payment return
		$this->db->select('dsr.id,dsr.payment_date,dsr.payment,db_sales.sales_code');
		$this->db->from('db_salespaymentsreturn as dsr');
		$this->db->join('db_sales', 'db_sales.id = dsr.sales_id', 'left');
		$this->db->where_in('dsr.payment_type',$datatype);
		$this->db->where("dsr.payment_date BETWEEN '$startDate' and  '$endDate'");
		$sales_payments = $this->db->get();
		if($sales_payments->num_rows()>0){
			$result['sales_paymentsreturn'] = $sales_payments->result();
		}
		//Getting Purchases
		$this->db->select('db_purchasepayments.id,db_purchasepayments.payment_date,db_purchasepayments.payment,db_purchase.purchase_code');
		$this->db->from('db_purchasepayments');
		$this->db->join('db_purchase', 'db_purchase.id = db_purchasepayments.purchase_id');
		$this->db->where_in('db_purchasepayments.payment_type',$datatype);
		$this->db->where("db_purchasepayments.payment_date BETWEEN '$startDate' and  '$endDate'");
		$purchase_payments = $this->db->get();
		//print_r($purchase_payments); exit;
		if($purchase_payments->num_rows()>0){
			$result['purchase_payments'] = $purchase_payments->result();
		}

		// getting purchases payment return
		$this->db->select('dpr.id,dpr.payment_date,dpr.payment,db_sales.sales_code');
		$this->db->from('db_purchasepaymentsreturn as dpr');
		$this->db->join('db_sales', 'db_sales.id = dpr.purchase_id', 'left');
		$this->db->where_in('dpr.payment_type',$datatype);
		$this->db->where("dpr.payment_date BETWEEN '$startDate' and  '$endDate'");
		$sales_payments = $this->db->get();
		if($sales_payments->num_rows()>0){
			$result['purchase_paymentsreturn'] = $sales_payments->result();
		}

		//Getting Expenses
		$this->db->select('id,expense_code,expense_date,expense_amt,expense_for');
		$this->db->from('db_expense');
		$this->db->where("expense_date BETWEEN '$startDate' and  '$endDate'");
		$expenses = $this->db->get();
		if($expenses->num_rows()>0){
			$result['expenses'] = $expenses->result();
		}

		//Getting Transactions
		$this->db->select("*");
		$this->db->from('db_transactions');
		$this->db->where("db_transactions.date BETWEEN '$startDate' and  '$endDate'");
		if($type!=1){
		    $this->db->where_in('db_transactions.bank_id',$datatypeid);
		}

		$transactions = $this->db->get();
		//print_r($purchase_payments); exit;
		if($transactions->num_rows()>0){
			$result['transactions'] = $transactions->result();
		}
		$result['last_openning_balance'] = $this->lastOpenningBalanceData($dataty,$startDate,$datatype,$type);
		//$last_openning_date = date('Y-m-d', strtotime($startDate. '-1 days'));
		$result['last_openning_date'] = $this->lastOpenningBalanceData($dataty,$startDate,$datatype,$type);
		//echo "<pre>";print_r($datatype);echo "<pre/>";exit;
		return $result;
	}
	public function lastOpenningBalanceData($dataty,$lastDate,$datatype,$type){
	    $datatype=implode("','",$datatype);
		//return $lastDate;
		$orderPayments = $this->db->query("SELECT sum(payment) as order_payment FROM order_payments WHERE DATE(payment_date) < DATE('$lastDate') and payment_type in ('$dataty')");

		$salesPayments = $this->db->query("SELECT sum(payment) as sales_payment FROM db_salespayments WHERE DATE(payment_date) < DATE('$lastDate') and payment_type in ('$datatype')");
		$salesPaymentsReturn = $this->db->query("SELECT sum(payment) as sales_paymentr FROM db_salespaymentsreturn WHERE DATE(payment_date) < DATE('$lastDate') and payment_type in ('$datatype')");

		$purchasePayments = $this->db->query("SELECT sum(payment) as purchase_payment FROM db_purchasepayments WHERE DATE(payment_date) < DATE('$lastDate') and payment_type in ('$datatype')");
		$purchasePaymentsReturn = $this->db->query("SELECT sum(payment) as purchase_paymentr FROM db_purchasepaymentsreturn WHERE DATE(payment_date) < DATE('$lastDate') and payment_type in ('$datatype')");
		$expenses = 0;
		if($type==1)
		    $expenses = $this->db->query("SELECT sum(expense_amt) as expense_amount FROM db_expense WHERE DATE(expense_date) < DATE('$lastDate')")->row()->expense_amount;

		if($type==1)
		    $transactionsWithdraw = $this->db->query("SELECT sum(amount) as trans_withdraw FROM db_transactions WHERE DATE(date) < DATE('$lastDate') AND type='1'");
		else
		    $transactionsWithdraw = $this->db->query("SELECT sum(amount) as trans_withdraw FROM db_transactions WHERE DATE(date) < DATE('$lastDate') AND type='1' AND bank_id in ($dataty)");

		if($type==1)
		    $transactionsDeposit = $this->db->query("SELECT sum(amount) trans_deposit FROM db_transactions WHERE DATE(date) < DATE('$lastDate') AND type='2'");
		else
		    $transactionsDeposit = $this->db->query("SELECT sum(amount) trans_deposit FROM db_transactions WHERE DATE(date) < DATE('$lastDate') AND type='2' AND bank_id in ($dataty)");

		$firstOpenningBalance = $this->db->query("SELECT sum(amount) as first_cash FROM db_openning_balance_info WHERE bank_id in ($dataty)");

		$orderPayments = $orderPayments->row()->order_payment;
		$salesPayments = $salesPayments->row()->sales_payment;
		$salesPaymentsReturn = $salesPaymentsReturn->row()->sales_paymentr;
		$purchasePayments = $purchasePayments->row()->purchase_payment;
		$purchasePaymentsReturn = $purchasePaymentsReturn->row()->purchase_paymentr;
		$expenses = $expenses?$expenses:0;
		$transactionsWithdraw = $transactionsWithdraw->row()->trans_withdraw;
		$transactionsDeposit = $transactionsDeposit->row()->trans_deposit;

		$firstOpenningBalance = $firstOpenningBalance->row()->first_cash;
		/*echo "<pre>f";
		print_r($firstOpenningBalance);
		echo "<br>s";
		print_r($salesPayments);
		echo "<br>sr";
		print_r($salesPaymentsReturn);
		echo "<br>p";
		print_r($purchasePayments);
		echo "<br>pr";
		print_r($purchasePaymentsReturn);
		echo "<br>e";
		print_r($expenses);
		echo "<br>w";
		print_r($transactionsWithdraw);
		echo "<br>t";
		print_r($transactionsDeposit);
		echo "<pre/>";*/
		if($type==1){
    		$totalPlusFigure  = ($orderPayments+$salesPayments+$transactionsWithdraw+$firstOpenningBalance+$purchasePaymentsReturn);
    		$totalMinusFigure = ($purchasePayments+$expenses+$transactionsDeposit+$salesPaymentsReturn);
		}else{
    		$totalPlusFigure  = ($orderPayments+$salesPayments+$transactionsDeposit+$firstOpenningBalance+$purchasePaymentsReturn);
    		$totalMinusFigure = ($purchasePayments+$expenses+$transactionsWithdraw+$salesPaymentsReturn);
		}

		$openningBalance = $totalPlusFigure - $totalMinusFigure;
		/*echo "<br>to";
		print_r($openningBalance);*/
		//exit;
		return $openningBalance;
	}

	function getData($table,$select,$join=false,$conditions=false){
		$this->db->select($select);
		$this->db->from($table);
		$this->db->get();
	}



	public function show_salesman_report(){
		extract($_POST);

		$from_date=date("Y-m-d",strtotime($from_date));
		$to_date=date("Y-m-d",strtotime($to_date));

		$this->db->select("sum(grand_total) as grand_total,sum(paid_amount) as paid_amount,(select username from db_users where a.salesman_id=db_users.id) as username");


		if($view_all=="no")
			$this->db->where("(sales_date>='$from_date' and sales_date<='$to_date')");

		$this->db->from("db_sales as a");
		$this->db->where("sales_status= 'Final'");
		$this->db->group_by('salesman_id');

		//echo $this->db->get_compiled_select();exit();

		$q1=$this->db->get();
		if($q1->num_rows()>0){
			$i=0;
			$tot_grand_total=0;
			$tot_paid_amount=0;
			$tot_due_amount=0;
			foreach ($q1->result() as $res1) {
				echo "<tr>";
				echo "<td>".++$i."</td>";
				echo "<td>".$res1->username."</td>";
				echo "<td class='text-right'>".app_number_format($res1->grand_total)."</td>";
				echo "<td class='text-right'>".app_number_format($res1->paid_amount)."</td>";
				echo "<td class='text-right'>".app_number_format($res1->grand_total-$res1->paid_amount)."</td>";
				echo "</tr>";
				$tot_grand_total+=$res1->grand_total;
				$tot_paid_amount+=$res1->paid_amount;
				$tot_due_amount+=($res1->grand_total-$res1->paid_amount);

			}

			echo "<tr>
					  <td class='text-right text-bold' colspan='2'><b>Total :</b></td>
					  <td class='text-right text-bold'>".app_number_format($tot_grand_total)."</td>
					  <td class='text-right text-bold'>".app_number_format($tot_paid_amount)."</td>
					  <td class='text-right text-bold'>".app_number_format($tot_due_amount)."</td>
				  </tr>";
		}
		else{
			echo "<tr>";
			echo "<td class='text-center text-danger' colspan=13>No Records Found</td>";
			echo "</tr>";
		}

	    exit;
	}
}
