<?php

class Gallery extends DataObject {
    private static $db = array(
        'Title' => 'Varchar',
        'Rank' => 'Int',
        'ShortDescription' => 'Varchar'
    );

    // private static $has_many = array(
    //     'Pictures' => 'GalleryImage'
    // );

    private static $has_many = array(
        'Pictures' => 'Photo'
    );

    private static $summary_fields = array(
        'Title' => 'Title',
        'ImageCount' => 'Image Count'
    );

    public function ImageCount()
    {
        $images = DataObject::get("Photo","GalleryID = {$this->ID}");
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

        $fields->addFieldToTab("Root.Main",
                $photo=FormUtils::make_grid_field_editor(
                    'Pictures',
                    'Photo List',
                    $this->Pictures(),
                    'PhotoItemSort',
                    'RelationEditor',
                    array(),
                    false,
                    20,
                    true
                ));

        return $fields;
    }

    public function thePhoto(){
            $pictures = $this->Pictures();
            return $pictures;
    }
}