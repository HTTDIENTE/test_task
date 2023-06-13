jQuery(document).on('click', '.houses-filter-submit', function () {
    const name_house = jQuery('[name=name_house]').val();
    const building_type = jQuery('[name=building_type_select]:checked').data('type');
    const location_coordinates = jQuery('[name=location_coordinates]').val();
    const number_of_floors = jQuery('[name=number_of_floors] option:selected').val();
    const action = 'get_houses_filter';

    jQuery.ajax({
        method: "POST",
        url: `${location.origin}/wp-admin/admin-ajax.php`,
        data: {
            name_house: name_house,
            building_type: building_type,
            location_coordinates: location_coordinates,
            number_of_floors: number_of_floors,
            action: action
        }
    }).done(function(data) {
        window.location.href = data.data;
    });
});

