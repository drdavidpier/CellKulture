
    <form class="form-signin" action="<?php echo base_url('login/update_password'); ?>" method="POST">
        <h1 class="form-signin-heading">Update your password</h1>
            <?php if (isset($email_hash, $email_code)) { ?>
            <input type="hidden" value="<?php echo $email_hash ?>" name="email_hash" />
            <input type="hidden" value="<?php echo $email_code ?>" name="email_code" />
            <?php } ?>
            <input class="form-control" placeholder="Email" type="email" value="<?php echo (isset($email)) ? $email : ''; ?>" name="email" />
            <input class="form-control" placeholder="New Password" type="password" value="" name="password" />
            <input class="form-control" placeholder="Retype New Password" type="password" value="" name="password_conf" />
            <input class="btn btn-lg btn-phenol btn-block" type="submit" name="submit" value="Update My Password" />
    </form>
    <?php echo validation_errors('<p class="error">'); ?>
