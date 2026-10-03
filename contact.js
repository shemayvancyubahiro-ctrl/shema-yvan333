const sendBtn = document.getElementById("sendBtn");
const statusText = document.getElementById("status");

sendBtn.addEventListener("click", function () {
  const name = document.getElementById("name").value.trim();
  const email = document.getElementById("email").value.trim();
  const message = document.getElementById("message").value.trim();

  if (name === "" || message === "") {
    statusText.textContent = "Please enter your name and message.";
    statusText.style.color = "red";
    return;
  }

  const formData = new FormData();
  formData.append("name", name);
  formData.append("email", email);
  formData.append("message", message);

  fetch("submit.php", {
    method: "POST",
    body: formData
  })
    .then(function (response) { return response.json(); })
    .then(function (data) {
      if (data.ok) {
        statusText.textContent = "Thank you! Your message was sent.";
        statusText.style.color = "green";
        document.getElementById("name").value = "";
        document.getElementById("email").value = "";
        document.getElementById("message").value = "";
      } else {
        statusText.textContent = "Sorry, something went wrong: " + data.error;
        statusText.style.color = "red";
      }
    })
    .catch(function () {
      statusText.textContent = "Could not reach the server. Is Apache running?";
      statusText.style.color = "red";
    });
});