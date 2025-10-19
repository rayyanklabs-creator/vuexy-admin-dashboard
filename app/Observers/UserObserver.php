<?php

namespace App\Observers;

use App\Mail\UserCredentialMail;
use App\Models\Profile;
use App\Models\user;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UserObserver
{
    /**
     * Handle the user "created" event.
     */
    public function created(user $user): void
    {
        Profile::create([
            'user_id'    => $user->id,
            'first_name' => explode(' ', $user->name)[0] ?? '',
            'last_name'  => explode(' ', $user->name)[1] ?? '',
        ]);

        // If the admin created a role
        if (isset($user->assigned_role)) {
            $user->syncRoles($user->assigned_role);

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
    public function updated(user $user): void
    {
        //
    }

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
        //
    }
}
