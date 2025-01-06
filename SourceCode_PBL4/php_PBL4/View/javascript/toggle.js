const body = document.querySelector("body");
const sidebar = document.querySelector(".sidebar");
const toggle = document.querySelector(".toggle");
const modeswitch = document.querySelector(".toggle-switch");
const modetext = document.querySelector(".mode-text");
const logoImg = document.getElementById("logo-img");
const home = document.querySelector(".home")

toggle.addEventListener("click", () => {
    sidebar.classList.toggle("close");
});
 
modeswitch.addEventListener("click", () => {
    body.classList.toggle("dark");

    if (body.classList.contains("dark")){
        modetext.innerText = "Light";
        logoImg.src = "image/Nihongo.png"; 
    }
    else {
        modetext.innerText = "Dark";
        logoImg.src = "image/Nihongo2.png"; 
    }

});

document.addEventListener("DOMContentLoaded", function() {
    const links = document.querySelectorAll('.sidebar a');
    const activeLink = localStorage.getItem('activeLink');
    if (activeLink) {
        links.forEach(function(link) {
            if (link.getAttribute('href') === activeLink) {
                link.classList.add('active-link');
            } else {
                link.classList.remove('active-link'); 
            }
        });
    }

    links.forEach(function(link) {
        link.addEventListener('click', function() {
            localStorage.setItem('activeLink', link.getAttribute('href'));
            links.forEach(l => l.classList.remove('active-link'));
            link.classList.add('active-link');
        });
    });
});


