
<?php if(!empty($freezers)) : ?>
<div class="row">
    <?php foreach ($freezers as $freezer) : ?>
    <div class="col-xs-12 col-md-6">
        <div class="panel <?php echo freezer_class($freezer['type']); ?>">
            <h2 class="panel-title"><?php echo $freezer['name']; ?><span class="pull-right"><?php echo freezer_text($freezer['type']); ?></span>
                    <a href="<?php echo base_url('cold_storage/edit/'.$freezer['freezer_id']); ?>">
                        <span class="glyphicon glyphicon-pencil" style="color: #BDC3C7; font-size: 20px;"></span></a>
            </h2>
            <div class="row">
            <?php foreach ($boxes as $box) : ?>
                <?php if ($box['freezerid'] == $freezer['freezer_id']) : ?>
                <div class="col-xs-6 col-sm-4">
                    <a href="<?php echo base_url('freezer_box/box').'/'.$box['box_id']; ?>" class="btn btn-lg btn-block btn-default" style="padding-top:20px; padding-bottom:20px;"><?php echo $box['name']; ?></a>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-sm-offset-3">
                    <a href="<?php echo base_url('freezer_box/add'); ?>" class="btn btn-peter-river btn-block">Add Freezer Box</a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-sm-offset-3">
        <a href="<?php echo base_url('cold_storage/add'); ?>" class="btn btn-lg btn-turq btn-block" style="padding-top: 20px; padding-bottom: 20px;">Add Freezer</a>
    </div>
</div>
<?php else : ?>
    <div class="alert alert-info">You don't have any cold storage locations. Why not add some now.</div>
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-offset-4">
                <a href="<?php echo base_url('cold_storage/add'); ?>" class="btn btn-primary btn-lg btn-block" style="padding-top: 20px; padding-bottom: 20px; margin-bottom:200px;">Add Your First Freezer</a></h2>
            </div>
        </div>
<?php endif; ?>

<div style="margin-top:100px;">
<button type="button" class="btn btn-clouds" data-toggle="collapse" data-target="#demo">
  How do I add a frozen sample?
</button>
</div>

<div id="demo" class="collapse">
    <div class="panel panel-add">
        <p>CellKulture tracks cultures not samples. In order to enter a vial in cold storage you must first create a culture and save the vials location from there.</p>
    </div>
</div>