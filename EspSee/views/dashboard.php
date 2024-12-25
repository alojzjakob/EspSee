<div class="espsee_content">


<?php
if($user!==false){
    ?>

        <!--<h1>Dashboard</h1>-->
        
        <?php
            
        if($cameras!==false and count($cameras)>0){
        ?>
        
        <div class="espsee_page_actions">
            <div class="espsee_actions">
                <a class="espsee_button_link" href="<?php echo site_url(); ?>/wp-content/plugins/EspSee/views/modal/camera_add.php" rel="modal:open">
                    <i class="fa-solid fa-plus"></i> <i class="fa-solid fa-video"></i>
                </a>
            </div>
        </div>
        
        <div class="cameras_list">
            <?php

            foreach($cameras as $c){
                
                $active='inactive';
                if($c->updated_time_unix>(time()-60)){$active = 'active';}
                
                ?>
                
                <div id="camera_<?php echo $c->id; ?>" class="camera_item <?php echo $active; ?>">
                    
                    <div class="espsee_actions">
                        <?php
                        if($c->cameraProtocol==='https://'){
                        ?>
                        <a class="espsee_action_link" href="<?php echo site_url(); ?>/wp-content/plugins/EspSee/views/modal/camera_view.php?id=<?php echo $c->id; ?>" rel="modal:open">
                            <i class="fa-regular fa-eye"></i></a>
                        <?php
                        }else{
                            ?>
                            <a class="espsee_action_link disabled" href="#" title="Can't open HTTP camera in HTTPS website iframe. Use external link.">
                                <i class="fa-regular fa-eye-slash"></i></a>
                            <?php
                        }
                        ?>
                        
                        <a class="espsee_action_link" href="<?=$c->cameraProtocol?><?=$c->remoteAddr?>:<?=$c->cameraPort?>" target="_blank">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i></a>
                        
                        <a class="espsee_action_link" href="<?php echo site_url(); ?>/wp-content/plugins/EspSee/views/modal/camera_info.php?id=<?php echo $c->id; ?>" rel="modal:open">
                            <i class="fa-solid fa-circle-info"></i></a>
                        <a class="espsee_action_link" href="<?php echo site_url(); ?>/wp-content/plugins/EspSee/views/modal/camera_edit.php?id=<?php echo $c->id; ?>" rel="modal:open">
                            <i class="fa-solid fa-pencil"></i></a>
                        <a class="espsee_action_link red" href="<?php echo site_url(); ?>/wp-content/plugins/EspSee/views/modal/camera_remove.php?id=<?php echo $c->id; ?>" rel="modal:open">
                            <i class="fa-solid fa-trash"></i></a>
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
            ?>
        </div>
        <?php
        }else{
            ?>
            
            <div class="cta">
                <h1>No cameras in your dashboard...</h1>
                
                <a class="espsee_button_link big cta" href="<?php echo site_url(); ?>/wp-content/plugins/EspSee/views/modal/camera_add.php" rel="modal:open">
                    <i class="fa-solid fa-plus"></i> <i class="fa-solid fa-video"></i> Click here to add your first camera
                </a>
            </div>
            
            <?php
        }
        ?>
        
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
