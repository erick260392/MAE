<?php

namespace App\Notifications;

use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewQuoteReceived extends Notification
{
    use Queueable;

    public function __construct(public Quote $quote) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $quote = $this->quote->load('customer', 'items.product');

        $message = (new MailMessage)
            ->subject("Nueva cotización {$quote->folio} — {$quote->customer->name}")
            ->greeting('¡Nueva cotización recibida!')
            ->line('Se ha recibido una nueva cotización desde el catálogo público.')
            ->line("**Folio:** {$quote->folio}")
            ->line("**Cliente:** {$quote->customer->name}".($quote->customer->company ? " ({$quote->customer->company})" : ''))
            ->line("**Teléfono:** {$quote->customer->phone}");

        if ($quote->customer->email) {
            $message->line("**Correo:** {$quote->customer->email}");
        }

        if ($quote->customer->city) {
            $message->line("**Ciudad:** {$quote->customer->city}");
        }

        if ($quote->notes) {
            $message->line("**Notas:** {$quote->notes}");
        }

        $message->line('**Productos solicitados:**');

        foreach ($quote->items as $item) {
            $message->line("- {$item->product->name} × {$item->quantity} {$item->product->unit}");

            if ($item->notes) {
                $message->line("  Detalles: {$item->notes}");
            }
        }

        return $message
            ->action('Ver cotización en el panel', route('admin.quotes.show', $quote))
            ->salutation('Mangueras y Conexiones MAE');
    }
}
