<?php

namespace EmailService\Http\Controllers;

use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Http\Request;
use EmailService\ErrorLogging;

class EmailService
{
    protected $mail;
    protected $log;

    public function __construct(PHPMailer $email, ErrorLogging $log)
    {
        $this->mail = $email;
        $this->log = $log;
        
    }

    public function send($request)
    {
        $this->mail->setFrom($request->from['email'], $request->from['name']);
        $this->mail->addReplyTo($request->from['email'], $request->from['name']);
        $this->mail->addAddress($request->to['email'], $request->to['name']);

        $this->mail->Subject = $request->subject;
        $this->mail->msgHTML($request->message);
        if ($request->file_path) {
            $this->mail->addAttachment($file_path);
        }

        if (!$this->mail->send()) {
            return $this->createResponse($request->to['email'], $request->from['email'], '400', 'good');
        } else {
            return $this->createResponse($request->to['email'], $request->from['email'], '200', 'Mail sent successfully');
        }
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
