var color="#30BDEF";

$( ".rateYo_negocios" ).each(function( index ) {
    $(this).rateYo({
        rating: $(this).attr("data-rating"),
        starWidth: "20px",
        readOnly: true,
        ratedFill: color,
        normalFill: "#A0A0A0"
    });
});