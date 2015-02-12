<h1 class="text-center"><span class="phenol-text">CellKulture</span><small> Virtual Cell Culture Incubator</small></h1>
<?php echo validation_errors('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button>', '</div>'); ?>
<?php if(isset($email_error)) : ?>
<div class="alert alert-danger"><?php echo $email_error; ?><button type="button" class="close" data-dismiss="alert">×</button></div>
<?php endif; ?>

<form class="form-signin" method="post" action="<?php echo base_url('registration/validate'); ?>">
    <h1 class="form-signin-heading">Sign Up</h1>
        <input type="text" class="form-control" name="name" id="name" placeholder="Username" value="<?php echo set_value('name', $name); ?>" />
        <input type="text" class="form-control" name="email" id="email" placeholder="Email" value="<?php echo set_value('email', $email); ?>" />
        <input type="password" class="form-control" name="password" id="password" placeholder="Password" value="<?php echo set_value('password', $password); ?>" />
        <?php echo form_checkbox('terms', 'accept', TRUE); ?> I agree to <a href="<?php echo base_url('/docs/terms'); ?>">CellKulture terms</a>
    <button type="submit" name="save" class="btn btn-lg btn-phenol btn-block">Sign Me Up</button>
    <p>Or <?php echo anchor('login','Login','title="Login"'); ?></p>
</form>
