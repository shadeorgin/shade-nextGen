document.addEventListener("DOMContentLoaded", function () {
    const content = document.getElementById("content"); 
    const links = document.querySelectorAll("a[data-page],button[data-page]");
    const navbarCollapse = document.querySelector(".navbar-collapse"); 

    function loadPage(page) {
        fetch(`pages/${page}.html`)
          .then(response => response.text())
          .then(data => {
            content.innerHTML = data;
            if (page === "home") {
              const learnMoreBtn = document.getElementById("learn_more");
              const missionSection = document.getElementById("mission");
    
              if (learnMoreBtn && missionSection) {
                learnMoreBtn.addEventListener("click", () => {
                  missionSection.scrollIntoView({ behavior: "smooth", block: "start" });
                });
              }
            }
          })
          .catch(error => {
            content.innerHTML = "<h2>Page not found!</h2>";
          });
      }
    links.forEach(link => {
        link.addEventListener("click", function (event) {
            event.preventDefault();
            const page = this.getAttribute("data-page");
            loadPage(page);
            if (navbarCollapse.classList.contains("show")) {
                const bsCollapse = new bootstrap.Collapse(navbarCollapse, { toggle: true });
                bsCollapse.hide();
            }
        });
    });
    loadPage("home");
});