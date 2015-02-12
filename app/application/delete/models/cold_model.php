<?php

class Cold_model extends CI_Model {

    public function create($data)
    {
        $insert = $this->db->insert('freezer', $data);
        if($insert)
            return $this->db->insert_id();
        else
            return false;
    }

    public function create_related($data)
    {
        $insert = $this->db->insert('user_freezer', $data);
        return $insert;
    }

    public function update($id, $data)
    {
        $this->db->where('freezer_id', $id);
        $update = $this->db->update('freezer', $data);
        return $update;
    }

    public function get($id = false)
    {
        if ($id) $this->db->where('freezer_id', $id);
        $this->db->order_by('name', 'asc');
        $get = $this->db->get('freezer');

        if($id) return $get->row_array();
        if($get->num_rows > 1) return $get->result_array();
        return array();
    }
    
    public function get_freezer_permission($user, $freezer)
    {
        $this->db->select('freezer');
        $this->db->from('user_freezer');
        $this->db->where('user', $user);
        $this->db->where('freezer', $freezer);
            
            $get = $this->db->get();

        if($get->num_rows > 0) return true;
    }
    
    public function get_fbox_foredit($id = false)
    {
        if ($id) $this->db->where('box_id', $id);
        $this->db->order_by('name', 'asc');
        $get = $this->db->get('box');

        if($id) return $get->row_array();
        if($get->num_rows > 1) return $get->result_array();
        return array();
    }

    public function get_user_owned($user)
    {
        $this->db->where('user', $user);
        $this->db->order_by('name', 'asc');
        $get = $this->db->get('project');

        if($get->num_rows > 0) return $get->result_array();
        return array();
    }
    
    public function get_user_freezers($user)
    {
        $this->db->select();
        $this->db->from('freezer');
        $this->db->join('user_freezer', 'freezer.freezer_id = user_freezer.freezer');
        $this->db->where('user', $user);
        $get = $this->db->get();

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
    
    public function get_fbox($boxid)
    {
        $this->db->select('vial_locations');
        $this->db->from('box');
        $this->db->where('box_id', $boxid);
        $get = $this->db->get();

        $ret = $get->row();
        return $ret->vial_locations;
    }
    
    public function get_fbox_name($boxid) //this needs to be combined with method above
    {
        $this->db->select('name');
        $this->db->from('box');
        $this->db->where('box_id', $boxid);
        $get = $this->db->get();

        $ret = $get->row();
        return $ret->name;
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
            $this->db->select('u.*, up.freezer');
        else
            $this->db->select('u.*');
        
        $this->db->from('user u');
        if($id)
            $this->db->join('user_freezer up', 'up.user = u.id and up.freezer = '.$id, 'left');
        $this->db->where('lab', $lab); 
        $this->db->order_by('u.name', 'asc');
        $get = $this->db->get();

        if($get->num_rows > 0) return $get->result_array();
        return array();
    }
    
    public function get_related_fboxusers($id = false, $lab = false)
    {
        if($id)
            $this->db->select('u.*, up.fbox');
        else
            $this->db->select('u.*');
        
        $this->db->from('user u');
        if($id)
            $this->db->join('user_fbox up', 'up.user = u.id and up.fbox = '.$id, 'left');
        $this->db->where('lab', $lab); 
        $this->db->order_by('u.name', 'asc');
        $get = $this->db->get();

        if($get->num_rows > 0) return $get->result_array();
        return array();
    }

    public function delete($id)
    {
        $this->db->where('freezer_id', $id);
        $this->db->delete('freezer');
    }

    public function delete_related($id)
    {
        $this->db->where('freezer', $id);
        $this->db->delete('user_freezer');
    }
}
