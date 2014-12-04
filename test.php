<?php
	use ElephantIO\Client as Elephant;

	$elephant = new Elephant('http://localhost:1200', 'socket.io', 1, false, true, true);

	$elephant->init();
	$elephant->send(
	    ElephantIOClient::TYPE_EVENT,
	    null,
	    null,
	    json_encode(array('name' => 'foo', 'args' => 'bar'))
	);
	$elephant->close();

	echo 'tryin to send `bar` to the event `foo`';
?>