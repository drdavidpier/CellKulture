<?php if(empty($items)) redirect('cold_storage/add'); ?>
<?php echo validation_errors('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button>', '</div>'); ?>

<div class="row">
    <div class="col-md-6">
        <div class="panel">
            <form class="grid-form" method="post" action="<?php echo base_url('freezer_box/save'); ?>">
                <fieldset>
                    <div data-row-span="2">
                        <div data-field-span="1">
                            <label class="control-label" for="name" style="font-weight: bolder;">Box Name *</label>
                            <input type="text" class="" name="name" id="name" placeholder="e.g. Box Five (green)" value="<?php echo set_value('name', $name); ?>" autofocus/>
                        </div>
                        <div data-field-span="1">
                            <label class="control-label" for="type" style="font-weight: bolder;">Freezer *</label>
                            <?php if(!empty($items)){
                                echo form_dropdown('freezerid', $items, set_value('freezerid', $freezerid), ''); 
                            }else{
                                echo '<div class="alert alert-info">';
                                echo '<p>You dont have any freezers in which to store this box. Why not create one now</p>';
                                echo '<a href="'.base_url('cold_storage/add').'" class="btn btn-peter-river btn-block">Create Freezer</a>';
                                echo '</div>';
                            } ?>
                        </div>
                    </div>
                </fieldset>
        </div>
        <div class="panel">
            <fieldset>
            <?php if (isset($id)) echo form_hidden('id', $id); ?>
                <div class="btn-group btn-center">
                    <button type="submit" name="save" class="btn btn-phenol">Save</button>
                    <button type="submit" name="cancel" class="btn btn-clouds">Cancel</button>
            <?php if(isset($freezer_id)) : ?>
                    <a href="<?php echo base_url('cold_storage/remove_project/'.$freezer_id); ?>" class="btn btn-default remove-item-action">Delete</a>
            <?php endif; ?>
                </div>
            </fieldset>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel">
                <fieldset>
                    <div class="control-group">
                        <label class="control-label" style="font-weight: bolder;">Share this freezer box</label>
                        <p class="help-block">If you haven't added a lab group in your profile you won't see anyone to share your cultures with</p>
                            <div class="controls">
                            <?php foreach ($users as $user) { ?>
                                <div class="half-width">
                                    <label>
                                    <?php echo form_checkbox('users[]', $user['id'], set_checkbox('users[]', $user['id'], ($user['fbox'])?1:0)); ?>
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
