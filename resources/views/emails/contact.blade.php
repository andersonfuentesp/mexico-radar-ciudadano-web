<x-mail::message>
# Nuevo mensaje de contacto de {{ $nombre }}

**Asunto:** {{ $asunto }}

**Nombre:** {{ $nombre }}

**Teléfono:** {{ $telefono }}

**El usuario escribió:**
{{ $mensaje }}

Se recomienda responder al usuario lo antes posible.

Saludos cordiales,<br>
{{ config('app.name') }}
</x-mail::message>