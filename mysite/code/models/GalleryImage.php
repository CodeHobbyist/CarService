<?php

class GalleryImage extends Image
{
    private static $db = array(
        'ShortDescription' => 'Varchar'
    );

    private static $has_one = array(
        'Gallery' => 'Gallery'
    );
}