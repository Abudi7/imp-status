/**
 * ================================================================
 * Search Script 
 * ================================================================
 */

   $(document).ready(function() {
      // Prevent form submission
      $('#searchForm').submit(function(event) {
         event.preventDefault();
      });

      // Filter table rows based on search query
      $('#searchInput').keyup(function() {
         var searchQuery = $(this).val().toLowerCase();
         var found = false; // Flag to track if system is found

         $('#table tbody tr').each(function() {
            var system = $(this).find('td:first-child').text().toLowerCase();
            var info = $(this).find('td:nth-child(4)').text().toLowerCase();

            if (system.includes(searchQuery) || info.includes(searchQuery)) {
               $(this).show();
               found = true;
            } else {
               $(this).hide();
            }
         });

         // Show message if system is not found
         if (!found) {
            $('#notFoundMessage').text(' Sorry, the system you are searching for was not found.').show();
            $('#table').hide();
         } else {
            $('#notFoundMessage').hide();
            $('#table').show();
         }
      });
   });

