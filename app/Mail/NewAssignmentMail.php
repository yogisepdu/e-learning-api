<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Assignment;

class NewAssignmentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $assignment;
    /**
     * Create a new message instance.
     */
    public function __construct(Assignment $assignment)
    {
        //
        $this->assignment = $assignment;
    }

    public function build()
    {
        return $this->subject('Tugas Baru Tersedia')
                    ->view('emails.new_assignment');
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Assignment Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.new_assignment',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
