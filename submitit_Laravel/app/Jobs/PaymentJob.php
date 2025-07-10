<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class PaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email, $payment, $packagesData, $isClient;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $payment, $packages, $isClient)
    {
        $this->email = $email;
        $this->payment = $payment;
        $this->packagesData = $packages;
        $this->isClient = $isClient;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->isClient == 1)
        {
            $user_data = $this->payment;
            $password = $this->packagesData;
            Mail::to($this->email)->send(new \App\Mail\SignUpMail($user_data, $password));
        }
        else
        {
            Mail::to($this->email)->send(new \App\Mail\PaymentMail($this->payment, $this->packagesData));
        }
    }
}
