<?php
namespace App;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Symfony\Component\HttpFoundation\Request;
class WebSocketServer implements MessageComponentInterface
{
    protected $clients;
    //protected $users;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        //$this->users = [];
    }

    /**
     * Implemntaion with David 25.7.2023 User id  take it from the Database
     */
    // public function onOpen(ConnectionInterface $conn) {
    //     // Store the new connection to send messages to later
    //     $this->clients->attach($conn);
    //     $this->users[$conn->resourceId] = ["conn"=>$conn,"uid"=>null];
    //     echo "New connection! ({$conn->resourceId})\t\n";
    // }

    // public function onMessage(ConnectionInterface $from, $msg) {
    //     // Check if the message contains the subscription action
    //     if (str_contains($msg, 'action:subscribe:')) {
    //         // Extract the user identifier from the message
    //         $uid = substr($msg, 17);
    //         // Set the user identifier for the current connection
    //         $this->users[$from->resourceId]['uid'] = $uid;
    //     }

    
    //     // Calculate the number of other connections (excluding the current one)
    //     $numRecv = count($this->clients) - 1;
    //     echo sprintf('Conecation %d sending message "%s" to %d other connection%s' . "\n"
    //         , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
    //     // Loop through all connected clients and send the message to each client
    //     foreach ($this->clients as $client) {
    //         if ($from !== $client) {
    //             // Get the user information for the current client
    //             $user = $this->users[$client->resourceId];
    //             // The sender is not the receiver, send the message to each client connected
    //             $client->send($msg);
    //         }
    //     }
    // }
    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
