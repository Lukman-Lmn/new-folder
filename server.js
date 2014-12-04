// Default Modules from nodeJS, etc
var http  = require('http');
var colors = require('colors');
// My variables
var port = 9900;

//this is connection server listen on port
var server = http.createServer(function(request, response){
  
}).listen(port, function(){
  console.log('Server NodeJS for Client.PHP created. listening on port *:' + port);
});

// Make the server into Socket IO enabled
var io = require('socket.io').listen(server);

io.on('connection', function (socket){
    console.log('user connected, the ID is ' + socket.id);

    socket.on('chat',function(data){
    	console.log(data.msg);
        //io.to(destination).emit('chat', user_browser + ' : ' + msg);
        //io.to(socket.id).emit('chat', user_browser + ' : ' + msg);
        io.emit('msg_client',data.msg);
    });

    socket.on('disconnect', function () {
        console.log('User disconnected, the ID is ' + socket.id);
    });
});