<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bootgrid extends CI_Controller {
 
 public function __construct()
 {
  parent::__construct();
  $this->load->model('bootgrid_model');
 }

 function index()
 {
 	$this->load->model('Bootgrid_model');
 	$data['fetch_data']=$this->Bootgrid_model->fetch_data();
  $this->load->view('bootgrid',$data);
 }

 function fetch_data()
 {
  $data = $this->bootgrid_model->make_query();
  $array = array();
  foreach($data as $row)
  {
   $array[] = $row;
  }
  $output = array(
   'current'  => intval($_POST["current"]),
   'rowCount'  => 10,
   'total'   => intval($this->bootgrid_model->count_all_data()),
   'rows'   => $array
  );
  echo json_encode($output);
 }

 function action()
 {
  if($this->input->post('operation'))
  {
   $data = array(
    'name'   => $this->input->post('name'),
    'gender'  => $this->input->post('gender'),
    'designation' => $this->input->post('designation'),
    'age'   => $this->input->post('age')
   );
   if($this->input->post('operation') == 'Add')
   {
    $this->bootgrid_model->insert($data);
    echo 'Data Inserted';
   }
   if($this->input->post('operation') == 'Edit')
   {
    $this->bootgrid_model->update($data, $this->input->post('employee_id'));
    echo 'Data Updated';
   }
  }
 }

 function fetch_single_data()
 {
  if($this->input->post('id'))
  {
   $data = $this->bootgrid_model->fetch_single_data($this->input->post('id'));
   foreach($data as $row)
   {
    $output['name'] = $row['name'];
    $output['gender'] = $row['gender'];
    $output['designation'] = $row['designation'];
    $output['age'] = $row['age'];
   }
   echo json_encode($output);
  }
 }

 function delete_data()
 {
  if($this->input->post('id'))
  {
   $this->bootgrid_model->delete($this->input->post('id'));
   echo 'Data Deleted';
  }
 }
}

?>