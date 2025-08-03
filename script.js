<script>
document.getElementById("contactForm").addEventListener("submit", function (e) {
  e.preventDefault(); // prevent page reload

  const form = e.target;
  const formData = new FormData(form);
  const responseDiv = document.getElementById("formResponse");

  fetch("https://netmind.free.nf/form.php", {
    method: "POST",
    body: formData,
  })
    .then(res => res.text())
    .then(data => {
      responseDiv.innerHTML = `<span style="color: green;">Message Sent! ${data}</span>`;
      form.reset();
    })
    .catch(err => {
      responseDiv.innerHTML = `<span style="color: red;">Failed to send. Please try again.</span>`;
      console.error(err);
    });
});
</script>
// JavaScript Document