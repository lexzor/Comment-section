const WebSocket = require("ws");

let clients=[];
let clientsCount = 0;

const wss = new WebSocket.Server({ port: 8082 });

wss.on("connection", ws => {
    clientsCount++;
    clients.push(ws);

    console.log("Client [ " + clientsCount + " ] connected");
    
    ws.on('message', function(message) {
        clients.forEach((client) => { client.send(JSON.stringify(JSON.parse(message))); console.log(client)});
    });
});