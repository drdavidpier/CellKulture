<?php if ( ! defined('BASEPATH'));

class Invite extends CI_Controller {
    
    public function index($success = '0')
    {
        $this->load->helper('url');
        
        // Load View
        $this->title  = 'Invite A Labmate';
        $this->menu = 'dashboard|cold_storage';
        
        $this->load->model('user_model');
        $data = $this->user_model->get($this->session->userdata('user'));
        $data['success'] = $success;
                
        $this->load->view('invite', $data);
    }
    
    public function validate()
    {
        $this->load->library('form_validation');
        $this->load->model('user_model');
        $this->form_validation->set_rules('name', 'name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
        
        if($this->form_validation->run() === false)  {
            $data['name'] = $this->input->post('name');

            $data['email'] = $this->input->post('email');
            
            $data['error'] = true;
            
            // Load View
            $this->title  = 'Invite A Labmate';
            $this->menu = 'dashboard|cold_storage';
                
            $this->load->view('invite', $data);
        }else{
        $this->load->model('user_model');
        if($this->user_model->check_email($this->input->post('email'))){

            //send email with details to change lab group
            //send the email 
            $url = 'http://sendgrid.com/';
            $user = 'username';
            $pass = 'password'; 

            $params = array(
                'api_user'  => $user,
                'api_key'   => $pass,
                'to'        => $this->input->post('email'),
                'subject'   => $this->input->post('user_name').' would like you to join their lab on CellKulture.com',
                'html'      => '<table style="font-family: verdana, tahoma, sans-serif; color: #2C3E50; width:100%; padding:5px;">
                            <tr>
                                <td>
                                    <h1>Dear '.$this->input->post('name').'</h1>
                                    <p>'.$this->input->post('user_name').' would like to invite you to join the following lab group on CellKulture.com</p>
                                    <h1 align="center" style="color: #cd2727; ">'.$this->input->post('lab').'</h1>
                                    <p>To join this lab group simply copy and paste the lab group name above in to your profile on <a href="http://cellkulture.com">CellKulture.com</a></p>
                                    <br />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p style="color:#95A5A6">Regardless of your lab group your data always remains private by default. Once you have joined a lab group you can share as much or as little data as you like</p>
                                    <p></p>
                                    <p>Regards<br />The CellKulture Team</p>
                                </td>
                            </tr>
                            <tr style="background-color: #ECF0F1; padding: 8px;">
                                <td align="center">
                                    <h2><span style="color: #cd2727; ">CellKulture</span> Your Free Virtual Incubator</h2>
                                    <p></p>
                                    <p></p>
                                </td>
                            </tr>
                            <p></p>',
                // ----------- SORT THE PLAIN TEXT EMAIL --------------------------
                //'text'      => 'Dear'.$users['name'].'.  The CellKulture Team',
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
        }else{

            //send email to sign up and also lab group
            //send the email 
            $url = 'http://sendgrid.com/';
            $user = 'username';
            $pass = 'password'; 

            $params = array(
                'api_user'  => $user,
                'api_key'   => $pass,
                'to'        => $this->input->post('email'),
                'subject'   => $this->input->post('user_name').' would like you to join their lab on CellKulture.com',
                'html'      => '<table style="font-family: verdana, tahoma, sans-serif; color: #2C3E50; width:100%; padding:5px;">
                            <tr>
                                <td>
                                    <h1>Dear '.$this->input->post('name').'</h1>
                                    <p>'.$this->input->post('user_name').' would like to invite you to join the lab group "'.$this->input->post('lab').'" on CellKulture.com</p>
                                    <p>To join CellKulture and this lab group simply click the button below. Its Free!</p>
                                    <br />
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <p><a href="http://cellkulture.com/app/registration/index/'.$this->input->post('lab').'" style="background-color: #16A085; padding: 15px; padding-left:25px; padding-right:25px; color: #fff; border-radius: 2px; text-decoration: none;">Join '.$this->input->post('lab').'</a></p>
                                    
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p style="color:#95A5A6">Regardless of your lab group your data always remains private by default. Once you have joined a lab group you can share as much or as little data as you like</p>
                                    <p></p>
                                    <p>Regards<br />The CellKulture Team</p>
                                </td>
                            </tr>
                            <tr style="background-color: #ECF0F1; padding: 8px;">
                                <td align="center">
                                    <h2><span style="color: #cd2727; ">CellKulture</span> Your Free Virtual Incubator</h2>
                                    <p></p>
                                    <p></p>
                                </td>
                            </tr>
                            <p></p>',
                // ----------- SORT THE PLAIN TEXT EMAIL --------------------------
                //'text'      => 'Dear'.$users['name'].'.  The CellKulture Team',
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
        
        $sql_data = array(
            'name'    => $this->input->post('name'),
            'email'    => $this->input->post('email'),
            'user'    => $this->session->userdata('user'),
            'lab'      => $this->input->post('lab')
        );
        
        $invite = $this->user_model->user_invite($sql_data);
        
        redirect('invite/index/1');
        }
    }
}
