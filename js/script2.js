
    $(document).ready(function() {
        $('.has-submenu > a').click(function(e) {
            e.preventDefault(); // Prevent default link behavior

            // Toggle the submenu visibility
            $(this).siblings('.submenu').slideToggle();
        });
    });


    $(document).ready(function() {
        $('#plantSearchInput').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('#plantTable tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
    
