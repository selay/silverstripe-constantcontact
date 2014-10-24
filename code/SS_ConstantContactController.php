<?php
/**
 * @author Elmin wwwelminmail@gmail.com or elmin@selay.com.au
 * @copyright 2014
 */

require_once __DIR__.'/../src/Ctct/autoload.php';
use Ctct\ConstantContact;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;
use Ctct\Components\Contacts\EmailAddress;
use Ctct\Exceptions\CtctException;

class  SS_ConstantContactController extends ContentController{
    static $allowed_actions = array('subscribe');
    
    public static $url_handlers = array();

    public function init() {
        parent::init();
        
        
    }

 public function getLists(){
 	 $config=SiteConfig::current_site_config();
 	 $cc = new ConstantContact($config->CcApiKey);
	   try{
			return $cc->getLists($config->CcAccessToken);
		} catch (CtctException $ex) {} 
		return '';   
	 }

 public function subscribe(){
		 if (!$this->getRequest()->isAjax())  //if not ajax, say go and eat your grass. 
		    exit("Action is not allowed!");       
    	 $return=array('action'=>'','message'=>'');
    	 $config=SiteConfig::current_site_config();
         $cc = new ConstantContact($config->CcApiKey);
         $list=$config->CcListID?$config->CcListID:$this->getRequest()->postVar('list');
         if ($config->CcDisplayZip)
            $postcode=array('postal_code'=>$this->getRequest()->postVar('postcode')); 
 		 else $postcode=0;
         $email=$this->getRequest()->postVar('email');
         $first_name=$this->getRequest()->postVar('first_name');
         $last_name=$this->getRequest()->postVar('last_name');
         
        if (empty($email)||empty($first_name)||empty($last_name)){
        	$return['action']='E';
        	$return['message']=$config->CcRequiredMessage?$config->CcRequiredMessage:'Name and Email are required.';
        }	
    	  else try {
        
        // check to see if a contact with the email addess already exists in the account
        $response = $cc->getContactByEmail($config->CcAccessToken, $email);
 

        // create a new contact if one does not exist
        if (empty($response->results)) {
            $return['action'] = "C";

            $contact = new Contact();
            $contact->addEmail($email);
            $contact->addList($list); 
            $contact->first_name = $first_name;
            $contact->last_name = $last_name;
            if ($postcode)
            	$contact->addAddress($postcode);
            $returnContact = $cc->addContact($config->CcAccessToken, $contact); 
            if (!empty($returnContact)) $return['message']=$config->CcAddedMessage;
        // update the existing contact if address already existed
        } else {            
            $return['action'] = "U";

            $contact = $response->results[0];
            $contact->addList($list);
            $contact->first_name = $first_name;
            $contact->last_name = $last_name;
            if ($postcode)
            	$contact->addAddress($postcode);
            $returnContact = $cc->updateContact($config->CcAccessToken, $contact);  
             if (!empty($returnContact)) $return['message']=$config->CcUpdatedMessage;
        }
        
    // catch any exceptions thrown during the process and print the errors to screen
    } catch (CtctException $ex) {
    	$ex=$ex->getErrors();
    	print_r($ex);
    	$return['action']='E';
    	$return['message']=$config->CcErrorMessage;
    }
    return json_encode($return);
    }
}