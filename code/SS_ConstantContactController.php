<?php
/**
 * @author Elmin  wwwelminmail@gmail.com
 * @copyright 2014
 */

require_once __DIR__.'/../src/Ctct/autoload.php';
use Ctct\ConstantContact;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;
use Ctct\Components\Contacts\EmailAddress;
use Ctct\Exceptions\CtctException;

class  SS_ConstantContactController extends ContentController
{
    public static $allowed_actions = array('subscribe');
    
    public static $url_handlers = array();

    public function init()
    {
        parent::init();
    }

    public function getLists($array=false, $only=false)
    {
        $config=SiteConfig::current_site_config();
        $cc = new ConstantContact($config->CcApiKey);
        try {
            $lists=$cc->getLists($config->CcAccessToken);
            if ($array) {
                $array=array();
                foreach ($lists as $list) {
                    if ($only) {
                        if (in_array($list->id, $only)) {
                            $array[$list->id]=$list->name;
                        }
                    } else {
                        $array[$list->id]=$list->name;
                    }
                }
                return $array;
            }
            return $cc->getLists($config->CcAccessToken);
        } catch (CtctException $ex) {
        }
        return '';
    }


    public function subscribe()
    {
        if (!$this->getRequest()->isAjax()) {  //if not ajax, say go and eat your grass. 
            exit("Action is not allowed!");
        }
        $return=array('action'=>'','message'=>'');
        $config=SiteConfig::current_site_config();
        $cc = new ConstantContact($config->CcApiKey);
        $list=$this->getRequest()->postVar('list');
        if ($config->CcDisplayZip) {
            $postcode=array('postal_code'=>$this->getRequest()->postVar('postcode'));
        } else {
            $postcode=0;
        }
        $email=$this->getRequest()->postVar('email');
        $first_name=$this->getRequest()->postVar('first_name');
        $last_name=$this->getRequest()->postVar('last_name');
         
        if (empty($email)||empty($first_name)||empty($last_name)||empty($list)) {
            $return['action']='E';
            $return['message']=$config->CcRequiredMessage?$config->CcRequiredMessage:'Required fields are missing.';
        } else {
            try {
        
        // check to see if a contact with the email addess already exists in the account
        $response = $cc->getContactByEmail($config->CcAccessToken, $email);
 

        // create a new contact if one does not exist
        if (empty($response->results)) {
            $return['action'] = "C";

            $contact = new Contact();
            $contact->addEmail($email);
            if (!is_array($list)) {
                $contact->addList($list);
            } else {
                foreach ($list as $l) {
                    $contact->addList($l);
                }
            }
            $contact->first_name = $first_name;
            $contact->last_name = $last_name;
            if ($postcode) {
                $contact->addAddress($postcode);
            }
            $returnContact = $cc->addContact($config->CcAccessToken, $contact);
            if (!empty($returnContact)) {
                $return['message']=$config->CcAddedMessage;
            }
        // update the existing contact if address already existed
        } else {
            $return['action'] = "U";

            $contact = $response->results[0];
            if (!is_array($list)) {
                $contact->addList($list);
            } else {
                foreach ($list as $l) {
                    $contact->addList($l);
                }
            }
                
            $contact->first_name = $first_name;
            $contact->last_name = $last_name;
            if ($postcode) {
                $contact->addAddress($postcode);
            }
            $returnContact = $cc->updateContact($config->CcAccessToken, $contact);
            if (!empty($returnContact)) {
                $return['message']=$config->CcUpdatedMessage;
            }
        }
        
    // catch any exceptions thrown during the process and print the errors to screen
            } catch (CtctException $ex) {
                $ex=$ex->getErrors();
                print_r($ex);
                $return['action']='E';
                $return['message']=$config->CcErrorMessage;
            }
        }
        return json_encode($return);
    }
}
