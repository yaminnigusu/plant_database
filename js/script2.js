
    $(document).ready(function() {
        $('.has-submenu > a').click(function(e) {
            e.preventDefault(); // Prevent default link behavior

            // Toggle the submenu visibility
            $(this).siblings('.submenu').slideToggle();
        });
    });

