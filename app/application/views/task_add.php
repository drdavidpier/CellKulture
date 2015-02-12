<?php echo validation_errors('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">×</button>', '</div>'); ?>

    <a href="#viewrecord" class="btn btn-phenol pull-right visible-xs">Jump to Records</a> 
    
    
<form class="grid-form" method="post" action="<?php echo base_url('task/save'); ?>" accept-charset="utf-8" enctype="multipart/form-data">
    
    <fieldset>
		<div data-row-span="2">
			<div data-field-span="1">
                <label for="due_date">Date</label>
                                <input type="text" name="due_date" id="due_date" class="datepicker" value="<?php echo $due_date;?>">
                                <a href="#" title="Select date" class="btn datepicker-action">
                                </a>
            </div>
            <div data-field-span="1">
                    <label for="title">Passage</label>
                        <input type="text" class="" name="title" id="title" maxlength="50"
                               placeholder="P??" value="<?php echo set_value('title', $title); ?>" />
            </div>
        </div>
        <div data-row-span="1">
            <div data-field-span="1">
				<label  for="">Media Colour</label>
                <div class="btn-group btn-span-group">
                    <button type="button" class="btn btn-phenol btn-span btn-highlight" onclick="this.form.priority.value='0';"></button>
                    <button type="button" class="btn btn-alizarin btn-span btn-highlight" onclick="this.form.priority.value='1';"></button>
                    <button type="button" class="btn btn-carrot btn-span btn-highlight" onclick="this.form.priority.value='2';"></button>
                    <button type="button" class="btn btn-orange btn-span btn-highlight" onclick="this.form.priority.value='3';"></button>
                    <button type="button" class="btn btn-sun-flower btn-span btn-highlight" onclick="this.form.priority.value='4';"></button>
                </div>
            </div>
        </div>
        <div class="hidden">
            <label for="priority">Media Colour</label>
                <?php echo form_hidden('priority',$priority); ?>
            </div>
        
            <div data-row-span="2">
			    <div data-field-span="1">
                    <label for="flask_number">Number of Flasks/Plates</label>
                    <input type="text" class="" name="flask_number" id="flask_number" maxlength="50"
                               placeholder="" value="<?php echo set_value('flask_number', $flask_number); ?>" />
                </div>
                <div data-field-span="1">
                    <label for="flask_type">Culture Vessel</label>
                        <?php $flask_options = flask_type_text(); //
                              echo form_dropdown('flask_type', $flask_options, set_value('flask_type', $flask_type), ''); ?>
                </div>
            </div>
            <div data-row-span="2">
                <div data-field-span="1">
                    <label for="confluence">Confluency (%)</label>
                        <input type="text" class="" name="confluence" id="confluence" maxlength="4"
                               placeholder="" value="<?php echo set_value('confluence', $confluence); ?>" />
                </div>
			    <div data-field-span="1">
                    <label for="cell_quality">Cell Quality</label>
                        <?php $cell_quality_options = cell_quality_text(); //
                              echo form_dropdown('cell_quality', $cell_quality_options, set_value('cell_quality', $cell_quality), ''); ?>
                </div>
            </div>
            <div data-row-span="2">
                <div data-field-span="1">
                    <label class="control-label" for="photo">Upload Photo</label>
                    <div class="controls">
                        <input type="file" name="userfile" size="20" />
                    </div>
                </div>
                <div data-field-span="1">
                    <label for="infection">Infection?</label>
                        <?php $infection_options = infection_text();
                              echo form_dropdown('infection', $infection_options, set_value('infection', $infection), ''); ?>
                </div>
            </div>
            <div data-row-span="1">
                <div data-field-span="1">
                <div class="control-group">
                    <label class="control-label" for="description">Description</label>
                    <div class="controls">
                        <textarea name="description" id="description" class="" rows="3"><?php echo set_value('description', $description); ?></textarea>
                    </div>
                </div>
            </div>
            <!-- hidden form element -->
                <div class="hidden">
                    <label class="control-label" for="user_id">Assigned to</label>
                    <div class="controls">
                        <?php
                        //$options = array();
                        //foreach ($users as $value) {
                        //    $options[$value['id']] = $value['email'];
                        //}
                        //echo form_dropdown('user_id', $options, set_value('user_id', $user_id), '');
                        echo form_hidden('user_id', $user_id);
                        ?>
                    </div>
                </div>
            <!-- hidden form element -->
            <div class="hidden">
                    <label for="action">Action Taken</label>
                    <?php echo form_hidden('action','0'); ?>
                    <!-- <input type="text" name="action" value='0'> -->
                        <?php //$action_options = action_text(); //
                              //echo form_dropdown('action', $action_options, set_value('action', $action), ''); ?>
                    <label for="action_two">Second Action Taken</label>
                    <?php echo form_hidden('action_two','0'); ?>
            </div>
            
    <?php if (isset($task_id)) echo form_hidden('task_id', $task_id); ?>
    <?php if (isset($status)) echo form_hidden('status', $status); ?>
    <?php if (isset($project_id)) echo form_hidden('project_id', $project_id); ?>
</fieldset>
<fieldset>
    <h4>Action Taken</h4>
    <div class="btn-group" style="margin-bottom:0px">
        <button type="button" class="btn btn-clouds" data-toggle="collapse" data-target="#save" onclick="this.form.action.value='0';">None</button>
        <button type="button" class="btn btn-clouds" data-toggle="collapse" data-target="#save" onclick="this.form.action.value='1';">Media Change</button>
        <button type="button" class="btn btn-clouds" data-toggle="collapse" data-target="#passage" onclick="this.form.action.value='2';">Passage</button>
        <button type="button" class="btn btn-clouds" data-toggle="collapse" data-target="#freeze" onclick="this.form.action.value='3';">Freeze</button>
        <button type="button" class="btn btn-clouds" data-toggle="collapse" data-target="#remove" onclick="this.form.action.value='4';">Flask Removal</button>
    </div>
    
    <p>Additional Action?</p>
    <div class="btn-group" style="margin-bottom:10px">
        <button type="button" class="btn btn-default" data-toggle="collapse" data-target="#save" onclick="this.form.action_two.value='0';">None</button>
        <button type="button" class="btn btn-default" data-toggle="collapse" data-target="#save" onclick="this.form.action_two.value='1';">Media Change</button>
        <button type="button" class="btn btn-default" data-toggle="collapse" data-target="#passage" onclick="this.form.action_two.value='2';">Passage</button>
        <button type="button" class="btn btn-default" data-toggle="collapse" data-target="#freeze" onclick="this.form.action_two.value='3';">Freeze</button>
        <button type="button" class="btn btn-default" data-toggle="collapse" data-target="#remove" onclick="this.form.action_two.value='4';">Flask Removal</button>
    </div>
    
    <div id="save" class="collapse">
        <p>Your form is complete. Just click 'save' to enter your data</p>
    </div>
    
    <div id="passage" class="collapse <?php echo $collapse_passage; ?>">
    <legend><h4>Passage Details</h4></legend>
        <div data-row-span="2">
			<div data-field-span="1">
			    <label for="flask_number">Number of New Flasks</label>
                    <input type="text" class="" name="flask_number_new" id="flask_number_new" maxlength="50"
                               placeholder="" value="<?php echo set_value('flask_number_new', $flask_number_new); ?>" />
			</div>
			<div data-field-span="1">
			    <label for="split_ratio">Split Ratio</label>
                    <input type="text" class="" name="split_ratio" id="split_ratio" maxlength="50"
                               placeholder="" value="<?php echo set_value('split_ratio', $split_ratio); ?>" />
			</div>
		</div>
		<div data-row-span="1">
			<div data-field-span="1">
			<label for="split_ratio">New passage number (if different from below)</label>
                <input type="text" class="" name="passage_new" id="passage_new" maxlength="50"
                    placeholder="" value="<?php echo set_value('passage_new', $passage_new); ?>" />
			</div>
		</div>
	</div>
	
	<div id="freeze" class="collapse <?php echo $collapse_freeze; ?>">
	<legend><h4>Freezing Details</h4></legend>
        <div data-row-span="2">
			<div data-field-span="1">
			<label for="flasks_removed">Number of <?php echo well_type_text($flask_type); ?> used</label>
                <input type="text" class="" name="flasks_removed" id="flasks_removed" maxlength="50"
                    placeholder="" value="<?php echo set_value('flasks_removed', $flasks_removed); ?>" onblur="this.form.flasks_removed_two.value=this.value; this.form.removal_reason.value='2';"/>
			</div>
			<div data-field-span="1">
			<label for="vial_number">Number of Freezer Vials</label>
                <input type="text" class="" name="vial_number" id="vial_number" maxlength="50"
                    placeholder="" value="<?php echo set_value('vial_number', $vial_number); ?>" />
			</div>
		</div>
	</div>
	
	<div id="remove" class="collapse <?php echo $collapse_remove; ?>">
	<legend><h4>Details on flasks removed from current culture</h4></legend>
        <div data-row-span="2">
			<div data-field-span="1">
			<label for="flasks_removed_two">Number of flasks removed from cuture</label>
                <input type="text" class="" name="flasks_removed_two" id="flasks_removed_two" maxlength="50"
                    placeholder="" value="<?php echo set_value('flasks_removed_two', $flasks_removed_two); ?>" />
			</div>
			<div data-field-span="1">
			<label for="removal_reason">Reason For Removal</label>
                <?php $removal_reason_options = removal_reason_text(); //
                    echo form_dropdown('removal_reason', $removal_reason_options, set_value('removal_reason', $removal_reason), ''); ?>
			</div>
		</div>
	</div>
	<div class="btn-group btn-center">
        <button type="submit" name="save" class="btn btn-phenol" value="Click!" onclick="$('#loading').show();" id="savetask">
            Save
        </button>
        <button type="submit" name="cancel" class="btn btn-default">
            Cancel
        </button>
        <?php if(isset($task_id)) : ?>
        <a href="<?php echo base_url('task/remove/'.$project_id.'/'.$task_id); ?>" class="btn btn-danger remove-item-action">
            Remove
        </a>
        <?php endif; ?>
    </div>
    </fieldset>
</form>

<div id="loading" style="display:none;"><img src="<?php echo base_url('assets/img/loading.GIF'); ?>" alt="" /> Loading!</div>

<script type='text/javascript'>var downloadLink = document.getElementById('savetask');
addListener(downloadLink, 'click', function() {
  ga('send', 'event', 'button', 'click', 'savetask');
});

function addListener(element, type, callback) {
 if (element.addEventListener) element.addEventListener(type, callback);
 else if (element.attachEvent) element.attachEvent('on' + type, callback);
}
</script>