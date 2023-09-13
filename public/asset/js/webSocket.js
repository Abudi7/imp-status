  // Establish a WebSocket connection
  const socket = new WebSocket('ws://localhost:8000'); // Replace the URL with your WebSocket server URL

  socket.addEventListener('open', (event) => {
     console.log('WebSocket connection established.');
  });

  // Listen for incoming messages from the WebSocket server
  socket.addEventListener('message', (event) => {
     const message = event.data;

     // Display a Chrome browser notification with sound
     if (Notification.permission === 'granted') {
        showNotification('New Message', message);
     } else if (Notification.permission !== 'denied') {
        Notification.requestPermission().then((permission) => {
           if (permission === 'granted') {
              showNotification('IMP', message);
           }
        });
     }

     console.log('Received message:', message);
  });

  // Function to send a message via the WebSocket connection
  function sendMessage(message) {
     socket.send(message);
  }

  // Function to display a Chrome browser notification with sound
  function showNotification(title, body) {
     const options = {
        body: body,
        icon: '{{ asset(img/ favicon.png) }}', // Replace with the path to your notification icon
           sound: '{{ asset(asset / sound / notification.mp3) }}', // Replace with the path to your notification sound
       };
  new Notification(title, options);
   }