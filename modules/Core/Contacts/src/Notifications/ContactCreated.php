<?php

namespace Modules\Core\Contacts\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Core\Contacts\Helpers\TemplateRenderer;
use Modules\Core\Contacts\Models\Contact;

class ContactCreated extends Notification
{
    use Queueable;

    protected $contact;
    protected int $tenantId;

    public function __construct(Contact $contact, int $tenantId)
    {
        $this->contact = $contact;
        $this->tenantId = $tenantId;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $template = $this->getTemplate('mail');

        $data = $this->prepareTemplateData();
        $subject = TemplateRenderer::render($template['subject'] ?? 'New Contact Created', $data);
        $body = TemplateRenderer::render($template['template'], $data);

        return (new MailMessage)
            ->subject($subject)
            ->line($body)
            ->action('View Contact', url('/contacts/' . $this->contact['id']));
    }

    public function toArray($notifiable)
    {
        return [
            'contact_id' => $this->contact['id'],
            'name' => $this->contact['first_name'] . ' ' . $this->contact['last_name'],
            'type' => $this->contact['contact_type'],
        ];
    }

    protected function getTemplate($channel): array
    {
        return [] ?? $this->defaultTemplate($channel);
    }

    protected function defaultTemplate($channel): array
    {
        return [
            'subject' => 'New Contact Created',
            'template' => 'A new contact named {first_name} {last_name} ({contact_type}) has been added.',
        ];
    }

    protected function prepareTemplateData(): array
    {
        $data = $this->contact;
        $data['full_name'] = trim(($this->contact['salutation'] ?? '') . ' ' . $this->contact['first_name'] . ' ' . $this->contact['last_name'] . ' ' . ($this->contact['suffix'] ?? ''));
        return $data;
    }
}
