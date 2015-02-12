<?php if ( ! defined('BASEPATH'));

class Weeklydigest extends CI_Controller {

    public function index()
    {
        $this->load->model('user_model');
        $emailusers = $this->user_model->get(false);
        
        foreach($emailusers as $key => $users){

        $email_code = md5($this->config->item('salt').$users['name']);
        
        if($users['weeklydigest'] != 1){
            
        //Load models
        $this->load->model('project_model');
        $this->load->model('task_model');
        $this->load->helper('date');
        
        $flasks = $this->task_model->get_user_tasks_two($users['id']);
        
        if(empty($flasks)){
            $table = "<table style=\"width: 80%; border-collapse: collapse; border-spacing: 0;\">";
            $table .= " <tr>";
            $table .= " <td style=\"background-color: #1ABC9C; padding: 8px; color: #fff; text-align:center;\"><h2>Your Virtual Incubator is Empty</h2><br /><p><a href=\"http://cellkulture.com\" style=\"background-color: #16A085; padding: 15px; padding-left:25px; padding-right:25px; color: #fff; border-radius: 2px; text-decoration: none;\">Add a Culture</a></p></td>";
            $table .= "</tr>";
            $table .= "</table>";
        }else{
        
        foreach($flasks as $key => $value){
            //get last task
            $task2 = $this->task_model->get_last_task_date($value['project_id']);
            //get the days since last note
            $timestamp = strtotime($task2);
            $now = time();
            $timenow = timespan($timestamp, $now, false);
            $flasks[$key]['flask_date'] = $timenow;
            //get last task number and then convert to string
            $task3 = $this->task_model->get_last_task_details($task2 , $value['project_id']);
            $this->load->helper('tasks');
            $task4 = flask_type_csv($task3);
            $flasks[$key]['flask_type'] = $task4;
        }
            
        $table = "<table style=\"border: 1px solid #dddddd; width: 80%; border-collapse: collapse; border-spacing: 0;\">";
        $table .= " <tr>";
        $table .= "     <th style=\"background-color: #f9f9f9; border: 1px solid #dddddd; padding: 8px;\">Culture</th>";
        $table .= "     <th style=\"background-color: #f9f9f9; border: 1px solid #dddddd; padding: 8px;\">Vessel</th>";
        $table .= "     <th style=\"background-color: #f9f9f9; border: 1px solid #dddddd; padding: 8px;\">Days Since Last Action</th>";
        $table .= " </tr>";
        foreach($flasks as $value){
            $table .="<tr>";
            $table .= " <td style=\"border: 1px solid #dddddd; padding: 8px;\"><a href=\"http://cellkulture.com/app\" style=\"color: #cd2727; text-decoration: none;\">".$value['project_name']."</a></td>";
            $table .= " <td style=\"border: 1px solid #dddddd; padding: 8px;\">".$value['flask_type']."</td>";
            $table .= " <td style=\"border: 1px solid #dddddd; padding: 8px;\">".$value['flask_date']."</td>";
            $table .="</tr>";
        }
        $table .= "</table>";
        
        }
        //echo $table;
        
        //if($most_recent_culture_is_more_than_6_days_untouched){
        //    $neglected = $number_of_untouched_cultures.' cultures have not been looked at this week';
        //}
        
        $usertable = '<h3>No-one is in your lab group</h3><p>You can add lab group members in the profile section of CellKulture</p>';
        foreach($emailusers as $key => $labusers){
            if($labusers['lab'] == $users['lab'] && $labusers['name'] != $users['name']){
                $gettasks = $this->task_model->get_user_tasks_two($labusers['id']);
                $usertable = "<table style=\"border: 1px solid #dddddd; width: 80%; border-collapse: collapse; border-spacing: 0;\">";
                $usertable .= " <tr>";
                $usertable .= "     <th style=\"background-color: #f9f9f9; border: 1px solid #dddddd; padding: 8px;\">Lab Member</th>";
                $usertable .= "     <th style=\"background-color: #f9f9f9; border: 1px solid #dddddd; padding: 8px;\">Culture</th>";
                $usertable .= " </tr>";
                foreach($gettasks as $key => $usertasks){
                    $gettaskdate = $this->task_model->get_last_task_date($usertasks['project_id']);
                    $timesincetask = time() - strtotime($gettaskdate);
                    $weeksecs = 60 * 60 * 24 * 6;
                    if($timesincetask < $weeksecs){
                        $usertable .="<tr>";
                        $usertable .= " <td style=\"border: 1px solid #dddddd; padding: 8px;\"><a href=\"http://cellkulture.com/app\" style=\"color: #cd2727; text-decoration: none;\">".$labusers['name']."</a></td>";
                        $usertable .= " <td style=\"border: 1px solid #dddddd; padding: 8px;\">".$usertasks['project_name']."</td>";
                        $usertable .="</tr>";
                    }
                }
                $usertable .= "</table>";
            }
        }
        //echo $usertable;
        
        //send the email 
        $url = 'http://sendgrid.com/';
        $user = 'username';
        $pass = 'password'; 

        $params = array(
            'api_user'  => $user,
            'api_key'   => $pass,
            'to'        => $users['email'],
            'subject'   => 'CellKulture Weekly Digest',
            'html'      => '<table style="font-family: verdana, tahoma, sans-serif; color: #2C3E50; width:100%; padding:5px;">
                            <tr>
                                <td align="center"><h1>Dear '.$users['name'].'</h1></td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <p>Your cultures in the <span style="color: #cd2727; ">CellKulture</span> virtual incubator</p>
                                    <br />
                                    '.$table.'
                                    <br />
                                    <p>Active lab group members this week</p>
                                    <br />
                                    '.$usertable.'
                                    <br />
                                </td>
                            </tr>
                            <tr style="background-color: #ECF0F1; padding: 8px;">
                                <td align="center">
                                    <h2>Have a Great Weekend!</h2>
                                    <p style="color: #BDC3C7;"><a href="http://cellkulture.com/'.base_url().'/weeklydigest/unsubscribe/'.$users['email'].'/'.$email_code.'" style="color: #BDC3C7;">Unsubscribe</a> from the CellKulture weekly digest</p>
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
        }
    }
    
    public function unsubscribe($email, $email_code){
        if(isset($email, $email_code)){
            $email = trim($email);
            $email_hash = sha1($email.$email_code);
            $this->load->model('user_model');
            $verified = $this->user_model->verify_reset_password_code($email, $email_code);
            
            if($verified){
                $result = $this->user_model->update_preference($email);
                // Load View
                $this->title  = 'Update Preferences';
                $this->layout = 'login';
                $this->menu   = 'none';
                //this should happen
                $this->load->view('preference_changed');
            }else{
                // Load View
                $this->title  = 'Update Preferences';
                $this->layout = 'login';
                $this->menu   = 'none';
                //this should never happen
                $this->load->view('error');
            }
        }
    }
}
