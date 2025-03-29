<?php

namespace App\Jobs;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketPurchased;

class SendTicketConfirmation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function handle(): void
    {
        try {
            Log::info('Starting SendTicketConfirmation for ticket ID: ' . $this->ticket->id);
            Mail::to($this->ticket->user->email)->send(new TicketPurchased($this->ticket));
            Log::info('Ticket confirmation email sent for ticket ID: ' . $this->ticket->id);
        } catch (\Exception $e) {
            Log::error('Failed to send ticket confirmation for ticket ID: ' . $this->ticket->id . '. Error: ' . $e->getMessage());
            throw $e; // Повторно выбрасываем исключение, чтобы задача попала в failed_jobs
        }
    }
}
