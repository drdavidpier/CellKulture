<div class="row">
    <div class="col-lg-6">
    
        <div class="panel">
        <a href="<?php echo base_url('project/tasks/'.$project_id); ?>" class="btn btn-clouds" >Back to Parent Culture</a>
        <hr>
<!-- Load the form -->
            <?php echo $task_data; ?>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="panel">
            <h3>Comments</h3>
        <?php if($comments) { ?>
        <?php foreach ($comments as $comment) { ?>
        <div id="task-comment-id-<?php echo $comment['task_comments_id']; ?>" class="well">
            <p><strong><?php echo ($comment['name']?$comment['name']:$comment['email']); ?></strong> <em>(<?php echo $comment['date_created']; ?>)</em></p>
            <?php echo $comment['comment']; ?>
        </div>
        <?php } ?>
        <?php } else { ?>
        No comments yet
        <?php } ?>
        <!-- </div>
        <div class="panel"> -->
            <form class="grid-form" method="post" action="<?php echo base_url('task/comment'); ?>">
            <fieldset>
            <div data-row-span="1">
                <div data-field-span="1">
                <div class="control-group">
                    <label class="sr-only">New Comment</label>
                    <div class="controls">
                        <textarea name="comment" id="comment" rows="5" class="" placeholder="Add a comment here"><?php echo set_value('comment'); ?></textarea>
                    </div>
                    <button type="submit" name="submit" class="btn btn-phenol">Submit Comment</button>
                </div>
            </div>
            </fieldset>
            <?php echo form_hidden('project_id', $project_id); ?>
            <?php echo form_hidden('task_id', $task_id); ?>
            </form>
        </div>
    </div>
</div>