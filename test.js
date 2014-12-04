var io = require('socket.io').listen(1200);

io.sockets.on('connection', function (socket) {
  console.log('user connected!');

  socket.on('foo', function (data) {
    console.log('here we are in action event and data is: ' + data);
  });
});