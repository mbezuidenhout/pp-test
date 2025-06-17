<?php

namespace App\Mail;

use App\Models\Person;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PersonCreatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Person $person;

    private array $autoloadOptions;

    /**
     * Create a new message instance.
     */
    public function __construct(Person $person)
    {
        $this->person = $person;
        $this->autoloadOptions = app('autoload.options');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Person Created Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.person-created',
            with: [
                'person' => $this->person,
                'autoloadOptions' => $this->autoloadOptions,
            ],
        );
    }
}
