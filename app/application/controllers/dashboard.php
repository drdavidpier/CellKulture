<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    
    function Dashboard()
    {
        parent::__construct();
        
        if(!$this->usercontrol->has_permission('dashboard'))
            redirect('login');        
    }
    
    public function indexold()
    {
        $this->title = 'Virtual Incubator';
        $this->menu = 'users|dashboard|cold_storage';
        
        //Load models
        $this->load->model('project_model');
        $this->load->model('task_model');
        
        // Load tasks
        $data['tasks'] = $this->task_model->get_user_tasks($this->session->userdata('user'));
        
        $data['page_title']  = "Dashboard";
        //$data['status_arr'] = $this->task_model->get_status_array();
        
        // Load View
        $this->load->view('dashboard', $data);
    }
    
    public function index()
    {
        $this->title = 'Virtual Incubator';
        $this->menu = 'users|dashboard|cold_storage';
        
        //Load models
        $this->load->model('project_model');
        $this->load->model('task_model');
        
        
        $flasks = $this->task_model->get_user_tasks_two($this->session->userdata('user'));
        
        foreach($flasks as $key => $value){
            //get last task
            $task2 = $this->task_model->get_last_task_date($value['project_id']);
            
            $task3 = $this->task_model->get_last_task_details($task2 , $value['project_id']);
            $flasks[$key]['flask_type'] = $task3;
   
        }
        $data['tasks'] = $flasks;
        
        $data['page_title']  = "Dashboard";
        // Load text helper to be used in the view
        $this->load->helper('dashboard');
        // Load View
        $this->load->view('dashboard', $data);
        
    }
    
    public function archive()
    {
        $this->title = 'Culture Archive';
        $this->menu = 'dashboard|cold_storage';
        
        //Load models
        $this->load->model('project_model');
        $this->load->model('task_model');
        
        // Load tasks
        $data['tasks'] = $this->task_model->get_user_tasks($this->session->userdata('user'));
        
        $data['page_title']  = "Archive";
        //$data['status_arr'] = $this->task_model->get_status_array();
        
        // Load View
        $this->load->view('archive', $data);
    }

}
