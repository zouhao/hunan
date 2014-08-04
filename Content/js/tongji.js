var access_time=0;
function tongji(send_url){
	setInterval("timer()",1000);
	window.onbeforeunload = function(event) {
		$.get(send_url,{url:location.href,access_time:access_time});
	}
}
function timer(){
	++access_time;
}