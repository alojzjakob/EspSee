<?php

include(dirname(__FILE__).'/../../../../../wp-blog-header.php');

global $EspSee;

$token = $EspSee->get_user_token();

$data=array();

$data['token']='';

if($token!==false){
    $data['token']=$token->user_token;
}

?>

<h4>Add camera</h4>

<div>
    
    <p>
        <b>Heartbeat receiver domain:</b><br/> <code>www.espsee.com</code>
    </p>
    <p>
        <b>Heartbeat receiver port:</b><br/> <code>443</code>
    </p>
    <p>
        <b>Heartbeat receiver auth token:</b><br/> <code><?php echo $data['token']; ?></code>
    </p>
    <p>
        <b>Heartbeat receiver URI:</b><br/> <code>/heartbeat/</code>
    </p>
    <p>
        <a href="#close" class="f-right" rel="modal:close">close</a>
    <p>
</div>

