<div class="panel panel-concrete">
    <h2>Your Previous Cultures</h2>
    <div class="row">
        <div class="col-xs-12 col-lg-12">
            <?php foreach ($tasks as $task) : ?>
            <?php if ($task['project_archive'] != 0){ ?>
                <div class="col-xs-6 col-sm-3 col-md-2">
                    <div class="flask-lid">
                        <div class="panel panel-phenol panel-flask t25">
                            <h5><a href="<?php echo base_url('project/tasks/'.$task['project_id']); ?>"><?php echo $task['project_name']; ?></a></h5>
                        <p><?php //echo $task['flask_number']; ?></p> <!-- delete me -->
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>