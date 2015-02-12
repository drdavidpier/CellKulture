<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="panel">
            <p>Passage number <span class="bignumb">P<?php echo $task['title']; ?></span><span class="pull-right"><img src="<?php echo base_url('upload/profile').'/medium'.$photo; ?>" class="img-circle"></span></p>
            <p>Frozen on <span class="bignumb"><?php echo $task['due_date']; ?></span></p>
            <p>Frozen for <span class="bignumb"><?php if($time_since){echo $time_since;}else{echo '0 days';} ?></span></p>
            <p>At time of freezing cells were <span class="bignumb"><?php echo $task['confluence']; ?>%</span> Confluent and <span class="bignumb"><?php echo cell_quality_text($task['cell_quality']); ?></span> quality</p>
        </div>
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
    <div class="col-xs-12 col-md-6">
        <div class="col-xs-6">
            <a href="<?php echo base_url('project/tasks/'.$project_id); ?>" class="btn btn-phenol btn-lg btn-block">Go to Parent Culture</a>
        </div>
        <div class="col-xs-6">
            <a href="<?php echo base_url('freezer_box/box/'.$task['f_location']); ?>" class="btn btn-peter-river btn-lg btn-block">Go to Cold Storage Location</a>
        </div>
    </div>
</div>