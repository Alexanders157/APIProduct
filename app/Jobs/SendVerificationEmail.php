<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Notifications\VerifyEmail;

class SendVerificationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle(): void
    {
        try {
            Log::info('Starting SendVerificationEmail for user ID: ' . $this->user->id);
            $this->user->sendEmailVerificationNotification();
            Log::info('Verification email sent for user ID: ' . $this->user->id);
        } catch (\Exception $e) {
            Log::error('Failed to send verification email for user ID: ' . $this->user->id . '. Error: ' . $e->getMessage());
            throw $e;
        }
    }
}
