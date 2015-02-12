<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// ------------------------------------------------------------------------

/**
 * Outputs the task hierarchy HTML
 *
 * @access	public
 * @return	bool
 */
if ( ! function_exists('task_hierarchy_html'))
{
    function task_hierarchy_html($project, $tasks, $i = 0)
    {
        if($i == 0)
            echo '<ul class="task-hierarchy">';
        else
            echo '<ul>';
        
        foreach($tasks as $value){
            echo '<li class="level-'.$i.'">';
            
            echo anchor(base_url()."task/view/{$project}/{$value['id']}", $value['title']);
            if($value['children'])
                task_hierarchy_html ($project, $value['children'], $i + 1);
            
            echo '</li>';
        }
        
        echo '</ul>';
    }
}

/**
 * Outputs the task parents HTML
 *
 * @access	public
 * @return	bool
 */
if ( ! function_exists('task_parents_html'))
{
    function task_parents_html($project, $parent)
    {
        if($parent) {
            
            $CI =& get_instance();
            $CI->load->model('task_model');
            $tasks = $CI->task_model->get_parents($project, $parent);
            
            foreach($tasks as $value){
                echo anchor(base_url()."task/view/{$project}/{$value['id']}", $value['title']);
                echo ' > ';
            }
        }
    }
}

/**
 * Task priority text
 *
 * @access	public
 * @return	bool
 */
if ( ! function_exists('task_priority_text'))
{
    function task_priority_text($priority = FALSE)
    {
        $options = array('0' => 'Red', '1' => 'Light Red', '2' => 'Dark Orange', '3' => 'Orange', '4' => 'Yellow');
        
        if($priority !== FALSE)
            return $options[$priority];
        else 
            return $options;
    }
}

/**
 * Task priority label class
 *
 * @access	public
 * @return	bool
 */
if ( ! function_exists('task_priority_class'))
{
    function task_priority_class($priority)
    {
        $options = array('0' => 'panel-phenol-edge', '1' => 'panel-alizarin-edge', '2' => 'panel-carrot-edge', '3' => 'panel-orange-edge', '4' => 'panel-sun-flower-edge');
        return $options[$priority];
    }
}

/**
 * Flask Type text
 *
 * @access	public
 * @return	bool
 */
if ( ! function_exists('flask_type_text'))
{
    function flask_type_text($flask_type = FALSE)
    {
        $flask_options = array('0' => 'T25</span> Flasks', '1' => 'T75</span> Flasks', '2' => 'T125</span> Flasks', '14' => 'T175</span> Flasks', '3' => 'T225</span> Flasks', '4' => '96</span> Well Plate', '5' => '48</span> Well Plate', '6' => '24</span> Well Plate', '12' => '12</span> Well Plate', '7' => '6</span> Well Plate', '8' => '35</span>mm Dish', '9' => '60</span>mm Dish', '10' => '100</span>mm Dish', '13' => '150</span>mm Dish', '11' => 'Other');
        
        if($flask_type !== FALSE)
            return $flask_options[$flask_type];
        else 
            return $flask_options;
    }
}

/**
 * Flask Type text for csv export
 *
 * @access    public
 * @return	bool
 */
if ( ! function_exists('flask_type_csv'))
{
    function flask_type_csv($flask_type = FALSE)
    {
        $flask_options = array('0' => 'T25 Flask', '1' => 'T75 Flask', '2' => 'T125 Flask', '14' => 'T175 Flask', '3' => 'T225 Flask', '4' => '96 Well Plate', '5' => '48 Well Plate', '6' => '24 Well Plate', '12' => '12 Well Plate', '7' => '6 Well Plate', '8' => '35mm Dish', '9' => '60mm Dish', '10' => '100mm Dish', '13' => '150mm Dish', '11' => 'Other');
        
        if($flask_type !== FALSE)
            return $flask_options[$flask_type];
        else 
            return $flask_options;
    }
}

/**
 * Well Type text
 *
 * @access    public
 * @return	bool
 */
if ( ! function_exists('well_type_text'))
{
    function well_type_text($well_type = FALSE)
    {
        $well_options = array('0' => 'T25</span> Flasks', '1' => 'T75</span> Flasks', '2' => 'T125</span> Flasks', '14' => 'T175</span> Flasks', '3' => 'T225</span> Flasks', '4' => '96</span> Wells', '5' => '48</span> Wells', '6' => '24</span> Wells', '12' => '12</span> wells', '7' => '6</span> Wells', '8' => '35</span>mm Dishes', '9' => '60</span>mm Dishes', '10' => '100</span>mm Dishes', '13' => '150</span>mm Dishes', '11' => 'Other');
        
        if($well_type !== FALSE)
            return $well_options[$well_type];
        else 
            return $well_options;
    }
}

/**
 * Cell Quality text
 *
 * @access	public
 * @return	bool
 */
if ( ! function_exists('cell_quality_text'))
{
    function cell_quality_text($cell_quality = FALSE)
    {
        $cell_quality_options = array('0' => 'Very Good', '1' => 'Good', '2' => 'Acceptable', '3' => 'Poor', '4' => 'Very Poor', '5' => 'Unacceptable');
        
        if($cell_quality !== FALSE)
            return $cell_quality_options[$cell_quality];
        else 
            return $cell_quality_options;
    }
}

/**
 * Cell Quality label class
 *
 * @access	public
 * @return	bool
 */
if ( ! function_exists('cell_quality_class'))
{
    function cell_quality_class($cell_quality)
    {
        $cell_quality_options = array('0' => '', '1' => '', '2' => '', '3' => 'label label-default', '4' => 'label label-warning', '5' => 'label label-danger');
        return $cell_quality_options[$cell_quality];
    }
}

/**
 * Action text
 *
 * @access	public
 * @return	bool
 */
if ( ! function_exists('action_text'))
{
    function action_text($action = FALSE)
    {
        $action_options = array('0' => 'No Action Taken', '1' => 'Media Change', '2' => 'Passage', '3' => 'Freeze', '4' => 'Flask Removal');
        
        if($action !== FALSE)
            return $action_options[$action];
        else 
            return $action_options;
    }
}

/**
 * Action label class
 *
 * @access	public
 * @return	bool
 */
if ( ! function_exists('action_class'))
{
    function action_class($action)
    {
        $action_options = array('0' => 'concrete', '1' => 'concrete', '2' => 'phenol-text', '3' => 'peter-river', '4' => 'turq');
        return $action_options[$action];
    }
}

/**
 * Infection text
 *
 * @access	public
 * @return	bool
 */
if ( ! function_exists('infection_text'))
{
    function infection_text($infection = FALSE)
    {
        $infection_options = array('0' => 'No', '1' => 'Yes');
        
        if($infection !== FALSE)
            return $infection_options[$infection];
        else 
            return $infection_options;
    }
}

/**
 * Infection label class
 *
 * @access	public
 * @return	bool
 */
if ( ! function_exists('infection_class'))
{
    function infection_class($infection)
    {
        $infection_options = array('0' => '', '1' => 'panel-dark');
        return $infection_options[$infection];
    }
}

/**
 * Infection text
 *
 * @access	public
 * @return	bool
 */
if ( ! function_exists('removal_reason_text'))
{
    function removal_reason_text($removal_reason = FALSE)
    {
        $removal_reason_options = array('0' => 'unspecified reason', '1' => 'experiment', '2' => 'freezing', '3' => 'infection');
        
        if($removal_reason !== FALSE)
            return $removal_reason_options[$removal_reason];
        else 
            return $removal_reason_options;
    }
}


// ------------------------------------------------------------------------
