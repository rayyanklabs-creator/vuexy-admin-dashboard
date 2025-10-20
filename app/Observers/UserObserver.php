<?php

namespace App\Observers;

use App\Mail\UserCredentialMail;
use App\Models\Profile;
use App\Models\user;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UserObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the user "created" event.
     */
    public function created(user $user): void
    {
        $creator = Auth::user();
        if ($creator->can('create user') || $creator->hasRole([User::ADMIN, USER::SUPER_ADMIN])) {
            if (isset($user->password)) {
                Mail::to($user->email)->send(new UserCredentialMail(
                    $user->name,
                    $user->email,
                    $user->password
                ));
            }
            return;
        }
        if (Auth::check()) {
            VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
                return (new MailMessage)
                    ->subject('Verify Your Email Address')
                    ->line('Thanks for registering!')
                    ->action('Verify Email', $url);
            });
        }

        $user->sendEmailVerificationNotification();
    }

    /**
     * Handle the user "updated" event.
     */
    public function updated(user $user): void {}

    /**
     * Handle the user "deleted" event.
     */
    public function deleted(user $user): void
    {
        //
    }

    /**
     * Handle the user "restored" event.
     */
    public function restored(user $user): void
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     */
    public function forceDeleted(user $user): void
    {
        if ($user->profile) {
            $user->profile->delete();
        }
    }
}
