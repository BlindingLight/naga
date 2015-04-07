<?php

return [
	// enable email layer?
	'enabled' => false,
	// connections
	'connections' => [
		'default' => (object)[
			'connectionClass' => '\Naga\Core\Email\SwiftMailConnection',
			'smtpHost' => '',
			'smtpPort' => 465,
			'smtpAuthType' => 'ssl',
			'smtpUser' => '',
			'smtpPassword' => '',
			'senderName' => '',
			'senderEmail' => ''
		]
	]
];