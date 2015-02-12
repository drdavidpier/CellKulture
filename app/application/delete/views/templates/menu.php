<div class="navbar navbar-fixed-top navbar-inverse" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo base_url('dashboard'); ?>">CellKulture</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
                    
                    <?php if(strpos($menu, 'dashboard') !== FALSE) : ?>
                        <!-- Dashboard btn -->
                        <li <?php if($controller == 'dashboard') echo 'class="active"'; ?>>
                            <a href="<?php echo base_url('dashboard'); ?>">Virtual Incubator</a>
                        </li>
                    <?php endif; ?>
                    
                    <?php if(strpos($menu, 'cold_storage') !== FALSE) : ?>
                        <!-- cold storage btn -->
                        <li <?php if($controller == 'cold_storage') echo 'class="active"'; ?>>
                            <a href="<?php echo base_url('cold_storage'); ?>">Cold Storage</a>
                        </li>
                    <?php endif; ?>

                    <?php if(strpos($menu, 'add_user') !== FALSE && $this->usercontrol->has_permission('user')) : ?>
                        <!-- Users btn -->
                        <li>
                            <a href="<?php echo base_url('user/add'); ?>" class="">Add New User</a>
                        </li>
                    <?php endif; ?>

                    <?php if(strpos($menu, 'tasks') !== FALSE && $this->usercontrol->has_permission('project', 'tasks')) : ?>
                        <!-- View culture btn -->
                        <li class="active">
                            <a href="<?php echo base_url('project/tasks/'.$project_id); ?>" class="">Back to Parent Culture</a>
                        </li>
                    <?php endif; ?>

                    <?php if(strpos($menu, 'view_task') !== FALSE && $this->usercontrol->has_permission('project', 'task')) : ?>
                        <!-- View task board btn -->
                        <li>
                            <a href="<?php echo base_url('task/view/'.$project_id.'/'.$task_id); ?>" class="">
                                View Task
                            </a>
                        </li>
                    <?php endif; ?>
                        
                </ul>
                
                <?php if(strpos($menu, 'none') === FALSE) : ?>
                <ul class="nav navbar-nav pull-right">
                    
                    <?php if(strpos($menu, 'users') !== FALSE && $this->usercontrol->has_permission('user')) : ?>
                        <!-- Users btn -->
                        <li <?php if($controller == 'user') echo 'class="active"'; ?>>
                            <a href="<?php echo base_url('user'); ?>" class="">
                                <i></i>
                                Edit Users
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <li <?php if($controller == 'profile') echo 'class="active"'; ?>>
                        <a href="<?php echo base_url('profile'); ?>" class="">Profile</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('login/logout'); ?>" class="">Logout</a>
                    </li>
                </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel">
            <div class="row">
            <div class="col-xs-8 col-sm-10">
                <h1><?php echo $title; ?></h1>
            </div>
            <div class="col-xs-2 col-sm-1">  
            </div>
            <div class="col-xs-2 col-sm-1">
                <?php if($controller == 'project' or $controller == 'dashboard' or $controller == 'freezer_box' or $controller == 'freezer_vial') : ?>
                <div class="">
                    <div class="btn-group btn-group-right" style="margin-top:10px">
                        <button type="button" class="btn btn-link dropdown-toggle-noshaddow" data-toggle="dropdown">
                            <h2 class="panel-settings"><span class="glyphicon glyphicon-cog"></span></h2>
                        </button>
                        <ul class="dropdown-menu pull-right" role="menu">
                            <li>
                            
                            <?php if(strpos($menu, 'edit_project') !== FALSE && $this->usercontrol->has_permission('project')) : ?>
                                <a href="<?php echo base_url('project/edit/'.$project_id); ?>" class="">Edit Culture</a>
                            <?php endif; ?>
                            </li>
                            <li>
                            
                            <?php if($controller == 'project' && $method == 'tasks') { ?>
                            <a href="#" id="switch-project-view" class="" title="My Notes">Show only my notes</a>
                            </li>
                            <li>
                                <a href="javascript:window.print()" title="Print">Print Page</a>
                            </li>
                            <li>
                                <?php if(isset($stories)) { 
                                    echo "<a href=\"".base_url('project/download/'.$task['project_id']); 
                                    echo "\"  title=\"Download Raw Data\">Download Culture Data</a>";
                                    
                                    echo "<a href=\"".base_url('project/download_image/'.$task['project_id']); 
                                    echo "\"  title=\"Download Images\">Download Images</a>";
                                } ?>
                            <?php } ?>
                            </li>
                            <li>
                            <?php if($controller == 'dashboard' ) : ?>
                                <a href="<?php echo base_url('dashboard/archive/'); ?>" class="">Culture Archive</a>
                            <?php endif; ?> 
                            </li>
                            <li>
                            <?php if($controller == 'dashboard' && $method == 'archive') : ?>
                                <a href="<?php echo base_url('dashboard/'); ?>" class="">Back to Current Cultures</a>
                            <?php endif; ?> 
                            </li>
                            <li>
                            <?php if($controller == 'freezer_box' && $method == 'box') : ?>
                                <a href="<?php echo base_url('freezer_box/edit/'.$this->uri->segment(3)); ?>" class="">Edit This Box</a>
                            <?php endif; ?>
                            </li>
                            <li>
                            <?php if($controller == 'freezer_vial' && $method == 'view') : ?>
                                <a href="<?php echo base_url('freezer_vial/edit/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$this->uri->segment(5)); ?>" class="">Edit Vial Location</a>
                            <?php endif; ?>
                            </li>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            </div>
            </div>
        </div>
    </div>
</div>