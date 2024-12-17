<?php
$html = '<div><ul class="eventify-me-gallery-field">';
/* array with image IDs for hidden field */
$hidden = array();

if( $images = get_posts( array(
    'post_type' => 'attachment',
    'orderby' => 'post__in', /* we have to save the order */
    'order' => 'ASC',
    'post__in' => explode(',',$value), /* $value is the image IDs comma separated */
    'numberposts' => -1,
    'post_mime_type' => 'image'
) ) ) {

    foreach( $images as $image ) {
        $hidden[] = $image->ID;
        $image_src = wp_get_attachment_image_src( $image->ID, array( 80, 80 ) );
        $html .= '<li data-id="' . $image->ID .  '"><span style="background-image:url(' . $image_src[0] . ')"></span><a href="#" class="misha_gallery_remove">' . Eventify_Me_Admin_Svg::getCloseIcon() . '</a></li>';
    }

}

$html .= '</ul><div style="clear:both"></div></div>';
$html .= '<input type="hidden" name="'.$name.'" value="' . join(',',$hidden) . '" /><a href="#" class="button misha_upload_gallery_button">' . __('Add Images', EVENTIFYME_TEXTDOMAIN) . '</a>';

echo $html;