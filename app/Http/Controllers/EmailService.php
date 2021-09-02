<?php

namespace EmailService\Http\Controllers;

use EmailService\Email;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class EmailService
{
    protected $mail;
    protected $log;

    public function __construct(Email $log)
    {
        $this->log = $log;    
    }

    public function sendmail($from, $nameFrom, $to,$nameto,$subject,$message,$altmess)  {
        $password = '';
        $from  = $from; 
        $namefrom = $nameFrom;
        $mail = new PHPMailer();
        $mail->SMTPDebug = 1;
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->SMTPAuth   = true;
        $mail->Host   = env('MAIL_HOST');
        $mail->Port       = env('MAIL_PORT');
        $mail->Username   = $from;
        if ($from === env('MAIL_1_SENDER')) {
            $password = 'MAIL_1_PASSWORD';
        }
        elseif ($from === env('MAIL_2_SENDER')) {
            $password = 'MAIL_2_PASSWORD';
        }
        elseif ($from === env('MAIL_3_SENDER')) {
            $password = 'MAIL_3_PASSWORD';
        }
        elseif ($from === env('MAIL_4_SENDER')) {
            $password = 'MAIL_4_PASSWORD';
        }
        $mail->Password   = env($password);
        $mail->SMTPSecure = "ssl";
        $mail->setFrom($from,$namefrom);
        $mail->addCC($from,$namefrom);
        $mail->Subject  = $subject;
        $mail->isHTML();
        $mail->Body = $message;
        $mail->AltBody  = $altmess;
        $mail->addAddress($to, $nameto);

        return $mail->send();
    }

    public function send(Request $request)
    {
        $mail = $this->sendmail($request->from['email'],$request->from['name'], $request->to['email'],$request->to['name'],$request->subject,$request->message,$request->message);
        
        if (!$mail) {
            return $this->createResponse($request->to['email'], $request->from['email'], '400', 'Error sending');
        }

        return $this->createResponse($request->to['email'], $request->from['email'], '200', 'Mail sent successfully');
    }

    public function createResponse($to, $from, $code, $response)
    {
        $this->log->create([
            'to' => $to,
            'from' => $from,
            'response' => $response,
            'response_code' => $code
        ]);

        return response()->json(['status_code' => $code, 'message' => $response]);
    }
}