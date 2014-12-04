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

var users_connected_user_agents = [];

io.on('connection', function (socket){
    console.log('user connected, the ID is ' + socket.id);

    // Get data from CLIENT and send back data to ONLY that client
    // And then SEND data to ONLY one CLIENT
    socket.on('giveUserComputerData FROM CLIENT', function(user_browser_user_agent) {

        // Storing the user data to the array, the key is the user's session
        users_connected_user_agents[socket.id] = user_browser_user_agent;
        // DEBUG
        console.log('DEBUG, current array content is: '.underline.red); 
        console.log(users_connected_user_agents); 

        console.log('user connected! ' + ' the session ID is: ' + socket.id + ' the user browser is ' + user_browser_user_agent);
        // SEND data to ONLY one CLIENT
        io.to(socket.id).emit('giveUserHisBrowserAgent FROM SERVER', user_browser_user_agent, socket.id);
    });

    socket.on('message FROM CLIENT',function(data){
    	console.log(socket.id + ' is sending a message to ' + data.userIDdestination + ' , the message is: ' + data.theMessage);
        //io.to(destination).emit('chat', user_browser + ' : ' + msg);
        //io.to(socket.id).emit('chat', user_browser + ' : ' + msg);
        io.emit('message FROM SERVER', data.userIDdestination, data.theMessage);
    });

    // User disconnected
    socket.on('disconnect', function(){
        console.log('user with ID of ' + socket.id + ' is disconnected, his browser is ' + users_connected_user_agents[socket.id]);
        // Send data to ALL CLIENT
        io.emit('user disconnected FROM SERVER', socket.id, users_connected_user_agents[socket.id]);
        delete users_connected_user_agents[socket.id];
        // DEBUG
        console.log('DEBUG, current array content is: '.underline.red); 
        console.log(users_connected_user_agents);
    });

    socket.on('message FROM SERVER', function(user_browser_user_agent, socketID){
        $('#messages').append($('<li>').text('You are using ' + user_browser_user_agent + ' , your ID is ' + socketID));
    });

});