<?php
/*
Plugin Name: EspSee
Plugin URI: https://www.espsee.com
Description: <strong>EspSee</strong> plugin for WordPress
Author: Alojz Jakob
Author URI: mailto:alojzjakob@gmail.com
Version: 1.0
*/


include 'inc/AJWPPMVCF.php';

class EspSee extends AJWPMVC {
    
    private $current_user_token;
    private $cameras_table;
    private $user_tokens_table;
    public $user;
    public $user_token;
    
    
    public function __construct(){
        
        $this->current_user_token = 'TOKEN123';
        $this->cameras_table = 'espsee_cameras';
        $this->user_tokens_table = 'espsee_tokens';
        
        
        $this->user=false;
        
        add_action( 'plugins_loaded', array( $this, 'check_and_set_user' ) );
        add_filter('wp_head', array($this,'head_js'),10);
        add_filter('wp_footer', array($this,'footer_bar'),10);
        add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts'),100000 );
        add_shortcode('espsee-dashboard', array($this, 'shortcode_dashboard'));
        add_shortcode('espsee-token', array($this, 'shortcode_token'));
        
        
        // ajax
        // logged in
        add_action( 'wp_ajax_espsee', array($this,'ajax_handler') );
        // not logged in
        add_action( 'wp_ajax_nopriv_espsee', array($this,'ajax_handler') );
        
        
    }
    
    public function check_and_set_user(){
        $user=wp_get_current_user();
        if($user->ID){
            $usrObj=new WP_User($user->ID);
            $this->user=$usrObj->data;
            $this->set_user_token();
        }
    }
    
    
    public function shortcode_dashboard(){
        $data=array();
        
        $data['cameras'] = $this->get_user_cameras();
        $data['user'] = $this->user;
        
        return $this->load_view('dashboard',$data);
    }
    
    
    public function shortcode_token(){
        $data=array();
        
        $token = $this->get_user_token();
        
        $data['token']='';
        
        if($token!==false){
            $data['token']=$token->user_token;
        }else{
            $data['token']='Your EspSee.com auth token. <a href="/login/">Login</a> or <a href="/register/">register</a> to get your token.';
        }
        
        return $this->load_view('token',$data);
    }
    
    
    public function receive_heartbeat(){
        
        global $wpdb;
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if(!$data) die('Invalid request. Token or input data is invalid.');
        
        $user = $this->get_user_by_token($_GET['token']??'');
        
        if(!$user) die('Invalid request. Token or input data is invalid.');
        
        $user_id=0;
        if($user!==false){
            $user_id=$user->wp_user_id;
        }
        
        $existing = $this->get_existing_camera($data['macAddressEfuse']);
        if(!$existing){
            
            $wpdb->insert(
                $this->cameras_table,
                array(
                    'wp_user_id'=>$user_id,
                    'macAddressWiFi'=>$data['macAddressWiFi'],
                    'macAddressEfuse'=>$data['macAddressEfuse'],
                    //'extIP'=>$data['extIP'],
                    'remoteAddr'=>$_SERVER['REMOTE_ADDR'],
                    //'remotePort'=>$_SERVER['REMOTE_PORT'],
                    'created_time_unix'=>time(),
                    'updated_time_unix'=>time(),
                ),
                array(
                    '%d',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%d',
                    '%d',
                ),
            );
            
        }else{
            
            $wpdb->update(
                $this->cameras_table,
                array(
                    //'extIP'=>$data['extIP'],
                    'wp_user_id'=>$user_id,
                    'macAddressWiFi'=>$data['macAddressWiFi'],
                    'remoteAddr'=>$_SERVER['REMOTE_ADDR'],
                    //'remotePort'=>$_SERVER['REMOTE_PORT'],
                    'updated_time_unix'=>time(),
                ),
                array(
                    'macAddressEfuse'=>$data['macAddressEfuse'],
                ),
            );
            
        }
        
    }
    
    public function update_camera($id,$name,$port,$protocol){
        global $wpdb;
        
        $user_id=0;
        if($this->user!==false){
            $user_id=$this->user->ID;
        }
        
        $wpdb->update(
                $this->cameras_table,
                array(
                    'camera_name'=>$name,
                    'cameraPort'=>$port,
                    'cameraProtocol'=>$protocol,
                ),
                array(
                    'id'=>$id,
                    'wp_user_id'=>$user_id,
                ),
            ); 
        
        return $this->get_camera($id);
        
    }
    
    public function remove_camera($id){
        global $wpdb;
        
        $user_id=0;
        if($this->user!==false){
            $user_id=$this->user->ID;
        }
        
        $wpdb->delete(
                $this->cameras_table,
                array(
                    'id'=>$id,
                    'wp_user_id'=>$user_id,
                ),
            ); 
        
        return true;
        
    }
    
    public function get_camera($id){
        
        global $wpdb;
        
        return $wpdb->get_row("SELECT * FROM {$this->cameras_table} WHERE id = $id");
        
    }
    
    public function get_existing_camera($macAddressEfuse){
        
        global $wpdb;
        
        return $wpdb->get_row("SELECT * FROM {$this->cameras_table} WHERE macAddressEfuse = '$macAddressEfuse'");
        
    }
    
    public function get_user_cameras(){
        
        global $wpdb;
        
        if($this->user===false) return false;
        
        return $wpdb->get_results("SELECT * FROM {$this->cameras_table} WHERE wp_user_id = {$this->user->ID}");
        
    }
    
    public function get_user_token(){
        
        global $wpdb;
        
        if($this->user===false) return false;
        
        return $wpdb->get_row("SELECT * FROM {$this->user_tokens_table} WHERE wp_user_id = {$this->user->ID}");
        
    }
    
    public function get_user_by_token($token){
        
        global $wpdb;
        
        return $wpdb->get_row("SELECT * FROM {$this->user_tokens_table} WHERE user_token = '$token'");
        
    }
    
    
    public function set_user_token(){
        
        global $wpdb;
        
        if($this->user===false) return false;
        
        $existing = $this->get_user_token();
        if(!$existing){
            
            $token=$this->user->ID.'-'.$this->uuidV4();
            
            $wpdb->insert(
                $this->user_tokens_table,
                array(
                    'wp_user_id'=>$this->user->ID,
                    'user_token'=>$token,
                ),
                array(
                    '%d',
                    '%s',
                ),
            );
            
            return $token;
            
        }else{
            return $existing->user_token;
        }
        
    }
    
    
    public function uuidV4(){
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,
            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }
    
    
     public function enqueue_scripts(){
        

        wp_register_style('espsee-css', ((get_site_url()) . '/wp-content/plugins/EspSee/css/style.css'), array(), $this->jscss_ver);
        wp_enqueue_style('espsee-css');

        wp_enqueue_script( 'jquery-modal',((get_site_url()) . '/wp-content/plugins/EspSee/js/jquery.modal.min.js'), array('jquery'), $this->jscss_ver, TRUE );    
        wp_register_style('jquery-modal-css', ((get_site_url()) . '/wp-content/plugins/EspSee/css/jquery.modal.min.css'), array(), $this->jscss_ver);
        wp_enqueue_style('jquery-modal-css');
        
        wp_enqueue_script( 'espsee-js',((get_site_url()) . '/wp-content/plugins/EspSee/js/scripts.js'), array('jquery'), $this->jscss_ver, TRUE );    

       
        // clean login plugin fix!
        // wp_enqueue_style( 'clean-login-css', site_url().'/wp-content/plugins/clean-login/content/style.css', $this->jscss_ver );
        // wp_enqueue_style( 'clean-login-bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css', array(), $this->jscss_ver );
        
        // global $post;
        // //var_dump($post->post_title);
        // if ($post and $post->post_title!=='Contact' ) {
        //     //if ( function_exists( 'wpcf7_enqueue_scripts' ) ) {
        //         //wpcf7_enqueue_scripts();
        //         wp_deregister_script( 'google-recaptcha' );
        //     //}
        // }
    }
    
    
    function ajax_handler() {
        

        $method=$_REQUEST['method'];
        
        
        //var_dump($response);
        header('Content-Type: application/json; charset=utf-8');
        header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        
        
        if($method==='edit_camera'){
            $response=array('error'=>false,'data'=>$this->update_camera($_POST['id'],$_POST['camera_name'],$_POST['camera_port'],$_POST['protocol']));
            // }else{
            //     $response=array('error'=>true,'error_message'=>'You did not enter the camera name...');
            // }
        }
        
        if($method==='remove_camera'){
            $response=array('error'=>false,'data'=>$this->remove_camera($_POST['id']));
        }

        echo json_encode($response);
        
        wp_die();
        
    }
    
    public function head_js(){
        echo '  <script>
                    var espsee_ajax_url="'.admin_url('admin-ajax.php?action=espsee').'";
                    var espsee_site_url="'.site_url().'";
                    var is_user_logged_in='.((is_user_logged_in()==1)?'true':'false').';
                </script>';
    }
    
    
    public function footer_bar(){
        echo '  
                <!--<script src="https://storage.ko-fi.com/cdn/scripts/overlay-widget.js"></script>
                <script>
                kofiWidgetOverlay.draw("alojz", {
                    "type": "floating-chat",
                    "floating-chat.donateButton.text": "Support Us",
                    "floating-chat.donateButton.background-color": "#f45d22",
                    "floating-chat.donateButton.text-color": "#fff"
                });
                </script>-->
        
                <div id="espsee_footer" class="footer">
                    <a href="https://www.alojzjakob.com" target="_blank">
                        Made with <span style="color:#f44336;">❤</span> by Alojz
                    </a>
                    <a href="https://github.com/s60sc/ESP32-CAM_MJPEG2SD" target="_blank">
                        <i class="fa fa-github"></i> ESP32-CAM_MJPEG2SD
                    </a> by <a href="https://github.com/s60sc" target="_blank">
                        <i class="fa fa-github"></i> s60sc
                    </a>
                    
                    <!--<a href="https://www.espsee.com/contact/?your-subject=Bug+report" title="Bug report">
                        🐞
                    </a>-->
                    <a href="https://www.espsee.com/privacy-and-terms/">
                        T&C
                    </a>
                    <!--<a href="https://github.com/alojzjakob/EspSee" target="_blank">
                        <i class="fa-brands fa-github"></i>
                    </a>-->
                </div>
                ';
    }
    
    
}


$EspSee = new EspSee();



/*
 array(33) {
  ["llevel"]=>
  int(0)
  ["night"]=>
  string(2) "No"
  ["atemp"]=>
  string(4) "61.1"
  ["battv"]=>
  string(3) "n/a"
  ["showRecord"]=>
  int(0)
  ["camModel"]=>
  string(6) "OV2640"
  ["RCactive"]=>
  string(1) "0"
  ["maxSteerAngle"]=>
  string(2) "45"
  ["maxDutyCycle"]=>
  string(3) "100"
  ["minDutyCycle"]=>
  string(2) "10"
  ["allowReverse"]=>
  string(1) "1"
  ["autoControl"]=>
  string(1) "1"
  ["waitTime"]=>
  string(2) "20"
  ["sustainId"]=>
  string(1) "0"
  ["card_size"]=>
  string(5) "7.2GB"
  ["used_bytes"]=>
  string(5) "8.0MB"
  ["free_bytes"]=>
  string(5) "7.2GB"
  ["total_bytes"]=>
  string(5) "7.2GB"
  ["free_psram"]=>
  string(5) "1.4MB"
  ["progressBar"]=>
  int(0)
  ["cfgGroup"]=>
  string(2) "-1"
  ["alertMsg"]=>
  string(0) ""
  ["clockUTC"]=>
  string(10) "1717201780"
  ["clock"]=>
  string(19) "2024-06-01 01:29:40"
  ["up_time"]=>
  string(10) "0-00:43:36"
  ["free_heap"]=>
  string(4) "53KB"
  ["wifi_rssi"]=>
  string(7) "-62 dBm"
  ["fw_version"]=>
  string(5) "9.7.1"
  ["macAddressEfuse"]=>
  string(12) "44FFB77A9834"
  ["macAddressWiFi"]=>
  string(12) "44FF3FFEAF5C"
  ["extIP"]=>
  string(14) "178.237.223.93"
  ["httpPort"]=>
  string(2) "80"
  ["httpsPort"]=>
  string(3) "443"
}
 
 */
