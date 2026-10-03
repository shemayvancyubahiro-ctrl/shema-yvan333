const sendBtn = document.getElementById("sendBtn");
const statusText = document.getElementById("status");

sendBtn.addEventListener("click", async function () {
  const name = document.getElementById("name").value.trim();
  const email = document.getElementById("email").value.trim();
  const message = document.getElementById("message").value.trim();

  if (name === "" || message === "") {
    statusText.textContent = "Please write your name and a message.";
    statusText.style.color = "red";
    return;
  }

  sendBtn.disabled = true;
  statusText.textContent = "Sending...";
  statusText.style.color = "black";

  try {
    const response = await fetch("submit.php", {
      method: "POST",
      body: new URLSearchParams({ name: name, email: email, message: message })
    });
    const result = await response.json();

    if (!result.ok) {
      throw new Error("Not saved");
    }

    statusText.textContent = "Thank you! Your message was sent.";
    statusText.style.color = "green";
    document.getElementById("name").value = "";
    document.getElementById("email").value = "";
    document.getElementById("message").value = "";
  } catch (error) {
    console.error(error);
    statusText.textContent = "Sorry, the message could not be sent. Try again.";
    statusText.style.color = "red";
  }

  sendBtn.disabled = false;
});
<script src="contact.js"></script>