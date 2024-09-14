document.addEventListener('DOMContentLoaded', function () {
    resetSession();
});

function resetSession() {
    const resetUrl = document.getElementById('python_url_reset').value;
    fetch(resetUrl, {
        method: 'GET',
        credentials: 'include'  // Asegura que las cookies sean enviadas con la solicitud
    })
        .then(response => response.json())
        .then(data => {
            //console.log('Sesión reiniciada:', data.message);
            //document.getElementById('messages').innerHTML = '';  // Limpia el chat
            //addMessageToChat('assistant', '¡Hola! La sesión ha sido reiniciada. ¿Cómo puedo ayudarte hoy?');
        })
        .catch(error => {
            console.error('Error al reiniciar la sesión:', error);
            addMessageToChat('assistant', 'Error al conectar con el servicio para reiniciar. Inténtalo más tarde.');
        });
}

function handleKeyPress(event) {
    if (event.key === 'Enter') {
        event.preventDefault(); // Evita el envío del formulario
        document.getElementById('send-button').click(); // Simula un click en el botón de enviar
    }
}

function addMessageToChat(sender, text) {
    const messageDiv = document.createElement('div');
    messageDiv.classList.add('flex', sender === 'user' ? 'justify-end' : 'justify-start');
    const iconHtml = sender === 'user' ? '<i class="fas fa-user message-icon"></i>' :
        '<i class="fas fa-robot message-icon"></i>';
    const bgColorClass = sender === 'user' ? 'bg-gray-100 text-gray-800' : 'bg-blue-100 text-blue-900';
    const messageContent = `
    <div class="${bgColorClass} max-w-xs lg:max-w-md p-3 rounded-lg flex items-center">
        ${iconHtml}
        <span>${text}</span>
    </div>
`;
    messageDiv.innerHTML = messageContent;
    document.getElementById('messages').appendChild(messageDiv);
    document.getElementById('chat-container').scrollTop = document.getElementById('chat-container').scrollHeight;
}

function simulateReply(userInput) {
    // Muestra el indicador de escribiendo
    document.getElementById('typing-indicator').classList.remove('hidden-animation');

    // Prepara la solicitud POST incluyendo credenciales para cookies de sesión
    fetch(document.getElementById('python_url').value, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            prompt: userInput,
            user_id: document.getElementById('user_id_token').value,  // Envía el user_id encriptado
            user_name: document.getElementById('first_name').value  // Envía el user_id encriptado
        }),
        credentials: 'include' // Asegura que las cookies sean enviadas con la solicitud
    })
        .then(response => response.json())
        .then(data => {
            // Asume que la respuesta del servidor es un JSON con una clave 'response'
            const nextReply = data.response || 'Lo siento, no pude entender eso. ¿Podrías intentar de nuevo?';

            addMessageToChat('assistant', nextReply);
            document.getElementById('typing-indicator').classList.add('hidden-animation');
        })
        .catch(error => {
            console.error('Error:', error);
            addMessageToChat('assistant', 'Error al conectar con el servicio. Inténtalo más tarde.');
            document.getElementById('typing-indicator').classList.add('hidden-animation');
        });
}

document.getElementById('send-button').addEventListener('click', function () {
    const userInput = document.getElementById('user-input').value.trim();
    if (userInput !== '') {
        addMessageToChat('user', userInput);
        document.getElementById('user-input').value = '';
        simulateReply(userInput);
    }
});

// Opcional: Inicia la conversación con un mensaje de bienvenida del asistente.
addMessageToChat('assistant', '¡Hola! Soy el asistente virtual de La Familia. ¿Cómo puedo ayudarte hoy?');

/*
// Define las respuestas del asistente y el mensaje por defecto para consultas no reconocidas.
const assistantReplies = [{
        query: 'hola',
        reply: '¡Hola! Soy el asistente virtual de La Familia. ¿Cómo puedo ayudarte hoy?'
    },
    {
        query: 'promoción',
        reply: 'Tenemos una gran promoción de pollo a la brasa con un 20% de descuento.'
    },
    {
        query: 'pedido',
        reply: 'Puedes hacer tu pedido en línea a través de nuestra web o llamarnos directamente.'
    },
    {
        query: 'tiempo',
        reply: 'Excelente, tu pedido estará listo en 30 minutos. ¿Puedo ayudarte en algo más?'
    }
];
const defaultMessage = 'Parece que no te entendí, ¿puedes reformular tu pregunta?';

function handleKeyPress(event) {
    if (event.key === 'Enter') {
        event.preventDefault(); // Evita el envío del formulario
        document.getElementById('send-button').click(); // Simula un click en el botón de enviar
    }
}

function addMessageToChat(sender, text) {
    const messageDiv = document.createElement('div');
    messageDiv.classList.add('flex', sender === 'user' ? 'justify-end' : 'justify-start');
    const iconHtml = sender === 'user' ? '<i class="fas fa-user message-icon"></i>' :
        '<i class="fas fa-robot message-icon"></i>';
    const bgColorClass = sender === 'user' ? 'bg-gray-100 text-gray-800' : 'bg-blue-100 text-blue-900';
    const messageContent = `
<div class="${bgColorClass} max-w-xs lg:max-w-md p-3 rounded-lg flex items-center">
    ${iconHtml}
    <span>${text}</span>
</div>
`;
    messageDiv.innerHTML = messageContent;
    document.getElementById('messages').appendChild(messageDiv);
    // Asegura que el contenedor de chat se desplace hacia abajo para mostrar el último mensaje.
    document.getElementById('chat-container').scrollTop = document.getElementById('chat-container').scrollHeight;
}

function simulateReply(userInput) {
    // Muestra el indicador de escribiendo
    document.getElementById('typing-indicator').classList.remove('hidden-animation');

    // Busca una respuesta basada en la entrada del usuario
    const reply = assistantReplies.find(r => userInput.toLowerCase().includes(r.query.toLowerCase()));
    const nextReply = reply ? reply.reply : defaultMessage;

    // Espera 2 segundos antes de mostrar la respuesta y ocultar el indicador
    setTimeout(() => {
        addMessageToChat('assistant', nextReply);
        document.getElementById('typing-indicator').classList.add('hidden-animation');
    }, 2000); // Ajusta este tiempo según sea necesario
}

document.getElementById('send-button').addEventListener('click', function() {
    const userInput = document.getElementById('user-input').value.trim();
    if (userInput !== '') {
        addMessageToChat('user', userInput);
        document.getElementById('user-input').value = '';
        simulateReply(userInput);
    }
});

// Opcional: Inicia la conversación con un mensaje de bienvenida del asistente.
addMessageToChat('assistant', '¡Hola! Soy el asistente virtual de La Familia. ¿Cómo puedo ayudarte hoy?');*/