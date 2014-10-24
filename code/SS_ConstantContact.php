<?php

/**
 * @author Elmin wwwelminmail@gmail.com or elmin@selay.com.au
 * @copyright 2014
 */
class SS_ConstantContactExtension extends DataExtension { 
        private static $db = array(
          'CcTitle'=>'Varchar(255)',
          'CcApiKey'=>'Varchar(255)',
          'CcAccessToken'=>'Varchar(255)',
          'CcListID'=>'Varchar',
          'CcDisplayZip'=>'Boolean',
          'CcInfoText'=>'Text',
          'CcRequiredMessage'=>'Varchar(255)',
          'CcErrorMessage'=>'Varchar(255)',
          'CcAddedMessage'=>'Varchar(255)',
          'CcUpdatedMessage'=>'Varchar(255)',
          'CcInputFieldClasses'=>'Varchar(255)',
          'CcSubmitButtonText'=>'Varchar',
          'CcSubmitButtonClasses'=>'Varchar(255)',
        );  
        public function updateCMSFields(FieldList $fields) {
             $fields->addFieldToTab("Root.ConstantContact", TextField::create('CcTitle')->setTitle('Widget Title')->setAttribute('placeholder', 'Widget Title'));
            $fields->addFieldToTab("Root.ConstantContact", TextField::create('CcApiKey')->setTitle('API KEY <span style="color:red">*</span>')->setAttribute('placeholder', 'API KEY'));
            $fields->addFieldToTab("Root.ConstantContact", TextField::create('CcAccessToken')->setTitle('ACCESS TOKEN<span style="color:red">*</span>')->setAttribute('placeholder', 'ACCESS TOKEN'));
             $fields->addFieldToTab("Root.ConstantContact", TextField::create('CcListID')->setTitle('List ID')->setAttribute('placeholder', 'List ID'));
            
            $fields->addFieldToTab("Root.ConstantContact", CheckboxField::create('CcDisplayZip')->setTitle('Show Postcode field'));
       
            $fields->addFieldToTab("Root.ConstantContact", TextareaField::create('CcInfoText')->setTitle('Message under Title')->setAttribute('placeholder', 'This text will be displayed under Title in the widget.'));
             $fields->addFieldToTab("Root.ConstantContact", TextareaField::create('CcRequiredMessage')->setTitle('Required Fields Error')->setAttribute('placeholder', 'Please write a message that will be displayed if required fields are not filled.'));

            $fields->addFieldToTab("Root.ConstantContact", TextareaField::create('CcErrorMessage')->setTitle('Error Message')->setAttribute('placeholder', 'Please write an error message to be displayed if a contact can not be added.'));
             $fields->addFieldToTab("Root.ConstantContact", TextareaField::create('CcAddedMessage')->setTitle('Successfully Created Message')->setAttribute('placeholder', 'Please write a success message to be displayed if a contact is successfully added to the list.'));
              $fields->addFieldToTab("Root.ConstantContact", TextareaField::create('CcUpdatedMessage')->setTitle('Successfully Updated Message')->setAttribute('placeholder', 'Please write a success message to be displayed if a contact, who already exists in the list, is successfully updated.'));
             
              $fields->addFieldToTab("Root.ConstantContact", TextField::create('CcInputFieldClasses')->setTitle('Input Field Classes')->setAttribute('placeholder', 'Please write classes you want to be added to the input fields.'));
            
             $fields->addFieldToTab("Root.ConstantContact", TextField::create('CcSubmitButtonText')->setTitle('Submit button text')->setAttribute('placeholder', 'Please write a submit button text. e.g Sign up'));

              $fields->addFieldToTab("Root.ConstantContact", TextField::create('CcSubmitButtonClasses')->setTitle('Submit button classes')->setAttribute('placeholder', 'Please write classes you want for the submit button'));

             $fields->addFieldToTab("Root.ConstantContact", LiteralField::create('LiteralL', 'Please use instructions <a target="_blank" href="https://developer.constantcontact.com/api-keys.html">here </a> to get API and Token. List ID is the list where you want new subscribers to be added. To find your list id, check the url in your ConstantContact account with the target list opened. URL should have ?listid=NUMBER. OR, leave List ID field empty. If empty, it will pull all your available lists and display them in the widget. If you still want to limit users to a single list, you can grab list id from the checkbox value in the widget. <div style="color:red">* All fields above are REQUIRED</div>'));

        }
     public static function hasMethod(){}
     public static function Link(){}


     public function SS_ConstantContactFrom(){

        Requirements::css( CONSTANT_CONTACT_BASE . '/css/cc_ccs.css');
        Requirements::javascript( CONSTANT_CONTACT_BASE . '/js/cc_js.js');
        $config=SiteConfig::current_site_config();
        if ($config->CcListID)
           $listfield=HiddenField::create('list')->setValue(SiteConfig::current_site_config()->CcListID);
        else {
            $inArray=array();
            $lists=singleton('SS_ConstantContactController')->getLists();
            if ($lists)
             foreach ($lists as $list) 
                 $inArray[$list->id]=$list->name;
               $listfield=CheckboxSetField::create('list', 'List Options', $inArray)->addExtraClass('form-control '.$config->CcInputFieldClasses);
        }

          
          $fields = new FieldList(array(
                 TextField::create('email')->setTitle('')->addExtraClass('form-control '.$config->CcInputFieldClasses)->setAttribute('placeholder', 'Email'),
                 TextField::create('first_name')->setTitle('')->addExtraClass('form-control '.$config->CcInputFieldClasses)->setAttribute('placeholder', 'First Name'),
                 TextField::create('last_name')->setTitle('')->addExtraClass('form-control '.$config->CcInputFieldClasses)->setAttribute('placeholder', 'Last Name'),
                 $listfield,
                LiteralField::create('SignUpButton', '<input type="button" class="'.$config->CcSubmitButtonClasses.'" id="subscribe_to_cc_list" value="'.($config->CcSubmitButtonText?$config->CcSubmitButtonText:"Sign Up").'" />')  
            ));

          if ($config->CcDisplayZip)  //if postcode field is needed. 
            $fields->push(TextField::create('postcode')->setTitle('')->addExtraClass('form-control '.$config->CcInputFieldClasses)->setAttribute('placeholder', 'Postcode'));
            

            // Create actions
            $actions = new FieldList(
                 FormAction::create('signup')->addExtraClass('small button')
            );        
            $form = new Form($this, 'SS_ConstantContactSignup', $fields, $actions);
            $form->addExtraClass('custom constant-contect-signup');
            // Load the form with previously sent data
            $form->loadDataFrom(Controller::curr()->getRequest()->postVars());
            return $form;       
    }

}

