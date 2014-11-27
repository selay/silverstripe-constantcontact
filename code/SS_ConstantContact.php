<?php

/**
 * @author Elmin elmin@selay.com.au
 * @copyright 2014
 */
class SS_ConstantContactExtension extends DataExtension { 
        private static $db = array(
          'CcTitle'=>'Varchar(255)',
          'CcApiKey'=>'Varchar(255)',
          'CcAccessToken'=>'Varchar(255)',
          'CcListID'=>'MultiValueField',
          'CcListCache'=>'Text',
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
        public function onAfterWrite(){

              $listIDs=$this->owner->CcListID->getValues();
              $lists=serialize(singleton('SS_ConstantContactController')->getLists(true,  $listIDs));
              if ($lists!= $this->owner->CcListCache){
                $this->owner->CcListCache=$lists;
                $this->owner->write();
              }
           
        }
        public function updateCMSFields(FieldList $fields) {
              $aLists=singleton('SS_ConstantContactController')->getLists(true);

             $fields->addFieldToTab("Root.ConstantContact", TextField::create('CcTitle')->setTitle('Widget Title')->setAttribute('placeholder', 'Widget Title'));
            $fields->addFieldToTab("Root.ConstantContact", TextField::create('CcApiKey')->setTitle('API KEY <span style="color:red">*</span>')->setAttribute('placeholder', 'API KEY'));
            $fields->addFieldToTab("Root.ConstantContact", TextField::create('CcAccessToken')->setTitle('ACCESS TOKEN<span style="color:red">*</span>')->setAttribute('placeholder', 'ACCESS TOKEN'));
             $fields->addFieldToTab("Root.ConstantContact", new MultiValueDropdownField('CcListID', 'Subscription List(s)', $aLists));

            $fields->addFieldToTab("Root.ConstantContact", CheckboxField::create('CcDisplayZip')->setTitle('Show Postcode field'));
       
            $fields->addFieldToTab("Root.ConstantContact", TextareaField::create('CcInfoText')->setTitle('Message under Title')->setAttribute('placeholder', 'This text will be displayed under Title in the widget.'));
             $fields->addFieldToTab("Root.ConstantContact", TextareaField::create('CcRequiredMessage')->setTitle('Required Fields Error')->setAttribute('placeholder', 'Please write a message that will be displayed if required fields are not filled.'));

            $fields->addFieldToTab("Root.ConstantContact", TextareaField::create('CcErrorMessage')->setTitle('Error Message')->setAttribute('placeholder', 'Please write an error message to be displayed if a contact can not be added.'));
             $fields->addFieldToTab("Root.ConstantContact", TextareaField::create('CcAddedMessage')->setTitle('Successfully Created Message')->setAttribute('placeholder', 'Please write a success message to be displayed if a contact is successfully added to the list.'));
              $fields->addFieldToTab("Root.ConstantContact", TextareaField::create('CcUpdatedMessage')->setTitle('Successfully Updated Message')->setAttribute('placeholder', 'Please write a success message to be displayed if a contact, who already exists in the list, is successfully updated.'));
             
              $fields->addFieldToTab("Root.ConstantContact", TextField::create('CcInputFieldClasses')->setTitle('Input Field Classes')->setAttribute('placeholder', 'Please write classes you want to be added to the input fields.'));
            
             $fields->addFieldToTab("Root.ConstantContact", TextField::create('CcSubmitButtonText')->setTitle('Submit button text')->setAttribute('placeholder', 'Please write a submit button text. e.g Sign up'));

              $fields->addFieldToTab("Root.ConstantContact", TextField::create('CcSubmitButtonClasses')->setTitle('Submit button classes')->setAttribute('placeholder', 'Please write classes you want for the submit button'));

             $fields->addFieldToTab("Root.ConstantContact", LiteralField::create('LiteralL', 'Please use instructions <a target="_blank" href="https://developer.constantcontact.com/api-keys.html">here </a> to get API and Token. 
               <div> If you select only one list above, the lists checkbox will not be shown in the front-end. If more than one, they will be displayed as checkboxes. If left empty, all available lists will be listed as checkboxes.</div> <div style="color:red">* All fields above are REQUIRED</div>'));

        }
     public static function hasMethod(){}
     public static function Link(){}


     public function SS_ConstantContactFrom(){

        Requirements::css( CONSTANT_CONTACT_BASE . '/css/cc_ccs.css');
        Requirements::javascript( CONSTANT_CONTACT_BASE . '/js/cc_js.js');
        $config=$this->owner;

        $listIDs=$config->CcListID->getValues();
      
        if (is_array( $listIDs)){
        if (count( $listIDs)==1)
           $listfield=HiddenField::create('list')->setValue(reset( $listIDs));
        else {
               $lists=$this->owner->CcListCache;
               if (!$lists){ 
                   $lists=singleton('SS_ConstantContactController')->getLists(true,  $listIDs);
                   if ($lists) {
                    $this->owner->CcListCache=serialize($lists);
                    $this->owner->write();
                  }
               } else $lists=unserialize($lists);

               $listfield=CheckboxSetField::create('list', 'List Options', $lists)->addExtraClass('form-control '.$config->CcInputFieldClasses);
        }
      } else return false; 
          
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

