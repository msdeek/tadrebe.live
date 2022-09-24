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
	    var myvideo = document.getElementById("vabb");
		var myaudio = document.getElementById("vbbb");
		const wrapperPlayer = document.querySelector('.td-video-wrapper')
		const fullscreen = document.querySelector('.fullscreen')
		const playPause = document.querySelector('.play_pause')
		const play = document.querySelector('.play')
		const pause = document.querySelector('.pause')
		const rangeProgress = document.querySelector('.range_progress')
		const volume = document.querySelector('.volume')
		const halfVolume = document.querySelector('.half_volume')
		const mute = document.querySelector('.mute')
		const rangeVolume = document.querySelector('.range_volume')
		const zoomIn = document.querySelector('.zoom_in')
		const zoomOut = document.querySelector('.zoom_out')
		const current = document.querySelector('.current')
		const duration = document.querySelector('.duration')
		const timeHover = document.querySelector('.time_hover')

		myvideo.addEventListener('timeupdate', ()=>{
			const  VP = (myvideo.currentTime / myvideo.duration) * 100
			rangeProgress.style.setProperty('--seek-before-width', `${VP}%`);
			rangeProgress.value = VP
			current.textContent =  convertTime(Math.round(myvideo.currentTime))
			duration.textContent =  convertTime(Math.round(myvideo.duration))
		})

		rangeProgress.addEventListener('input', ()=>{
			myvideo.currentTime = (myvideo.duration / 100 ) * rangeProgress.value
			//myaudio.currentTime = (myvideo.duration / 100 ) * rangeProgress.value
			isPlay()
	   })

	
	   rangeProgress.addEventListener('mousemove', (e)=>{
		let time =  (myvideo.duration / 100 ) * (e.offsetX / e.target.clientWidth) *  parseInt(e.target.getAttribute('max'), 10).toFixed(2)
		timeHover.style.display= 'inline'
		timeHover.textContent = convertTime(Math.round(time))
		timeHover.style.left = (e.offsetX / e.target.clientWidth) * 100 + '%'
	})

	myvideo.addEventListener( 'seeking', () => {
		myvideo.play();
		myaudio.play();
		myaudio.currentTime = myvideo.currentTime;
		
	});
	
	rangeProgress.addEventListener('mouseout', (e)=>{
		timeHover.style.display= 'none'
	})

	function isPlay(){
		pause.classList.add('active')
		play.classList.remove('active')
		myvideo.play()
		myaudio.play()
		playPause.setAttribute('data-play', 'true')
	}

	playPause.addEventListener('click', (e)=>{
		let isPlay = playPause.getAttribute('data-play')
		if(isPlay === 'true'){
			play.classList.add('active')
			pause.classList.remove('active')
			playPause.setAttribute('data-play', 'false')
			myvideo.pause()
			myaudio.pause()
		} else{
			pause.classList.add('active')
			play.classList.remove('active')
			playPause.setAttribute('data-play', 'true')
			myvideo.play()
			myaudio.play()
		}
	})

	play.addEventListener('click', ()=>{
	
			myvideo.play()
			myaudio.play()
		
	})

	pause.addEventListener('click', (e)=>{
		myvideo.pause()
			myaudio.pause()
	})

	const convertTime = (seconds) => {
		var min = Math.floor(seconds / 60);
		var sec = seconds % 60;
		min = (min < 10) ? "0" + min : min;
		sec = (sec < 10) ? "0" + sec : sec;
		return  min + ":" + sec
	}

	
	function isMute(e){
		let isMute = e.getAttribute('data-mute')
		if(isMute === 'true'){
			e.setAttribute('data-mute', 'false')
			volume.classList.add('active')
			halfVolume.classList.remove('active')
			mute.classList.remove('active')
			myaudio.setAttribute('muted', '')
			myaudio.volume = rangeVolume.value
		}else{
			e.setAttribute('data-mute', 'true')
			volume.classList.remove('active')
			halfVolume.classList.remove('active')
			mute.classList.add('active')
			myaudio.volume = 0
		}
	}

	volume.addEventListener('click', ()=>{
		let isMute = volume.getAttribute('data-mute')
		if(isMute === 'true'){
			volume.setAttribute('data-mute', 'false')
			volume.classList.add('active')
			halfVolume.classList.remove('active')
			mute.classList.remove('active')
			myaudio.setAttribute('muted', '')
			myaudio.volume = rangeVolume.value
		}else{
			volume.setAttribute('data-mute', 'true')
			volume.classList.remove('active')
			halfVolume.classList.remove('active')
			mute.classList.add('active')
			myaudio.volume = 0
		}

	})

	mute.addEventListener('click', ()=>{
		let isMute = mute.getAttribute('data-mute')
		if(isMute === 'true'){
			mute.setAttribute('data-mute', 'false')
			volume.classList.add('active')
			halfVolume.classList.remove('active')
			mute.classList.remove('active')
			myaudio.setAttribute('muted', '')
			myaudio.volume = rangeVolume.value
		}else{
			mute.setAttribute('data-mute', 'true')
			volume.classList.remove('active')
			halfVolume.classList.remove('active')
			mute.classList.add('active')
			myaudio.volume = 0
		}

	})

	rangeVolume.addEventListener('click', ()=>{
		let isMute = rangeVolume.getAttribute('data-mute')
		if(isMute === 'true'){
			rangeVolume.setAttribute('data-mute', 'false')
			volume.classList.add('active')
			halfVolume.classList.remove('active')
			mute.classList.remove('active')
			myaudio.setAttribute('muted', '')
			myaudio.volume = rangeVolume.value
		}
	})

	zoomIn.addEventListener('click', ()=>{
		let zoom = fullscreen.getAttribute('data-zoom')
    if(zoom === 'true'){
        zoomIn.classList.add('active')
        zoomOut.classList.remove('active')
        fullscreen.setAttribute('data-zoom', 'false')
        document.exitFullscreen()
    } else{
        zoomIn.classList.remove('active')
        zoomOut.classList.add('active')
        wrapperPlayer.requestFullscreen()
        fullscreen.setAttribute('data-zoom', 'true')
    }
	})

	zoomOut.addEventListener('click', ()=>{
		let zoom = fullscreen.getAttribute('data-zoom')
    if(zoom === 'true'){
        zoomIn.classList.add('active')
        zoomOut.classList.remove('active')
        fullscreen.setAttribute('data-zoom', 'false')
        document.exitFullscreen()
    } else{
        zoomIn.classList.remove('active')
        zoomOut.classList.add('active')
        wrapperPlayer.requestFullscreen()
        fullscreen.setAttribute('data-zoom', 'true')
    }
	})
});
	$(document).ready(function () {
		$(document)
		var myvideoa = document.getElementById("va");
		var myaudiob = document.getElementById("vb");
		myaudiob.preload = 'auto';
		

		myvideoa.addEventListener( 'play', () => {
			myaudiob.play();
			//setTimeout(checkStarted, 500);
		});

		myvideoa.addEventListener( 'canplay', () => {
			myaudiob.play();
			//setTimeout(checkStarted, 500);
		});

		myvideoa.addEventListener( 'pause', () => {
			myaudiob.pause();
		});
		myvideoa.addEventListener( 'seeking', () => {
			myvideoa.play();
			myaudiob.play();
			myaudiob.currentTime = myvideoa.currentTime;
			
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
