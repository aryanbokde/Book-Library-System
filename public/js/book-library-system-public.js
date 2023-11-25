
jQuery(document).ready(function ($) {

    var priceDisplay = $("#price-display");

	$("#price-range-slider").slider({
        range: true,
        min: 1,
        max: 3000, // Set your maximum price
        values: [0, 3000], // Set default values
        slide: function (event, ui) {
            $("#min-price").val(ui.values[0]);
            $("#max-price").val(ui.values[1]);

            // Update the price display
            priceDisplay.text(ui.values[0] + " - " + ui.values[1]);
        }
    });

    // Set initial values
    $("#min-price").val($("#price-range-slider").slider("values", 0));
    $("#max-price").val($("#price-range-slider").slider("values", 1));
});


jQuery(document).ready(function ($) {
    
    var ajaxurl = ajax_object.ajax_url; // Replace with the actual AJAX URL

    function defaultloadPosts(page) {
        $.ajax({
            url: ajaxurl,
            type: 'GET',
            data: {
                action: 'bls_book_search',
                form_data : page,
            },
            success: function(response) {
                $('#book-search-results').html(response);
                updatePaginationDefault(page);
            }
        });
    }

    function updatePaginationDefault(currentPage) {
        $('.pagination-get-request a').removeClass('active');
        $('.pagination-get-request a[data-page="' + currentPage + '"]').addClass('active');
    }

    $(document).on('click', '.pagination-get-request a', function(e) {
        e.preventDefault();
        var page = $(this).data('page');
        defaultloadPosts(page);
    });


    defaultloadPosts(1); // Load initial posts


    $('#book-search-form').submit(function (e) {
		e.preventDefault();
		loadPosts(1);
    });


    function loadPosts(page) {

        var formData = $('#book-search-form').serialize();
		// Add custom value
        formData += '&page=' + page;
        console.log(formData);
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'bls_book_search',
                form_data: formData,
            },
            success: function(response) {
                $('#book-search-results').html(response);
                updatePagination(page);
            }
        });

    }

    function updatePagination(currentPage) {
        $('.pagination-post-request a').removeClass('active');
        $('.pagination-post-request a[data-page="' + currentPage + '"]').addClass('active');
    }

    $(document).on('click', '.pagination-post-request a', function(e) {
        e.preventDefault();
        var page = $(this).data('page');
        loadPosts(page);
    });

}); 






