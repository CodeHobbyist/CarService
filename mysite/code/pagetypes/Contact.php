<?php

    class Contact extends Page {
        private static $db = array (
            'CompanyEmailAddress' => 'Text'
        );

        public function getCMSFields() {

            $fields = parent::getCMSFields();

            $fields -> addFieldToTab('Root.EmailAddressSetting', TextField::create('CompanyEmailAddress'));
            return $fields;
        }
    }

    class Contact_Controller extends Page_Controller {
        private static $allowed_actions = array(
            'ContactForm'
        );

        public function ContactForm () {
            $fields = new FieldList(
                new TextField('FirstName','FirstName*'),
                new TextField('LastName','LastName*'),
                new TextField('Subject','Subject'),
                new EmailField('EmaillAddress','EmaillAddress*'),
                new TextareaField('Message','Message*')
            );

            $actions = new FieldList(
                new FormAction('SubmitForm', 'Submit')
            );

            $valiator = new RequiredFields(
                'FirstName',
                'LastName',
                'EmaillAddress',
                'Message'
            );

            return new Form($this, 'ContactForm', $fields, $actions, $valiator);
        }

        public function SubmitForm($data, $form) {

            $email = new Email();

            $email -> setTo('CompanyEmailAddress');
            $email -> setFrom($data['EmaillAddress']);
            $email -> setSubject("The enquiry is about {$data['Subject']}");
            
                $messageBody = "
                    <p><strong>Name:</strong></p> {$data['FirstName']} </p>
                    <p><strong>Content:</strong></p><br> {$data['Message']} </p>
                ";
            $email -> setBody($messageBody);
            $email -> send();

            return [
                'Content' => '<p>Thank you for the enquiry</p>',
                'ContactForm' => ''
            ];
        }


    }
?>