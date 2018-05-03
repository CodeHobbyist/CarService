<?php
class Photo extends DataObject {

	private static $db = array(
		'Caption'=>'text',
        'AltLabel' => "Varchar(40)",
        'PhotoItemSort' => 'Int'
	);

    private static $has_one = array(
        'FeaturedImage'=>'Image',
        'Gallery' => 'Gallery'
    );

    private static $summary_fields = array(
        'getGridThumbnail' => '',
        'Caption' => 'Caption'
    );

    public function getGridThumbnail() {
        if($this->FeaturedImage()->exists()) {
             return $this->FeaturedImage()->SetWidth(100);
        }

        return "(no image)";
    }

    private static $singular_name = 'Item';
    private static $plural_name = 'Items';

    private static $default_sort = 'PhotoItemSort';

    public function getCMSFields() {
       $fields = parent::getCMSFields();

       $fields->removeFieldFromTab("Root.Main",'Caption');
       $fields->removeByName('Gallery');
       $fields->removeByName('PhotoItemSort');

       $fields->addFieldToTab('Root.Main',TextField::create('AltLabel', 'Label')->setDescription('Optional: if you would like to use an alternate label for this page (its title is displayed by default)'));

       $fields->addFieldToTab(
            'Root.Main',
             $externalField = DisplayLogicWrapper::create(
                // UploadField::create('FeaturedImage', 'Image'),
                TextField::create('Caption','Title')
                )
            );

        return $fields;
    }

}