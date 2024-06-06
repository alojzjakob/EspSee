<?php

include(dirname(__FILE__).'/../../../../../wp-blog-header.php');

global $EspSee;

$camera=$EspSee->get_camera($_GET['id']);

?>

<h4 class="red">Remove camera</h4>

<form id="remove_camera_form" data-camera_id="<?php echo $_GET['id']; ?>">
    <p>Really remove: <strong><?php echo stripslashes($camera->camera_name); ?></strong>?</p>
    
    <div>
   
        <p>
            This will remove camera from the system, but if external heartbeat is not disabled on camera and it comes online, it will be added again to your account based on your auth token.
        </p>
   
        <p>
            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
        <p>
        
        <p class="error_message" id="error_message"></p>
        <p>
            <button type="submit" id="remove_camera">Remove</button> <a href="#close" class="f-right" rel="modal:close">cancel</a>
        <p>
    </div>
</form>

<script type="text/javascript">

remove_camera_handler();

</script>
