<?php

class CustomerAdmin extends ModelAdmin {

    private static $menu_title = 'Customer';

    private static $url_segment = 'customer';

    private static $managed_models = array(
        'Customer'
    );

    //private static $menu_icon = 'mysite/icons/anyimage.png';
}