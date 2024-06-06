<div class="espsee_content">


<?php
if($user!==false){
    ?>

        <!--<h1>Dashboard</h1>-->
        
        <div class="espsee_page_actions">
            <div class="espsee_actions">
                <a class="espsee_button_link" href="<?php echo site_url(); ?>/wp-content/plugins/EspSee/views/modal/camera_add.php" rel="modal:open">
                    <i class="fa fa-plus"></i> <i class="fa fa-video-camera"></i>
                </a>
            </div>
        </div>
        
        <div class="cameras_list">
            <?php
            if($cameras!==false){
                foreach($cameras as $c){
                    
                    $active='inactive';
                    if($c->updated_time_unix>(time()-60)){$active = 'active';}
                    
                    ?>
                    
                    <div id="camera_<?php echo $c->id; ?>" class="camera_item <?php echo $active; ?>">
                        
                        <div class="espsee_actions">
                            <a class="espsee_action_link" href="<?php echo site_url(); ?>/wp-content/plugins/EspSee/views/modal/camera_info.php?id=<?php echo $c->id; ?>" rel="modal:open">
                                <i class="fa fa-info-circle"></i>
                            </a>
                            <a class="espsee_action_link" href="<?php echo site_url(); ?>/wp-content/plugins/EspSee/views/modal/camera_edit.php?id=<?php echo $c->id; ?>" rel="modal:open">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <a class="espsee_action_link red" href="<?php echo site_url(); ?>/wp-content/plugins/EspSee/views/modal/camera_remove.php?id=<?php echo $c->id; ?>" rel="modal:open">
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>
                        
                        <div class="camera_name">
                            <?php
                            if($c->camera_name!==''){
                                echo $c->camera_name;
                            }
                            ?>
                        </div>
                        
                        <a href="<?=$c->cameraProtocol?><?=$c->remoteAddr?>:<?=$c->cameraPort?>" target="_blank">
                        <?php
                            echo $c->cameraProtocol.$c->remoteAddr.':'.$c->cameraPort;
                        ?>
                        </a>
                        
                    </div>
                    
                    <?php
                }
            }
            ?>
        </div>
        
        <!--<iframe id="camera_iframe" name="camera_iframe">
        </iframe>-->
        
        
    <?
}else{
    ?>
    
        <p>Please <a href="/login/">Login</a> to see your dashboard.</p>
        <p>No account? <a href="/register/">register here</a>.</p>
    
    <?php
}
?>
    

</div>
