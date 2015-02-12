<?php

class User_model extends CI_Model {

    private $salt = 'rF6g4p890nd0m';
    
    private $error = false;
    
    // User info
    private $photo;
    
    public $USER_LEVEL_ADMIN = 1;
    public $USER_LEVEL_PM = 2;
    public $USER_LEVEL_DEV = 3;

    public function create($data)
    {
        $data['password'] = sha1($data['password'].$this->salt);
        $insert = $this->db->insert('user', $data);
        return $insert;
    }

    public function update($id, $data)
    {
        if(isset($data['password']))
            $data['password'] = sha1($data['password'].$this->salt);
        $this->db->where('id', $id);
        $update = $this->db->update('user', $data);
        return $update;
    }

    public function get($id = false)
    {
        if ($id) $this->db->where('id', $id);
        $this->db->order_by('id', 'asc');
        $get = $this->db->get('user');

        if($id) return $get->row_array();
        if($get->num_rows > 0) return $get->result_array();
        return array();
    }
    
    public function get_last_task($id)
    {
        $this->db->select_max('date_created');
        $this->db->from('task');
        $this->db->where('user_id', $id);
        
        $get = $this->db->get();
        $ret = $get->row();
        return $ret->date_created;
    }
    
    public function get_num_culture($id)
    {
        $this->db->select('id');
        $this->db->from('project');
        $this->db->where('user', $id);
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    public function get_count()
    {
        $this->db->select('count(*) as count');
        $get = $this->db->get('user')->row_array();

        return $get['count'];
    }
    
    public function check_email($email)
    {
        $this->db->where('email', $email);
        $get = $this->db->get('user');
        $ret = $get->row();
        return $ret->email;
    }
    
    public function validate($email, $password)
    {
        $this->db->where('email', $email)->where('password', sha1($password.$this->salt));
        $get = $this->db->get('user');

        if($get->num_rows > 0) return $get->row_array();
        return array();
    }
    
    public function validate_github($email, $github_username, $github_token)
    {
        $this->db->where('email', $email);
        $get = $this->db->get('user');

        if($get->num_rows > 0) {
            // Save user's github username
            $user = $get->row_array();
            
            $this->db->where('id', $user['id'])->
                update('user', array(
                    'github_username' => $github_username,
                    'github_token' => $github_token
                    ));
            
            return $user;
        }
        return array();
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('user');
    }

    public function upload_photo()
    {
        
        $this->load->library('upload');

        $config = array(
            'allowed_types' => 'gif|png|jpg|jpeg',
            'upload_path' => getcwd().'/upload/profile',
            'max_size' => 2048,
            'overwrite' => true
        );
        $this->upload->initialize($config);

        // Run the upload
        if (!$this->upload->do_upload('photo')) {
            // Problem in upload
            $this->error = $this->upload->display_errors();
            echo $this->upload->display_errors();
            return false;
        }

        // Resize images
        $upload_data = $this->upload->data();
        if(!$this->user_model->prepare_image($upload_data)){
            return false;
        }

        $this->photo = $upload_data['file_name'];
        
        return true;
    }

    public function prepare_image($data)
    {
        // Make it square - Crop
        if($data['image_width'] > $data['image_height'])
            $size = $data['image_height'];
        else
            $size = $data['image_width'];
        
        $config = array(
            'source_image'   => $data['full_path'],
            'maintain_ratio' => false,
            'width'          => $size,
            'height'         => $size
        );
        
        $this->load->library('image_lib'); 

        $this->image_lib->clear();
        $this->image_lib->initialize($config); 
        if(!$this->image_lib->crop()){
            $this->error = $this->image_lib->display_errors();
            return false;
        }
        
        // Resize in three different sizes
        $target = array(
            0 => array('name' => 'large', 'width' => 128, 'height' => 128),
            1 => array('name' => 'medium', 'width' => 64, 'height' => 64),
            2 => array('name' => 'thumb', 'width' => 32, 'height' => 32)
        );
        
        for($i = 0; $i < count($target); $i++) {
            // Image library settings
            // Resize
            $config = array(
                'source_image' => $data['full_path'],
                'new_image'    => $data['file_path'].$target[$i]['name'].$data['file_name'],
                'width'        => $target[$i]['width'],
                'height'       => $target[$i]['height'],
                'master_dim'   => (($data['image_width'] / $data['image_height']) >= ($target[$i]['width'] / $target[$i]['height']))?'height':'width'
            );

            $this->image_lib->clear();
            $this->image_lib->initialize($config); 
            if(!$this->image_lib->resize()){
                $this->error = $this->image_lib->display_errors();
                return false;
            }
            
        }
        
        return true;
    }
    
    function get_photo()
    {
        return $this->photo;
    }
    
    function error_message()
    {
        return $this->error;
    }
    
    public function email_exists($email)
    {
        $sql = "SELECT name, email FROM user WHERE email = '{$email}' LIMIT 1";
        $result = $this->db->query($sql);
        $row = $result->row();
        
        return ($result->num_rows() === 1 && $row->email) ? $row->name : false;
    }
    
    public function update_password(){
        $email = $this->input->post('email');
        $password = sha1($this->input->post('password').$this->salt);
        
        $sql = "UPDATE user SET password = '{$password}' WHERE email = '{$email}' LIMIT 1";
        $this->db->query($sql);
        
        if($this->db->affected_rows() === 1){
            return true;
        }else{
            return false;
        }
    }
    
    public function verify_reset_password_code($email, $code){

        $sql = "SELECT name, email FROM user WHERE email = '{$email}' LIMIT 1";
        $result = $this->db->query($sql);
        $row = $result->row();
        
        if($result->num_rows() === 1){
            return($code == md5($this->config->item('salt').$row->name)) ? true : false;
        }else{
            return false;
        }
    }
    
    public function feature_notification($data){
        
        $insert = $this->db->insert('user_feature_notification', $data);
    }
    
    public function check_feature_notification($user){
        
        $this->db->select('feature_name');
        $this->db->from('user_feature_notification');
        $this->db->where('user_id', $user);
        $get = $this->db->get();
        
        if($get->num_rows > 0) return true;
    }
    
}
