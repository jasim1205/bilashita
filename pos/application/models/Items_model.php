<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Items_model extends CI_Model {

	//Datatable start
	/*var $table = 'db_items as a';
	var $column_order = array( 'a.id','a.item_image','a.item_code','a.item_name','b.category_name','sb.subcategory_name','sbc.childcategory_name','c.unit_name',
	                            '(select sum(qty) from db_stockentry where db_stockentry.item_id=a.id) as stock'
	                            ,'a.alert_qty','a.purchase_price','a.final_price','d.tax_name','d.tax','a.status','e.brand_name','a.tax_type','a.hsn','a.sku'); //set column field database for datatable orderable
	var $column_search = array( 'a.id','a.item_image','a.item_code','a.item_name','b.category_name','sb.subcategory_name','sbc.childcategory_name','c.unit_name','a.stock','a.alert_qty','a.purchase_price','a.final_price','d.tax_name','d.tax','a.status','e.brand_name','a.custom_barcode','a.tax_type','a.hsn','a.sku'); //set column field database for datatable searchable
	var $order = array('a.id' => 'desc');*/ // default order
	var $table = 'db_items as a';
	var $column_order = array( 'a.id','a.item_image','a.web_price','a.old_price','a.item_code','a.item_name','b.category_name','sb.subcategory_name','sbc.childcategory_name','c.unit_name',
	                            '(select sum(qty) from db_stockentry where db_stockentry.item_id=a.id) as stock'
	                            ,'a.alert_qty','a.purchase_price','a.final_price','d.tax_name','d.tax','a.status','e.brand_name','a.tax_type','a.hsn','a.sku'); //set column field database for datatable orderable
	var $column_search = array( 'a.id','a.item_image','a.item_code','a.item_name','b.category_name','sb.subcategory_name','sbc.childcategory_name','c.unit_name','a.stock','a.alert_qty','a.purchase_price','a.final_price','d.tax_name','d.tax','a.status','e.brand_name','a.custom_barcode','a.tax_type','a.hsn','a.sku'); //set column field database for datatable searchable
	var $order = array('a.id' => 'desc'); // default order

	public function __construct()
	{
		parent::__construct();
	}

	private function _get_datatables_query()
	{
		$this->db->select($this->column_order);
		$this->db->from($this->table);
		$this->db->select("CASE WHEN e.brand_name IS NULL THEN '' ELSE e.brand_name END AS brand_name");
		$this->db->join('db_brands as e','e.id=a.brand_id','left');

		$this->db->join('db_category as b','b.id=a.category_id','left');
		$this->db->join('db_subcategory as sb','sb.id=a.subcategory_id','left');
		$this->db->join('db_childcategory as sbc','sbc.id=a.childcategory_id','left');
		$this->db->join('db_units as c','c.id=a.unit_id','left');
		$this->db->join('db_tax as d','d.id=a.tax_id','left');


		$brand_id = $this->input->post('brand_id');
		$category_id = $this->input->post('category_id');
		if(!empty($brand_id)){
			$this->db->where("a.brand_id",$brand_id);
		}
		if(!empty($category_id)){
			$this->db->where("a.category_id",$category_id);
		}


		$i = 0;

		foreach ($this->column_search as $item) // loop column
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{

				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}

		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		}
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}
	//Datatable end

	public function stock_entry($entry_date,$item_id,$qty='0',$warehouse_id,$note=''){
		$q1=$this->db->query("insert into db_stockentry(entry_date,item_id,qty,status,note,warehouse_id) values('$entry_date',$item_id,$qty,1,'$note',$warehouse_id)");
		if(!$q1){
			return false;
		}
		else{
			return true;
		}

	}
	//Save Cutomers
	public function verify_and_save(){
		//Filtering XSS and html escape from user inputs
		extract($this->security->xss_clean(html_escape(array_merge($this->data,$_POST))));

		$this->db->trans_begin();
		$this->db->trans_strict(TRUE);

		$file_name=$file_name_two=$file_name_three=$file_name_four=$file_name_five='';
		if(!empty($_FILES['item_image']['name'])){
			$new_name = time();
			$config['file_name'] = $new_name;
			$config['upload_path']          = './uploads/items/';
	        $config['allowed_types']        = 'jpg|png|jpeg';
	        $config['max_size']             = 1024;
	        $config['max_width']            = 1000;
	        $config['max_height']           = 1000;

	        $this->load->library('upload', $config);

	        if ( ! $this->upload->do_upload('item_image')){
                $error = array('error' => $this->upload->display_errors());
                print($error['error']);
                exit();
	        }else{
	        	$file_name=$this->upload->data('file_name');
	        	/*Create Thumbnail*/
	        	$config['image_library'] = 'gd2';
				$config['source_image'] = 'uploads/items/'.$file_name;
				$config['create_thumb'] = TRUE;
				$config['maintain_ratio'] = TRUE;
				$config['width']         = 75;
				$config['height']       = 50;
				$this->load->library('image_lib', $config);
				$this->image_lib->resize();
				//end
	        }
		}
		if(!empty($_FILES['item_image_two']['name'])){
			$new_name = time();
			$config['file_name'] = $new_name;
			$config['upload_path']          = './uploads/items/';
	        $config['allowed_types']        = 'jpg|png|jpeg';
	        $config['max_size']             = 1024;
	        $config['max_width']            = 1000;
	        $config['max_height']           = 1000;

	        $this->load->library('upload', $config);

	        if ( ! $this->upload->do_upload('item_image_two')){
                $error = array('error' => $this->upload->display_errors());
                print($error['error']);
                exit();
	        }else{
	        	$file_name_two=$this->upload->data('file_name');
	        	/*Create Thumbnail*/
	        	$config['image_library'] = 'gd2';
				$config['source_image'] = 'uploads/items/'.$file_name;
				$config['create_thumb'] = TRUE;
				$config['maintain_ratio'] = TRUE;
				$config['width']         = 75;
				$config['height']       = 50;
				$this->load->library('image_lib', $config);
				$this->image_lib->resize();
				//end
	        }
		}
		if(!empty($_FILES['item_image_three']['name'])){
			$new_name = time();
			$config['file_name'] = $new_name;
			$config['upload_path']          = './uploads/items/';
	        $config['allowed_types']        = 'jpg|png|jpeg';
	        $config['max_size']             = 1024;
	        $config['max_width']            = 1000;
	        $config['max_height']           = 1000;

	        $this->load->library('upload', $config);

	        if ( ! $this->upload->do_upload('item_image_three')){
                $error = array('error' => $this->upload->display_errors());
                print($error['error']);
                exit();
	        }else{
	        	$file_name_three=$this->upload->data('file_name');
	        	/*Create Thumbnail*/
	        	$config['image_library'] = 'gd2';
				$config['source_image'] = 'uploads/items/'.$file_name;
				$config['create_thumb'] = TRUE;
				$config['maintain_ratio'] = TRUE;
				$config['width']         = 75;
				$config['height']       = 50;
				$this->load->library('image_lib', $config);
				$this->image_lib->resize();
				//end
	        }
		}
		if(!empty($_FILES['item_image_four']['name'])){
			$new_name = time();
			$config['file_name'] = $new_name;
			$config['upload_path']          = './uploads/items/';
	        $config['allowed_types']        = 'jpg|png|jpeg';
	        $config['max_size']             = 1024;
	        $config['max_width']            = 1000;
	        $config['max_height']           = 1000;

	        $this->load->library('upload', $config);

	        if ( ! $this->upload->do_upload('item_image_four')){
                $error = array('error' => $this->upload->display_errors());
                print($error['error']);
                exit();
	        }else{
	        	$file_name_four=$this->upload->data('file_name');
	        	/*Create Thumbnail*/
	        	$config['image_library'] = 'gd2';
				$config['source_image'] = 'uploads/items/'.$file_name;
				$config['create_thumb'] = TRUE;
				$config['maintain_ratio'] = TRUE;
				$config['width']         = 75;
				$config['height']       = 50;
				$this->load->library('image_lib', $config);
				$this->image_lib->resize();
				//end
	        }
		}
		if(!empty($_FILES['item_image_five']['name'])){
			$new_name = time();
			$config['file_name'] = $new_name;
			$config['upload_path']          = './uploads/items/';
	        $config['allowed_types']        = 'jpg|png|jpeg';
	        $config['max_size']             = 1024;
	        $config['max_width']            = 1000;
	        $config['max_height']           = 1000;

	        $this->load->library('upload', $config);

	        if ( ! $this->upload->do_upload('item_image_five')){
                $error = array('error' => $this->upload->display_errors());
                print($error['error']);
                exit();
	        }else{
	        	$file_name_five=$this->upload->data('file_name');
	        	/*Create Thumbnail*/
	        	$config['image_library'] = 'gd2';
				$config['source_image'] = 'uploads/items/'.$file_name;
				$config['create_thumb'] = TRUE;
				$config['maintain_ratio'] = TRUE;
				$config['width']         = 75;
				$config['height']       = 50;
				$this->load->library('image_lib', $config);
				$this->image_lib->resize();
				//end
	        }
		}

		//Validate This items already exist or not
		/*$query=$this->db->query("select * from db_items where upper(item_name)=upper('$item_name')");
		if($query->num_rows()>0){
			return "Sorry! This Items Name already Exist.";
		}*/

		$qs5="select item_init from db_company";
		$q5=$this->db->query($qs5);
		$item_init=$q5->row()->item_init;

		//Create items unique Number
		$this->db->query("ALTER TABLE db_items AUTO_INCREMENT = 1");
		$qs4="select coalesce(max(id),0)+1 as maxid from db_items";
		$q1=$this->db->query($qs4);
		$maxid=$q1->row()->maxid;
		$item_code=$item_init.str_pad($maxid, 4, '0', STR_PAD_LEFT);
		//end

		$new_opening_stock = (empty($new_opening_stock)) ? 0 :$new_opening_stock;
		//$stock = $current_opening_stock + $new_opening_stock;

		$alert_qty = empty(trim($alert_qty)) ? '0' : $alert_qty;
		$profit_margin = (empty(trim($profit_margin))) ? 'null' : $profit_margin;

		$expire_date= (!empty(trim($expire_date))) ? date('Y-m-d',strtotime($expire_date)) : null;
		// $mfg_date= (!empty(trim($mfg_date))) ? date('Y-m-d',strtotime($mfg_date)) : 'null';
		$slug= str_replace(' ', '-', strtolower($item_name));

		$query1="insert into db_items(description,item_code,item_name,brand_id,category_id,subcategory_id,childcategory_id,sku,hsn,unit_id,alert_qty,lot_number,expire_date,
									old_price,price,tax_id,purchase_price,tax_type,profit_margin,web_price,
									sales_price,custom_barcode,final_price,wholesale_price,weight,is_feature,is_latest,is_top,is_review,is_front,short_description,long_description,
									system_ip,system_name,created_date,created_time,created_by,status)

							values('$description','$item_code','$item_name','$brand_id','$category_id','$subcategory_id','$childcategory_id','$sku','$hsn','$unit_id','$alert_qty','$lot_number','$expire_date',
									'$old_price','$price','$tax_id','$purchase_price','$tax_type',$profit_margin,'$web_price',
									'$sales_price','$custom_barcode','$final_price','$wholesale_price','$weight','$is_feature','$is_latest','$is_top','$is_review','$is_front','$short_description','$long_description',
									'$SYSTEM_IP','$SYSTEM_NAME','$CUR_DATE','$CUR_TIME','$CUR_USERNAME',1)";

		$query1=$this->db->simple_query($query1);
		if(!$query1){
			return "failed";
		}
		$item_id = $this->db->insert_id();
		if(!empty($new_opening_stock) && $new_opening_stock!=0){
			$q1=$this->stock_entry($CUR_DATE,$item_id,$new_opening_stock,$warehouse_id,$adjustment_note);
			if(!$q1){
				return "failed";
			}
		}
		//UPDATE itemS QUANTITY IN itemS TABLE
		$this->load->model('pos_model');
		$q6=$this->pos_model->update_items_quantity($item_id);
		if(!$q6){
			return "failed";
		}
		if ($query1){

				if(!empty($file_name)){
					//echo "update db_items set item_image ='$file_name' where id=".$item_id;exit();
					$q1=$this->db->query("update db_items set item_image ='uploads/items/$file_name' where id=".$item_id);
					$q1=$this->db->query("update db_items set item_image_two ='uploads/items/$file_name_two' where id=".$item_id);
					$q1=$this->db->query("update db_items set item_image_three ='uploads/items/$file_name_three' where id=".$item_id);
					$q1=$this->db->query("update db_items set item_image_four ='uploads/items/$file_name_four' where id=".$item_id);
					$q1=$this->db->query("update db_items set item_image_five ='uploads/items/$file_name_five' where id=".$item_id);
				}
				$this->db->query("update db_items set expire_date=null where expire_date='0000-00-00'");
				$this->db->trans_commit();
				$this->session->set_flashdata('success', 'Success!! New Item Added Successfully!');
		        return "success";
		}
		else{
				$this->db->trans_rollback();
				//unlink('uploads/items/'.$file_name);
		        return "failed";
		}

	}

	//Get items_details
	public function get_details($id,$data){
		//Validate This items already exist or not
		$query=$this->db->query("select *,(select sum(qty) from db_stockentry where db_stockentry.item_id=db_items.id) as stock from db_items where upper(id)=upper('$id')");
		if($query->num_rows()==0){
			show_404();exit;
		}
		else{
			$query=$query->row();
			$data['q_id']=$query->id;
			$data['item_code']=$query->item_code;
			$data['item_name']=$query->item_name;
			$data['description']=$query->description;
			$data['brand_id']=$query->brand_id;
			$data['category_id']=$query->category_id;
			$data['subcategory_id']=$query->subcategory_id;
			$data['childcategory_id']=$query->childcategory_id;
			$data['sku']=$query->sku;
			$data['hsn']=$query->hsn;
			$data['unit_id']=$query->unit_id;
			$data['alert_qty']=$query->alert_qty;
			$data['web_price']=$query->web_price;
			$data['old_price']=$query->old_price;
			$data['mfg_date']=$query->mfg_date;
			$data['price']=$query->price;
			$data['tax_id']=$query->tax_id;
			$data['purchase_price']=$query->purchase_price;
			$data['tax_type']=$query->tax_type;
			$data['profit_margin']=$query->profit_margin;
			$data['sales_price']=$query->sales_price;
			$data['final_price']=$query->final_price;
			$data['wholesale_price']=$query->wholesale_price;
			$data['weight']=$query->weight;
			$data['is_feature']=$query->is_feature;
			$data['is_latest']=$query->is_latest;
			$data['is_top']=$query->is_top;
			$data['is_review']=$query->is_review;
			$data['is_front']=$query->is_front;
			$data['short_description']=$query->short_description;
			$data['long_description']=$query->long_description;
			$data['stock']=$query->stock;
			$data['lot_number']=$query->lot_number;
			$data['custom_barcode']=$query->custom_barcode;
			$data['item_image']=$query->item_image;
			$data['item_image_two']=$query->item_image_two;
			$data['item_image_three']=$query->item_image_three;
			$data['item_image_four']=$query->item_image_four;
			$data['item_image_five']=$query->item_image_five;
			$data['expire_date']=(!empty($query->expire_date)) ? show_date($query->expire_date):'';

			return $data;
		}
	}
	public function update_items(){
	  
		//Filtering XSS and html escape from user inputs
		extract($this->security->xss_clean(html_escape(array_merge($this->data,$_POST))));

		//Validate This items already exist or not
		$this->db->trans_begin();
		/*$query=$this->db->query("select * from db_items where upper(item_name)=upper('$item_name') and id<>$q_id");
		if($query->num_rows()>0){
			return "This Items Name already Exist.";
		}
		else{*/

			$file_name=$item_image='';
			if(!empty($_FILES['item_image']['name'])){

				$new_name = time();
				$config['file_name'] = $new_name;
				$config['upload_path']          = './uploads/items/';
		        $config['allowed_types']        = 'jpg|png';
		        $config['max_size']             = 1024;
		        $config['max_width']            = 1000;
		        $config['max_height']           = 1000;

		        $this->load->library('upload', $config);

		        if ( ! $this->upload->do_upload('item_image'))
		        {
					$error = array('error' => $this->upload->display_errors());
					print($error['error']);
					exit();
		        }
		        else
		        {
		        	$file_name=$this->upload->data('file_name');

		        	/*Create Thumbnail*/
		        	$config['image_library'] = 'gd2';
					$config['source_image'] = 'uploads/items/'.$file_name;
					$config['create_thumb'] = TRUE;
					$config['maintain_ratio'] = TRUE;
					$config['width']         = 75;
					$config['height']       = 50;
					$this->load->library('image_lib', $config);
					$this->image_lib->resize();
					//end

					$item_image.=" ,item_image='".$config['source_image']."' ";

		        }
			}

			if(!empty($_FILES['item_image_two']['name'])){

				$new_name = time();
				$config['file_name'] = $new_name;
				$config['upload_path']          = './uploads/items/';
		        $config['allowed_types']        = 'jpg|png';
		        $config['max_size']             = 1024;
		        $config['max_width']            = 1000;
		        $config['max_height']           = 1000;

		        $this->load->library('upload', $config);

		        if ( ! $this->upload->do_upload('item_image_two'))
		        {
					$error = array('error' => $this->upload->display_errors());
					print($error['error']);
					exit();
		        }
		        else
		        {
		        	$file_name=$this->upload->data('file_name');

		        	/*Create Thumbnail*/
		        	$config['image_library'] = 'gd2';
					$config['source_image'] = 'uploads/items/'.$file_name;
					$config['create_thumb'] = TRUE;
					$config['maintain_ratio'] = TRUE;
					$config['width']         = 75;
					$config['height']       = 50;
					$this->load->library('image_lib', $config);
					$this->image_lib->resize();
					//end

					$item_image.=" ,item_image_two='".$config['source_image']."' ";

		        }
			}

			if(!empty($_FILES['item_image_three']['name'])){

				$new_name = time();
				$config['file_name'] = $new_name;
				$config['upload_path']          = './uploads/items/';
		        $config['allowed_types']        = 'jpg|png';
		        $config['max_size']             = 1024;
		        $config['max_width']            = 1000;
		        $config['max_height']           = 1000;

		        $this->load->library('upload', $config);

		        if ( ! $this->upload->do_upload('item_image_three'))
		        {
					$error = array('error' => $this->upload->display_errors());
					print($error['error']);
					exit();
		        }
		        else
		        {
		        	$file_name=$this->upload->data('file_name');

		        	/*Create Thumbnail*/
		        	$config['image_library'] = 'gd2';
					$config['source_image'] = 'uploads/items/'.$file_name;
					$config['create_thumb'] = TRUE;
					$config['maintain_ratio'] = TRUE;
					$config['width']         = 75;
					$config['height']       = 50;
					$this->load->library('image_lib', $config);
					$this->image_lib->resize();
					//end

					$item_image.=" ,item_image_three='".$config['source_image']."' ";

		        }
			}

			if(!empty($_FILES['item_image_four']['name'])){

				$new_name = time();
				$config['file_name'] = $new_name;
				$config['upload_path']          = './uploads/items/';
		        $config['allowed_types']        = 'jpg|png';
		        $config['max_size']             = 1024;
		        $config['max_width']            = 1000;
		        $config['max_height']           = 1000;

		        $this->load->library('upload', $config);

		        if ( ! $this->upload->do_upload('item_image_four'))
		        {
					$error = array('error' => $this->upload->display_errors());
					print($error['error']);
					exit();
		        }
		        else
		        {
		        	$file_name=$this->upload->data('file_name');

		        	/*Create Thumbnail*/
		        	$config['image_library'] = 'gd2';
					$config['source_image'] = 'uploads/items/'.$file_name;
					$config['create_thumb'] = TRUE;
					$config['maintain_ratio'] = TRUE;
					$config['width']         = 75;
					$config['height']       = 50;
					$this->load->library('image_lib', $config);
					$this->image_lib->resize();
					//end

					$item_image.=" ,item_image_four='".$config['source_image']."' ";

		        }
			}

			if(!empty($_FILES['item_image_five']['name'])){

				$new_name = time();
				$config['file_name'] = $new_name;
				$config['upload_path']          = './uploads/items/';
		        $config['allowed_types']        = 'jpg|png';
		        $config['max_size']             = 1024;
		        $config['max_width']            = 1000;
		        $config['max_height']           = 1000;

		        $this->load->library('upload', $config);

		        if ( ! $this->upload->do_upload('item_image_five'))
		        {
					$error = array('error' => $this->upload->display_errors());
					print($error['error']);
					exit();
		        }
		        else
		        {
		        	$file_name=$this->upload->data('file_name');

		        	/*Create Thumbnail*/
		        	$config['image_library'] = 'gd2';
					$config['source_image'] = 'uploads/items/'.$file_name;
					$config['create_thumb'] = TRUE;
					$config['maintain_ratio'] = TRUE;
					$config['width']         = 75;
					$config['height']       = 50;
					$this->load->library('image_lib', $config);
					$this->image_lib->resize();
					//end

					$item_image.=" ,item_image_five='".$config['source_image']."' ";

		        }
			}

			//$stock = $current_opening_stock + $new_opening_stock;
			$alert_qty = (empty(trim($alert_qty))) ? '0' : $alert_qty;
			$profit_margin = (empty(trim($profit_margin))) ? 'null' : $profit_margin;
			// $mfg_date= (!empty(trim($mfg_date))) ? date('Y-m-d',strtotime($mfg_date)) : 'null';
			$expire_date= (!empty(trim($expire_date))) ? date('Y-m-d',strtotime($expire_date)) : 'null';
			$query1="update db_items set
						item_name='$item_name',
						description='$description',
						brand_id='$brand_id',
						category_id='$category_id',
						subcategory_id='$subcategory_id',
						childcategory_id='$childcategory_id',
						sku='$sku',
						hsn='$hsn',
						unit_id='$unit_id',
						alert_qty='$alert_qty',
						lot_number='$lot_number',
						expire_date='$expire_date',
						custom_barcode='$custom_barcode',
						old_price='$old_price',
						web_price='$web_price',
						price='$price',
						tax_id='$tax_id',
						purchase_price='$purchase_price',
						tax_type='$tax_type',
						profit_margin=$profit_margin,
						sales_price='$sales_price',
						final_price='$final_price',
						long_description='$long_description',
						short_description='$short_description',
						is_review='$is_review',
						is_front='$is_front',
						is_top='$is_top',
						is_latest='$is_latest',
						is_feature='$is_feature',
						weight='$weight',
						wholesale_price='$wholesale_price'
						$item_image
						where id=$q_id";

			$query1=$this->db->query($query1);
			if(!$query1){
				return "failed";
			}
			if(!empty($new_opening_stock) && $new_opening_stock!=0){
				$q1=$this->stock_entry($CUR_DATE,$q_id,$new_opening_stock,$warehouse_id,$adjustment_note);
				if(!$q1){
					return "failed";
				}
			}
			//UPDATE itemS QUANTITY IN itemS TABLE
			$this->load->model('pos_model');
			$q6=$this->pos_model->update_items_quantity($q_id);
			if(!$q6){
				return "failed";
			}

			if ($query1){
				   $this->db->query("update db_items set expire_date=null where expire_date='0000-00-00'");
				   $this->db->trans_commit();
				   $this->session->set_flashdata('success', 'Success!! Item Updated Successfully!');
			        return "success";
			}
			else{
					$this->db->trans_rollback();
			        return "failed";
			}
		/*}*/
	}
	public function update_status($id,$status){
        $query1="update db_items set status='$status' where id=$id";
        if ($this->db->simple_query($query1)){
            echo "success";
        }
        else{
            echo "failed";
        }
	}
	public function delete_items_from_table($ids){
		$this->db->trans_begin();
		$q1=$this->db->query("delete from db_items where id in($ids)");
		$q2=$this->db->query("delete from db_stockentry where item_id in($ids)");
        if($q1 && $q2){
        	$this->db->trans_commit();
            echo "success";
        }
        else{
            echo "failed";
        }
	}


	public function inclusive($price='',$tax_per){
		return $price/(($tax_per/100)+1)/10;
	}

	//GET Labels from Purchase Invoice
	public function get_purchase_items_info($rowcount,$item_id,$purchase_qty){
		$q1=$this->db->select('*')->from('db_items')->where("id=$item_id")->get();
		$tax=$this->db->query("select tax from db_tax where id=".$q1->row()->tax_id)->row()->tax;

		$info['item_id'] = $q1->row()->id;
		$info['item_name'] = $q1->row()->item_name;
		$info['item_available_qty'] = $q1->row()->stock;
		$info['item_sales_qty'] = $purchase_qty;

	    return $this->return_row_with_data($rowcount,$info);
	}

	public function get_items_info($rowcount,$item_id){
		$q1=$this->db->select('*')->from('db_items')->where("id=$item_id")->get();
		$tax=$this->db->query("select tax from db_tax where id=".$q1->row()->tax_id)->row()->tax;

		$info['item_id'] = $q1->row()->id;
		$info['item_name'] = $q1->row()->item_name;
		$info['item_available_qty'] = $q1->row()->stock;
		$info['item_sales_qty'] = 1;

		$this->return_row_with_data($rowcount,$info);
	}


	public function return_row_with_data($rowcount,$info){
		extract($info);

		?>
            <tr id="row_<?=$rowcount;?>" data-row='<?=$rowcount;?>'>
               <td id="td_<?=$rowcount;?>_1">
                  <!-- item name  -->
                  <input type="text" style="font-weight: bold;" id="td_data_<?=$rowcount;?>_1" class="form-control no-padding" value='<?=$item_name;?>' readonly >
               </td>
               <!-- Qty -->
               <td id="td_<?=$rowcount;?>_3">
                  <div class="input-group ">
                     <span class="input-group-btn">
                     <button onclick="decrement_qty(<?=$rowcount;?>)" type="button" class="btn btn-default btn-flat"><i class="fa fa-minus text-danger"></i></button></span>
                     <input typ="text" value="<?=$item_sales_qty;?>" class="form-control no-padding text-center" onkeyup="calculate_tax(<?=$rowcount;?>)" id="td_data_<?=$rowcount;?>_3" name="td_data_<?=$rowcount;?>_3">
                     <span class="input-group-btn">
                     <button onclick="increment_qty(<?=$rowcount;?>)" type="button" class="btn btn-default btn-flat"><i class="fa fa-plus text-success"></i></button></span>
                  </div>
               </td>

               <!-- Remove button -->
               <td id="td_<?=$rowcount;?>_16" style="text-align: center;">
                  <a class=" fa fa-fw fa-minus-square text-red" style="cursor: pointer;font-size: 34px;" onclick="removerow(<?=$rowcount;?>)" title="Delete ?" name="td_data_<?=$rowcount;?>_16" id="td_data_<?=$rowcount;?>_16"></a>
               </td>
              <input type="hidden" id="tr_available_qty_<?=$rowcount;?>_13" value="<?=$item_available_qty;?>">
               <input type="hidden" id="tr_item_id_<?=$rowcount;?>" name="tr_item_id_<?=$rowcount;?>" value="<?=$item_id;?>">
            </tr>
		<?php

	}
	public function xss_html_filter($input){
		return $this->security->xss_clean(html_escape($input));
	}

	public function preview_labels(){
		//print_r($_POST);exit();
		$CI =& get_instance();
		//Filtering XSS and html escape from user inputs
		$company_name=$this->db->query("select company_name from db_company")->row()->company_name;
		$rowcount = $this->input->post('hidden_rowcount');
		?>
		<div style="position:relative; left:-0mm; height:1.3in !important;  width:39mm !important;">
			<div class="inner-div-2" style=" height:1in !important;  width:100% !important;">
				<div style="">

					<?php
					//Import post data from form
					for($i=1;$i<=$rowcount;$i++){
						if(isset($_POST['tr_item_id_'.$i]) && !empty($_POST['tr_item_id_'.$i])){
							$item_id 			=$this->xss_html_filter(trim($_POST['tr_item_id_'.$i]));
							$item_count 		=$this->xss_html_filter(trim($_POST['td_data_'.$i."_3"]));
							$res1=$this->db->query("select * from db_items where id=$item_id")->row();

							$item_name =$res1->item_name;
							$item_code = (!empty($res1->custom_barcode)) ? $res1->custom_barcode : $res1->item_code;
							$item_price =$res1->sales_price;

							for($j=1;$j<=$item_count;$j++){
							?>
							<div style="height:0.8in !important; line-height: 0.8in; width:100% !important;margin-top:0px;page-break-inside: avoid;" class="label_border text-center">
    							<div style="display:inline-block;vertical-align:sub;width:100%; line-height: 12px !important; font-size:12px;">
    								<b style="display: block !important; font-size:18px; line-height: 18px !important;" class="text-uppercase"><?=$company_name;?></b>
    									<span style="display: block !important; font-size:10px">
    									    <?= $item_name;?>
    									</span>
    								<b style="font-size:12px">Price:</b>
    								<span style="font-size:12px"><?= $CI->currency($item_price);?></span>
    								<img class="center-block" style="height: 0.25in !important; width: 90%; opacity: 1.0; margin-top:2px;" src="<?php echo base_url();?>barcode/<?php echo $item_code;?>">

    							</div>
							</div>
							<?php
							}
						}

					}//for end
					?>


				</div>
			</div>
		</div>
		<?php

	}


	public function delete_stock_entry($entry_id){
		$item_id = $this->input->post('item_id');
        $this->db->trans_begin();
		$q1=$this->db->query("delete from db_stockentry where id=$entry_id");
		if(!$q1){
			return "failed";
		}
		//UPDATE itemS QUANTITY IN itemS TABLE
		$this->load->model('pos_model');
		$q6=$this->pos_model->update_items_quantity($item_id);

		if(!$q6){
			return "failed";
		}

		$this->session->set_flashdata('success', 'Success!! Item Opening Stock Entry Deleted!');
		$this->db->trans_commit();
		return "success";
	}

}
