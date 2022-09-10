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
  $(function () {
    console.log("test");

    $.ajax({
      method: "post",
      url: init_cpanel_connecions.ajaxurl,
      dataType: "json",
      data: {
        action: "init_cpanel_connecions",
      },
      success: (response) => {
        var username = response.username;
        var password = response.password;
        var url = response.url;
        var services = response.services;
        var cdata = {
          username: username,
          password: password,
          service: services,
          moodlewsrestformat: "json",
        };

        var mdata = $.ajax({
          type: "GET",
          data: cdata,
          url: url + "/login/token.php",
        });
        mdata.then((res) => {
          //======> Returns data in JSON format

          $("#token").val(res["token"]);

          $.ajax({
            url: update_cptoken.ajaxurl,
            data: {
              action: "update_cptoken",
              ress: res,
            },
          });
          
        });
      },
    });
  });

  $(function () {
    console.log("");

    $.ajax({
      method: "post",
      url: get_moodle_courses.ajaxurl,
      dataType: "json",
      data: {
        action: "get_moodle_courses",
      },
    });

    $.ajax({
      method: "post",
      url: get_moodle_allitems.ajaxurl,
      timeout: 86400,
      dataType: "json",
      data: {
        action: "get_moodle_allitems",
      },
    });


  });

  

  get_moodle_allitems

  $(document).ready(function () {
    $(document)
      .find("#test_connection_button")
      .on("click", function () {
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
          url: init_cpanel_connecions.ajaxurl,
          dataType: "json",
          data: {
            action: "init_cpanel_connecions",
            username: username,
            password: password,
            url: url,
            services: services,
          },
          success: (response) => {
            $("#test_connection_response").html("Your Token is: ");
            $("#test_connection_response").append(response.data);
            console.log(response.data);
          },
        });
      });
  });
})(jQuery);
