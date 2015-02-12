<?php if(isset($users)) { ?>
<div class="table-responsive">
    <table class="table table-bordered table-striped ">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th class="hidden-phone">Level</th>
            <th class="hidden-phone">Date Created</th>
            <th>Last Active</th>
            <th>Cultures</th>
            <th>Lab</th>
            <th>Actions</th>
        </tr>
    <?php foreach ($users as $user) { ?>
        <tr id="user_<?php echo $user['id']; ?>" class="<?php echo $user['highlight']; ?>">
            <td><?php echo $user['id']; ?></td>
            <td><?php echo $user['name']; ?></td>
            <td><?php echo anchor('profile/view/'.$user['id'], $user['email'], 'style="color:#cc2727"'); ?></td>
            <td class="hidden-phone"><?php echo $level_list[$user['level']]; ?></td>
            <td class="hidden-phone"><?php echo date("j/M/Y, g:i a", strtotime($user['date_created'])); ?></td>
            <td><?php echo $user['days_since'].' Days Ago'; ?></td>
            <td><?php echo $user['cultures']; ?></td>
            <td><?php echo $user['lab']; ?></td>
            <td>
                <a href="<?php echo base_url('user/edit/'.$user['id']); ?>"
                   class="btn btn-xs btn-phenol"
                   title="Edit">
                    <span class="glyphicon glyphicon-pencil"></span>
                </a>
                <a href="<?php echo base_url('user/remove/'.$user['id']); ?>"
                   class="btn btn-xs btn-clouds remove-item-action"
                   title="Remove">
                    <span class="glyphicon glyphicon-remove"></span>
                </a>
            </td>
        </tr>
    <?php } ?>
    </table>
</div>
<?php } else { ?>
    <div class="alert">No users registered.</div>
<?php } ?>

