const signinBtn = document.getElementById("signinBtn");
const signupBtn = document.getElementById("signupBtn");
const nameField = document.getElementById("nameField");
const title = document.getElementById("title");

signupBtn.onclick = function () {
    nameField.style.maxHeight = "60px";
    title.textContent = "Register";

    signupBtn.classList.remove("disable");
    signinBtn.classList.add("disable");

    signinBtn.removeAttribute("name");

    signupBtn.setAttribute("type", "submit");
    signupBtn.setAttribute("name", "register");
};

signinBtn.onclick = function () {
    if (title.textContent === "Register") {

        nameField.style.maxHeight = "0";
        title.textContent = "Login";

        signupBtn.classList.add("disable");
        signinBtn.classList.remove("disable");

        signupBtn.setAttribute("type", "button");
        signupBtn.removeAttribute("name");

        signinBtn.setAttribute("name", "login");
    }
};