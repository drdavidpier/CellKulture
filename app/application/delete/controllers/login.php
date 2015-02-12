<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

    public function index()
    {
        // Check if users exist
        $this->load->model('user_model');
        $users = $this->user_model->get_count();
        if($users == 0)
            redirect('install');
        
        // Check if database version is correct
        $this->load->model('database_model');
        if(!$this->database_model->is_up_to_date())
            redirect('install');
        
        // Load View
        $this->title  = 'Login';
        $this->layout = 'login';
        $this->menu   = 'none';
        
        $data['email'] = '';
        $data['password'] = '';
                
        $this->load->view('login', $data);
    }
    
    public function validate()
    {
        $this->load->model('user_model');
        $result = $this->user_model->validate($this->input->post('email'),$this->input->post('password'));
        
        if($result) {
            $this->session->set_userdata(array(
                'logged' => true,
                'user'  => $result['id'],
                'level' => $result['level'],
                'lab'   => $result['lab']
            ));
            
            redirect('dashboard');
        } else {
            // Load View
            $this->title  = 'Login';
            $this->layout = 'login';
            $this->menu   = 'none';

            $data['email'] = $this->input->post('email');
            $data['password'] = $this->input->post('password');
            
            $data['error'] = true;
            
            $this->load->view('login', $data);
        }
    }
    
    public function registration()
    {
        $this->load->library('form_validation');
        // field name, error message, validation rules
        $this->form_validation->set_rules('user_name', 'User Name', 'trim|required|min_length[4]|xss_clean');
        $this->form_validation->set_rules('email_address', 'Your Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
        $this->form_validation->set_rules('con_password', 'Password Confirmation', 'trim|required|matches[password]');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->registration();
        }
        else
        {
            $this->user_model->add_user();
            
            $data['email'] = $this->input->post('email');
            $data['password'] = $this->input->post('password');
            
            $this->load->view('login', $data);
        }
        
    }
    
    public function logout()
    {
        $this->session->unset_userdata('logged');

        redirect('login');
    }
    
    public function reset_password()
    {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('email','Email Address','trim|required|valid_email|xxs_clean');
            
            if($this->form_validation->run() === false) 
            {
                // Load View
                $this->title  = 'Reset Password';
                $this->layout = 'login';
                $this->menu   = 'none';
                //email didnt validate
                $this->load->view('view_reset_password', array('error' => 'Please supply a valid email address'));
            }else{
                $this->load->model('user_model');
                $email = trim($this->input->post('email'));
                $result = $this->user_model->email_exists($email);
                
                if($result){
                    //if we found the email, $result is now their first name
                    $this->send_reset_password_email($email, $result);
                    // Load View
                $this->title  = 'Reset Password';
                $this->layout = 'login';
                $this->menu   = 'none';
                $data['email'] = $email;
                    $this->load->view('view_reset_password_sent', $data);
                }else{
                    // Load View
                $this->title  = 'Reset Password';
                $this->layout = 'login';
                $this->menu   = 'none';
                    $this->load->view('view_reset_password', array('error' => 'Email address not registered with CellKulture'));
                }
            }
    }
    
    public function reset_password_form($email, $email_code){
        if(isset($email, $email_code)){
            $email = trim($email);
            $email_hash = sha1($email.$email_code);
            $this->load->model('user_model');
            $verified = $this->user_model->verify_reset_password_code($email, $email_code);
            
            if($verified){
                // Load View
                $this->title  = 'Reset Password';
                $this->layout = 'login';
                $this->menu   = 'none';
                $this->load->view('view_update_password', array('email_hash' => $email_hash, 'email_code' => $email_code, 'email' => $email));
            }else{
                // Load View
                $this->title  = 'Reset Password';
                $this->layout = 'login';
                $this->menu   = 'none';
                //send back to password page and dont update
                $this->load->view('view_reset_password', array('error' => 'There was a problem with your link. Please try the link in your email again or request a new password'));
                
            }
        }
    }
    
    private function send_reset_password_email($email, $name)
    {
        $email_code = md5($this->config->item('salt').$name);
        
        $url = 'http://sendgrid.com/';
        $user = 'username';
        $pass = 'password'; 

        $params = array(
            'api_user'  => $user,
            'api_key'   => $pass,
            'to'        => $email,
            'subject'   => 'Reset Your CellKulture Password',
            'html'      => '<p>Dear '.$name.'</p><p>A password reset link has been requested for CellKulture.com using this email account. Click the link below to reset password</p><p><a href="vm-3.drdavepier.kd.io/'.base_url().'/login/reset_password_form/'.$email.'/'.$email_code.'">Reset Your Password</a><p>If you did not request a password reset simply ignore this email or report it to info@cellkulture.com</p><p>The CellKulture Team</p>',
            'text'      => 'Dear'.$name.'. A password reset link has been requested for CellKulture.com using this email account. Please copy and paste this link into your browser to reset password "drdavepier.kd.io/'.base_url().'/login/reset_password_form/'.$email.'/'.$email_code.'. If you did not request a password reset simply ignore this email or report it to info@cellkulture.com. The CellKulture Team',
            'from'      => 'info@cellkulture.com',
        );

        $request =  $url.'api/mail.send.json';

        // Generate curl request
        $session = curl_init($request);
        // Tell curl to use HTTP POST
        curl_setopt ($session, CURLOPT_POST, true);
        // Tell curl that this is the body of the POST
        curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
        // Tell curl not to return headers, but do return the response
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

        // obtain response
        $response = curl_exec($session);
        curl_close($session);
    }
    
    public function update_password()
    {
        if (!isset($_POST['email'], $_POST['email_hash']) || $_POST['email_hash'] !== sha1($_POST['email'].$_POST['email_code'])) {
            //either a hacker or changed email in the email field
            die('Error updating your password');
        }
        
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('email_hash','Email Hash', 'trim|required');
        $this->form_validation->set_rules('email','Email','trim|required|valid_email|xss_clean');
        $this->form_validation->set_rules('password','Password','trim|required|xss_clean');
        $this->form_validation->set_rules('password_conf','Confirmed Password','trim|required|xss_clean');
        
        if($this->form_validation->run() == false){
            // Load View
                $this->title  = 'Reset Password';
                $this->layout = 'login';
                $this->menu   = 'none';
            //user didnt validate, send back to update password form
            $this->load->view('view_update_password');
        }else{
            //sucessful update
            // returns user name if sucessful
            $this->load->model('user_model');
            $result = $this->user_model->update_password();
            
            if ($result){
                // Load View
                $this->title  = 'Reset Password';
                $this->layout = 'login';
                $this->menu   = 'none';
                $this->load->view('view_update_password_sucess');
            }else{
                // Load View
                $this->title  = 'Reset Password';
                $this->layout = 'login';
                $this->menu   = 'none';
                //this should never happen
                $this->load->view('view_update_password',array('error' => 'Problem updating your record. Please contact login@cellKulture.com'));
            }
        }
    }
}
