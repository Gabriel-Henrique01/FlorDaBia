function toggleMenu() {
    const menuItems = document.getElementById("menuItems");
    const menuToggle = document.getElementById("menuToggle");
    
    menuItems.classList.toggle("active");

    // Altera a imagem do ícone
    if (menuItems.classList.contains("active")) {
        menuToggle.src = "img/x.svg"; // Caminho para o ícone de "X"
    } else {
        menuToggle.src = "img/iconmenu.svg"; // Caminho para o ícone de menu
    }
}