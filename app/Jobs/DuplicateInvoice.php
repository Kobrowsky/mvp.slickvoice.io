<?php

namespace Sv\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Sv\Invoice;

class DuplicateInvoice extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var Invoice
     */
    protected $invoice;

    /**
     * @var string
     */
    protected $increment;

    /**
     * Create a new job instance.
     *
     * @param Invoice $invoice
     * @param string $increment
     */
    public function __construct(Invoice $invoice, $increment)
    {
        $this->invoice = $invoice;
        $this->increment = $increment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $newInvoice = $this->invoice->replicate();
        $newInvoice->public_id = Invoice::getNextPublicId();
        $newInvoice->due_date = $this->invoice->due_date->{camel_case('add' . $this->increment)}();
        $newInvoice->try_on_date = $newInvoice->due_date;
        $newInvoice->num_tries = 0;
        $newInvoice->status = 'pending';
        $newInvoice->charge_id = null;
        $newInvoice->charge_date = null;
        $newInvoice->charge_fee = 0;
        $newInvoice->repeat = $this->increment;
        $newInvoice->save();

        // replicate invoice items
        foreach ($this->invoice->items as $item) {
            $newInvoice->items()->save($item->replicate());
        }
    }
}
