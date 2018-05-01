<?php

class Customer extends DataObject {
    private static $db = array(
        'FirstName' => 'Varchar',
        'LastName' => 'Varchar',
        'Phone' => 'Varchar', 
    );

    private static $has_one = array(
        'ImageName' => 'Image' 
    );

    private static $summary_fields = array(
        'FirstName' => 'First Name',
        'LastName' => 'Last Name',
        'Phone' => 'Phone',
    );

    private static $searchable_fields = array(
        'FirstName'
    );

    public function searchableFields() {
        return array(
            'FirstName' => array(
                'filter' => 'PartialMatchFilter',
                'title' => 'FirstName',
                'field' => 'TextField'
            ),
            'LastName' => array(
                'filter' => 'PartialMatchFilter',
                'title' => 'LastName',
                'field' => 'TextField'
            ),
            
        );
    }

    public function getCMSFields() {
        $fields = FieldList::create(TabSet::create('Root'));

        $fields->addFieldsToTab('Root.Main', array(
            TextField::create('FirstName'),
            TextField::create('LastName'),
            TextField::create('Phone')
        ));

        $fields->addFieldToTab('Root.Attachments', $photo = UploadField::create('ImageName'));

        $photo->setFolderName('customer-photo')
              ->getValidator()->setAllowedExtensions(array('jpg','gif','jpeg','png'));

        return $fields;
    }
}