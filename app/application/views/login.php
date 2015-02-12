<h1 class="text-center"><span class="phenol-text">CellKulture</span><small> Virtual Cell Culture Incubator</small></h1> 
 <?php if(isset($answer)) { ?>
    <div class="alert alert-success"><?php echo $answer; ?></div>
    <?php } ?>
    
    <?php if(isset($already_installed) && $already_installed) { ?>
        <?php if(isset($update_database) && $update_database) { ?>
            <form class="form-signin" action="<?php echo base_url('install/database'); ?>" method="post">
                <button class="btn btn-primary" type="upgrade">Upgrade</button>
            </form>
        <?php } else { ?>
            <div class="alert">Simple Task Board has already been installed.</div>
        <?php } ?>
    <?php } else { ?>
        <form class="form-signin" method="post"
              action="<?php echo (isset($already_installed) && !$already_installed)?base_url('install/run'):base_url('login/validate'); ?>">
            <h1 class="form-signin-heading">Login</h1>
            <input name="email" type="text" class="form-control" placeholder="Email" value="<?php echo set_value('email'); ?>">
            <input name="password" type="password" class="form-control" placeholder="Password" value="<?php echo set_value('password'); ?>">
            
            <?php if(isset($error) && $error) : ?>
            
                <div class="alert alert-danger">
                    Your email or password do not match our records. If you continue to experience difficulty please email <a href="mailto:login@cellkulture.com" target="_blank">login@cellculture.com</a>
                    
                    <?php if(isset($error_github) && $error_github) : ?>
                    <p>
                    <strong>Gihub user:</strong> are you sure you have an user?
                    You need an user already registered with your email in order to sign in with Github.
                    </p>
                    <?php endif; ?>
                    
                </div>
                
            <?php endif; ?>
                
            <?php if(isset($already_installed) && !$already_installed) : ?>
                <button class="btn btn-lg btn-phenol btn-block" type="submit" name="install">Install</button>
            <?php else : ?>
                <button class="btn btn-lg btn-phenol btn-block" type="submit" name="login">Login</button>
                <!-- <a href="<?php echo base_url('login/github'); ?>" class="btn">
                    Github
                </a> -->
                <a href="<?php echo base_url('login/reset_password'); ?>">Reset Password</a>
                <br /><?php echo safe_mailto('info@cellkulture.com', 'Contact Us'); ?>
            <?php endif; ?>
        </form>
    <?php } ?>