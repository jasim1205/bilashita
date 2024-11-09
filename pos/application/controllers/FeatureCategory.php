<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Featurecategory extends MY_Controller {
	public function __construct(){
		parent::__construct();
		$this->load_global();
		$this->load->model('Featurecategory_model','featurecategory');
	}

	public function add(){
		$this->permission_check('items_category_add');
		$data=$this->data;
		$data['page_title']=$this->lang->line('FeatureCategory');
		$this->load->view('featurecategory-create', $data);
	}


	public function newfeaturecategory(){
		$this->form_validation->set_rules('title', 'Title', 'trim|required');

		if ($this->form_validation->run() == TRUE) {
			$result=$this->featurecategory->verify_and_save();
			echo $result;
		} else {
			echo "Please Enter Title name.";
		}
	}
	public function update($id){
		$this->permission_check('items_category_edit');
		$data=$this->data;

		$result=$this->featurecategory->get_details($id,$data);
		$data=array_merge($data,$result);
		$data['page_title']=$this->lang->line('FeatureCategory');
		$this->load->view('featurecategory-create', $data);
	}
	public function update_featurecategory(){
		$this->form_validation->set_rules('title', 'Title', 'trim|required');
		$this->form_validation->set_rules('q_id', '', 'trim|required');

		if ($this->form_validation->run() == TRUE) {
			$result=$this->featurecategory->update_featurecategory();
			echo $result;
		} else {
			echo "Please Enter Title.";
		}
	}
	public function view(){
		$this->permission_check('items_category_view');
		$data=$this->data;
		$data['page_title']=$this->lang->line('FeatureCategory');
		$this->load->view('featurecategory-view', $data);
	}

	public function ajax_list()
	{
		$list = $this->featurecategory->get_datatables();

		$data = array();
		//$no = $_POST['start'];
        //echo $this->db->last_query();
        //print_r($list);

		foreach ($list as $featurecategory) {
			//$no++;
			$row = array();
			$row[] = $featurecategory->title;
			$row[] = $featurecategory->link;
			$row[] = "<img src='".base_url('uploads/feature_category/').$featurecategory->image."' width='80px'>";
			// if ($slider->order_by == 1) {
			// 	$row[] = 'Image Banner-1';
			// } else if ($slider->order_by == 2) {
			// 	$row[] = 'Image Banner-2';
			// } else if ($slider->order_by == 3) {
			// 	$row[] = 'Other Banner-3';
			// }else if ($slider->order_by == 4) {
			// 	$row[] = 'Other Banner-4';
			// }else if ($slider->order_by == 5) {
			// 	$row[] = 'Other Banner-5';
			// }

					$str2 = '<div class="btn-group" title="View Account">
										<a class="btn btn-primary btn-o dropdown-toggle" data-toggle="dropdown" href="#">
											Action <span class="caret"></span>
										</a>
										<ul role="menu" class="dropdown-menu dropdown-light pull-right">';

											if($this->permissions('items_category_edit'))
											$str2.='<li>
												<a title="Edit Record ?" href="update/'.$featurecategory->id.'">
													<i class="fa fa-fw fa-edit text-blue"></i>Edit
												</a>
											</li>';

											if($this->permissions('items_category_delete'))
											$str2.='<li>
												<a style="cursor:pointer" title="Delete Record ?" onclick="delete_slider('.$featurecategory->id.')">
													<i class="fa fa-fw fa-trash text-red"></i>Delete
												</a>
											</li>

										</ul>
									</div>';

			$row[] = $str2;
			$data[] = $row;
		}

		$output = array(
						"draw" => "",//$_POST['draw'],
						"recordsTotal" => $this->featurecategory->count_all(),
						"recordsFiltered" => $this->featurecategory->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}



	public function delete_featurecategory(){
        $this->load->model('Featurecategory_model');
		$this->permission_check_with_msg('items_category_delete');
		$id=$this->input->post('q_id');
		return $this->Featurecategory_model->delete_featurecategory_from_table($id);
	}


}

