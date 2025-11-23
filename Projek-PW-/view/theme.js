document.addEventListener("DOMContentLoaded", () => {

    const body = document.body;
    const navbar = document.getElementById("navbar");
    const sidebar = document.getElementById("sidebar");
    const mainContent = document.getElementById("main-content"); 
    const articleTableContainer = document.getElementById("article-table-container");
    
    const profileBtn = document.getElementById("profileDropdownBtn");
    const themeSwitch = document.getElementById("modeSwitch"); // Mengambil elemen switch mode

    // Ambil mode dari localStorage
    let mode = localStorage.getItem("themeMode") || "dark";

    // Terapkan mode saat halaman dimuat
    applyMode(mode);

    // Pastikan themeSwitch ada sebelum menambahkan listener
    if (themeSwitch) {
        themeSwitch.addEventListener("click", () => {
            mode = (mode === "dark") ? "light" : "dark";
            localStorage.setItem("themeMode", mode);
            applyMode(mode);
        });
    }


    function applyMode(mode) {
        // Mendapatkan semua kartu yang mungkin ada di dashboard
        const allCards = document.querySelectorAll(".card-custom"); 

        // === DARK MODE ===
        if (mode === "dark") {
            body.classList.remove("light-mode");
            body.classList.add("dark-mode");

            sidebar?.classList.remove("light-mode");
            sidebar?.classList.add("dark-mode");

            mainContent?.classList.remove("light-mode");
            mainContent?.classList.add("dark-mode"); 

            articleTableContainer?.classList.remove("light-mode");
            articleTableContainer?.classList.add("dark-mode");

            allCards.forEach(card => {
                card.classList.remove("light-mode");
                card.classList.add("dark-mode");
            });

            navbar?.classList.remove("light-mode");
            navbar?.classList.add("dark-mode");

            profileBtn?.classList.remove("light-mode");
            profileBtn?.classList.add("dark-mode");
            
            themeSwitch?.classList.remove("light");
        
        // === LIGHT MODE ===
        } else {
            body.classList.remove("dark-mode");
            body.classList.add("light-mode");

            sidebar?.classList.remove("dark-mode");
            sidebar?.classList.add("light-mode");
            
            mainContent?.classList.remove("dark-mode");
            mainContent?.classList.add("light-mode"); 

            articleTableContainer?.classList.remove("dark-mode");
            articleTableContainer?.classList.add("light-mode");
            
            allCards.forEach(card => {
                card.classList.remove("dark-mode");
                card.classList.add("light-mode");
            });

            navbar?.classList.remove("dark-mode");
            navbar?.classList.add("light-mode");

            profileBtn?.classList.remove("dark-mode");
            profileBtn?.classList.add("light-mode");

            themeSwitch?.classList.add("light");
        }
    }
});