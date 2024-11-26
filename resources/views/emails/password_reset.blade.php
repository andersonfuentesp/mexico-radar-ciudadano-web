<x-mail::message>
# Código de Recuperación de Contraseña

Hola,

Recibimos tu solicitud para restablecer la contraseña. Por favor, utiliza el siguiente código en tu aplicación para continuar con el proceso:

<x-mail::panel>
**{{ $code }}**
</x-mail::panel>

Si no solicitaste este cambio, puedes ignorar este correo.

Saludos cordiales,  
{{ config('app.name') }}
</x-mail::message>
