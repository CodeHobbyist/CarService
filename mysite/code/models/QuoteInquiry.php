<?php

class QuoteInquiry extends DataObject
{
    private static $db = array(
        'FullName' => 'Varchar',
        'EMail' => 'Varchar',
        'Phone' => 'Varchar',
        'Make' => 'Varchar',
        'Model' => 'Varchar',
        'Year' => 'Varchar',
        'ChassisNumber' => 'Varchar',
        'Rego' => 'Varchar',
        'Description' => 'Text'
    );

    private static $summary_fields = array(
        'FullName',
        'Rego',
        'Phone'
    );

}