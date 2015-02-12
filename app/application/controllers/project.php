<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project extends CI_Controller {
    
    private $error = false;
    
    public function index()
    {
        redirect('dashboard');
    }
    
    public function download ($project_id)
    {
        ob_start();
        $this->load->helper('stb_date');
        $this->load->helper('tasks');
        
        // Check permission
        if(!$this->usercontrol->has_permission('project', 'tasks'))
            redirect('dashboard');
            
        // Check permission for THIS project
        $this->load->model('task_model');
        $project_permission = $this->task_model->get_project_permission($this->session->userdata('user'), $project_id);
        if($project_permission != '1')
        {
            redirect('dashboard');
        }
        
        // Load tasks
        $this->load->model('task_model');
        $stories = $this->task_model->get_download($project_id);
        //var_dump($stories);
        
        foreach($stories as $key => $subarray) {
            if($stories[$key]['infection'] == 1){
                $stories[$key]['infection'] = 'Infected';
            }else{
                $stories[$key]['infection'] = 'None';
            }
            $stories[$key]['flask_type'] = flask_type_csv($stories[$key]['flask_type']);
            $stories[$key]['Media_colour'] = task_priority_text($stories[$key]['Media_colour']);
            $stories[$key]['cell_quality'] = cell_quality_text($stories[$key]['cell_quality']);
            $stories[$key]['First_Action_Taken'] = action_text($stories[$key]['First_Action_Taken']);
            $stories[$key]['removal_reason'] = removal_reason_text($stories[$key]['removal_reason']);
            $stories[$key]['Second_Action'] = action_text($stories[$key]['Second_Action']);
        }
        $stories = array_merge(array(array_keys($stories[0])), $stories);
        
        $fp = fopen('php://output', 'w');
        foreach ($stories as $fields) {
            fputcsv($fp, $fields);
        }
       
        $data = file_get_contents('php://output'); // Read the file's contents
        $name = 'data.csv';
        
        // Build the headers to push out the file properly.
        header('Pragma: public');     // required
        header('Expires: 0');         // no cache
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private',false);
        header('Content-Disposition: attachment; filename="'.basename($name).'"');  // Add the file name
        header('Content-Transfer-Encoding: binary');
        header('Connection: close');
        exit();
       
       ob_end_flush();

    }
    
    public function download_from_drdavidpier ($project_id)
    {
        ob_start();
        $this->load->helper('stb_date');
        $this->load->helper('tasks');
        
        // Check permission
        if(!$this->usercontrol->has_permission('project', 'tasks'))
            redirect('dashboard');
            
        
        
        // Load tasks
        $this->load->model('task_model');
        $stories = $this->task_model->get($project_id);
        //var_dump($stories);
        
        foreach($stories as $key => $subarray) {
            if($stories[$key]['infection'] == 1){
                $stories[$key]['infection'] = 'Infected';
            }else{
                $stories[$key]['infection'] = 'None';
            }
            $stories[$key]['flask_type'] = 'flask';
        }
        $stories = array_merge(array(array_keys($stories[0])), $stories);
        
        $fp = fopen('php://output', 'w');
        foreach ($stories as $fields) {
            fputcsv($fp, $fields);
        }
        
        $data = file_get_contents('php://output'); // Read the file's contents
        $name = 'data.csv';
        
        // Build the headers to push out the file properly.
        header('Pragma: public');     // required
        header('Expires: 0');         // no cache
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private',false);
        header('Content-Disposition: attachment; filename="'.basename($name).'"');  // Add the file name
        header('Content-Transfer-Encoding: binary');
        header('Connection: close');
        exit();
        
        ob_end_flush();

    }
    
    public function download_image ($project_id)
    {
        ob_start();
        $this->load->helper('tasks');
        $this->load->library('zip');
        
        // Check permission for THIS project
        $this->load->model('task_model');
        $project_permission = $this->task_model->get_project_permission($this->session->userdata('user'), $project_id);
        if($project_permission != '1')
        {
            redirect('dashboard');
        }
        
        // Check permission
        if(!$this->usercontrol->has_permission('project', 'tasks'))
            redirect('dashboard');
        
        // Load tasks
        $this->load->model('task_model');
        $data = $this->task_model->zippath($project_id);
        
        $this->zip->download('photos.zip');
        ob_end_flush();
    }
    
    public function tasks($project_id)
    {
        
        $this->load->helper('stb_date');
        $this->load->helper('tasks');
        
        // Check permission
        if(!$this->usercontrol->has_permission('project', 'tasks'))
            redirect('dashboard');
            
        // Check permission for THIS project
        $this->load->model('task_model');
        $project_permission = $this->task_model->get_project_permission($this->session->userdata('user'), $project_id);
        if($project_permission != '1')
        {
            redirect('dashboard');
        }
        
        // Load tasks
        $tasks = $this->task_model->get($project_id);
        $task = array('user_id' => '0');
        
        foreach ($tasks as $task) {
            if ($task['status'] == 0) {
                $data['stories'][] = $task;
            } elseif ($task['status'] == 1) {
                $data['tasks'][] = $task;
            } elseif ($task['status'] == 2) {
                $data['tests'][] = $task;
            } elseif ($task['status'] == 3) {
                $data['done'][] = $task;
            }
        }
        
        // Load project info
        $this->load->model('project_model');
        $project = $this->project_model->get($project_id);
        $this->title = "{$project['name']}";
        $this->menu = 'dashboard|cold_storage|edit_project|new_task';
        
        $data['project_id']    = $project_id;
        
        $data['current_user'] = $this->session->userdata('user');
        
        $db_users = $this->project_model->get_related_users($project_id);
        $users = array();
        foreach ($db_users as $user) {
            $users[$user['id']] = $user;
        }
        $data['users'] = $users;
        
        // Load text helper to be used in the view
        $this->load->helper('text');
        
        //default value for passage
        if(isset($task['passage_new'])) 
        {
           $initialPassage = $task['passage_new']; 
        }
        elseif(isset($task['title'])) 
        {
            $initialPassage = $task['title'];
        }
        else
        {
            $initialPassage = '1';
        }
        
        //default value for confluence
        if(isset($task['confluence']))
        {
            $initialconfluence = $task['confluence'];
        }
        else
        {
            $initialconfluence = '0';
        }
        
        //default value for flask type
        if(isset($task['flask_type']))
        {
            $initialflasktype = $task['flask_type'];
        }
        else
        {
            $initialflasktype = '0';
        }
        
        //default value for flask number
        if(isset($task['flask_number_new']))
        {
            $initialflaskno = $task['flask_number_new'];
        }
        elseif(isset($task['flask_number']))
        {
            $initialflaskno = $task['flask_number'];
        }
        else
        {
            $initialflaskno = '1';
        }
        
        // ading new task code added here 
        $task_data['parent_id']      = 0;
        $task_data['title']          = $initialPassage; 
        $task_data['description']    = '';
        $task_data['priority']       = '0';
        $task_data['due_date']     = date("d-m-Y");
        $task_data['flask_type']     = $initialflasktype;
        $task_data['flask_number']   = $initialflaskno;
        $task_data['confluence']     = $initialconfluence;
        $task_data['cell_quality']   = '0';
        $task_data['action']         = '0';
        $task_data['infection']      = '0';
        $task_data['flask_number_new'] = $initialflaskno;
        $task_data['split_ratio']    = '';
        $task_data['passage_new']    = $initialPassage + 1;
        $task_data['flasks_removed'] = '0';
        $task_data['vial_number']    = '0';
        $task_data['f_location']     = '';
        $task_data['removal_reason'] = '';
        $task_data['flasks_removed_two'] = '';
        $task_data['action_two']     = '';
        $task_data['collapse_passage'] = '';
        $task_data['collapse_freeze'] = '';
        $task_data['collapse_remove'] = '';
        
        //need to write a model function to get the photo url here
        if(isset($tasks)){
        $photo = $this->project_model->get_photo($task['user_id']);
        $data['photo'] = $photo;
        }else{
            $data['photo'] = 'default.png';
        }
        
        $project = $project_id;
            settype($project, "string"); 
        $task_data['project_id'] = $project;
        //$task_data['users']      = $this->task_model->get_related_users($project);
        $task_data['user_id']    = $this->session->userdata('user');
        $task_data['tasks']      = $this->task_model->get_hierarchy($project);
        
        if($this->error)
            $task_data['error'] = $this->error;
            
        $data['task_data'] = $this->load->view('task_add',$task_data,true);
        
        $this->load->model('vial_model');
        $data['vial_id'] = $this->vial_model->get($project_id);
        
        $this->load->view('task_board', $data);
        
    }

    public function add()
    {
        // Check permission
        if(!$this->usercontrol->has_permission('project'))
            redirect('dashboard');
        
        $this->load->model('project_model');
        
        $this->title = "Add a New Culture";
        $this->menu = 'dashboard|cold_storage';
        
        $data['archive'] = false;
        
        $data['user']        = '';
        $data['name']        = '';
        $data['description'] = '';
        
        $id = $this->session->userdata('user');
        $lab = $this->session->userdata('lab');
        $users = $this->project_model->get_related_users($id, $lab);
        foreach ($users as $key => $value) {
            if($value['id'] == $this->session->userdata('user'))
                $users[$key]['project'] = 1;
            else
                $users[$key]['project'] = 0;
        }
        $data['users'] = $users;
        
        if($this->error)
            $data['error'] = $this->error;
        
        $this->load->view('project_add', $data);
    }

    public function edit($id)
    {
        // Check permission
        if(!$this->usercontrol->has_permission('project'))
            redirect('dashboard');
        
        // Check permission for THIS project
        $this->load->model('task_model');
        $project_permission = $this->task_model->get_project_permission($this->session->userdata('user'), $id);
        if($project_permission != '1')
        {
            redirect('dashboard');
        }
        
        $this->load->model('project_model');
        
        $data = $this->project_model->get($id);
        
        $this->title = "Edit Culture Details";
        $this->menu = 'dashboard|tasks';
        $data['archive'] = true;
        
        $data['project_id']  = $id;
        $lab = $this->session->userdata('lab');
        $data['users'] = $this->project_model->get_related_users($id, $lab);
        
        if($this->error)
            $data['error'] = $this->error;
        
        $this->load->view('project_add', $data);
    }
    
    public function save()
    {
        if($this->input->post('cancel') !== FALSE)
            redirect('dashboard');
            
        // Check permission
        if(!$this->usercontrol->has_permission('project'))
            redirect('dashboard');

        // Get project ID - false if new entry
        $project_id = $this->input->post('id');
        
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim');
        $this->form_validation->set_rules('users[]', 'Associated Users', '');
        
        if($this->form_validation->run() === false)  {
            $this->error = true;
            
            if ($project_id)
                $this->edit ($project_id);
            else
                $this->add ();
            
            return;
        }
        
        $this->load->model('project_model');

        $sql_data = array(
            'user'        => $this->session->userdata('user'),
            'name'        => $this->input->post('name'),
            'description' => $this->input->post('description'),
            'archive'     => $this->input->post('archive')
        );

        if ($project_id)
            $this->project_model->update($project_id,$sql_data);
        else
            $project_id = $this->project_model->create($sql_data);

        // Related users
        $this->project_model->delete_related($project_id);

        $users = $this->input->post('users');
        foreach ($users as $user) {
            $sql_data = array(
                'user' => $user,
                'project' => $project_id
            );
            $this->project_model->create_related($sql_data);
        }
        if ($project_id)
            redirect('project/tasks/'.$project_id);
        else
            redirect('project');
    }
    
    public function remove_project($project_id)
    {
        // Check permission for THIS project
        $this->load->model('task_model');
        $project_permission = $this->task_model->get_project_permission($this->session->userdata('user'), $project_id);
        if($project_permission != '1')
        {
            redirect('dashboard');
        }
        
        $this->load->model('project_model');
        $this->project_model->delete($project_id);
        redirect('dashboard');
    }
}