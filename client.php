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
	/**
	 * This file is part of the Elephant.io package
	 *
	 * For the full copyright and license information, please view the LICENSE file
	 * that was distributed with this source code.
	 *
	 * @copyright Wisembly
	 * @license   http://www.opensource.org/licenses/MIT-License MIT License
	 */

	use ElephantIO\Client,
	    ElephantIO\Engine\SocketIO\Version1X;

	require __DIR__ . '/vendor/autoload.php';

	$client = new Client(new Version1X('http://192.168.10.10:9900'));

	$client->initialize();

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$chat['destination'] = $_POST['destination'];
		$chat['msg'] = $_POST['msg'];
    $chat['browser'] = 'Browser Name';
		//$client->emit('chat',$chat);
    $client->emit('chat message',$chat);
	}

	$client->close();

	?>

    <ul id="messages"></ul>
    <form action="<?php $_SERVER["PHP_SELF"] ?>" method="POST">
      <input name="destination" autocomplete="off" placeholder="destination" />
      <input name="msg" autocomplete="off" placeholder="your message" />
      <button>Send</button>
    </form>


  </body>
</html>