<div class="profile-photo">
    <?php //var_dump($user);
    //echo $task['user_id']; ?>
    <?php if($user['photo']) : ?>
    <img src="<?php echo base_url().$user['photo']; ?>" />
    <?php else : ?>
    <img src="<?php echo base_url().'assets/img/profile/default32.png'; ?>" />
    <?php endif; ?>
</div>