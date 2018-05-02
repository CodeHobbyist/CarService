<?php


class HomePage_Controller extends Page_Controller
{
    private static $allowed_actions = array(
        'QuoteForm'
    );

    public function QuoteForm()
    {
        $form = Form::create(
            $this,
            __FUNCTION__,
            FieldList::create(
                TextField::create('FullName', 'Full name'),
                TextField::create('EMail', 'E-mail'),
                TextField::create('Phone', 'Contact number'),
                TextField::create('Make', 'Vehicle make'),
                TextField::create('Model', 'Vehicle model'),
                TextField::create('Year', 'Vehicle year'),
                TextField::create('ChassisNumber', 'Chassis number'),
                TextField::create('Rego', 'Registration number')->setAttribute('placeholder', 'It\'s quite important.'),
                TextareaField::create('Description', 'Description of damage')

            ),
            FieldList::create(
                FormAction::create('sendQuoteForm', 'Submit')
                    ->addExtraClass('btn btn-default-color')
            ),
            RequiredFields::create('FullName', 'EMail', 'Phone', 'Make', 'Year', 'Rego', 'Description')
        )->addExtraClass('quote-form');

        $data = Session::get("FormData.{$form->getName()}.data");

        return $data ? $form->loadDataFrom($data) : $form;
    }

    public function sendQuoteForm($data, $form)
    {
        Session::set("FormData.{$form->getName()}.data", $data);

        $theInquiry = QuoteInquiry::create();

        if(strlen($data['Description']) < 10) {
            $form->addErrorMessage('Message', 'Your description is too short', 'bad');
            return $this->redirectBack();
        }

        $form->saveInto($theInquiry);
        $theInquiry->write();

        $form->sessionMessage('Thanks for your inquiry. We\' get back to you soon.', 'good');
        Session::clear("FormData.{$form->getName()}.data");

        return $this->redirect('/someSuccessUrl');
    }
}