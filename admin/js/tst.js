(function ($) {
    "use strict";
  
    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */
    $(document).ready(function ($) {
        
        console.log("test");
  
        $.ajax({
          method: "post",
          url: init_cpanel_connecions.ajaxurl,
          dataType: "json",
          data: {
            action: "init_cpanel_connecions",
          },
          success: (response) => {
  
            console.log(response);
          },
          
        });
        var usrename = init_cpanel_connecions.cpusername;
        var password = "<?php echo get_option('cppassword');?>";
        var service = "<?php echo get_option('cpservice');?>";
        var url = "<?php echo get_option('cpurl');?>";
        console.log(usrename);
          var data = {
            username: usrename,
            password: password,
            service: service,
            moodlewsrestformat: 'json',
          
          }
             
        var mdata = $.ajax(
          {   type: 'GET',
              data: data,
              url: url+"/login/token.php"
          }
       );
  mdata.then(res=>{  //======> Returns data in JSON format
  console.log(res)    
  })
     
    });
  
    $(document).ready(function () {
      $(document)
        .find("#test_connection_button")
        .on("click", function () {
          console.log("test");
  
          var username = $("#cpusername").val();
          var password = $("#cppassword").val();
          var url = $("#cpurl").val();
          var services = $("#cpservice ").val();
          var htmlS = "<span>Success</span>";
          var htmle = "<span>Fail</span>";
          console.log(url);
          console.log(username);
          console.log(password);
          console.log(services);
          $.ajax({
            method: "post",
            url: test_connection.ajaxurl,
            dataType: "json",
            data: {
              action: "test_connection",
              username: username,
              password: password,
              url: url,
              services: services,
            },
            success: (response) => {
              $("#test_connection_response").html("Your Token is: ");
              $("#test_connection_response").append(response.success);
              console.log(response);
            },
          });
        });
    });
  function modata(){
    var domainname = 'http://localhost:5000';
  var token = 'cc30ab5ad466ac5c375fcfc063f2ae4a';
  var functionname = 'core_course_get_courses';
  var serverurl = domainname + '/webservice/rest/server.php' ;
  
  var data = {
    wstoken: token,
    wsfunction: functionname,
    moodlewsrestformat: 'json',
  
  }
  var mdata = $.ajax(
                        {   type: 'GET',
                            data: data,
                            url: serverurl
                        }
                     );
  console.log(mdata);
  mdata.then(res=>{  //======> Returns data in JSON format
    console.log(res)    
  })
  }
  $(document).ready(function ($){
    var domainname = 'http://localhost:5000';
  var token = 'cc30ab5ad466ac5c375fcfc063f2ae4a';
  var functionname = 'core_course_get_courses';
  var serverurl = domainname + '/webservice/rest/server.php' ;
  
  var data = {
    wstoken: token,
    wsfunction: functionname,
    moodlewsrestformat: 'json',
  
  }
  var mdata = $.ajax(
                        {   type: 'GET',
                            data: data,
                            url: serverurl
                        }
                     );
  mdata.then(res=>{  //======> Returns data in JSON format
    var resp =  $.ajax({
      url: init_cpanel_connecions.ajaxurl,
      data: {
        action: "init_cpanel_connecions",
        ress: res
      },
      success: function(data){
        console.log('happay')
      }
  
    });
  })
  
    /**console.log(res);
    res.then(res=>{  //======> Returns data in JSON format
      console.log(res)    
    })*/
  });
  
  })(jQuery);
  