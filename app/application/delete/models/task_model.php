<?php

class Task_model extends CI_Model {

    public function create($data)
    {
        if(!$data['parent_id'])
            $data['parent_id'] = null;
        
        $this->db->select_max('code');
        $this->db->where('project_id', $data['project_id']);
        $get = $this->db->get('task');
        
        if($get->num_rows > 0) {
            $row = $get->row_array();
            $data['code'] = $row['code'] + 1;
        } else
            $data['code'] = 1;
        
        $insert = $this->db->insert('task', $data);
        if($insert)
            return $this->db->insert_id();
        
        return false;
    }


    public function update($project, $id, $data, $move = false)
    {
        $this->db->trans_start();
        
        if(isset($data['parent_id']) && !$data['parent_id'])
            $data['parent_id'] = null;
        
        if($move) {
            $this->timer($id, 'move', $data['status']);
        }
        
        $this->db->where('project_id', $project);
        $this->db->where('task_id', $id);
        $this->db->update('task', $data);
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function get($project, $id = false, $status = false)
    {
        $this->db->select('t.*, sum(th.duration) as total_duration, th_last.date_created as task_history_date_created')->
                from('task t')->
                join('task_history th', 't.task_id = th.task_id', 'left')->
                join('task_history th_last', 't.task_id = th_last.task_id AND th_last.date_finished IS NULL', 'left')->
                where('t.project_id', $project);
        
        if ($id) $this->db->where('t.task_id', $id);
        if ($status) $this->db->where('t.status', $status);
        
        $this->db->group_by('t.task_id')->
                order_by('th.date_finished', 'desc')->
                order_by('t.status', 'asc');
                //order_by('t.priority', 'asc');
        
        $get = $this->db->get();

        if ($id) return $get->row_array();
        if($get->num_rows > 0) return $get->result_array();
        return array();
    }
    
    public function get_download($project, $id = false, $status = false)
    {
        $this->db->select('project.name as Culture_Name, task.due_date as Date, task.title as Passage, user.name as User_Name, task.flask_number, task.flask_type, task.confluence, task.priority as Media_colour, task.description, task.cell_quality, task.infection, task.action as First_Action_Taken, task.split_ratio, task.flasks_removed, task.removal_reason, task.vial_number as Number_of_vials_frozen, task.action_two as Second_Action');
        $this->db->from('task');
        $this->db->join('project', 'task.project_id = project.id');
        $this->db->join('user', 'task.user_id = user.id');
        $this->db->where('task.project_id', $project);
        
        $get = $this->db->get();
        if($get->num_rows > 0) return $get->result_array();
        return array();
    }
    
    public function zippath($project)
    {
        $this->db->select('photo')->
                from('task')->
                where('project_id', $project);
        
        $query = $this->db->get();
        $base_path = 'upload/cell/';
        
        foreach ($query->result_array() as $row)
        {
            if(isset($row['photo'])){
            $this->zip->read_file($base_path.$row['photo']);
            }
        }
    }
    
    public function get_hierarchy($project, $parent = null, $single_array = true, $output = array(), $i = 0, $current_id = false)
    {
        // Select tasks according to project and parent - count children so it knows if there are any
        $this->db->select('task.*, count(child.task_id) as children')->
                from('task')->
                join('task child', 'task.task_id = child.parent_id', 'left')->
                where('task.project_id', $project)->
                where('task.parent_id', $parent)->
                order_by('task.title', 'asc')->
                group_by('task.task_id');
        
        if($current_id)
            $this->db->where('task.task_id !=', $current_id);
        
        $tasks = $this->db->get()->result_array();

        // If it IS a single array, add children in the same array
        if($single_array) {
            foreach ($tasks as $value) {
                $output[] = array(
                    'id' => $value['task_id'], 
                    'title' => str_repeat('&nbsp;&nbsp;', $i).(($i)?'- ':'').$value['title']
                    );

                if($value['children'] > 0)
                    $output = $this->get_hierarchy($project, $value['task_id'], $single_array, $output, $i+1, $current_id);
            }
        } else {
            // If it IS NOT a single array, add children as a sub array
            foreach ($tasks as $value) {
                $output[] = array(
                    'id' => $value['task_id'], 
                    'title' => $value['title'],
                    'children' => ($value['children'] > 0)?$this->get_hierarchy($project, $value['task_id'], $single_array, $output, $i+1, $current_id):array()
                    );
            }
        }
        
        return $output;
    }

    public function get_parents($project, $id)
    {
        $this->db->select('task.*')->
                from('task')->
                where('task.project_id', $project)->
                where('task.task_id', $id);
        
        $tasks = $this->db->get()->result_array();

        foreach ($tasks as $value) {
            if($value['parent_id'])
                $output = $this->get_parents ($project, $value['parent_id']);
            
            $output[] = array('id' => $value['task_id'], 'title' => $value['title']);
        }
        
        return $output;
    }
    
    public function get_project_permission($user, $project)
    {
        $this->db->select('project');
        $this->db->from('user_project');
        $this->db->where('user', $user);
        $this->db->where('project', $project);
            
            $get = $this->db->get();

        if($get->num_rows > 0) return true;
    }


    public function get_user_tasks($user)
    {
        // Get Projects and 5 tasks in each project
        $query = 'SELECT t.*, p.id as project_id, p.name as project_name, p.archive as project_archive
        FROM project p
        JOIN user_project up ON p.id = up.project AND up.user = '.$user.'
        LEFT JOIN (
            SELECT tmp.* 
            FROM (
                SELECT *, IF( @prev <> project_id, @rownum := 1, @rownum := @rownum+1 ) AS rank, @prev := project_id
                FROM task t
                JOIN (
                    SELECT @rownum := NULL, @prev := 0
                ) AS r 
                WHERE user_id = '.$user.' 
                ORDER BY t.project_id
            ) AS tmp
            WHERE tmp.rank <= 1
        ) AS t ON p.id = t.project_id
        ORDER BY p.id asc';
        
        $get = $this->db->query($query);

        if($get->num_rows > 0)
            return $get->result_array();
        
        return array();
    }
    
    public function get_user_tasks_two($user)
    {
        $this->db->select(' project.id as project_id, project.name as project_name, project.archive as project_archive');
        $this->db->from('project');
        $this->db->join('user_project', 'project.id = user_project.project AND user_project.user = '.$user.'');
        $this->db->order_by('id', 'asc');
        $get = $this->db->get();

        //if($id) return $get->row_array();
        if($get->num_rows > 0) return $get->result_array();
        return array();
    }
    
    public function get_last_task_date($id)
    {
        $this->db->select_max('date_created');
        //$this->db->select('flask_type');
        $this->db->from('task');
        $this->db->where('project_id', $id);
        
        $get = $this->db->get();
        $ret = $get->row();
        return $ret->date_created;
    }
    
    public function get_last_task_details($date, $id)
    {
        $this->db->select('flask_type');
        $this->db->from('task');
        $this->db->where('date_created', $date);
        $this->db->where('project_id', $id);
        
        $get = $this->db->get();
        if($get->num_rows > 0){
        $ret = $get->row();
        return $ret->flask_type;}
        else{
            return '11';
        }
    }
    
    public function get_project_user_tasks($project, $user, $limit = FALSE)
    {
        $this->db->select('t.*')->
                distinct()->
                from('task t')->
                where('t.user_id', $user)->
                where('t.project_id', $project)->
                where('t.status !=', 3)->
                order_by('t.status', 'desc');
        if($limit)
            $this->db->limit($limit);
        $get = $this->db->get();

        if($get->num_rows > 0) return $get->result_array();
        return array();
    }
    
    public function get_related_users($project)
    {

        $project = '1';
        echo $project;
        $this->db->select('u.*, up.project');
        
        $this->db->from('user u');
        $this->db->join('user_project up', 'up.user = u.id and up.project = '.$project, 'left');
        $this->db->order_by('u.email', 'asc');
        $get = $this->db->get();

        if($get->num_rows > 0) return $get->result_array();
        return array();
    }

    public function delete($project, $id)
    {
        $this->db->trans_start();
                
        // Remove task
        $this->db->where('project_id', $project);
        $this->db->where('task_id', $id);
        $this->db->delete('task');
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    
    public function create_comment($data)
    {
        return $this->db->insert('task_comments', $data);
    }

    public function get_comments($task)
    {
        $this->db->select('task_comments.*, user.name, user.email')->
                from('task_comments')->
                join('user', 'task_comments.user_id = user.id')->
                where('task_id', $task);
        $get = $this->db->get();
        
        if($get->num_rows > 0)
            return $get->result_array();
        
        return array();
    }
    
    public function get_history($task, $detail = false)
    {
        if($detail)
            // Get all entries
            $this->db->select('u.email, th.status, th.date_created, th.date_finished, th.duration')->
                    from('task_history th')->
                    join('user u', 'th.user_id = u.id', 'left')->
                    where('task_id', $task)->
                    order_by('status, date_created');
        else
            // Get sum fro each phase
            $this->db->select('status, sum(duration) as duration')->
                    from('task_history')->
                    where('task_id', $task)->
                    group_by('status')->
                    order_by('status');
        
        $get = $this->db->get();
        
        if($get->num_rows > 0)
            return $get->result_array();
        
        return array();
    }
    
    public function get_last_history($task)
    {
        $this->db->select('status, date_created')->
                from('task_history')->
                where('task_id', $task)->
                where('date_finished', NULL);
        $get = $this->db->get();
        
        if($get->num_rows > 0)
            return $get->row_array();
        
        return array();
    }
    
    public function get_status_array()
    {
        return array(
            0 => 'To Do',
            1 => 'In Progress',
            2 => 'Testing',
            3 => 'Done'
        );
    }

    public function timer($task_id, $type = 'move', $status = false)
    {
        $this->db->trans_start();
        
        if($type != 'play') {
            
            // Update user ID, date finished and duration
            $history = $this->db->select('task_history_id, status, date_created')->
                    where('task_id', $task_id)->
                    where('date_finished', NULL)->
                    get('task_history')->row_array();

            if($history){
                $now = strtotime(date('Y-m-d H:i:s'));
                $before = strtotime($history['date_created']);
                
                if($status === false)
                    $status = $history['status'];

                $this->db->where('task_history_id', $history['task_history_id'])->
                        set('user_id', $this->session->userdata('user'))->
                        set('date_finished', date('Y-m-d H:i:s'))->
                        set('duration', $now - $before)->
                        update('task_history');
            }
            
        }
        
        if($status === false) {
            // Get status of last entry in case it was not defined
            $history = $this->db->select('status')->
                    from('task_history')->
                    where('task_id', $task_id)->
                    order_by('task_history_id', 'desc')->
                    get()->row_array();
            $status = $history['status'];
        }

        if($type != 'stop') {
            
            // Create new entry in history
            $history_data = array(
                'task_id' => $task_id,
                'user_id' => $this->session->userdata('user'),
                'status' => $status,
                'date_created' => date('Y-m-d H:i:s'),
                'date_finished' => NULL
            );
            $this->db->insert('task_history', $history_data);
            
        }
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    
    
    /* upload functions - delete these if not ussed */
    
    public function make_thumb($data)
    {
        $config['image_library'] = 'gd2';
        $config['source_image']	= './upload/cell/'.$filename;
        $config['new_image'] = './upload/cell/thumbs/';
        //$config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = FALSE;
        $config['width']	 = 100;
        $config['height']	= 100;
        $this->load->library('image_lib', $config); 
        $this->image_lib->resize();
        
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

}
