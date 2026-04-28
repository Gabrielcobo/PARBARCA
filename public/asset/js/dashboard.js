document.addEventListener('DOMContentLoaded', function(){
    
    // Inicializar todas las funcionalidades
    initAnimations();
    initTooltips();
    initAutoDismissAlerts();
    initScrollToTop();
    initRefreshButton();
    initSearchFilter();
    
});

// Inicializar animaciones de tarjetas
function initAnimations() {
    const statCards = document.querySelectorAll('.stat-card');
    
    // Usar Intersection Observer para animar al hacer scroll
    const observer =new IntersectionObserver((entries) =>{
        entries.forEach(entry =>{
            if (entry.isIntersecting){
                const card =entry.target;
                const delay =card.getAttribute('data-delay') || '0s';
                card.style.animationDelay = delay;
                card.classList.add('animated');
                observer.unobserve(card); 
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });
    
    // Observar cada tarjeta
    statCards.forEach(card =>{
        observer.observe(card);
    });
}

// Inicializar tooltips de Bootstrap
 function initTooltips(){
    // Inicializar todos los tooltips si existen
    const tooltipTriggerList =[].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

// Auto-dismiss para alertas después de 5 segundos
function initAutoDismissAlerts(){
    const alerts =document.querySelectorAll('.alert:not(.alert-permanent)');
    
    alerts.forEach(alert =>{
        setTimeout(() =>{
            // Crear instancia de Bootstrap Alert si existe
            const bsAlert =new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
}

// Botón para scroll al inicio
function initScrollToTop(){
    // Crear botón flotante si no existe
    if (!document.querySelector('.scroll-to-top')){
        const scrollBtn =document.createElement('button');
        scrollBtn.innerHTML ='<i class="fa-solid fa-arrow-up"></i>';
        scrollBtn.className ='scroll-to-top';
        scrollBtn.setAttribute('aria-label', 'Volver arriba');
        scrollBtn.style.cssText = `
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 45px;
            height: 45px;
            background: var(--primary-color, #bc422a);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            z-index: 1000;
        `;
        
        document.body.appendChild(scrollBtn);
        
        // Mostrar/ocultar botón según scroll
        window.addEventListener('scroll', () =>{
            if (window.pageYOffset > 300){
                scrollBtn.style.display = 'flex';
            } else{
                scrollBtn.style.display ='none';
            }
        });
        
        // Scroll al hacer click
        scrollBtn.addEventListener('click', () =>{
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
}

// Actualizar datos del dashboard (simular refresh)
function initRefreshButton(){
    // Buscar o crear botón de refresh en el header
    const cardHeaders = document.querySelectorAll('.card-header-custom');
    
    cardHeaders.forEach(header =>{
        // Verificar si ya tiene botón de refresh
        if (!header.querySelector('.refresh-btn')){
            const refreshBtn =document.createElement('button');
            refreshBtn.innerHTML ='<i class="fa-solid fa-rotate-right"></i>';
            refreshBtn.className ='refresh-btn btn btn-sm btn-outline-secondary';
            refreshBtn.setAttribute('aria-label', 'Actualizar datos');
            refreshBtn.style.borderRadius ='50%';
            refreshBtn.style.width ='32px';
            refreshBtn.style.height ='32px';
            refreshBtn.style.padding ='0';
            
            refreshBtn.addEventListener('click', function(e) {
                e.preventDefault();
                refreshData(this);
            });
            
            header.appendChild(refreshBtn);
        }
    });
}

// Refrescar datos de una sección específica
function refreshData(button){
    // Mostrar spinner en el botón
    const originalIcon =button.innerHTML;
    button.innerHTML ='<i class="fa-solid fa-spinner fa-spin"></i>';
    button.disabled =true;
    
    // Simular carga de datos (aquí puedes hacer una petición AJAX real)
    setTimeout(() =>{
        // Restaurar botón
        button.innerHTML =originalIcon;
        button.disabled =false;
        
        // Mostrar notificación de actualización
        showNotification('Datos actualizados correctamente', 'success');
    }, 1000);
}

// Mostrar notificación temporal
function showNotification(message, type = 'info'){
    // Crear elemento de notificación
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show notification-toast`;
    notification.style.cssText =`
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 250px;
        animation: slideIn 0.5s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
    
    notification.innerHTML =`
        <i class="fa-solid ${type ==='success' ? 'fa-check-circle' : 'fa-info-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-cerrar después de 3 segundos
    setTimeout(() =>{
        if (notification && notification.remove){
            notification.remove();
        }
    }, 3000);
}

// Función de búsqueda y filtrado para la timeline
function initSearchFilter(){
    // Crear input de búsqueda en cada card-header
    const cardHeaders = document.querySelectorAll('.card-header-custom');
    
    cardHeaders.forEach(header =>{
        // Verificar si ya existe el buscador
        if (!header.querySelector('.search-input')){
            const searchDiv =document.createElement('div');
            searchDiv.className ='search-wrapper';
            
            const searchInput =document.createElement('input');
            searchInput.type ='text';
            searchInput.placeholder ='Buscar...';
            searchInput.className ='search-input form-control form-control-sm';
            searchInput.style.width ='200px';
            
            searchInput.addEventListener('input', function(e){
                filterTimeline(this, header);
            });
            
            searchDiv.appendChild(searchInput);
            header.appendChild(searchDiv);
        }
    });
}

// Filtrar elementos del timeline
function filterTimeline(input, header){
    const searchTerm =input.value.toLowerCase();
    const timeline =header.parentElement.querySelector('.activity-timeline');
    
    if (timeline){
        const items =timeline.querySelectorAll('.timeline-item');
        
        items.forEach(item =>{
            const text =item.textContent.toLowerCase();
            if (text.includes(searchTerm)){
                item.style.display ='';
            } else{
                item.style.display ='none';
            }
        });
        
        // Mostrar mensaje si no hay resultados
        let noResultsMsg =timeline.parentElement.querySelector('.no-results-message');
        
        const visibleItems =Array.from(items).filter(item =>item.style.display !== 'none');
        
        if (visibleItems.length ===0 && searchTerm !=='') {
            if (!noResultsMsg){
                noResultsMsg =document.createElement('div');
                noResultsMsg.className ='no-results-message text-center text-muted py-4';
                noResultsMsg.innerHTML ='<i class="fa-solid fa-search"></i><p class="mt-2">No se encontraron resultados</p>';
                timeline.parentElement.appendChild(noResultsMsg);
            }
            noResultsMsg.style.display ='';
        } else if (noResultsMsg) {
            noResultsMsg.style.display ='none';
        }
    }
}

// Actualizar estadísticas en tiempo real 
function updateStats(section, data){

    console.log(`Actualizando ${section} con:`, data);
    
    // Ejemplo de actualización de valores
    if (section ==='admin' && data.total_requerimientos){
        const totalReqs =document.querySelector('.stat-card:first-child .stat-info h3');
        if (totalReqs){
            totalReqs.textContent =data.total_requerimientos;
        }
    }
}

// Exportar funciones para uso global 
window.dashboard ={
    refreshData,
    showNotification,
    updateStats
};

// Animación de entrada para elementos
const observeElements =document.querySelectorAll('.card-large, .dashboard-banner');
const additionalObserver =new IntersectionObserver((entries) =>{
    entries.forEach(entry =>{
        if (entry.isIntersecting){
            entry.target.style.opacity ='1';
            entry.target.style.transform ='translateY(0)';
            additionalObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.1 });

observeElements.forEach(el =>{
    el.style.opacity ='0';
    el.style.transform ='translateY(20px)';
    el.style.transition ='opacity 0.5s ease, transform 0.5s ease';
    additionalObserver.observe(el);
});