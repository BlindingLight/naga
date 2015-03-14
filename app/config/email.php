<?php

return array(
	// enable email layer?
	'enabled' => false,
	// connections
	'connections' => array(
		'default' => (object)array(
			'connectionClass' => '\Naga\Core\Email\SwiftMailConnection',
			'smtpHost' => '',
			'smtpUser' => '',
			'smtpPassword' => '',
			'smtpAuthType' => 'ssl',
			'smtpPort' => 465,
			'senderEmail' => '',
			'senderName' => ''
		)
	)
);