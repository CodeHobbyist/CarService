<?php
/**
 * @package mysite
 */
class FormUtils {

    /**
     * @param $name
     * @param null $strTitle
     * @param SS_List|null $dataList
     * @param null $sortColumn
     * @param null $multiClasses
     * @param string $type
     * @param bool $removeAddButton
     * @param int $itemsPerPage
     * @return GridField
     *
     * Create a {@link GridField} with given option
     * Global function for all type of grid fields
     */
    public static function make_grid_field_editor($name, $strTitle = null, SS_List $dataList = null, $sortColumn = null, $type = 'RelationEditor', $multiClasses = array(), $removeAddButton = false, $itemsPerPage = 20, $removeAddExistingAutocompleter = false) {

        $gridConfig = self::select_grid_field_config($type, $sortColumn, $multiClasses, $itemsPerPage, $removeAddButton, $removeAddExistingAutocompleter);

        return new GridField($name, $strTitle, $dataList, $gridConfig);
    }

    /**
     * @param FieldList $fields
     * @param $arrOrder
     */
    public static function rearrange_tabs(FieldList $fields, $arrOrder){
        if($fields->hasTabSet()){
            $tabSet = self::tab_set_from_fields($fields);
            if($tabSet){
                $arrTabs = array();

                foreach($arrOrder as $strName){
                    $tab = $tabSet->fieldByName($strName);
                    if($tab){
                        $tabSet->removeByName($strName);
                        $arrTabs[] = $tab;
                    }
                }
                foreach($tabSet->FieldList() as $field){
                    $arrTabs[] = $tabSet->fieldByName($field->getName());
                    $tabSet->removeByName($field->getName());
                }
                foreach($arrTabs as $tab){
                    $tabSet->push($tab);
                }

            }
        }
    }

    /**
     * @param $strTabName
     * @param FieldList $fields
     * @param $arrOrder
     */
    public static function rearrange_fields_in_tab($strTabName, FieldList $fields, $arrOrder){
        $tab = $fields->findOrMakeTab($strTabName);

        $arrFields = array();
        foreach($tab->Fields() as $formField){
            $arrFields[$formField->getName()] = $formField;
            $tab->removeByName($formField->getName());
        }

        foreach($arrOrder as $strFieldName){
            if(array_key_exists($strFieldName, $arrFields)){
                $tab->Fields()->push($arrFields[$strFieldName]);
            }
        }
    }

    /**
     * Create a read only {@link TextField}
     */
    public static function make_readonly_text_field($name, $title = null){
        return TextField::create($name, $title)->performReadonlyTransformation();
    }

    /**
     * @param FieldList $fields
     * @return mixed
     */
    private static function tab_set_from_fields(FieldList $fields){
        foreach($fields as $field){
            if(is_object($field) && $field instanceof TabSet){
                return $field;
            }
        }
    }

    /**
     * @param $type
     * @param $sortColumn
     * @param $multiClasses
     * @param $itemsPerPage
     * @param $removeAddButton
     * @param $removeAddExistingAutocompleter
     * @return GridFieldConfig_RecordEditor|GridFieldConfig_RecordViewer|GridFieldConfig_RelationEditor
     */
    private static function select_grid_field_config($type, $sortColumn, $multiClasses = array(), $itemsPerPage, $removeAddButton,$removeAddExistingAutocompleter){
        if ('RelationEditor' == $type) {
            $gridConfig = new GridFieldConfig_RelationEditor($itemsPerPage);
        } elseif ('RecordEditor' == $type) {
            $gridConfig = new GridFieldConfig_RecordEditor($itemsPerPage);
        } else {
            $gridConfig = new GridFieldConfig_RecordViewer($itemsPerPage);
        }


        if (count($multiClasses) > 1){
            if(class_exists('GridFieldAddNewMultiClass')) {
                $gridConfig->removeComponentsByType('GridFieldAddNewButton');
                $gridConfig->addComponent($addNewDropdown = new GridFieldAddNewMultiClass());
                $addNewDropdown->setClasses($multiClasses);

            }
            else{
                user_error('Class not found GridFieldAddNewMultiClass', 'error');
            }

        }

        if($sortColumn){
            if(ClassInfo::exists('GridFieldOrderableRows')){
                $gridConfig->addComponent(new GridFieldOrderableRows($sortColumn));
            }
            elseif(ClassInfo::exists('GridFieldSortableRows')) {
                $gridConfig->addComponent(new GridFieldSortableRows($sortColumn));
            }

        }

        if ($removeAddButton) {
            $gridConfig->removeComponentsByType('GridFieldAddNewButton');
        }

        if ($removeAddExistingAutocompleter) {
            $gridConfig->removeComponentsByType('GridFieldAddExistingAutocompleter');
        }

        return $gridConfig;
    }

}