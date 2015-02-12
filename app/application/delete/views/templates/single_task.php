<div data-scrollreveal="enter bottom over 1s and move 300px after 0.3s">
<div id="task_<?php echo $task['task_id']; ?>" <?php if($task['status'] == 3) { ?> title="<?php echo strip_tags($task['description']); ?>"<?php } ?>
        class="panel <?php echo ($task['user_id'] == $current_user)?'my-task':'project-task'; ?> <?php echo task_priority_class($task['priority']); ?> <?php echo infection_class($task['infection']); ?>">
    
    <?php if($task['infection'] == 1) 
        {
            echo "<h2>Infection</h2>";
        }
    ?>

    <div class="row">
    <div class="col-xs-8">
    <h3><?php $phpdate = strtotime( $task['due_date'] );
    $mysqldate = date( 'd-m-y', $phpdate );
    echo $mysqldate; ?>
    <span class="<?php echo action_class($task['action']); ?>">
    <?php echo action_text($task['action']); ?>
    </span>
    <span class="<?php echo action_class($task['action_two']); ?>">
        <?php if($task['action_two'] != 0)
        {
            echo "<span class=\"dark\">&</span> ".action_text($task['action_two']);
        }; ?>
    </span>
    </h3>
    <p><span class="bignumb">P<?php echo $task['title']; ?></span> |
        Cell quality is <span class="<?php echo cell_quality_class($task['cell_quality']); ?>"><?php echo cell_quality_text($task['cell_quality']); ?>
    </span></p>
    <p><span class="bignumb"><?php echo $task['flask_number']; ?></span> x <span class="bignumb"><?php echo flask_type_text($task['flask_type']); ?> | <span class="bignumb"><?php echo $task['confluence']; ?>%</span> Confluent</p>
    
    </div>
    
    <?php //-----------this loads and image but currently only the main user - needs work ------------------------------
    //$this->load->view('profile_photo'); ?>
    
    <div class="col-xs-4">
    
    <!-- Button trigger modal -->
    <a href="<?php echo base_url()."upload/cell/".$task['photo']; ?>" target="_blank">
        <img src="
        <?php 
        if($task['photo'] !== NULL)
        {
            echo base_url()."upload/cell/thumbs/".$task['photo'];
        } 
        else
        {
            echo base_url()."assets/img/default.png";
        }
        ?>" style="width:100%" title="Click to open full image in new tab">
    </a>
    </div>
    </div>

<?php if($users[$task['user_id']]['photo'] == null){
    $photo_string = 'default.png';
}else{
    $photo_string = $users[$task['user_id']]['photo'];
} 
?>

    <p> <?php echo strip_tags($task['description']); ?> </p>
        <p><img src="<?php echo base_url('upload/profile').'/thumb'.$photo_string; ?>" title="<?php echo $users[$task['user_id']]['name']; ?>" class="img-circle">
            <span class="pull-right noprint"><a href="<?php echo base_url('task/view/'.$project.'/'.$task['task_id']); ?>">
            <span class="glyphicon glyphicon-pencil" style="color: #BDC3C7"></span>
        </a>
    </span>
    </p>
</div>

<?php 
if($task['action'] == 4 || $task['action_two'] == 4) 
{
$imagefile = base_url('assets/img/arrow-span2.png');
echo <<<"PASSAGE"
<div class="row">
    <div class="col-xs-4 col-sm-4">
        <img src="$imagefile" width="100%">
    </div>
    <div class="col-xs-8 col-sm-8">
    	<div class="panel panel-">
PASSAGE;
echo "<p><span class=\"bignumb\">".$task['flasks_removed_two'];
echo "</span> Flasks removed for ".removal_reason_text($task['removal_reason']);
echo <<<"CLOSINGTAGS"
		</div>
	</div>
</div>
CLOSINGTAGS;
}
?>

<?php if($task['action'] == 3 || $task['action_two'] == 3) : ?>
<?php $imagefile = base_url('assets/img/arrow-span2.png'); ?>
<div class="row">
    <div class="col-xs-4 col-sm-4">
        <img src="<?php echo $imagefile; ?>" width="100%">
    </div>
    <div class="col-xs-8 col-sm-8">
        <div class="panel panel-peter-river">
            <p><span class="bignumb"><?php echo $task['flasks_removed']; ?></span> x <span class="bignumb"><?php echo well_type_text($task['flask_type']); ?> frozen in <span class="bignumb"><?php echo $task['vial_number']; ?></span> vials</p>
            <?php if(!empty($task['f_location'])) : ?>
                <?php $i = 1;
                foreach($vial as $vial_id) : ?>
                    <?php if($vial_id['taskid'] == $task['task_id']) : ?>
                        <p>Vial <?php echo $i; ?>
                        <a href="<?php echo base_url('freezer_vial/view/'.$project.'/'.$task['task_id'].'/'.$vial_id['vial_id']); ?>" class="btn btn-xs btn-peter-river">Vial details</a>
                        <a href="<?php echo base_url('freezer_box/box/'.$task['f_location']); ?>" class="btn btn-xs btn-peter-river">Storage Location</a>
                        </p>
                        <hr class="belize-hole-hr">
                        <?php $i++; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
            <a href="<?php echo base_url('freezer_vial/add/'.$project.'/'.$task['task_id']); ?>" class="btn btn-xs btn-peter-river">Add vial location</a>
		</div>
	</div>
</div>
<?php endif; ?>
</div>