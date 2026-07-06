<?php

namespace App\Mail;

use App\Models\Policy;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PolicyIssuedMail extends Mailable
{
    use Queueable, SerializesModels;
    public function __construct(public Policy $policy, public string $docxPath) {}
    public function envelope(): Envelope { return new Envelope(subject: 'Ваш полис № '.$this->policy->number); }
    public function content(): Content { return new Content(view: 'emails.policy_issued', with: ['policy'=>$this->policy]); }
    public function attachments(): array { return [ Attachment::fromPath($this->docxPath) ]; }
}
