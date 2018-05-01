<?php

    class Testimonial extends DataObject {
        private static $db = array (
            'FirstName' => 'Varchar',
            'LastName' => 'Varchar',
            'PositionTitle' => 'Varchar',
            'ContentText' => 'HTMLText'
        );

        private static $has_one = array (
            'ProfilePhoto' => 'Image'
        );

        private static $summary_fields = array (
            'FirstName' => 'FirstName',
            'PositionTitle' => 'PositionTitle'
        );

        public function seachableFields() {
            return array (
                'FirstName' => array (
                    'filter' => 'ExactMatchFilter',
                    'title' => 'FirstName',
                    'field' => 'TextField'
                )
            );
        }

        public function getCMSFields() {
            $fields = FieldList::create(TabSet::create('Root'));

            $fields->addFieldsToTab('Root.Main', array(
                TextField::create('FirstName'),
                TextField::create('LastName'),
                TextField::create('PositionTitle'),
                HtmlEditorField::create('ContentText')

            ));
            
            $fields->addFieldsToTab('Root.PhotoAttachments', $uploads = UploadField::create('ProfilePhoto'));
            
            $uploads->setFolderName('testimonial-photos')->getValidator()->setAllowedExtensions(array('png','jpg','jpeg','gif'));
            
            return $fields;

        }
    }


?>