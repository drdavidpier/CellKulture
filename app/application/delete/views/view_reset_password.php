<?php echo validation_errors('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button>', '</div>'); 
        if (isset($error)){
            echo '<div class="alert alert-danger">'.$error.'<button type="button" class="close" data-dismiss="alert">×</button>', '</div>';
        }
    ?>
<form class="form-signin" action="reset_password" method="POST">
    <h1 class="form-signin-heading">Reset Password</h1>
    <input class="form-control" type="email" placeholder="Email" value="<?php echo set_value('email'); ?>" name="email" />
    <input class="btn btn-lg btn-phenol btn-block" type="submit" name="submit" value="Reset My Password" />
</form>