<div class="panel">
<?php if($success == '1') : ?>
    <div class="alert alert-success">
        <p><strong>Data Saved.</strong></p>
    </div>
<?php endif; ?>
<?php echo validation_errors('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button>', '</div>'); ?>

<form class="form-horizontal" method="post" action="<?php echo base_url('profile/save'); ?>" accept-charset="utf-8" enctype="multipart/form-data">
  <fieldset> 
    <div class="row">
        <div class="col-sm-9">
        <div class="row">
            <div class="control-group form-margin">
                <label class="col-sm-3 control-label" for="name">Name *</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="name" id="name"
                           placeholder="Name" value="<?php echo set_value('name', $name); ?>"/>
                    <span class="help-block">This is how other members of your lab group will find you</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="control-group form-margin">
                <label class="col-sm-3 control-label" for="email">Email *</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="email" id="email"
                           placeholder="Email" value="<?php echo set_value('email', $email); ?>" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="control-group form-margin">
                <label class="col-sm-3 control-label" for="email">Lab Group *</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="lab" id="lab"
                           placeholder="Lab Group" value="<?php echo set_value('lab', $lab); ?>" />
                    <span class="help-block">By default we have created a unique lab name for you. 
                    If you wish to share your cultures with others change this to your lab name and make sure everyone in the lab uses EXACTLY the same name. 
                    Even with a lab name entered here everything stays private by default.</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="control-group form-margin">
                <label class="col-sm-3 control-label" for="password">Password</label>
                <div class="col-sm-6">
                    <?php if (isset($id)) : ?>
                    
                        <input type="password" class="form-control" name="password" id="password" disabled
                               placeholder="Password" value="<?php echo set_value('password', $password); ?>" />
                        <?php echo form_checkbox('reset_password', 1, false, 'id="reset_password" title="Edit Password"'); ?>
                        
                    <?php else : ?>
                        
                        <input type="password" class="form-control" name="password" id="password"
                               placeholder="Password" value="<?php echo set_value('password', $password); ?>" />
                        <?php echo form_hidden('reset_password', 1); ?>
                        
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
                <div class="btn-group btn-center">
                    <button type="submit" name="save" class="btn btn-phenol">Save</button>
                    <button type="submit" name="cancel" class="btn btn-clouds">Cancel</button>
                </div>
            </div>
        </div>
        </div>
        <div class="col-sm-3">
            <div class="control-group">
                <label class="control-label sr-only" for="photo">Photo</label>
                <div class="controls">
                    <div id="profile_photo" class="form-margin">
                        <?php if($photo) : ?>
                        <img src="<?php echo base_url().$photo; ?>" title="Your photo" class="img-circle center-block"/>
                        <?php else : ?>
                        <img src="<?php echo site_url().'upload/profile/largedefault.png'; ?>" title="Your photo" class="img-circle" />
                        <?php endif; ?>
                    </div>
                    <div class="form-margin">
                    <?php echo form_upload('photo'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


</fieldset>
</form>
</div>