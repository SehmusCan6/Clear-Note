
    function validateRegisterForm() {
    const name = document.forms[0]["name"].value.trim();
    const surname = document.forms[0]["surname"].value.trim();
    const username = document.forms[0]["username"].value.trim();
    const email = document.forms[0]["email"].value.trim();
    const password = document.forms[0]["password"].value;


    const namePattern = /^[A-Za-zğüşöçİĞÜŞÖÇ\s]+$/;

    if (!namePattern.test(name)) {
    alert("Name should only contain letters.");
    return false;
}

    if (!namePattern.test(surname)) {
    alert("Surname should only contain letters.");
    return false;
}

    if (username.length < 4 || !/^[a-zA-Z0-9_]+$/.test(username)) {
    alert("Username must be at least 4 characters and contain only letters, numbers, or underscores.");
    return false;
}


    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
    alert("Please enter a valid email address.");
    return false;
}


    const passwordPattern = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/;
    if (!passwordPattern.test(password)) {
    alert("Password must be at least 6 characters long and include both letters and numbers.");
    return false;
}


    return true;
}

