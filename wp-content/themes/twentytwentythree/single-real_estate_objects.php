<?php
echo '<p> Title: ' . get_the_title() . '</p>';
echo '<p> Description: ' . get_the_content() . '</p>';
echo '<p> House name: ' . get_field( 'name_house' ) . '</p>';
echo '<p> Location coordinates: ' . get_field( 'location_coordinates' ) . '</p>';
echo '<p> Number of floors: ' . get_field( 'number_of_floors' ) . '</p>';
echo '<p> Building type: ' . get_field( 'building_type' ) . '</p>';
