function sendMessage(question = null) {
    let chatBody = document.getElementById("chat-body");
    let message = question ? question : document.getElementById("user-input").value.trim();
    if (message === "") return;

    // Add user message
    let userMessageDiv = document.createElement("div");
    userMessageDiv.classList.add("user-message");
    userMessageDiv.textContent = message;
    chatBody.appendChild(userMessageDiv);

    // Clear input field
    if (!question) document.getElementById("user-input").value = "";

    // Scroll to bottom
    chatBody.scrollTop = chatBody.scrollHeight;

    // Get chatbot response
    setTimeout(() => {
        let botMessageDiv = document.createElement("div");
        botMessageDiv.classList.add("bot-message");
        botMessageDiv.textContent = getBotResponse(message);
        chatBody.appendChild(botMessageDiv);

        // Remove old suggestions
        let oldSuggestions = document.querySelector(".suggestions");
        if (oldSuggestions) oldSuggestions.remove();

        // Show new suggestions
        setTimeout(() => {
            showSuggestions();
        }, 500);

        chatBody.scrollTop = chatBody.scrollHeight;
    }, 1000);
}

// Function to get chatbot responses
function getBotResponse(input) {
    let responses = {
        "What courses are available?": "We offer Computer Science, IT, ECE, EEE, Civil, Mechanical, and AI & DS.",
        "What is the admission process?": "The admission process includes eligibility check, application submission, and counseling.",
        "What is the fee structure?": "The fee varies based on the course and category. Visit our website for details.",
        "Do you provide scholarships?": "Yes, we offer scholarships based on merit and financial need.",
        "How can I contact the college?": "You can contact us via email at info@college.com or call +91 9876543210.",
        "How does the college recommendation system work?": "Our system filters colleges based on cutoff marks, caste, location, and department.",
        "Can I view colleges on a map?": "Yes! We provide a map-based interface to explore colleges visually.",
        "How do I calculate my cutoff marks?": "Cutoff marks are calculated as: (Maths / 2) + (Physics / 4) + (Chemistry / 4).",
        "Which colleges accept my cutoff score?": "Enter your cutoff, caste, and department. Our system will filter suitable colleges.",
        "What is the minimum cutoff for top engineering colleges?": "For CSE, it's around 190+ for top-tier colleges. It varies for other departments.",
        "Can I apply for multiple colleges?": "Yes, you can apply to multiple colleges based on eligibility and availability.",
        "Is hostel facility available?": "Yes, most colleges provide hostel facilities. Check the respective college details.",
        "What are the top colleges for Computer Science?": "Top CSE colleges include CEG, PSG, SRM, VIT, and SSN.",
        "Do private colleges have entrance exams?": "Some private colleges conduct their own exams, while others accept direct admission.",
        "What are the placement opportunities?": "Most colleges have placement cells with companies visiting for recruitment every year.",
        "Can I get an education loan?": "Yes, banks provide loans based on admission letters. Scholarships can also help reduce costs.",
        "How to check if a college is AICTE-approved?": "Visit the AICTE website or our platform to verify accreditation details.",
        "Do government colleges have better faculty than private ones?": "It depends. Government colleges have experienced faculty, but private ones may have better industry exposure.",
        "Can I change my branch after the first year?": "Some colleges allow branch changes based on first-year performance. Check individual policies.",
    };

    return responses[input] || "I'm not sure about that. Can you ask something else?";
}
function showSuggestions() {
    let chatBody = document.getElementById("chat-body");
    let suggestionsDiv = document.createElement("div");
    suggestionsDiv.classList.add("suggestions");

    let questionOptions = [
        "How does the college recommendation system work?",
        "Which colleges accept my cutoff score?",
        "Can I view colleges on a map?",
        "Do you provide scholarships?",
        "What is the fee structure?",
        "What is the admission process?",
        "What are the placement opportunities?",
        "Can I apply for multiple colleges?",
        "Do government colleges have better faculty than private ones?",
        "Is hostel facility available?"
    ];

    // Shuffle and pick 3 questions
    questionOptions.sort(() => Math.random() - 0.5);
    questionOptions.slice(0, 3).forEach((question) => {
        let suggestionDiv = document.createElement("div");
        suggestionDiv.classList.add("suggestion");

        let icon = document.createElement("img");
        icon.src = "icon.jpg";  // Replace with actual icon path
        icon.classList.add("suggestion-icon");

        let button = document.createElement("button");
        button.classList.add("suggestion-text");
        button.textContent = question;
        button.onclick = () => sendMessage(question);

        suggestionDiv.appendChild(icon);
        suggestionDiv.appendChild(button);
        suggestionsDiv.appendChild(suggestionDiv);
    });

    chatBody.appendChild(suggestionsDiv);
}

// Toggle Chatbot Open and Close
function toggleChatbot() {
    let chatbot = document.getElementById("chatbot");
    let chatIcon = document.getElementById("chat-icon");
    if (chatbot.classList.contains("show")) {
        chatbot.classList.remove("show");
        chatIcon.style.transform = "scale(1)";
    } else {
        chatbot.classList.add("show");
        chatIcon.style.transform = "scale(0.9)";
    }
}
