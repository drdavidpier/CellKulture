<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Task extends CI_Controller {
    
    private $error = false;
    
    function Task()
    {
        parent::__construct();
        
        if(!$this->usercontrol->has_permission('task'))
            redirect('dashboard');
    }
    
    public function index()
    {
        redirect('dashboard');
    }

    public function add($project)
    {
        $this->load->helper('tasks');
        $this->load->model('task_model');
        
        // Check permission for THIS project
        $project_permission = $this->task_model->get_project_permission($this->session->userdata('user'), $project);
        if($project_permission != '1')
        {
            redirect('dashboard');
        }
        
        $this->title = 'New Task';
        $this->menu = 'dashboard|tasks';
        
        $data['parent_id']      = 0;
        $data['title']          = '';
        $data['description']    = '';
        $data['priority']       = '2';
        $data['due_date']       = date("d-m-Y");
        
        $data['project_id'] = $project;
        $data['users']      = $this->task_model->get_related_users($project);
        $data['user_id']    = $this->session->userdata('user');
        $data['tasks']      = $this->task_model->get_hierarchy($project);
        
        if($this->error)
            $data['error'] = $this->error;
        
        $this->load->view('task_add', $data);
        
    }

    public function edit($project, $id)
    {
        $this->load->helper('tasks');
        $this->load->model('task_model');
        
        $data = $this->task_model->get($project, $id);
        
        // Check permission for THIS project
        $project_permission = $this->task_model->get_project_permission($this->session->userdata('user'), $project);
        if($project_permission != '1')
        {
            redirect('dashboard');
        }
        
        if($data['due_date']){
            $data['due_date'] = date('m/d/Y', strtotime($data['due_date']));
        }else{
            $data['due_date'] = date("d-m-Y");
        }
        
        $this->title = "Edit Task #{$data['code']}";
        $this->menu = 'dashboard|tasks';
        
        $data['project_id']  = $project;
        $data['users'] = $this->task_model->get_related_users($project);
        $data['tasks'] = $this->task_model->get_hierarchy($project, null, true, array(), 0, $id);
        
        if($this->error)
            $data['error'] = $this->error;
        
        $this->load->view('task_add', $data);
    }

    public function view($project, $id)
    {
        $this->load->helper('tasks');
        $this->load->helper('stb_date');
        
        $this->load->model('task_model');
        $this->load->model('user_model');
        
        // Check permission for THIS project
        $project_permission = $this->task_model->get_project_permission($this->session->userdata('user'), $project);
        if($project_permission != '1')
        {
            redirect('dashboard');
        }
        
        $data = $this->task_model->get($project, $id);
        
        $this->title = "Edit Note";
        $this->menu = 'dashboard|cold_storage|tasks|edit_task';
                
        $data['project_id']  = $project;
        
        if($data['parent_id'])
            $data['parent_tasks'] = $this->task_model->get_parents($project, $data['parent_id']);
        else
            $data['parent_tasks'] = false;
        
        $data['children_tasks'] = $this->task_model->get_hierarchy($project, $id, false);
        
        $user = $this->user_model->get($data['user_id']);
        $data['user'] = $user;
        
        $data['comments'] = $this->task_model->get_comments($id);
        
        $data['task_history'] = $this->task_model->get_history($id);
        $data['task_history_last'] = $this->task_model->get_last_history($id);
        $data['status_arr'] = $this->task_model->get_status_array();
        
        // make the collapsed items open if action is in database by setting class to 'in'
        $data['collapse_passage'] = '';
        $data['collapse_freeze'] = '';
        $data['collapse_remove'] = '';
        /*
        $data['none'] = '';
        $data['media'] = '';
        $data['passage'] = '';
        $data['freeze'] = '';
        $data['remove'] = '';
        
        if($data['action'] == '0' || $data['action_two'] == '0'){
            $data['none'] = 'active';
        }
        if($data['action'] == '2' || $data['action_two'] == '2'){
            $data['media'] = 'active';
        }*/
        if($data['action'] == '2' || $data['action_two'] == '2'){
            $data['collapse_passage'] = 'in';
        }
        if($data['action'] == '3' || $data['action_two'] == '3'){
            $data['collapse_freeze'] = 'in';
        }
        if($data['action'] == '4' || $data['action_two'] == '4'){
            $data['collapse_remove'] = 'in';
        }
        
        $data['task_data'] = $this->load->view('task_add',$data,true);
        $this->load->view('task', $data);
    }
    
    public function save()
    {
        $project_id = $this->input->post('project_id');
        $id = $this->input->post('task_id');
        
        if($this->input->post('cancel') !== FALSE) {
            if($id)
                redirect('task/view/'.$project_id.'/'.$id);
            else
                redirect('project/tasks/'.$project_id);
        }
                    
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('parent_id', 'Parent', '');
        $this->form_validation->set_rules('priority', 'Priority', '');
        $this->form_validation->set_rules('description', 'Description', 'trim');
        $this->form_validation->set_rules('user_id', 'Assigned to', '');
        $this->form_validation->set_rules('due_date', 'Due Date', 'trim');
        
        if($this->form_validation->run() === false)  {
            $this->error = true;
            
            if ($id)
                $this->edit ($project_id, $id);
            else
                if($project_id)
                    $this->add ($project_id);
                else
                    redirect('dashboard');
            
            return;
        }
        
        $this->load->model('task_model');
        
        $sql_data = array(
            'project_id'  => $project_id,
            'status'      => ($this->input->post('status'))?$this->input->post('status'):0,
            'title'       => $this->input->post('title'),
            'parent_id'   => $this->input->post('parent_id'),
            'description' => $this->input->post('description'),
            'priority'    => $this->input->post('priority'),
            'user_id'     => $this->input->post('user_id'),
            'due_date'    => ($this->input->post('due_date'))?date('Y-m-d', strtotime($this->input->post('due_date'))):NULL,
            'flask_type'  => $this->input->post('flask_type'),
            'flask_number'=> $this->input->post('flask_number'),
            'confluence'  => $this->input->post('confluence'),
            'cell_quality'=> $this->input->post('cell_quality'),
            'action'      => $this->input->post('action'),
            'infection'   => $this->input->post('infection'),
            'flask_number_new'=> $this->input->post('flask_number_new'),
            'split_ratio' => $this->input->post('split_ratio'),
            'passage_new' => ($this->input->post('action') == '2')?$this->input->post('passage_new'):NULL,
            'flasks_removed' => $this->input->post('flasks_removed'),
            'vial_number' => $this->input->post('vial_number'),
            //'f_location'  => $this->input->post('f_location'),
            'removal_reason' => $this->input->post('removal_reason'),
            'flasks_removed_two' => $this->input->post('flasks_removed_two'),
            'action_two'  => $this->input->post('action_two')
        );
        
        if ($id)
            $this->task_model->update($this->input->post('project_id'), $id, $sql_data);
        else
            $id = $this->task_model->create($sql_data);

        //add in upload function here
        $config['upload_path'] = './upload/cell/';
        $config['allowed_types'] = 'gif|jpg|png|tif';
        $config['max_size'] = '10000';
        $config['max_width']  = '2024';
        $config['max_height']  = '1768';
        $config['remove_spaces'] = TRUE;
        
        $this->load->library('upload', $config);
        
        if ( ! $this->upload->do_upload())
        {
            $error = array('error' => $this->upload->display_errors());
            //$this->load->view('upload_forms', $error);
        }
        else
        {
            $data = array('upload_data' => $this->upload->data());
            $upload_data = $this->upload->data();
            $filename = $upload_data['file_name'];

            $sql_photo = array('photo' => $filename);
            $this->task_model->update($this->input->post('project_id'), $id, $sql_photo);
            
            // Resize images
            $config['image_library'] = 'gd2';
            $config['source_image']	= './upload/cell/'.$filename;
            $config['new_image'] = './upload/cell/thumbs/';
            $config['maintain_ratio'] = TRUE;
            $config['width']	 = 150;
            $config['height']	= 100;
            $this->load->library('image_lib', $config); 
            $this->image_lib->resize();
            echo $this->image_lib->display_errors();
        }
        // end of added uploader code
        
        redirect('project/tasks/'.$this->input->post('project_id'));
    }

    public function ajax_comment($project_id, $task_id, $status)
    {
        if(!IS_AJAX) {
            redirect('project/tasks/'.$project_id);
        }
        
        $this->layout = 'ajax';
        
        if($this->input->post('user_id')) {
            
            // Save user
            $this->load->model('task_model');
            
            if($this->input->post('user_id') != $this->session->userdata('user')) {
                $sql_data = array('user_id' => $this->input->post('user_id'));

                $this->task_model->update(
                        $this->input->post('project_id'),
                        $this->input->post('task_id'),
                        $sql_data);
            }
            
            // Save comment
            if($this->input->post('comment')) {
                $data = array(
                    'task_id' => $this->input->post('task_id'),
                    'user_id' => $this->session->userdata('user'),
                    'comment' => $this->input->post('comment')
                );

                $this->task_model->create_comment($data);
            }

            echo base_url("task/move/$project_id/$task_id/$status");
            
        } else {
            
            // Load modal
            $this->load->model('task_model');
            
            $data = array(
                'task_id' => $task_id,
                'project_id' => $project_id,
                'status' => $status,
                'user_id' => $this->session->userdata('user'),
                'users' => $this->task_model->get_related_users($project_id)
            );

            $this->load->view('task_comment_modal', $data);
            
        }
    }
    
    public function remove($project, $id)
    {
        // Check permission for THIS project
        $this->load->model('task_model');
        $project_permission = $this->task_model->get_project_permission($this->session->userdata('user'), $project);
        if($project_permission != '1')
        {
            redirect('dashboard');
        }
        
        $this->load->model('task_model');
        $this->task_model->delete($project, $id);

        redirect('project/tasks/'.$project);
    }
    
    public function comment()
    {
        // TODO: Check if user is related to project
        
        $data = array(
            'task_id' => $this->input->post('task_id'),
            'user_id' => $this->session->userdata('user'),
            'comment' => $this->input->post('comment')
        );
        
        $this->load->model('task_model');
        $this->task_model->create_comment($data);

        redirect('task/view/'.$this->input->post('project_id').'/'.$data['task_id']);
    }
   
}
