<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Freezer_vial extends CI_Controller {
    
    private $error = false;
    
    function Freezer_vial()
    {
        parent::__construct();
        
        $this->output->enable_profiler(false);
        
        if(!$this->usercontrol->has_permission('dashboard'))
            redirect('login');        
    }
    
    public function view($culture_id, $sample_id, $vial_id)
    {
        //Load models
        $this->load->model('vial_model');
        $this->load->model('task_model');
        $this->load->model('project_model');
        
        // Check permission for THIS project
        $this->load->model('task_model');
        $project_permission = $this->task_model->get_project_permission($this->session->userdata('user'), $culture_id);
        if($project_permission != '1')
        {
            redirect('dashboard');
        }
        
        $data['project_id'] = $culture_id;
        $data['sample_name'] = $this->vial_model->get_name($culture_id);
        $data['task'] = $this->task_model->get($culture_id, $sample_id);
        
        $this->load->helper('date');
        $due_date = $data['task']['due_date'];
        $timestamp = strtotime($due_date);
        $now = time();
        $data['time_since'] = timespan($timestamp, $now, false);
        
        $this->title = 'Vial History - '.$data['sample_name'].' - P'.$data['task']['title'];
        $this->menu = 'dashboard|cold_storage';
        
        $photo = $this->project_model->get_photo_single($data['task']['user_id']);
        $data['photo'] = $photo;
        
        $this->load->helper('tasks');
        
        // Load View
        $this->load->view('vial_history', $data);
    }

    public function add($culture_id, $sample_id, $success = '0')
    {
        // Check permission
        if(!$this->usercontrol->has_permission('project'))
            redirect('dashboard');
        
        $this->load->helper('cold');
        $this->load->model('project_model');
        $this->load->model('cold_model');
        
        $this->title = "Add Frozen Vials";
        $this->menu = 'dashboard|cold_storage';
        
        // Check permission for THIS project
        $this->load->model('task_model');
        $project_permission = $this->task_model->get_project_permission($this->session->userdata('user'), $culture_id);
        if($project_permission != '1')
        {
            redirect('dashboard');
        }
        
        $data['boxid']  = '';
        $data['column']  = '';
        $data['row']  = '';
        $data['vial_id'] = '0';
        
        // Load boxes
        $data['freezers'] = $this->cold_model->get_user_freezers($this->session->userdata('user'));
        $data['boxes'] = $this->cold_model->get_user_fbox($this->session->userdata('user'));
        $fboxes = array();
        foreach ($data['boxes'] as $box)
        {
            $fboxes[$box['box_id']] = $box['name'];
        }
        $data['freezer_box'] = $fboxes;
        $data['sample_id'] = $sample_id;
        $data['culture_id'] = $culture_id;
        $data['array_primer'] = array('1' => '1', '2' => '2', '3' => '3', '4' => '4','5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10');
        $data['array_primer_row'] = array('' => '1', '1' => '2', '2' => '3', '3' => '4','4' => '5', '5' => '6', '6' => '7', '7' => '8', '8' => '9', '9' => '10');
        
        $id = $this->session->userdata('user');
        $lab = $this->session->userdata('lab');
        $users = $this->project_model->get_related_users($id, $lab);
        foreach ($users as $key => $value) {
            if($value['id'] == $this->session->userdata('user'))
                $users[$key]['project'] = 1;
            else
                $users[$key]['project'] = 0;
        }
        $data['users'] = $users;
        
        if($this->error)
            $data['error'] = $this->error;
            
        $data['success'] = $success;
        
        $this->load->view('vial_add', $data);
    }
    
    public function edit($culture_id, $sample_id, $vial_id)
    {
        // Check permission
        if(!$this->usercontrol->has_permission('project'))
            redirect('dashboard');
        
        $this->load->helper('cold');
        $this->load->model('cold_model');
        $this->load->model('vial_model');
        
        // Check permission for THIS project
        $this->load->model('task_model');
        $project_permission = $this->task_model->get_project_permission($this->session->userdata('user'), $culture_id);
        if($project_permission != '1')
        {
            redirect('dashboard');
        }
        
        $this->title = "Edit Frozen Vial";
        $this->menu = 'dashboard|cold_storage';
        
        
        // bunch of code to work out where the vial is and populate the dropdown
        $vial_location = $this->vial_model->get_box_location($vial_id);
        $loc_string = strval($vial_location); //turns the number to a string so I can get each value
        $rest = substr($loc_string, -1); //get last number which will be column
        if($rest == '0') {$rest = '10';}
        $rest1 = substr($loc_string, -2, 1);
        if(strlen($loc_string) == '3') {
            $rest2 = substr($loc_string, -3, 1);
            $rest1 = $rest2.$rest1;
        }
        $rest1 = intval($rest1) ; //DONT DO THIS ANYMORE???????  remove 1 so that this gives correct number in dropdown - see array below
        
        $data['boxid']  = '';
        $data['column']  = $rest;
        $data['row']  = $rest1;
        $data['vial_id'] = $vial_id;
        
        // Load boxes
        $data['freezers'] = $this->cold_model->get_user_freezers($this->session->userdata('user'));
        $data['boxes'] = $this->cold_model->get_user_fbox($this->session->userdata('user'));
        $fboxes = array();
        foreach ($data['boxes'] as $box)
        {
            $fboxes[$box['box_id']] = $box['name'];
        }
        $data['freezer_box'] = $fboxes;
        $data['sample_id'] = $sample_id;
        $data['culture_id'] = $culture_id;
        $data['array_primer'] = array('1' => '1', '2' => '2', '3' => '3', '4' => '4','5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10');
        $data['array_primer_row'] = array('' => '1', '1' => '2', '2' => '3', '3' => '4','4' => '5', '5' => '6', '6' => '7', '7' => '8', '8' => '9', '9' => '10');
        
        if($this->error)
            $data['error'] = $this->error;
            
        $data['success'] = '0';
        
        $this->load->view('vial_add', $data);
    }
    
    public function save()
    {
        if($this->input->post('cancel') !== FALSE)
            redirect('dashboard');
           
        // Check permission
        if(!$this->usercontrol->has_permission('project'))
            redirect('dashboard');
        
        $this->load->model('fbox_model');
        $this->load->model('cold_model');
        $this->load->model('vial_model');
        $this->load->model('task_model');
        
        $data['cell'] = $this->cold_model->get_fbox($this->input->post('boxid'));
        $array_locations = unserialize($data['cell']);
        
        if($data['cell'] == Null){
            $array_locations = $data['cell'];
        }else{
            $array_locations = unserialize($data['cell']);
        }
        
        $sample_id = $this->input->post('sample_id');
        $culture_id = $this->input->post('culture_id');
        $vial_id = $this->input->post('vial_id');
        $sample_name = $this->vial_model->get_name($culture_id);
        $sample_passage = $this->vial_model->get_passage($culture_id, $sample_id);
        $sample_date = $this->vial_model->get_date($culture_id, $sample_id);
        $box_id = $this->input->post('boxid');
        
        // get original vial location in array and 'delete' vial. Needs to happen early in method before its updated
        $redirect = '0';
        if($vial_id > 0){
            $vial_location = $this->vial_model->get_box_location($vial_id);
            $array_locations[$vial_location] = '<span class="empty_cell">Empty</span>';
            //echo $vial_location;
            //add a variable for redirection at end of method
            $redirect = '1';
        }
        
        //save vial to vial table
        $db_data['boxid'] = $box_id;
        $db_data['taskid'] = $sample_id;
        $db_data['projectid'] = $culture_id;
        
        $row   = $this->input->post('row');
        $column   = $this->input->post('column');
        $cal_location = $row.$column;
        
        switch ($cal_location)
        {
            case "110":
                $cal_location = '20';
                break;
            case "210":
                $cal_location = '30';
                break;
            case "310":
                $cal_location = '40';
                break;
            case "410":
                $cal_location = '50';
                break;
            case "510":
                $cal_location = '60';
                break;
            case "610":
                $cal_location = '70';
                break;
            case "710":
                $cal_location = '80';
                break;
            case "810":
                $cal_location = '90';
                break;
            case "910":
                $cal_location = '100';
                break;
            default:
                $cal_location = $cal_location;;
        }
        
        $db_data['arrayid'] = $cal_location;
        if ($vial_id > 0)
            $this->vial_model->update($vial_id, $db_data);
        else
            $vial_id = $this->vial_model->create($db_data);
        $vial_link = '<a href="'.base_url('freezer_vial/view/'.$culture_id.'/'.$sample_id.'/'.$vial_id).'" class="filled_cell">'.$sample_name.'<br />P '.$sample_passage.'<br />'.$sample_date.'</a>';
        
        $array_locations[$cal_location] = $vial_link;
        
        $string_locations = serialize( $array_locations );
        
        $sql_data = array(
            'vial_locations'   => $string_locations,
        );
        
        $box_id = $this->input->post('boxid');
        $this->fbox_model->update($box_id,$sql_data);
        
        //save box number to task table
        $task_data = array('f_location' => $box_id);
        $this->task_model->update($culture_id,$sample_id,$task_data);
        
        // Load View
        if($redirect == '1'){
            redirect('freezer_vial/view/'.$culture_id.'/'.$sample_id.'/'.$vial_id);
        }else{
            redirect('freezer_vial/add/'.$culture_id.'/'.$sample_id.'/1');
        }
    }
}