<?php

include(dirname(__FILE__).'/../../../../../wp-blog-header.php');

global $EspSee;

$camera=$EspSee->get_camera($_GET['id']);

?>

<h4>Camera info</h4>

<div>
    
    <p>
        <b>Name:</b><br/>
        <code><?php echo $camera->camera_name ;?></code>
    </p>
    
    <p>
        <b>Address:</b><br/>
        <code><?php echo $camera->cameraProtocol.$camera->remoteAddr.':'.$camera->cameraPort; ?></code>
    </p>
    
    <p>
        <b>EFUSE MAC:</b><br/>
        <code><?php
        
        $chunks = str_split($camera->macAddressEfuse, 2);
        $mac = implode(':', $chunks);

        echo $mac; ?></code>
    </p>
    
    <p>
        <b>WiFi MAC:</b><br/>
        <code><?php
        $chunks = str_split($camera->macAddressWiFi, 2);
        $mac = implode(':', $chunks);
        echo $mac;
        ?></code>
    </p>
    
    <p>
        <a href="#close" class="f-right" rel="modal:close">close</a>
    <p>
</div>
