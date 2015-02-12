<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
    
    private $LEVEL;
    private $error = false;
        
    function User()
    {
        parent::__construct();
        
        if(!$this->usercontrol->has_permission('user'))
            redirect('dashboard');
        
        $this->LEVEL = array(
            1 => 'Admin',
            2 => 'Standard',
            3 => 'No Rights'
        );
    }
    
    public function index()
    {
        // Load open transports
        $this->load->model('user_model');
        
        $users = $this->user_model->get(false);
        $data['level_list'] = $this->LEVEL;

        foreach($users as $key => $value){
            //get last task
            $task2 = $this->user_model->get_last_task($value['id']);
            
            $task = strtotime($task2);
            $today =  strtotime(date('Y-m-d H:i:s'));
            
            $seconds_since = $today - $task;
            $days_since = intval($seconds_since/(60*60*24));
            
            $users[$key]['days_since'] = $days_since;
            
            //set color of table row
            if($days_since > 20){
                $users[$key]['highlight'] = 'danger';
            }else{
                $users[$key]['highlight'] = '';
            }
            
            //get number of projects
            $cultures = $this->user_model->get_num_culture($value['id']);
            $users[$key]['cultures'] = $cultures;
            
        }
        $data['users'] = $users;
        
        
        $this->title = 'Users';
        $this->layout = 'default';
        $this->menu = 'dashboard|cold_storage|add_user';
        
        // Load View
        $this->load->view('users', $data);
    }

    public function add()
    {
        $this->title = 'New User';
        $this->menu = 'dashboard|add_user';
        
        $data['email']    = '';
        $data['password'] = '';
        $data['level']    = '1';
        $data['level_list'] = $this->LEVEL;
        
        if($this->error)
            $data['error'] = $this->error;
        
        $this->load->view('users_add', $data);
    }

    public function edit($id)
    {
        $this->load->model('user_model');
        $data = $this->user_model->get($id);
        
        $data['password'] = '';
        
        $this->title = "Edit User #$id";
        $this->menu = 'dashboard|add_user';
        
        $data['level_list'] = $this->LEVEL;
        
        if($this->error)
            $data['error'] = $this->error;
        
        $this->load->view('users_add', $data);
    }
    
    public function remove($id)
    {
        $this->load->model('user_model');
        $this->user_model->delete($id);
        
        redirect('user');
    }
    
    public function save()
    {
        if($this->input->post('cancel') !== FALSE)
            redirect('user');
            
        $user_id = $this->input->post('id');
        
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim');
        $this->form_validation->set_rules('level', 'Level', 'required');
        
        if($this->form_validation->run() === false)  {
            $this->error = true;
            
            if ($user_id)
                $this->edit ($user_id);
            else
                $this->add ();
            
            return;
        }
        
        $this->load->model('user_model');
        
        $sql_data = array(
            'email'    => $this->input->post('email'),
            'level'    => $this->input->post('level')
        );
        
        if($this->input->post('reset_password')){
            $sql_data['password'] = $this->input->post('password');
        }
        
        if ($user_id)
            $this->user_model->update($user_id,$sql_data);
        else
            $this->user_model->create($sql_data);

        redirect('user');
    }
    
}
