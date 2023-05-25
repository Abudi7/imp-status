/**
 * ================================================================
 * 
 * 
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
            $('#table tbody tr').each(function() {
                var system = $(this).find('td:first-child a').text().toLowerCase();
                var info = $(this).find('td:nth-child(3)').text().toLowerCase();
                if (system.includes(searchQuery) || info.includes(searchQuery)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });

