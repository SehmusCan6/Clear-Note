function toggleChatbot() {
    var chatbot = document.getElementById("chatbot");
    chatbot.style.display = (chatbot.style.display === "none" || chatbot.style.display === "") ? "block" : "none";
}

function sendChat(message) {
    var messagesContainer = document.getElementById("chatbot-messages");


    var userMessage = document.createElement("div");
    userMessage.style.textAlign = "right";
    userMessage.innerHTML = "<strong>You:</strong> " + message;
    messagesContainer.appendChild(userMessage);


    var botResponse = document.createElement("div");
    botResponse.style.textAlign = "left";
    botResponse.innerHTML = "<strong>Bot:</strong> " + getBotResponse(message);
    messagesContainer.appendChild(botResponse);


    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function getBotResponse(message) {
    var responses = {
        "What is Clear Notes": "Clear Notes is a note-taking app that helps you create, organize, and store notes easily.",
        "Which devices can I use it on?": "You can use it on web browsers, Android, and iOS devices.",
        "Is the app free, or does it have a paid version?": "It has a free version with basic features and a paid version with extra tools.",
        "Can I organize my notes into categories?": "Yes, you can create folders and tags to organize your notes.",
        "How can I contact customer support?": "You can reach our support team at email@example.com."
    };

    message = message.trim();
    return responses[message] || "Sorry, I don't understand that. Try asking something else!";
}