<?php echo validation_errors('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button>', '</div>'); ?>
<?php if($success == '1') : ?>
    <div class="alert alert-success">
        <p><strong>Data Saved. </strong>Feel free to add another vial from the same culture or <a href="<?php echo base_url('project/tasks/'.$culture_id); ?>">go back to the incubator</a></p>
    </div>
<?php endif; ?>
<?php if(!empty($boxes) && !empty($freezers)) : ?>
<div class="row">
    <div class="col-md-6">
        <div class="panel">
            <form class="grid-form" method="post" action="<?php echo base_url('freezer_vial/save'); ?>">
                <fieldset>
                    <div data-row-span="3">
                        <div data-field-span="1">
                            <label class="control-label" for="boxid" style="font-weight: bolder;">Freezer Box *</label>
                            <?php echo form_dropdown('boxid', $freezer_box, set_value('boxid', $boxid), ''); ?>
                        </div>
                        <div data-field-span="1">
                            <label class="control-label" for="boxrow" style="font-weight: bolder;">Box row *</label>
                            <?php echo form_dropdown('row', $array_primer_row, set_value('row', $row), ''); ?>
                        </div>
                        <div data-field-span="1">
                            <label class="control-label" for="boxcolumn" style="font-weight: bolder;">Box column *</label>
                            <?php echo form_dropdown('column', $array_primer, set_value('column', $column), ''); ?>
                        </div>
                        <p class="help-block">Save the location of your vial in the relevant freezer box by column and row number. Take care when entering this data as it will overwrite any previous data.</p>
                    </div>
                </fieldset>
        </div>
        <div class="panel">
            <fieldset>
            <?php echo form_hidden('vial_id', $vial_id); ?>
            <?php echo form_hidden('sample_id', $sample_id); ?>
            <?php echo form_hidden('culture_id', $culture_id); ?>
            <?php if (isset($id)) echo form_hidden('id', $id); ?>
                <p class="help-block">Currently only one vial can be saved at a time. Please save the form and repeat this process if you have more than one vial</p>
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
    <!-- <div class="col-md-6">
        <div class="panel">
                <fieldset>
                    <div class="control-group">
                        <label class="control-label" style="font-weight: bolder;">Select which members from your lab to share this culture</label>
                        <p class="help-block">If you haven't added a lab group in your profile you will see all other users who also havent specified a lab group. Your cultures remain private unless you share them</p>
                            <div class="controls">
                            <?php foreach ($users as $user) { ?>
                                <div class="half-width">
                                    <label>
                                    <?php echo form_checkbox('users[]', $user['id'], set_checkbox('users[]', $user['id'], ($user['project'])?1:0)); ?>
                                    <?php echo $user['name']; ?></label>
                                </div>
                            <?php } ?>
                            </div>
                    </div>
                </fieldset>
        </div>
    </div> -->
</form>
</div>
<?php elseif(empty($boxes)) : ?>
<div class="row">
    <div class="col-md-6">
        <div class="alert alert-info">
            <h2>No Freezer Boxes Found :(</h2>
            <p>Please click the link and create at least one freezer box</p>
        </div>
    </div>
    <div class="col-xs-12 col-md-4 col-md-offset-1">
        <a href="<?php echo base_url('freezer_box/add'); ?>" class="btn btn-lg btn-peter-river btn-block" style="padding-top:20px; padding-bottom:20px;">Create your first freezer box</a>
    </div>
</div>
<?php else : ?>
<div class="row">
    <div class="col-md-6">
        <div class="alert alert-info">
            <h2>No Cold Storage Found :(</h2>
            <p>Please click the link and create a freezer AND at least one freezer box so we can keep track of everything</p>
        </div>
    </div>
    <div class="col-xs-12 col-md-4 col-md-offset-1">
        <a href="<?php echo base_url('cold_storage/add'); ?>" class="btn btn-lg btn-peter-river btn-block" style="padding-top:20px; padding-bottom:20px;">Create your first freezer</a>
    </div>
</div>
<?php endif; ?>