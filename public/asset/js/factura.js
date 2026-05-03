// Filtrar facturas por número
function filtrarFacturas(){
    const input =document.getElementById('searchInput');
    const filter =input.value.toLowerCase();
    const rows =document.querySelectorAll('.factura-row');
    
    rows.forEach(row =>{
        const numero = row.getAttribute('data-numero') || '';
        if (numero.includes(filter)){
            row.style.display ='';
        } else {
            row.style.display ='none';
        }
    });
}

// Ver factura 
function verFactura(id){
    const modal =new bootstrap.Modal(document.getElementById('modalFactura'));
    const contenido =document.getElementById('facturaContenido');
    
    contenido.innerHTML ='<div class="text-center p-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div></div>';
    modal.show();
    
    fetch(`index.php?action=cliente_factura_ver_ajax&id=${id}`)
        .then(response =>response.text())
        .then(data =>{
            contenido.innerHTML =data;
        })
        .catch(error =>{
            contenido.innerHTML ='<div class="alert alert-danger">Error al cargar la factura</div>';
        });
}

// Descargar factura PDF
function descargarFactura(id){
    window.open(`index.php?action=cliente_factura_descargar&id=${id}`, '_blank');
}

// Auto-ocultar alertas
document.addEventListener('DOMContentLoaded', function(){
    const alerts = document.querySelectorAll('.alert-success, .alert-danger');
    alerts.forEach(alert =>{
        setTimeout(() =>{
            const bsAlert =new bootstrap.Alert(alert);
            bsAlert.close();
        }, 3000);
    });
});