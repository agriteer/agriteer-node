<?php

namespace EmailService\Http\Controllers;

use EmailService\Email;
use EmailService\Message;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Facades\Log;


class EmailService 
{
    protected $msg;

    public function __construct(Message $msg)
    {
        $this->log = $msg;    
    }

    public function contact(Request $req)
    {
        $body = [
            'from' => [ 'email' => 'test_mail@agriteer.com', 'name' => 'Agriteer Support' ],
            'to' => [ 'email' => $req->email, 'name' => $req->name ],
            'subject' => 'Re: ' . $req->subject,
            'message' => "<div><p>Thanks for contacting Agriteer ". $req->name ."</p><p>Due to an increase in support volume, it may take longer than usual for our team to reply. We apologize for any inconvenience, and will get back to you as soon as we can.</p><p>If you'd like send more information, kindly reply to this email.</p><p>Agriteer Team</p></div>"
        ];

        $body2 = [
            'from' => [ 'email' => 'test_mail@agriteer.com', 'name' => 'Contact Form' ],
            'to' => [ 'email' => 'support@agriteer.com', 'name' => $req->name ],
            'subject' => $req->subject,
            'message' => "<div><p><strong>Name: </strong>".$req->name."</p><p><strong>Email: </strong>".$req->email."</p><p><strong>Subject: </strong>".$req->subject."</p><p><strong>Message: </strong>". $req->message ."</p></div>"
        ];

        $send = $this->send($body);
        $send = $this->send($body2);
        
        return response()->json($send);
    }

    public function sendmail($from, $nameFrom, $to,$nameto,$subject,$message)
    {
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
        elseif ($from === env('MAIL_5_SENDER')) {
            $password = 'MAIL_5_PASSWORD';
        }
        $mail->Password   = env($password);
        $mail->SMTPSecure = "ssl";
        $mail->setFrom($from,$namefrom);
        $mail->Subject  = $subject;
        $mail->isHTML();
        $mail->Body = $message;
        $mail->AltBody  = $message;
        $mail->addAddress($to, $nameto);

        $mail->send();
    }

    public function send($request)
    {
        $mail = $this->sendmail(
            $request['from']['email'],
            $request['from']['name'],
            $request['to']['email'],
            $request['to']['name'],
            $request['subject'],
            $request['message']
        );
        
        if (!$mail) {
            return $this->createMessage($request, false);
        }

        return $this->createMessage($request, true);
    }

    public function createMessage($request, $status = true)
    {
        $data = [
            'to' => $request['to']['email'],
            'nameTo' => $request['to']['name'],
            'from' => $request['from']['name'],
            'nameFrom' => $request['from']['name'],
            'subject' => $request['subject'],
            'message' => $request['message'],
            'status' => $status
        ];

        $this->log->create($data);

        $response = ($status === false) ? 'Error saving email' : 'Message successfully sent';

        return ['status_code' => 200, 'message' => $response];
    }
}