<?php

include(dirname(__FILE__).'/../../../../../wp-blog-header.php');

global $EspSee;

$camera=$EspSee->get_camera($_GET['id']);

?>

<h4>Edit camera</h4>

<form id="edit_camera_form" data-camera_id="<?php echo $_GET['id']; ?>">
    <!--<p><strong><?php echo stripslashes($camera->camera_name); ?></strong></p>-->
    
    <div>
   
        <p>
            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
            Camera name: <input type="text" id="camera_name" name="camera_name" placeholder="Enter the camera name" value="<?php echo stripslashes($camera->camera_name); ?>">
        <p>
        
         <p>
            Camera protocol: 
            <select name="protocol">
                <option value="https://" <?php if($camera->cameraProtocol==='https://') echo ' selected'; ?>>HTTPS</option>
                <option value="http://" <?php if($camera->cameraProtocol==='http://') echo ' selected'; ?>>HTTP</option>
            </select>
        <p>
        
        <p>
            Camera port: <input type="number" min="1" max="65535" id="camera_port" name="camera_port" placeholder="Enter the camera port" value="<?php echo stripslashes($camera->cameraPort); ?>">
        <p>
        
        
        <p class="error_message" id="error_message"></p>
        <p>
            <button type="submit" id="edit_camera">Save</button> <a href="#close" class="f-right" rel="modal:close">cancel</a>
        <p>
    </div>
</form>

<script type="text/javascript">

edit_camera_handler();

</script>
