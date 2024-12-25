function lbryGetCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

String.prototype.stripSlashes = function(){
    return this.replace(/\\(.)/mg, "$1");
}

jQuery.noConflict();
jQuery( document ).ready(function( $ ) {
  // Code that uses jQuery's $ can follow here.
  
  // LIGHT DARK THEME SWITCH
  $(function(){
      //https://github.com/ForEvolve/bootstrap-dark
      $(".lbry-light-dark").each(function(){
          $(this).on('click',function(e){
              e.preventDefault();
              
              if(lbryGetCookie('lbry_dark')==1){
                  document.cookie = "lbry_dark=0; path=/";
                  $("body").removeClass('dark');
                  //$("body").removeClass('bootstrap-dark');
              }else{
                  document.cookie = "lbry_dark=1; path=/";
                  $("body").addClass('dark');
                  //$("body").addClass('bootstrap-dark');
              }
              
              
              
          });
      });
          
  });

  

  
  
});


//https://github.com/kylefox/jquery-modal





function edit_camera_handler(){
  jQuery( document ).ready(function( $ ) {
    $("#edit_camera_form").on('submit',function(e){
      e.preventDefault();
      var form_data=$("#edit_camera_form").serialize();
      var camera_id=$("#edit_camera_form").data('camera_id');
      $.ajax({
                    type: "POST",
                    url: espsee_ajax_url+'&method=edit_camera',
                    data: form_data,
                    success: function(response){
                      if(!response.error){
                        
                        var camera_name='';
                        
                        var camera_address=response.data.cameraProtocol+response.data.remoteAddr+':'+response.data.cameraPort;
                        
                        if(response.data.camera_name!==''){
                          camera_name=response.data.camera_name;
                        }
                        
                        var unix_timestamp = Math.round(Date.now()/1000);
                        
                        //console.log(unix_timestamp);
                        
                        var camera_active='inactive';
                        
                        if(response.data.updated_time_unix>(unix_timestamp-60)){
                          camera_active='active';
                        }
                        
                        var camera_iframe_link=`<a class="espsee_action_link disabled" href="#" title="Can't open HTTP camera in HTTPS website iframe. Use external link.">
                                                    <i class="fa-regular fa-eye-slash"></i></a>`;
                        
                        if(response.data.cameraProtocol==='https://'){
                            
                            camera_iframe_link=`<a class="espsee_action_link" href="${espsee_site_url}/wp-content/plugins/EspSee/views/modal/camera_view.php?id=${response.data.id}" rel="modal:open">
                                                    <i class="fa-regular fa-eye"></i></a>`;
                        }
                        
                        var new_html=`<div id="camera_${response.data.id}" class="camera_item ${camera_active}">
                                        
                                        <div class="espsee_actions">
                                        
                                          ${camera_iframe_link}
                                        
                                          <a class="espsee_action_link" href="${camera_address}" target="_blank">
                                              <i class="fa-solid fa-arrow-up-right-from-square"></i></a>
                                        
                                          <a class="espsee_action_link" href="${espsee_site_url}/wp-content/plugins/EspSee/views/modal/camera_info.php?id=${response.data.id}" rel="modal:open">
                                              <i class="fa-solid fa-circle-info"></i></a>
                                          <a class="espsee_action_link" href="${espsee_site_url}/wp-content/plugins/EspSee/views/modal/camera_edit.php?id=${response.data.id}" rel="modal:open">
                                              <i class="fa-solid fa-pencil"></i></a>
                                          <a class="espsee_action_link red" href="${espsee_site_url}/wp-content/plugins/EspSee/views/modal/camera_remove.php?id=${response.data.id}" rel="modal:open">
                                              <i class="fa-solid fa-trash"></i></a>
                                        </div>
                                        
                                        <div class="camera_name">
                                          ${camera_name}
                                        </div>
                                        
                                        <a href="${camera_address}" target="_blank">
                                          ${camera_address}
                                        </a>
                                        
                                    </div>
                                  `;
                        
                        //var new_html=``;
                        
                        $("#camera_"+camera_id).fadeOut(200,function(){
                          $("#camera_"+camera_id).replaceWith(new_html);
                          $("#camera_"+response.data.id).fadeIn();
                        });
                        
                        $.modal.close();
                      }else{
                        $("#error_message").html(response.error_message).fadeIn();
                      }
                    },
                  });
      return false;
    });
  });
}


function remove_camera_handler(){
  jQuery( document ).ready(function( $ ) {
    $("#remove_camera_form").on('submit',function(e){
      e.preventDefault();
      var form_data=$("#remove_camera_form").serialize();
      var camera_id=$("#remove_camera_form").data('camera_id');
      $.ajax({
                    type: "POST",
                    url: espsee_ajax_url+'&method=remove_camera',
                    data: form_data,
                    success: function(response){
                      $.modal.close();
                      $("#camera_"+camera_id).fadeOut(function(){
                        $(this).remove();
                      });
                    },
                  });
      return false;
    });
  });
}








