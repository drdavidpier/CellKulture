<?php

class Vial_model extends CI_Model {
    
    public function create($data)
    {
        $insert = $this->db->insert('vial', $data);
        if($insert)
            return $this->db->insert_id();
        else
            return false;
    }

    public function create_related($data)
    {
        $insert = $this->db->insert('user_vial', $data);
        return $insert;
    }

    public function update($id, $data)
    {
        $this->db->where('vial_id', $id);
        $update = $this->db->update('vial', $data);
        return $update;
    }

    public function get($culture_id)
    {
        $this->db->select('vial_id, taskid');
        //$this->db->from('vial');
        //$this->db->where('taskid', $sample_id);
        $this->db->where('projectid', $culture_id);
        $get = $this->db->get('vial');
        
        if($get->num_rows > 0) return $get->result_array();
        return array();
    }
    
    public function get_freezer_permission($user, $freezer)
    {
        $this->db->select('vial');
        $this->db->from('user_vial');
        $this->db->where('user', $user);
        $this->db->where('vial', $freezer);
            
            $get = $this->db->get();

        if($get->num_rows > 0) return true;
    }
    
    public function get_box_location($vial_id)
    {
        $this->db->select('arrayid');
        $this->db->from('vial');
        $this->db->where('vial_id', $vial_id);
        $get = $this->db->get();

        $ret = $get->row();
        return $ret->arrayid;
    }
    
    public function get_name($id = false)
    {
        $this->db->select('name');
        $this->db->from('project');
        $this->db->where('id', $id);
        
        $query = $this->db->get();
        $ret = $query->row();
        return $ret->name;
    }
    public function get_passage($culture = false, $sample = false)
    {
        $this->db->select('title');
        $this->db->from('task');
        $this->db->where('project_id', $culture);
        $this->db->where('task_id', $sample);
        
        $query = $this->db->get();
        $ret = $query->row();
        return $ret->title;
    }
    public function get_date($culture = false, $sample = false)
    {
        $this->db->select('due_date');
        $this->db->from('task');
        $this->db->where('project_id', $culture);
        $this->db->where('task_id', $sample);
        
        $query = $this->db->get();
        $ret = $query->row();
        return $ret->due_date;
    }

    public function get_user_owned($user)
    {
        $this->db->where('user', $user);
        $this->db->order_by('name', 'asc');
        $get = $this->db->get('project');

        if($get->num_rows > 0) return $get->result_array();
        return array();
    }
    
    public function get_user_fbox($user)
    {
        $this->db->select();
        $this->db->from('box');
        $this->db->join('user_fbox', 'box.box_id = user_fbox.fbox');
        $this->db->where('user', $user);
        $get = $this->db->get();

        if($get->num_rows > 0) return $get->result_array();
        return array();
    }
    
    public function get_user_related($user)
    {
        $this->db->select('p.*, u.project');
        $this->db->from('project p');
        $this->db->join('user_project u', 'p.id = u.project');
        $this->db->where('u.user', $user);
        $this->db->order_by('p.name', 'asc');
        $get = $this->db->get();

        if($get->num_rows > 0) return $get->result_array();
        return array();
    }

    public function get_related_users($id = false, $lab = false)
    {
        if($id)
            $this->db->select('u.*, up.project');
        else
            $this->db->select('u.*');
        
        $this->db->from('user u');
        if($id)
            $this->db->join('user_project up', 'up.user = u.id and up.project = '.$id, 'left');
        $this->db->where('lab', $lab); 
        $this->db->order_by('u.name', 'asc');
        $get = $this->db->get();

        if($get->num_rows > 0) return $get->result_array();
        return array();
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('project');
    }

    public function delete_related($id)
    {
        $this->db->where('vial', $id);
        $this->db->delete('user_vial');
    }
}
