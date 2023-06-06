
  // Function to update the calendar table
  function updateTable(url, tableId) {
    // Create an XMLHttpRequest object
    var xhr = new XMLHttpRequest();
    
    // Configure the AJAX request
    xhr.open("GET", url, true);
    
    // Set the callback function when the request is complete
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
        // Parse the response JSON or HTML based on your server's response
        var responseData = JSON.parse(xhr.responseText);
        
        // Get the table element
        var table = document.getElementById(tableId);
        
        // Update the table with the new data
        table.innerHTML = responseData;
      }
    };
    
    // Send the AJAX request
    xhr.send();
  }
  
  // Call the updateTable function initially for each URL and table ID
  updateTable("http://localhost/AP_status/system_status/public/index.php/scheduled/maintenance", "calendar-table");
  updateTable("http://localhost/AP_status/system_status/public/index.php/allSubscription", ".container");
  // Add more updateTable calls for additional URLs and table IDs
  
  // Set a timer to call the function periodically for each URL and table ID
  setInterval(function() {
    updateTable("http://localhost/AP_status/system_status/public/index.php/scheduled/maintenance", "calendar-table1");
    updateTable("http://localhost/other_page", "calendar-table2");
    // Add more updateTable calls for additional URLs and table IDs
  }, 5000); // Refresh every 5 seconds (adjust as needed)
