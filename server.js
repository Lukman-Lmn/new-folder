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

    // Get socket.id from client
    socket.on('giveUserComputerData', function(user_browser_user_agent){
        user_data[socket.id] = user_browser_user_agent;

        //Send socket.id to client its self
        io.to(socket.id).emit('giveUserHisBrowserAgent', 'You are ' + user_browser_user_agent + ' (' + socket.id + ')', socket.id);
    });

    // Get chat data from client
    socket.on('chat message', function(chat){
        console.log(chat.destination + ' : ' + chat.msg);

        //this is for set timeout
        setTimeout(function() {
          // Send data to spesific client & sender it self
          io.to(chat.destination).emit('chat message', chat.msg);
          io.to(socket.id).emit('chat message', chat.msg);
        }, 0);
    });

    socket.on('disconnect', function () {
        logger.info('SocketIO > Disconnected socket ' + socket.id);
    });
});

server.listen(port);

