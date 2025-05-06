window.addEventListener("load", () => {
document.getElementById("nav-list").classList.add("nav-list-show");
});
//Añadido manualmente este estilo en javascript ya que había confusión entre archivos.
window.addEventListener("load", () => {
document.querySelectorAll("#nav-list li").forEach(li => {
li.style.opacity = "1";
li.style.transform = "translateY(0)";
});
});

const loginButton = document.getElementById('login-button');
const loginPopup = document.getElementById('login-popup');

console.log(loginPopup.style);
loginButton.addEventListener("click", function () {
console.log("boton pulsado");
loginPopup.style.display = 'flex'; 
loginPopup.style.zIndex ="999";
});

window.addEventListener("click", (event) => {
if (event.target === loginPopup) {
loginPopup.style.display = "none";
}
});