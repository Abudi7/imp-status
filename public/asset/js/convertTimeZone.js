// Function to convert and display dates in the user's local time zone
function displayLocalDates() {
    // Select all elements with the class 'date-convert'
    const dateElements = document.querySelectorAll('.date-convert');
    
    // Loop through each selected element
    dateElements.forEach(function (element) {
        // Get the date string from the element's content
        const dateString = element.textContent;
        console.log(dateString);
        // Create a new Date object, appending 'UTC' to the date string to ensure proper timezone handling
        const utcDate = new Date(dateString + ' UTC');
        // Create a new Date object in the user's local time zone
        const localDate = new Date(utcDate.toLocaleString('at-AT', { timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone }));
        // Update the element's content to display the local date and time
        element.textContent = localDate.toLocaleString();
    });
}

// Call the function to convert and display dates when the page is loaded
window.addEventListener('load', displayLocalDates);
