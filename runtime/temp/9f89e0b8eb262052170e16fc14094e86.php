<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:78:"/data/wwwroot/thinkSwoole/public/../application/index/view/websocket_test.html";i:1509814855;}*/ ?>
<script type="text/javascript">
var  websocket = new WebSocket('ws://119.29.208.229:10000'); 
console.log(websocket);
websocket.onopen = function (evt) { onOpen(evt) }; 
websocket.onclose = function (evt) { onClose(evt) }; 
websocket.onmessage = function (evt) { onMessage(evt) }; 
websocket.onerror = function (evt) { onError(evt) }; 
 
function onOpen(evt) {
	console.log("Connected to WebSocket server."); 
	onMessage("asdas");
} 
function onClose(evt) { 
	console.log("Disconnected"); 
}
function onMessage(evt) { 
	console.log(evt)
	console.log('Retrieved data from server: ' + evt.data); 
} 
function onError(evt) { 
	console.log('Error occured: ' + evt.data); 
}
</script>
