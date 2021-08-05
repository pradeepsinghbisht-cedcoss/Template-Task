jQuery(document).ready(function(){

	var fb_app_id = wp_cbb_script_obj.fb_app_id;
	window.fbAsyncInit = function() {
		FB.init({
			appId            : fb_app_id,
		  	autoLogAppEvents : true,
		  	xfbml            : true,
	      	version          : 'v9.0'
		});
	};

	(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
});