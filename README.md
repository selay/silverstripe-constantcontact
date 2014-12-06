Constant Contact Module/Widget for Silverstripe 
=================
A simple ajax-based Constant Contact signup widget/module for Silverstripe -> fully customizable in CMS Settings.  

## Requirements
* SilverStripe 3.1.0 or + (will work with 3.0 if you change one word: private to public $db in SS_ConstantContact.php)
* PHP PHP 5.3+ with cURL extension for API
* [Silverstripe Multivaluefield](https://github.com/silverstripe-australia/silverstripe-multivaluefield).

## Features
* Easy to setup under CMS settings by setting error/success messages/API details and input field classes
* Uses Constant Contact PHP SDK for v2 API to add/update contact lists. Full SDK is provided for your own extension.
* No need to configure at the config file level. Just configure in CMS.
* Supports contact submission to single and multile lists. Options allow setting the submission to a single, selected, or all lists. All available lists are pulled via API, and shown in CMS. If you select only one list, the lists checkbox will not be shown in the front-end. If more than one, they will be displayed as checkboxes. If left empty, all available lists will be listed as checkboxes so that customers can choose which list they want to sign up. 
* Uses ajax to submit and return the error/success messages which can be customized in CMS. 
* Provides template to customize with javascript and css files in the project folder 

See `composer.json` for exact set of dependencies.


## Installation
* Download the module https://github.com/selay/silverstripe-constantcontact 
* Extract the downloaded archive into your site root so that the destination folder is called contactcontact, opening the extracted folder should contain _config.php in the root along with other files/folders
* Run dev/build?flush=all to rebuild the database 
* Go to /admin/settings and click Constant Contact Tab to set up API details and customization. You can also set input class names and and submit button text here. 
* Use  <% include SS_ConstantContactForm %> anywhere in your site template to include the widget. Run /?flush=all to let it find the template location. 
* Edit the template constantcontact/templates/SS_ConstantContactForm.ss to adapt to your design. CSS and javascript files can be edited if needed for customization. 
* Enjoy
 
If you prefer you may also install using composer:
```
composer require /selay/silverstripe-constantcontact
```

## Usage
* Please see Installation info above. 
* Front end:
![alt tag](https://github.com/selay/silverstripe-constantcontact/blob/master/screenshots/front-end.png)
![alt tag](https://github.com/selay/silverstripe-constantcontact/blob/master/screenshots/front-end-progress.png)
![alt tag](https://github.com/selay/silverstripe-constantcontact/blob/master/screenshots/front-end-done.png)
* Back end: 
![alt tag](https://github.com/selay/silverstripe-constantcontact/blob/master/screenshots/back-end.png)
In case of any problem, let me know and I will try to help you at my earliest convenience :)

## Reporting an issue
Please consider these obvious stuff faciliate the issue resolution. 
* Ensure you specify what version of SilverStripe you are using i.e. 3.0.5, 3.1-master etc. 
* Include any JavaScript or PHP errors you receive.  
* Include your own code if you have modified.

### Translations

It is not language dependent, and all texts can be custmized under Settings.

### Donation 
This module is available free of charge. I am constantly looking for ways to produce more free stuff for you.  
if you have ended up here searching for free stuff, you know and appreciate the value of the time and energy, right?
You can contriute a small amount you afford to buy me a coffee as encouragement and appreciation if you like. Please do not donate if you don't afford or this donation can affect your financial situation.  

[![alt tag](https://www.paypalobjects.com/en_AU/i/btn/btn_donateCC_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9DYDUD9TBH8PN)

<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9DYDUD9TBH8PN" target="_blank">
![alt tag](https://www.paypalobjects.com/en_AU/i/btn/btn_donateCC_LG.gif)</a>
Enjoy this module and contact me if you need any help. I will do my best to get back to you within 12-14 hours. 
