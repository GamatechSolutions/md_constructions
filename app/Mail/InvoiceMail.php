<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Invoice;

class InvoiceMail extends Mailable
{
	use Queueable, SerializesModels;
	
	private $invoice;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
		$data = [
			'invoice' => $this->invoice
		];

		$email = $this->view('invoice.mail')->with($data);

		$email->attachFromStorage("invoices/{$this->invoice->pdf_document}", "Faktura {$this->invoice->id}");

		return $email;
    }
}
