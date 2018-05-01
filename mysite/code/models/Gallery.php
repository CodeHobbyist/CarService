<?php

class Gallery extends DataObject {
    private static $db = array(
        'Title' => 'Varchar',
        'Rank' => 'Int',
        'ShortDescription' => 'Varchar'
    );

    private static $has_many = array(
        'Pictures' => 'GalleryImage'
    );

    private static $summary_fields = array(
        'Title',
        'ImageCount'
    );

    public function ImageCount()
    {
        $images = DataObject::get("GalleryImage","GalleryID = {$this->ID}");
        return $images ? $images->Count() : 0;
    }

    public function getCMSFields()
    {
        $fields = FieldList::create(TabSet::create('Root'));

        $fields->addFieldsToTab('Root.Main', array(
            TextField::create('Title'),
            TextField::create('Rank'),
            TextField::create('ShortDescription')
        ));

        $fields->addFieldToTab('Root.Pictures', $photo = UploadField::create('Pictures'));

        $photo->setFolderName('gallery-pictures')
              ->getValidator()->setAllowedExtensions(array('jpg','gif','jpeg','png'));

        return $fields;
    }
}