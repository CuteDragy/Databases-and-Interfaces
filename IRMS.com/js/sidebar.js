function toggleSidebar() {
    const sidebar = document.getElementById("mySidebar");
    const overlay = document.getElementById("overlay");

    // Toggle the 'show' class
    sidebar.classList.toggle("show");
    overlay.classList.toggle("show");
}
