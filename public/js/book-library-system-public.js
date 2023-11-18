
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

    $.ajax({
        url: ajax_object.ajax_url,
        type: 'GET',
        data: {
            action: 'bls_book_search',
        },
        success: function (response) {
            $('#book-search-results').html(response);
        },
    });

    $('#book-search-form').submit(function (e) {

		e.preventDefault();
		var formData = $(this).serialize();
		
        $.ajax({
            type: 'post',
            url: ajax_object.ajax_url,
            data: {
                action: 'bls_book_search',
                form_data: formData,
            },
            success: function (response) {
                $('#book-search-results').html(response);
            },
        });

        return false;
    });

}); 





