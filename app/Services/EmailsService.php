<?php
namespace App\Services;

use App\Models\EmailVerification;
use App\Models\User;
use App\Models\Agency;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

require 'lib/SendGrid.php';
require 'lib/Client.php';

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
    public function send($to, $from, $subject, $html)
    {
        $sendGridApiKey = "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";
        $sendGrid = new SendGrid($sendGridApiKey);
        $email    = new SendGrid\Email();

        $email->addTo($to)
            ->setFrom($from)
            ->setSubject($subject)
            ->setHtml($html);

        $sendGrid->send($email);

        return true;
    }
}