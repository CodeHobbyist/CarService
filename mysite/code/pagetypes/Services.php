<?php

class Services extends Page {
    private static $db = array(
        'Offered' => 'Varchar',
        'Teaser' => 'Text',
        'Date' => 'Date', 
    );

    private static $has_one = array(
        'Photo' => 'Image' 
    );

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        $fields->addFieldToTab('Root.Main', DateField::create('Date','Date')->setConfig('showcalendar', true),
            'Content');
        $fields->addFieldToTab('Root.Main', TextareaField::create('Teaser'), 'Content');
        $fields->addFieldToTab('Root.Main', TextareaField::create('Offered'), 'Content');
     
        $fields->addFieldToTab('Root.Attachments', $photo = UploadField::create('Photo'));

        $photo->setFolderName('services-photo')
              ->getValidator()->setAllowedExtensions(array('jpg','gif','jpeg','png'));

        return $fields;
    }
 
}

class Services_Controller extends Page_Controller {

}