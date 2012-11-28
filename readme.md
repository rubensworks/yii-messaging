Messaging
===========

A Yii plugin that allows you to easily create a messaging/chat functionality into your 
existing User-based application.

Installation
------------

TODO
Unpack to `protected/extensions/`. Add the following to your `protected/config/main.php`:

~~~
<?php
return array(
	// …
	'modules'=>array(
                ...
		'messaging'=>array(
				'class'=>'ext.messaging.MessagingModule',
				'userModel'=>'[YOUR USER MODEL]',
				'getNameMethod'=>'[METHOD TO GET THE USER NAME IN THE USER MODEL]',
				'isOnlineMethod'=>'[METHOD TO CHECK IF THE USER IS ONLINE IN THE USER MODEL]',
			),
		),
                ...
);
~~~