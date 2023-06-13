<?php
/*
 * Page Template Name: Houses
 */
get_header();

echo do_shortcode( '[get_houses_filter]' );
echo '<hr>';
echo do_shortcode( '[get_houses]' );

get_footer();
