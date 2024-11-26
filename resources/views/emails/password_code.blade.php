<x-mail::message>
# Código de Recuperación de Contraseña

Hola {{ $user->name }},

Recibimos tu solicitud para restablecer la contraseña. Por favor, utiliza el siguiente código en tu aplicación para continuar con el proceso:

<x-mail::panel>
**{{ $code }}**
</x-mail::panel>

### Información del Usuario
- **Correo Electrónico:** {{ $user->email }}
- **Estado del Usuario:** 
  @if ($user->status === 'active')
    Activo
  @elseif ($user->status === 'inactive')
    Inactivo
  @else
    No especificado
  @endif

Si no solicitaste este cambio, puedes ignorar este correo.

Saludos cordiales,  
{{ config('app.name') }}
</x-mail::message>
