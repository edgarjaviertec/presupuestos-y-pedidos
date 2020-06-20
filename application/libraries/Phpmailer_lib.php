<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'vendor/autoload.php';


class Phpmailer_lib
{
	public function __construct(){
		log_message('Debug', 'PHPMailer class is loaded.');
	}

	public function load(){
		// Include PHPMailer library files
		require_once APPPATH.'third_party/PHPMailer/src/Exception.php';
		require_once APPPATH.'third_party/PHPMailer/src/PHPMailer.php';
		require_once APPPATH.'third_party/PHPMailer/src/SMTP.php';
		$mail = new PHPMailer;
		return $mail;
	}
}
