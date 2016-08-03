# Http Terrasoft API


- [Description](#Description)
- [Installation](#Installation)
- [Configuration](#Configuration)
- [Bases request](#Bases request)
    - [Select](#Select)
    - [Create](#Create)
    - [Update](#Update)
    - [Delete](#Delete)
- [Handler Responce](#Handler)



<a name="Description"></a>
## Description

The wrapper for use with API Terrasoft
Link to the documentation on http

https://academy.terrasoft.ru/documents/technic-sdk/7-8-0/rabota-s-obektami-bpmonline-po-protokolu-odata-s-ispolzovaniem-http

The package uses the Laravel framework and its environment

###### If you find a bug or something else (and you always have) I ask you to contact me by mail agoalofalife@gmail.com


<a name="Installation"></a>
## Installation
composer require agoalofalife/bpm-online

To work correctly you need to write two providers if file app/config.php : 

	agoalofalife\bpmOnline\bpmOnlineServiceProvider::class
        agoalofalife\bpmOnline\bpmRegisterServiceProvider::class

And one facade : 

	CookieBpm' => agoalofalife\bpmOnline\Facade\Authentication::class


<a name="Configuration"></a>
## Configuration

You will need to set up your configuration file

	 php artisan config:publish 


<a name="Bases request"></a>
## Bases request

Protocol On Date offers four types of request
Select, Create, Delete and Update.

This package supports these features
At the heart of any query is creating a base class

	$api = new ApiBpm('Case', 'xml');
	
The first parameter is a collection if you do not know this term, read the documentation
###### The first parameter can be empty only if you want all collections


The second parameter specifies the format of the request, there are only two XML and Json
	

<a name="Select"></a>
### Select
Sample rich methods
	amount  	  : If you want the request to return more than 40 records at a time, it can be implemented using the parameter $ top
	
	skip    	  : In bpm'online support the use of parameter $ the skip , which allows you to query the service resources , skipping the specified number of entries.
	
	orderby 	  : Service resources can be obtained in the form of sort.
	
	filterConstructor : Request the type of filter,Design   filterConstructor allows you to build logical expressions the conditions selecting the desired object , Expressions filterConstructor can be used to reference the properties and literals , as well as strings, numbers and Boolean expressions (true, false). Expressions $ filter supports arithmetic , logical operations , and operations groups ,strings , date and time of the operation.
	
	guid		  :  For a sample of a particular object on the guid
	


Here are some examples for a sample
	$api = new ApiBpm('Case', 'xml'); 
    	$api->select()->run();// method run necessarily


more complex structures..

	     $api        = new ApiBpm('Case','json');
	     $api->select()->guid('00000000-0000-0000-0000-000000000000')->run();


 	    $api         = new ApiBpm('Case','json');
	    $api->select()->filterConstructor('Id eq guid\'00000000-0000-0000-0000-000000000000\'')->run();

  	    $api         = new ApiBpm('Case','json');
	    $api->select()->orderby('Number','desc')->run();

	    $api         = new ApiBpm('Case','json');
	    $api->select()->skip(40)->amount(2)->orderby('Number','desc')->run();		



<a name="Create"></a>
### Create
	
 	  $api    = new ApiBpm('Case', 'xml'); 
	  $result = $newCase->create()->run($data); // in method run pass an array

	  $data   = [
        'CategoryId'   => '00000000-0000-0000-0000-000000000000',
        'StatusId'     => '00000000-0000-0000-0000-000000000000',
        'Subject'      => 'Hello',
        'Symptoms'     => 'Hello',
        "OriginId"     => "00000000-0000-0000-0000-000000000000",
        "ContactId"    => "00000000-0000-0000-0000-000000000000"
    		   ];



<a name="Update"></a>
### Update

 	  $api    = new ApiBpm('Case', 'xml'); // or json
	  $result = $newCase->update()->guid('00000000-0000-0000-0000-000000000000')->run($data);// in method run pass an array


<a name="Delete"></a>
### Delete
Removal is quite simple

	 $api    = new ApiBpm('Case', 'xml'); 
         $api->delete()->guid('00000000-0000-0000-0000-000000000000')->run();
       
<a name="Handler"></a>
### Handler  Responce

Each request code obrabavtyvaet answer in the form of easy to read, there are a few answers custom transformations

   For XML : 
	 ->getData()
	 
   For JSON : 
	->toArray()
	->json()
	
  For All  :  
	->CollectData()
	
	
for example..

	   $api        = new ApiBpm('Case','xml');
           $result     = $api->select()->skip(40)->amount(2)->orderby('Number','desc')->run()->CollectData();
           
		or

	  $result      = $api->select()->skip(40)->amount(2)->orderby('Number','desc')->run()->getData();


