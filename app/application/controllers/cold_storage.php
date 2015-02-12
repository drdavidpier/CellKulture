<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cold_storage extends CI_Controller {
    
    private $error = false;
    
    function Cold_storage()
    {
        parent::__construct();
        
        $this->output->enable_profiler(false);
        
        if(!$this->usercontrol->has_permission('dashboard'))
            redirect('login');        
    }
    
    public function index()
    {
        $this->title = 'Cold Storage';
        $this->menu = 'dashboard|cold_storage';
        
        //Load models
        $this->load->model('project_model');
        $this->load->model('task_model');
        $this->load->model('cold_model');
        
        $this->load->helper('cold');
        
        // Load freezers
        $data['freezers'] = $this->cold_model->get_user_freezers($this->session->userdata('user'));
        
        // Load boxes
        $data['boxes'] = $this->cold_model->get_user_fbox($this->session->userdata('user'));
        
        $data['page_title']  = "Dashboard";
        
        // Load View
        $this->load->view('cold_storage', $data);
    }

    public function add()
    {
        // Check permission
        if(!$this->usercontrol->has_permission('project'))
            redirect('dashboard');
        
        $this->load->helper('cold');
        $this->load->model('cold_model');
        
        $this->title = "Add a New Freezer";
        $this->menu = 'dashboard|cold_storage';
        
        $data['name']  = '';
        $data['type']  = '0';
        
        $id = $this->session->userdata('user');
        $lab = $this->session->userdata('lab');
        $users = $this->cold_model->get_related_users($id, $lab);
        foreach ($users as $key => $value) {
            if($value['id'] == $this->session->userdata('user'))
                $users[$key]['freezer'] = 1;
            else
                $users[$key]['freezer'] = 0;
        }
        $data['users'] = $users;
        
        if($this->error)
            $data['error'] = $this->error;
        
        $this->load->view('freezer_add', $data);
    }
    
    public function edit($id)
    {
        // Check permission
        if(!$this->usercontrol->has_permission('project'))
            redirect('dashboard');
        
        $this->load->helper('cold');
        $this->load->model('cold_model');
        
        $this->title = "Edit Freezer";
        $this->menu = 'dashboard|cold_storage';
        
        // Check permission for THIS freezer
        $freezer_permission = $this->cold_model->get_freezer_permission($this->session->userdata('user'), $id);
        if($freezer_permission != '1')
        {
            redirect('cold_storage');
        }
        
        $data = $this->cold_model->get($id);
        
        $data['id']  = $id;
        $lab = $this->session->userdata('lab');
        $data['users'] = $this->cold_model->get_related_users($id, $lab);
        
        if($this->error)
            $data['error'] = $this->error;
        
        $this->load->view('freezer_add', $data);
    }
    
    public function save()
    {
        if($this->input->post('cancel') !== FALSE)
            redirect('cold_storage');
            
        // Check permission
        if(!$this->usercontrol->has_permission('project'))
            redirect('dashboard');

        // Get project ID - false if new entry
        $freezer_id = $this->input->post('id');
        
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('type', 'Type', '');
        $this->form_validation->set_rules('users[]', 'Associated Users', '');
        
        if($this->form_validation->run() === false)  {
            $this->error = true;
            
            if ($freezer_id)
                $this->edit ($freezer_id);
            else
                $this->add ();
            return;
        }
        
        $this->load->model('cold_model');

        $sql_data = array(
            'name'        => $this->input->post('name'),
            'type'        => $this->input->post('type'),
        );

        if ($freezer_id)
            $this->cold_model->update($freezer_id,$sql_data);
        else
            $freezer_id = $this->cold_model->create($sql_data);

        // Related users
        $this->cold_model->delete_related($freezer_id);

        $users = $this->input->post('users');
        foreach ($users as $user) {
            $sql_data = array(
                'user' => $user,
                'freezer' => $freezer_id
            );
            $this->cold_model->create_related($sql_data);
        }

            redirect('cold_storage');
    }
    
    public function remove_project($project_id)
    {
        // Check permission for THIS freezer
        $freezer_permission = $this->cold_model->get_freezer_permission($this->session->userdata('user'), $id);
        if($freezer_permission != '1')
        {
            redirect('cold_storage');
        }
        
        $this->load->model('cold_model');
        $this->cold_model->delete($project_id);
        redirect('cold_storage');
    }
}
