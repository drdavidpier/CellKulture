<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// ------------------------------------------------------------------------


/**
 * Freezer label class
 *
 * @access	public
 * @return	bool
 */
if ( ! function_exists('freezer_class'))
{
    function freezer_class($freezer)
    {
        $freezer_options = array('0' => 'panel-peter-river', '1' => 'panel-belize-hole');
        return $freezer_options[$freezer];
    }
}

/**
 * Freezer text
 *
 * @access	public
 * @return	bool
 */
if ( ! function_exists('freezer_text'))
{
    function freezer_text($freezer = FALSE)
    {
        $freezer_options = array('0' => '-80', '1' => 'LN2');
        
        if($freezer !== FALSE)
            return $freezer_options[$freezer];
        else 
            return $freezer_options;
    }
}

// ------------------------------------------------------------------------
