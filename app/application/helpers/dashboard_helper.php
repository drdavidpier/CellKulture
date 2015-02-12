<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// ------------------------------------------------------------------------

/**
 * dashboard first div class
 *
 * @access    public
 * @return	bool
 */
if ( ! function_exists('dashboard_one_class'))
{
    function dashboard_one_class($flask_type = FALSE)
    {
        $dashboard_one_options = array(
            '0' => '<div class="col-xs-6 col-sm-3 col-md-2"><div class="flask-lid"><div class="panel panel-phenol panel-flask t25">', 
            '1' => '<div class="col-xs-8 col-sm-4 col-md-3"><div class="flask-lid-t75"><div class="panel panel-phenol panel-flask t75">', 
            '2' => '<div class="col-xs-9 col-sm-5 col-md-4"><div class="flask-lid-t75"><div class="panel panel-phenol panel-flask t175">', 
            '3' => '<div class="col-xs-11 col-sm-6 col-md-5"><div class="flask-lid-t75"><div class="panel panel-phenol panel-flask t225">', 
            '4' => '<div class="col-xs-4 col-sm-3 col-md-2"><div class="panel panel-phenol panel-flask ninetysixwell"><span class="label label-danger">', 
            '5' => '<div class="col-xs-4 col-sm-3 col-md-2"><div class="panel panel-phenol panel-flask fourtyeightwell"><span class="label label-danger">', 
            '6' => '<div class="col-xs-4 col-sm-3 col-md-2"><div class="panel panel-phenol panel-flask twentyfourwell"><span class="label label-danger">', 
            '7' => '<div class="col-xs-4 col-sm-3 col-md-2"><div class="panel panel-phenol panel-flask sixwell"><span class="label label-danger">',  
            '8' => '<div class="col-xs-4 col-sm-2 col-md-1"><div class="dish"><div>', 
            '9' => '<div class="col-xs-6 col-sm-3 col-md-2"><div class="dish"><div>', 
            '10' => '<div class="col-xs-9 col-sm-4 col-md-3"><div class="dish"><div>', 
            '11' => '<div class="col-xs-6 col-sm-3 col-md-2"><div><div class="panel panel-phenol t25">',
            '12' => '<div class="col-xs-4 col-sm-3 col-md-2"><div class="panel panel-phenol panel-flask twelvewell"><span class="label label-danger">',
            '13' => '<div class="col-xs-11 col-sm-5 col-md-4"><div class="dish"><div>',
            '14' => '<div class="col-xs-9 col-sm-5 col-md-4"><div class="flask-lid-t75"><div class="panel panel-phenol panel-flask t175">');
        return $dashboard_one_options[$flask_type];
    }
}

/**
 * dashboard first div class
 *
 * @access	public
 * @return	bool
 */
if ( ! function_exists('dashboard_two_class'))
{
    function dashboard_two_class($flask_type = FALSE)
    {
        $dashboard_two_options = array(
            '0' => '</div></div>', 
            '1' => '</div></div>', 
            '2' => '</div></div>',
            '14' => '</div></div>',
            '3' => '</div></div>', 
            '4' => '</span></div>', 
            '5' => '</span></div>', 
            '6' => '</span></div>', 
            '12' => '</span></div>', 
            '7' => '</span></div>', 
            '8' => '</div></div>', 
            '9' => '</div></div>', 
            '10' => '</div></div>', 
            '13' => '</div></div>', 
            '11' => '</div></div>',);
        return $dashboard_two_options[$flask_type];
    }
}

/**
 * dashboard first div class
 *
 * @access	public
 * @return	bool
 */
if ( ! function_exists('dashboard_three_class'))
{
    function dashboard_three_class($flask_type = FALSE)
    {
        $dashboard_three_options = array('0' => 'T25</span> Flasks', '1' => 'T75</span> Flasks', '2' => 'T125</span> Flasks', '3' => 'T225</span> Flasks', '4' => '96</span> Well Plate', '5' => '48</span> Well Plate', '6' => '24</span> Well Plate', '12' => '12</span> well plate', '7' => '6</span> Well Plate', '8' => '35</span>mm Dish', '9' => '60</span>mm DIsh', '10' => '100</span>mm Dish', '13' => '150</span>mm Dish', '11' => 'Other');
        return $dashboard_three_options[$flask_type];
    }
}


// ------------------------------------------------------------------------
