<?php

class Gallery extends DataObject {
    private static $db = array(
        'Title' => 'Varchar',
        'Rank' => 'Varchar'
    );

    private static $many_many = array(
        'Pictures' => 'Image'
    );


    public function getCMSFields()
    {
        $fields = FieldList::create(TabSet::create('Root'));

        $fields->addFieldsToTab('Root.Main', array(
            TextField::create('Title'),
            TextField::create('Rank')
        ));

        $fields->addFieldToTab('Root.Pictures', $photo = UploadField::create('Pictures'));

        $photo->setFolderName('gallery-pictures')
              ->getValidator()->setAllowedExtensions(array('jpg','gif','jpeg','png'));

        return $fields;
    }
}