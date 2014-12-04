<!doctype html>
<html>
  <head>
    <title>Socket.IO chat</title>
    <style>
      * { margin: 0; padding: 0; box-sizing: border-box; }
      body { font: 13px Helvetica, Arial; }
      form { background: #000; padding: 3px; position: fixed; bottom: 0; width: 100%; }
      form input { border: 0; padding: 10px; width: 43%; margin-right: 1%; }
      form button { width: 9%; background: rgb(130, 224, 255); border: none; padding: 10px; }
      #messages { list-style-type: none; margin: 0; padding: 0; }
      #messages li { padding: 5px 10px; }
      #messages li:nth-child(odd) { background: #eee; }
    </style>
  </head>
  <body>
  
	<?php
	use ElephantIO\Client,
	    ElephantIO\Engine\SocketIO\Version1X;

	require __DIR__ . '/vendor/autoload.php';

	$client = new Client(new Version1X('http://192.168.10.10:9900'));

	$client->initialize();

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$data['userIDDestination'] = $_POST['userIDDestination'];
		$data['theMessage'] = $_POST['theMessage'];
		$client->emit('message FROM CLIENT', $data);
	}

	$client->close();
	?>

    <ul id="messages"></ul>

    <form id="form1" action="client.php" method="POST">
      <input id="userIDDestination" name="userIDDestination" autocomplete="off" placeholder="The Destination" />
      <input id="theMessage" name="theMessage" autocomplete="off" placeholder="The message" />
      <button>Send</button>
    </form>

    <script src="http://code.jquery.com/jquery-1.11.1.js"></script>
    <script>
      ////////////////////////// Script to detect User's Browser //////////////////////////
      // check browser name
      var nVer = navigator.appVersion;
      var nAgt = navigator.userAgent;
      var browserName  = navigator.appName;
      var fullVersion  = ''+parseFloat(navigator.appVersion); 
      var majorVersion = parseInt(navigator.appVersion,10);
      var nameOffset,verOffset,ix;

      // In Opera 15+, the true version is after "OPR/" 
      if ((verOffset=nAgt.indexOf("OPR/"))!=-1) {
       browserName = "Opera";
       fullVersion = nAgt.substring(verOffset+4);
      }
      // In older Opera, the true version is after "Opera" or after "Version"
      else if ((verOffset=nAgt.indexOf("Opera"))!=-1) {
       browserName = "Opera";
       fullVersion = nAgt.substring(verOffset+6);
       if ((verOffset=nAgt.indexOf("Version"))!=-1) 
         fullVersion = nAgt.substring(verOffset+8);
      }
      // In MSIE, the true version is after "MSIE" in userAgent
      else if ((verOffset=nAgt.indexOf("MSIE"))!=-1) {
       browserName = "Microsoft Internet Explorer";
       fullVersion = nAgt.substring(verOffset+5);
      }
      // In Chrome, the true version is after "Chrome" 
      else if ((verOffset=nAgt.indexOf("Chrome"))!=-1) {
       browserName = "Chrome";
       fullVersion = nAgt.substring(verOffset+7);
      }
      // In Safari, the true version is after "Safari" or after "Version" 
      else if ((verOffset=nAgt.indexOf("Safari"))!=-1) {
       browserName = "Safari";
       fullVersion = nAgt.substring(verOffset+7);
       if ((verOffset=nAgt.indexOf("Version"))!=-1) 
         fullVersion = nAgt.substring(verOffset+8);
      }
      // In Firefox, the true version is after "Firefox" 
      else if ((verOffset=nAgt.indexOf("Firefox"))!=-1) {
       browserName = "Firefox";
       fullVersion = nAgt.substring(verOffset+8);
      }
      // In most other browsers, "name/version" is at the end of userAgent 
      else if ( (nameOffset=nAgt.lastIndexOf(' ')+1) < 
                (verOffset=nAgt.lastIndexOf('/')) ) 
      {
       browserName = nAgt.substring(nameOffset,verOffset);
       fullVersion = nAgt.substring(verOffset+1);
       if (browserName.toLowerCase()==browserName.toUpperCase()) {
        browserName = navigator.appName;
       }
      }
      // trim the fullVersion string at semicolon/space if present
      if ((ix=fullVersion.indexOf(";"))!=-1)
         fullVersion=fullVersion.substring(0,ix);
      if ((ix=fullVersion.indexOf(" "))!=-1)
         fullVersion=fullVersion.substring(0,ix);

      majorVersion = parseInt(''+fullVersion,10);
      if (isNaN(majorVersion)) {
       fullVersion  = ''+parseFloat(navigator.appVersion); 
       majorVersion = parseInt(navigator.appVersion,10);
      }
      ////////////////////////// END of Script to detect User's Browser //////////////////////////

      // Get the User's browser
      var user_browser_user_agent = browserName;
      // Give the Server the user's browser agent
      socket.emit('giveUserComputerData FROM CLIENT', user_browser_user_agent);

      // Sent form data to php page
      $('#form1').submit(function() {
      	var jsonObject = {
      		'userIDdestination': $('#userIDDestination').val(),
      		'theMessage': $('#theMessage').val()
      	};
        socket.emit('message FROM CLIENT', jsonObject );
        $('#theMessage').val('');
        return false;
      });

      // Listen to server
      socket.on('giveUserHisBrowserAgent FROM SERVER', function(user_browser_user_agent, socketID){
        $('#messages').append($('<li>').text('You are using ' + user_browser_user_agent + ' , your ID is ' + socketID));
      });

      // Listen to server
      socket.on('message FROM SERVER', function(user_browser_user_agent, socketID){
        $('#messages').append($('<li>').text('You are using ' + user_browser_user_agent + ' , your ID is ' + socketID));
      });

      // Listen to server
      socket.on('user disconnected FROM SERVER', function(userSessionID, userBrowserAgent){
        $('#messages').append($('<li>').text('User with ID of ' + userSessionID + ' and browser agent of ' + userBrowserAgent + ' just left.'));
      });


    </script>

  </body>
</html>