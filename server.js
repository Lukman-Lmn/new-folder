var server     = require('http').createServer(),
    io         = require('socket.io')(server),
    logger     = require('winston'),
    port       = 9900;

// Logger config
logger.remove(logger.transports.Console);
logger.add(logger.transports.Console, { colorize: true, timestamp: true });
logger.info('SocketIO > listening on port ' + port);

var user_data = [];

io.on('connection', function (socket){
    logger.info('SocketIO > Connected socket ' + socket.id);

    // Get data from CLIENT
    socket.on('chat message', function(chat){
        console.log(chat.destination + ' : ' + chat.msg);

        //this is for 
        setTimeout(function() {
          // Send data to ALL CLIENT
          io.to(chat.destination).emit('chat message', chat.msg);
          io.to(socket.id).emit('chat message', chat.msg);
          //io.emit('chat message', chat.destination + ' : ' + chat.msg);
        }, 0);
    });

    socket.on('chat',function(data){
    	console.log(data);
        //io.to(destination).emit('chat', user_browser + ' : ' + msg);
        //io.to(socket.id).emit('chat', user_browser + ' : ' + msg);
        io.emit('msg_client',data.msg);
    });

    socket.on('disconnect', function () {
        logger.info('SocketIO > Disconnected socket ' + socket.id);
    });
});

server.listen(port);

