<?php
namespace App\Services;

use App\Models\EmailVerification;
use App\Models\User;
use App\Models\Agency;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

require_once __DIR__.'/../../vendor/sendgrid/sendgrid/lib/SendGrid.php';
require_once __DIR__.'/../../vendor/sendgrid/sendgrid/lib/Client.php';


class EmailsService{
    /**
     * This function is used to send an email using SendGrid
     *
     * @param $to
     * @param $from
     * @param $subject
     * @param $html
     * @return bool
     */
    public function send($to, $from, $subject, $html,$invitedBy)
    {
        $sendGridApiKey = "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";

        //Solution to instantiate a class which have d/t namespace from namespaced class
        $sendGridClass = new \ReflectionClass('SendGrid');
        $sendGrid = $sendGridClass->newInstanceArgs(array($sendGridApiKey));

        $emailClass  = new \ReflectionClass('\\SendGrid\\Email');
        $email = $emailClass->newInstance();

        $email->addTo($to)
            ->setFrom($from)
            ->setSubject($subject)
            ->addSubstitution("%AdminName%",array($invitedBy))
            ->setHtml(' ')
            ->setTemplateId('45bd4441-12f8-4b18-82dd-03256f261876');

        $sendGrid->send($email);

        return true;
    }
}