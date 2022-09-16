(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
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
		console.log("teddddddddddddddddst");
	
		$.ajax({
		  method: "post",
		  url: add_user_to_cpnel.ajaxurl,
		  dataType: "json",
		  data: {
			action: "add_user_to_cpnel",
		  },
		});
		$.ajax({
			method: "post",
			url: enroll_user_to_cpanel.ajaxurl,
			dataType: "json",
			data: {
			  action: "enroll_user_to_cpanel",
			},
		  });
		 
		  $.ajax({
			method: "post",
			url: display_bigbluebuttonbn_meeting.ajaxurl,
			dataType: "json",
			data: {
			  action: "display_bigbluebuttonbn_meeting",
			},
		  });
	
	  });
	  function getMobileOperatingSystem() {
		var userAgent = navigator.userAgent || navigator.vendor || window.opera;
	
		// Windows Phone must come first because its UA also contains "Android"
		if (/windows phone/i.test(userAgent)) {
			return "Windows Phone";
		}
	
		if (/android/i.test(userAgent)) {
			return "Android";
		}
	
		// iOS detection from: http://stackoverflow.com/a/9039885/177710
		if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
			return "iOS";
		}
	
		return "unknown";
	} 
	  $(document).ready(function () {
		$(document)
		function getOS() {
			var userAgent = window.navigator.userAgent,
				platform = window.navigator?.userAgentData?.platform || window.navigator.platform,
				macosPlatforms = ['Macintosh', 'MacIntel', 'MacPPC', 'Mac68K'],
				windowsPlatforms = ['Win32', 'Win64', 'Windows', 'WinCE'],
				iosPlatforms = ['iPhone', 'iPad', 'iPod'],
				os = null;
		  
			if (macosPlatforms.indexOf(platform) !== -1) {
			  os = 'Mac OS';
			} else if (iosPlatforms.indexOf(platform) !== -1) {
			  os = 'iOS';
			} else if (windowsPlatforms.indexOf(platform) !== -1) {
			  os = 'Windows';
			} else if (/Android/.test(userAgent)) {
			  os = 'Android';
			} else if (/Linux/.test(platform)) {
			  os = 'Linux';
			}
		  
			return os;
		  }
		
		var os = getOS();
		var myvideo = document.getElementById("va");
		var myaudio = document.getElementById("vb");
		var change_time_state = true;
		myaudio.preload = 'auto';
		  /**
		mediaController = new MediaController();
		myvideo.controller = mediaController;
		myaudio.controller = mediaController;
		mediaController.play();
*/
		myvideo.addEventListener( 'play', () => {
			myaudio.play();
			//setTimeout(checkStarted, 500);
		});

		myvideo.addEventListener( 'canplay', () => {
			myaudio.play();
			//setTimeout(checkStarted, 500);
		});

		myvideo.addEventListener( 'pause', () => {
			myaudio.pause();
		});
		myvideo.addEventListener( 'seeking', () => {
			myvideo.play();
			myaudio.play();
			myaudio.currentTime = myvideo.currentTime;
			
		});


		/*myvideo.onplay  = function() { 
			myaudio.play();  
			if(change_time_state){
				myaudio.currentTime = myvideo.currentTime;
				change_time_state = false;
			}
		}
		myvideo.onpause = function() { myaudio.pause();
			myaudio.pause();
        	change_time_state = true; 
		}
		myvideo.ontimeupdate = function() { 
			myaudio.currentTime = myvideo.currentTime - 2 ;
			change_time_state = false;
		}*/


		//myvideo.ontimeupdate = function() { myaudio.currentTime = myvideo.currentTime}
		//var vcurrentTime = myvideo.currentTime + seconds ;
		/*if(os !== 'iOS') {
			
			
			myvideo.ontimeupdate = function() { myaudio.currentTime = myvideo.currentTime}
		} else {
			myvideo.autoplay = true;
			myvideo.onplay = (event) => {
				myaudio.play();
			}
			myvideo.onpause = (event) => {
				myaudio.puse();
			}

		}**/
			
	  });
})( jQuery );
