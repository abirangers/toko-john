<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\OrderInvoiceMail;
use Illuminate\Support\Facades\Mail;

class SendOrderInvoiceMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $orderDetails;
    /**
     * Create a new job instance.
     */
    public function __construct($email, $orderDetails)
    {
        $this->email = $email;
        $this->orderDetails = $orderDetails;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->email)->send(new OrderInvoiceMail($this->orderDetails));
    }
}