
<?php echo validation_errors('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button>', '</div>'); ?>

<div class="row">
    <div class="col-md-6">
        <div class="panel">
            <form class="grid-form" method="post" action="<?php echo base_url('project/save'); ?>">
                <fieldset>
                    <div data-row-span="1">
                        <div data-field-span="1">
                            <label class="control-label" for="name" style="font-weight: bolder;">Cell type *</label>
                            <input type="text" class="" name="name" id="name" placeholder="e.g. NIH-3T3" value="<?php echo set_value('name', $name); ?>" autofocus/>
                        </div>
                        <div data-field-span="1">
                            <label class="control-label" for="description" style="font-weight: bolder;">Description *</label>
                            <textarea name="description" id="description" class="" placeholder="Optional general notes about this culture" rows="4"><?php echo set_value('description', $description); ?></textarea>
                        </div>
                    <?php if($archive) : ?>    
                        <div data-field-span="1">
                            <label><?php echo form_checkbox('archive', '1', FALSE); ?> Archive culture <span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="right" 
                            data-original-title="Archiving a culture removes it from your virtual incubator but retains all the data. You can access the data using the options on the virtual incubator page. You can also return the culture to the virtual incubator at any time" 
                            id="myButton"></span></label>

                        </div>
                    <?php endif; ?>
                    </div>
                </fieldset>
        </div>
        <div class="panel">
            <fieldset>
            <?php if (isset($id)) echo form_hidden('id', $id); ?>
                <div class="btn-group btn-center">
                    <button type="submit" name="save" class="btn btn-phenol">Save</button>
                    <button type="submit" name="cancel" class="btn btn-clouds">Cancel</button>
            <?php if(isset($project_id)) : ?>
                    <a href="<?php echo base_url('project/remove_project/'.$project_id); ?>" class="btn btn-default remove-item-action">Delete</a>
            <?php endif; ?>
                </div>
            </fieldset>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel">
                <fieldset>
                    <div class="control-group">
                        <label class="control-label" style="font-weight: bolder;">Share this culture with your lab</label>
                        <p class="help-block">If you haven't added a lab group in your profile you won't see anyone to share your cultures with</p>
                            <div class="controls">
                            <?php foreach ($users as $user) { ?>
                                <div class="half-width">
                                    <label>
                                    <label>
                                    <?php echo form_checkbox('users[]', $user['id'], set_checkbox('users[]', $user['id'], ($user['project'])?1:0)); ?>
                                    <?php if($user['id'] != $this->session->userdata('user')){
                                        echo $user['name'];
                                    }else{
                                        echo 'Me';
                                    }?></label>
                                </div>
                            <?php } ?>
                            </div>
                    </div>
                </fieldset>
        </div>
    </div>

</form>
</div>
