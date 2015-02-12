<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Registration extends CI_Controller {
    
    public function index()
    {
        $this->load->helper('url');
        
        // Load View
        $this->title  = 'Login';
        $this->layout = 'login';
        $this->menu   = 'none';
        
        $data['email'] = '';
        $data['password'] = '';
        $data['name'] = '';
                
        $this->load->view('registration', $data);
    }
    
    public function validate()
    {
        $this->load->library('form_validation');
        $this->load->model('user_model');
        $this->form_validation->set_rules('name', 'Username', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('terms', 'Terms and conditions', 'required');
        
        if($this->form_validation->run() === false)  {
            $data['name'] = $this->input->post('name');
            $data['email'] = $this->input->post('email');
            $data['password'] = $this->input->post('password');
            $data['terms'] = $this->input->post('terms');
            
            $data['error'] = true;
            
            // Load View
            $this->title  = 'Login';
            $this->layout = 'login';
            $this->menu   = 'none';
            
            $this->load->view('registration', $data);
        }
        elseif($this->user_model->check_email($this->input->post('email')) == $this->input->post('email')){
            $data['name'] = $this->input->post('name');
            $data['email'] = $this->input->post('email');
            $data['password'] = $this->input->post('password');
            $data['terms'] = $this->input->post('terms');
            
            $data['email_error'] = "There is already a user with this email address.";
            $data['error'] = true;
            
            // Load View
            $this->title  = 'Login';
            $this->layout = 'login';
            $this->menu   = 'none';
            
            $this->load->view('registration', $data);
        }else
        {
        $this->load->model('user_model');
        
        $sql_data = array(
            'name'    => $this->input->post('name'),
            'email'    => $this->input->post('email'),
            'password'    => $this->input->post('password'),
            'level'    => '2',
            'lab'      => random_string('alpha', 8)
        );
        
        $this->user_model->create($sql_data);
        
        redirect('login');
        }
    }
}