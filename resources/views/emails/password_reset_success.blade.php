<x-mail::message>
# Contraseña Restablecida Exitosamente

Hola {{ $user->name }},

Tu contraseña ha sido restablecida correctamente. Ahora puedes iniciar sesión con tu nueva contraseña.

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

Si no realizaste esta acción, te recomendamos contactar con nuestro equipo de soporte de inmediato.

Saludos cordiales,  
{{ config('app.name') }}
</x-mail::message>
