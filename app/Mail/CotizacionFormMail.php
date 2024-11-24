<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CotizacionFormMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $details;

    /**
     * Create a new message instance.
     *
     * @param array $details
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Solicitud de Cotización Recibida',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        // Asegúrate de crear la vista 'emails.cotizacion' en resources/views/emails/cotizacion.blade.php
        return new Content(
            markdown: 'emails.cotizacion', // El nombre de la vista Blade para el correo
            with: [
                'nombre' => $this->details['name'],
                'telefono' => $this->details['phone'],
                'email' => $this->details['email'],
                'servicios' => $this->details['services'], // Asegúrate de que estos índices coincidan con los nombres de los campos de tu formulario
                'descripcionProyecto' => $this->details['project_description'],
                'fechaInicio' => $this->details['start_date'] ?? 'No especificado', // Asumiendo que este campo podría no ser obligatorio
                'presupuestoEstimado' => $this->details['estimated_budget'] ?? 'No especificado',
                'comoNosEncontraron' => $this->details['discovery_method'],
                'otrosServicios' => $this->details['other_services'] ?? 'No especificado', // En caso de que hayan elegido "Otro" en los servicios
                'otrosDiscoveryMethod' => $this->details['other_discovery_method'] ?? 'No especificado', // En caso de que hayan elegido "Otro" en cómo nos encontraron
                'comentariosAdicionales' => $this->details['additional_comments'] ?? 'N/A',
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
