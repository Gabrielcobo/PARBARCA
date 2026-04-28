const sidebar =document.getElementById('sidebar');
const sidebarToggle =document.getElementById('sidebarToggle');
const closeBtn =document.getElementById('closeMenu');

// Verificar si estamos en móvil/tablet
function isMobile(){
    return window.innerWidth <=1024;
}

// Abrir sidebar
function openSidebar(){
    sidebar.classList.add('active');
}

// Cerrar sidebar
function closeSidebar(){
    sidebar.classList.remove('active');
}

// Toggle sidebar
function toggleSidebar(){
    if (sidebar.classList.contains('active')) {
        closeSidebar();
    } else{
        openSidebar();
    }
}

// Evento: clic en el logo/header (abre/cierra solo en móvil)
if (sidebarToggle){
    sidebarToggle.addEventListener('click', function(e){
        if (e.target ===closeBtn || (closeBtn && closeBtn.contains(e.target))){
            return;
        }
        
        // Solo toggle en móvil/tablet
        if (isMobile()){
            toggleSidebar();
        }
    });
}

// Evento: clic fuera del sidebar 
document.addEventListener('click', function(event){
    if (!isMobile()) return;
    
    const isClickInsideSidebar =sidebar.contains(event.target);
    const isClickOnToggle =sidebarToggle ? sidebarToggle.contains(event.target) : false;
    
    if (!isClickInsideSidebar && !isClickOnToggle && sidebar.classList.contains('active')){
        closeSidebar();
    }
});

// Cerrar sidebar al redimensionar a escritorio
window.addEventListener('resize', function(){
    if (!isMobile() && sidebar.classList.contains('active')){
        closeSidebar();
    }
});