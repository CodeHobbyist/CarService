<?php

/**
 * Provides a tagging interface, storing links between tag DataObjects and a parent DataObject.
 *
 * @package forms
 * @subpackage fields
 */
class TagField extends DropdownField
{
    /**
     * @var array
     */
    public static $allowed_actions = array(
        'suggest',
    );

    /**
     * @var bool
     */
    protected $shouldLazyLoad = false;

    /**
     * @var int
     */
    protected $lazyLoadItemLimit = 10;

    /**
     * @var bool
     */
    protected $canCreate = true;

    /**
     * @var string
     */
    protected $titleField = 'Title';

    /**
     * @var bool
     */
    protected $isMultiple = true;

    /**
     * @param string $name
     * @param string $title
     * @param null|DataList $source
     * @param null|DataList $value
     */
    public function __construct($name, $title = '', $source = null, $value = null)
    {
        parent::__construct($name, $title, $source, $value);
    }

    /**
     * @return bool
     */
    public function getShouldLazyLoad()
    {
        return $this->shouldLazyLoad;
    }

    /**
     * @param bool $shouldLazyLoad
     *
     * @return static
     */
    public function setShouldLazyLoad($shouldLazyLoad)
    {
        $this->shouldLazyLoad = $shouldLazyLoad;

        return $this;
    }

    /**
     * @return int
     */
    public function getLazyLoadItemLimit()
    {
        return $this->lazyLoadItemLimit;
    }

    /**
     * @param int $lazyLoadItemLimit
     *
     * @return static
     */
    public function setLazyLoadItemLimit($lazyLoadItemLimit)
    {
        $this->lazyLoadItemLimit = $lazyLoadItemLimit;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsMultiple()
    {
        return $this->isMultiple;
    }

    /**
     * @param bool $isMultiple
     *
     * @return static
     */
    public function setIsMultiple($isMultiple)
    {
        $this->isMultiple = $isMultiple;

        return $this;
    }

    /**
     * @return bool
     */
    public function getCanCreate()
    {
        return $this->canCreate;
    }

    /**
     * @param bool $canCreate
     *
     * @return static
     */
    public function setCanCreate($canCreate)
    {
        $this->canCreate = $canCreate;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitleField()
    {
        return $this->titleField;
    }

    /**
     * @param string $titleField
     *
     * @return $this
     */
    public function setTitleField($titleField)
    {
        $this->titleField = $titleField;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function Field($properties = array())
    {
        Requirements::css(TAG_FIELD_DIR . '/css/select2.min.css');
        Requirements::css(TAG_FIELD_DIR . '/css/TagField.css');

        Requirements::javascript(THIRDPARTY_DIR . '/jquery/jquery.js');
        Requirements::javascript(THIRDPARTY_DIR . '/jquery-entwine/dist/jquery.entwine-dist.js');
        Requirements::javascript(TAG_FIELD_DIR . '/js/select2.js');
        Requirements::javascript(TAG_FIELD_DIR . '/js/TagField.js');

        $this->addExtraClass('ss-tag-field');

        if ($this->getIsMultiple()) {
            $this->setAttribute('multiple', 'multiple');
        }

        if ($this->shouldLazyLoad) {
            $this->setAttribute('data-ss-tag-field-suggest-url', $this->getSuggestURL());
        } else {
            $properties = array_merge($properties, array(
                'Options' => $this->getOptions()
            ));
        }

        $this->setAttribute('data-can-create', (int) $this->getCanCreate());

        return $this
            ->customise($properties)
            ->renderWith(array("templates/TagField"));
    }

    /**
     * @return string
     */
    protected function getSuggestURL()
    {
        return Controller::join_links($this->Link(), 'suggest');
    }

    /**
     * @return ArrayList
     */
    protected function getOptions()
    {
        $options = ArrayList::create();

        $source = $this->getSource();

        if (!$source) {
            $source = new ArrayList();
        }

        $dataClass = $source->dataClass();

        $values = $this->Value();

        // Mark selected tags while still returning a full list of possible options
        $ids = array(); // empty fallback array for comparing
        $values = $this->Value();
        if($values){
            // @TODO conversion from array to DataList to array...(?)
            if(is_array($values)) {
                $values = DataList::create($dataClass)->filter('ID', $values);
            }
            $ids = $values->column('ID');
        }

        $titleField = $this->getTitleField();

        foreach ($source as $object) {
            $options->push(
                ArrayData::create(array(
                    'Title' => $object->$titleField,
                    'Value' => $object->ID,
                    'Selected' => in_array($object->ID, $ids),
                ))
            );
        }

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value, $source = null)
    {
        if ($source instanceof DataObject) {
            $name = $this->getName();

            if ($source->hasMethod($name)) {
                $value = $source->$name()->getIDList();
            }
        } elseif ($value instanceof SS_List) {
            $value = $value->column('ID');
        }

        if (!is_array($value)) {
            return parent::setValue($value);
        }

        return parent::setValue(array_filter($value));
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes()
    {
        return array_merge(
            parent::getAttributes(),
            array('name' => $this->getName() . '[]')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function saveInto(DataObjectInterface $record)
    {
        parent::saveInto($record);

        $name = $this->getName();
        $titleField = $this->getTitleField();

        $source = $this->getSource();

        $values = $this->Value();

        if (!$values) {
            $values = array();
        }

        if (empty($record) || empty($source) || empty($titleField)) {
            return;
        }

        if (!$record->hasMethod($name)) {
            throw new Exception(
                sprintf("%s does not have a %s method", get_class($record), $name)
            );
        }

        $relation = $record->$name();

        foreach ($values as $i => $value) {
            if (!is_numeric($value)) {
                if (!$this->getCanCreate()) {
                    unset($values[$i]);
                    continue;
                }

                // Get or create record
                $record = $this->getOrCreateTag($value);
                $values[$i] = $record->ID;
            }
        }

        if ($values instanceof SS_List) {
            $values = iterator_to_array($values);
        }

        $relation->setByIDList(array_filter($values));
    }

    /**
     * Get or create tag with the given value
     *
     * @param string $term
     * @return DataObject
     */
    protected function getOrCreateTag($term)
    {
        // Check if existing record can be found
        $source = $this->getSource();
        $titleField = $this->getTitleField();
        $record = $source
            ->filter($titleField, $term)
            ->first();
        if ($record) {
            return $record;
        }

        // Create new instance if not yet saved
        $dataClass = $source->dataClass();
        $record = Injector::inst()->create($dataClass);
        $record->{$titleField} = $term;
        $record->write();
        return $record;
    }

    /**
     * Returns a JSON string of tags, for lazy loading.
     *
     * @param SS_HTTPRequest $request
     *
     * @return SS_HTTPResponse
     */
    public function suggest(SS_HTTPRequest $request)
    {
        $tags = $this->getTags($request->getVar('term'));

        $response = new SS_HTTPResponse();
        $response->addHeader('Content-Type', 'application/json');
        $response->setBody(json_encode(array('items' => $tags)));

        return $response;
    }

    /**
     * Returns array of arrays representing tags.
     *
     * @param string $term
     *
     * @return array
     */
    protected function getTags($term)
    {
        /**
         * @var DataList $source
         */
        $source = $this->getSource();

        $titleField = $this->getTitleField();

        $query = $source
            ->filter($titleField . ':PartialMatch:nocase', $term)
            ->sort($titleField)
            ->limit($this->getLazyLoadItemLimit());

        // Map into a distinct list
        $items = array();
        $titleField = $this->getTitleField();
        foreach ($query->map('ID', $titleField) as $id => $title) {
            $items[$title] = array(
                'id' => $id,
                'text' => $title
            );
        }

        return array_values($items);
    }

    /**
     * DropdownField assumes value will be a scalar so we must
     * override validate. This only applies to Silverstripe 3.2+
     *
     * @param Validator $validator
     * @return bool
     */
    public function validate($validator)
    {
        return true;
    }

    /**
     * Converts the field to a readonly variant.
     *
     * @return TagField_Readonly
     */
    public function performReadonlyTransformation()
    {
        $copy = $this->castedCopy('TagField_Readonly');
        $copy->setSource($this->getSource());
        return $copy;
    }
}

/**
 * A readonly extension of TagField useful for non-editable items within the CMS.
 *
 * @package forms
 * @subpackage fields
 */
class TagField_Readonly extends TagField
{
    protected $readonly = true;

    /**
     * Render the readonly field as HTML.
     *
     * @param array $properties
     * @return HTMLText
     */
    public function Field($properties = array())
    {
        $options = array();

        foreach ($this->getOptions()->filter('Selected', true) as $option) {
            $options[] = $option->Title;
        }

        $field = ReadonlyField::create($this->name.'_Readonly', $this->title);

        $field->setForm($this->form);
        $field->setValue(implode(', ', $options));
        return $field->Field();
    }
}
