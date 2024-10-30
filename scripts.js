document.addEventListener('DOMContentLoaded', function() {
    const menuBtn = document.getElementById('menu-btn');
    const menuPanel = document.getElementById('menu-panel');

    // Controlar la visibilidad del menú
    let menuVisible = false;

    function toggleMenu() {
        menuVisible = !menuVisible;
        if (menuVisible) {
            menuPanel.style.left = '0'; // Mostrar el menú
        } else {
            menuPanel.style.left = '-424px'; // Esconder el menú
        }
    }

    menuBtn.addEventListener('click', toggleMenu);

    // Ocultar el menú si se hace clic fuera de él
    document.addEventListener('click', function(event) {
        if (menuVisible && !menuPanel.contains(event.target) && event.target !== menuBtn) {
            toggleMenu();
        }
    });
})


// Función para mostrar el formulario según el botón presionado
function mostrarFormulario(formularioID) {
    // Ocultar todos los formularios
    document.getElementById('form_libro').style.display = 'none';
    document.getElementById('form_revista').style.display = 'none';
    document.getElementById('form_periodico').style.display = 'none';

    // Mostrar el formulario correspondiente
    document.getElementById(formularioID).style.display = 'block';
}

// Función para mostrar los datos de lectura según el botón presionado
function mostrarLectura(lecturaID) {
    // Ocultar todas las lecturas
    document.getElementById('lectura_libros').style.display = 'none';
    document.getElementById('lectura_revistas').style.display = 'none';
    document.getElementById('lectura_periodicos').style.display = 'none';
    document.getElementById('lectura_todo').style.display = 'none';

    // Mostrar la lectura correspondiente
    document.getElementById(lecturaID).style.display = 'block';
}

;