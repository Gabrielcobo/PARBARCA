// filtrar
function filtrarRequerimientos(){
    const input =document.getElementById('searchInput');
    const filter =input.value.toLowerCase();
    const rows =document.querySelectorAll('.requerimiento-row');
    
    rows.forEach(row =>{
        const titulo =row.cells[0]?.textContent.toLowerCase() || '';
        if (titulo.includes(filter)){
            row.style.display ='';
        } else{
            row.style.display ='none';
        }
    });
}

// ver detalle
function verDetalle(id){
    window.location.href ='index.php?action=cliente_requerimiento_detalle&id=' + id;
}

// editar requerimiento
function editarRequerimiento(id){
    window.location.href ='index.php?action=cliente_requerimiento_editar_form&id=' + id;
}

// eliminar requerimiento
function eliminarRequerimiento(id){
    Swal.fire({
        title:'¿Estás seguro?',
        text:"¡No podrás revertir esta acción!",
        icon:'warning',
        showCancelButton:true,
        confirmButtonColor:'#d34a31',
        cancelButtonColor:'#6c757d',
        confirmButtonText:'Sí, eliminar',
        cancelButtonText:'Cancelar',
        customClass:{
            popup:'swal-popup',
            title:'swal-title',
            confirmButton:'swal-btn',
            cancelButton:'swal-btn'
        }
    }).then((result) =>{
        if (result.isConfirmed){
            window.location.href ='index.php?action=cliente_requerimiento_eliminar&id=' + id;
        }
    });
}

// cerrar modal detalle
function cerrarModalDetalle(){
    document.getElementById('modalDetalle').style.display ='none';
}

// confirmar editar requerimiento
function confirmarEditar(){
    Swal.fire({
        title:'¿Guardar cambios?',
        text:"Se actualizará el requerimiento.",
        icon:'question',
        showCancelButton:true,
        confirmButtonColor:'#d34a31',
        cancelButtonColor:'#6c757d',
        confirmButtonText:'Sí, guardar',
        cancelButtonText:'Cancelar',
        customClass:{
            popup:'swal-popup',
            title:'swal-title',
            confirmButton:'swal-btn',
            cancelButton:'swal-btn'
        }
    }).then((result) =>{
        if (result.isConfirmed){
            document.getElementById('formEditar').submit();
        }
    });
};