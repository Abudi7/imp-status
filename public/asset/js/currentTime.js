// Update the current time element with the client's timezone
var currentTimeElement = document.getElementById("client-time");
var clientTimezone;

// Function to get the client's timezone based on their location
function getClientTimezone() {
if (typeof Intl === 'object' && typeof Intl.DateTimeFormat === 'function') {
    clientTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
} else {
    // Fallback to a default timezone if the Intl API is not supported
    clientTimezone = 'UTC';
}
}

// Function to update the current time element with the client's timezone
function updateCurrentTime() {
currentTimeElement.innerText = new Date().toLocaleString("en-US", { timeZone: clientTimezone });
}

// Get the client's timezone initially
getClientTimezone();

// Update the current time element with the client's timezone
updateCurrentTime();

// Set a timer to update the current time periodically
setInterval(updateCurrentTime, 1000); // Update every second

// Convert maintenance event times to client's time zone
var maintenanceEventElements = document.getElementsByClassName('maintenance-info');
for (var i = 0; i < maintenanceEventElements.length; i++) {
    var maintenanceEventElement = maintenanceEventElements[i];
    var maintenanceStart = new Date(maintenanceEventElement.dataset.maintenanceStart);
    var maintenanceEnd = new Date(maintenanceEventElement.dataset.maintenanceEnd);

    maintenanceEventElement.innerHTML += '<br>' + maintenanceStart.toLocaleString('en-US', { timeZone: clientTimeZone }) + ' -- ' + maintenanceEnd.toLocaleString('en-US', { timeZone: clientTimeZone });
} 