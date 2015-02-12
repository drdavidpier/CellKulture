<?php if(isset($tasks) && $tasks) : ?>
<div class="panel">
    <h2>Your Cultures</h2>
    <div class="row">
        <div class="col-xs-12 col-lg-12">
            <?php foreach ($tasks as $task) : ?>
            <?php if ($task['project_archive'] == 0){ ?>
                    <a href="<?php echo base_url('project/tasks/'.$task['project_id']); ?>">
                    <?php echo dashboard_one_class($task['flask_type']); ?>
                            <?php echo $task['project_name']; ?>
                    <?php echo dashboard_two_class($task['flask_type']); ?>
                    </a>
                </div>
            <?php } ?>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
            <a href="<?php echo base_url('project/add'); ?>" class="btn btn-default btn-block btn-lg" style="padding-top: 20px; padding-bottom: 20px;">Add Culture</a>
        </div>
    </div>
</div>
<?php else : ?>
<div class="row">
    <div class="col-md-6">
        <div class="panel">
            <h2>Welcome to CellKulture<br /><small> The home of your private virtual incubator</small></h2>
            <p>CellKulture is 100% focused on helping you track your cell and tissue cultures.</p>
            <p>Lets get started!</p>
        </div>
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">    
                <a href="<?php echo base_url('project/add'); ?>" class="btn btn-phenol btn-lg btn-block" style="padding-top: 20px; padding-bottom: 20px; margin-bottom:200px;">Add Your First Culture</a></h2>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <img src="<?php echo base_url('assets/css/images/incubator-flat.png'); ?>" style="width:100%">
    </div>
</div>
<?php endif; ?>