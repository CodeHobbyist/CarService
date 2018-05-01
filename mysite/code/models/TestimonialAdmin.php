<?php

    class TestimonialAdmin extends ModelAdmin {

        private static $menu_title = 'Testimonial';

        private static $url_segment = 'testimonial';

        private static $managed_models = array (
            'Testimonial'
        );

        //private static $menu_icon = '/mysite/icons/testimonials.png';
    }

?>