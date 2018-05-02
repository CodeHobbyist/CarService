<?php

class QuoteInquiryAdmin extends ModelAdmin {

    private static $menu_title = 'Quote Inquires';

    private static $url_segment = 'quote-inquires';

    private static $managed_models = array(
        'QuoteInquiry'
    );

    //private static $menu_icon = 'mysite/icons/anyimage.png';
}