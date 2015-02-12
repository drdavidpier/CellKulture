<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Freezer_box extends CI_Controller {
    
    private $error = false;
    
    function Freezer_box()
    {
        parent::__construct();
        
        $this->output->enable_profiler(false);
        
        if(!$this->usercontrol->has_permission('dashboard'))
            redirect('login');        
    }
    
    public function box($box_id)
    {
        //Load models
        $this->load->model('project_model');
        $this->load->model('task_model');
        $this->load->model('cold_model');
        $this->load->model('fbox_model');
        
        // Check permission for THIS freezer
        $freezer_permission = $this->fbox_model->get_freezer_permission($this->session->userdata('user'), $box_id);
        if($freezer_permission != '1')
        {
            redirect('cold_storage');
        }
        
        //table style
        $this->load->library('table');
        $tmpl = array ('table_open' => '<table class="table table80">',);
        $this->table->set_template($tmpl);
        
        $data['cell'] = $this->cold_model->get_fbox($box_id);
        $data['name'] = $this->cold_model->get_fbox_name($box_id);
        
        $this->title = 'Freezer Box'.' - '.$data['name'];
        $this->menu = 'dashboard|cold_storage';

        if($data['cell'] != Null){
            $array_locations = unserialize($data['cell']);
        }else{
            $array_locations = array();
        }
        
        $default_list = array_fill(1, 100, '<span class="empty_cell">Empty</span>');
        $filled_list = array_replace($default_list, $array_locations);
        
        $new_list = $this->table->make_columns($filled_list, 10);
        $data['table'] = $this->table->generate($new_list);
        
        // Load View
        $this->load->view('freezer_box', $data);
    }

    public function add()
    {
        // Check permission
        if(!$this->usercontrol->has_permission('project'))
            redirect('dashboard');
        
        $this->load->helper('cold');
        $this->load->model('fbox_model');
        $this->load->model('cold_model');
        
        $this->title = "Add a New Freezer Box";
        $this->menu = 'dashboard|cold_storage';
        
        $data['name']  = '';
        $data['freezerid']  = '';
        
        // Load freezers
        $data['freezers'] = $this->cold_model->get_user_freezers($this->session->userdata('user'));
        $items = array();
        foreach ($data['freezers'] as $freezer)
        {
            $items[$freezer['freezer_id']] = $freezer['name'];
        }
        $data['items'] = $items;
        
        $id = $this->session->userdata('user');
        $lab = $this->session->userdata('lab');
        $users = $this->fbox_model->get_related_users($id, $lab);
        foreach ($users as $key => $value) {
            if($value['id'] == $this->session->userdata('user'))
                $users[$key]['fbox'] = 1;
            else
                $users[$key]['fbox'] = 0;
        }
        $data['users'] = $users;
        
        if($this->error)
            $data['error'] = $this->error;
        
        $this->load->view('box_add', $data);
    }
    
    public function edit($id)
    {
        // Check permission
        if(!$this->usercontrol->has_permission('project'))
            redirect('dashboard');
        
        $this->load->helper('cold');
        $this->load->model('cold_model');
        $this->load->model('fbox_model');
        
        // Check permission for THIS freezer
        $freezer_permission = $this->fbox_model->get_freezer_permission($this->session->userdata('user'), $id);
        if($freezer_permission != '1')
        {
            redirect('cold_storage');
        }
        
        $this->title = "Edit Freezer Box";
        $this->menu = 'dashboard|cold_storage';
        
        $data = $this->cold_model->get_fbox_foredit($id);
        
        // Load freezers
        $data['freezers'] = $this->cold_model->get_user_freezers($this->session->userdata('user'));
        $items = array();
        foreach ($data['freezers'] as $freezer)
        {
            $items[$freezer['freezer_id']] = $freezer['name'];
        }
        $data['items'] = $items;
        
        $data['id']  = $id;
        $lab = $this->session->userdata('lab');
        $data['users'] = $this->cold_model->get_related_fboxusers($id, $lab);
        
        if($this->error)
            $data['error'] = $this->error;
        
        $this->load->view('box_add', $data);
    }
    
    public function save()
    {
        if($this->input->post('cancel') !== FALSE)
            redirect('cold_storage');
            
        // Check permission
        if(!$this->usercontrol->has_permission('project'))
            redirect('dashboard');

        // Get project ID - false if new entry
        $box_id = $this->input->post('id');
        
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('freezerid', 'Freezer ID', '');
        $this->form_validation->set_rules('users[]', 'Associated Users', '');
        
        if($this->form_validation->run() === false)  {
            $this->error = true;
            
            if ($box_id)
                $this->edit ($box_id);
            else
                $this->add ();
            
            return;
        }
        
        $this->load->model('fbox_model');

        $sql_data = array(
            'name'        => $this->input->post('name'),
            'freezerid'   => $this->input->post('freezerid'),
        );

        if ($box_id)
            $this->fbox_model->update($box_id,$sql_data);
        else
            $box_id = $this->fbox_model->create($sql_data);

        // Related users
        $this->fbox_model->delete_related($box_id);

        $users = $this->input->post('users');
        foreach ($users as $user) {
            $sql_data = array(
                'user' => $user,
                'fbox' => $box_id
            );
            $this->fbox_model->create_related($sql_data);
        }
// not working
        if ($box_id)
            redirect('freezer_box/box/'.$box_id);
        else
           redirect('cold_storage');
    }
}
