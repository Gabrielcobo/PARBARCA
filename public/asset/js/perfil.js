// Confirmar guardar perfil
document.getElementById('btnGuardar').addEventListener('click', function(){
    Swal.fire({
        title:'¿Guardar cambios?',
        text:"Se actualizarán los datos de tu perfil.",
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
            document.getElementById('formPerfil').submit();
        }
    });
});

// Confirmar cambiar contraseña
document.getElementById('btnPassword').addEventListener('click', function(){
    const actual =document.getElementById('password_actual').value;
    const nueva =document.getElementById('password_nueva').value;
    const confirmar =document.getElementById('password_confirmar').value;
    
    if (actual ==='' || nueva ==='' || confirmar ===''){
        Swal.fire({
            title:'Campos vacíos',
            text:'Todos los campos son obligatorios.',
            icon:'warning',
            confirmButtonColor:'#d34a31',
            customClass:{
                popup:'swal-popup',
                title:'swal-title',
                confirmButton:'swal-btn'
            }
        });
        return;
    }
    
    Swal.fire({
        title:'¿Cambiar contraseña?',
        text:"Se actualizará tu contraseña.",
        icon:'warning',
        showCancelButton:true,
        confirmButtonColor:'#d34a31',
        cancelButtonColor:'#6c757d',
        confirmButtonText:'Sí, cambiar',
        cancelButtonText:'Cancelar',
        customClass:{
            popup:'swal-popup',
            title:'swal-title',
            confirmButton:'swal-btn',
            cancelButton:'swal-btn'
        }
    }).then((result) =>{
        if (result.isConfirmed){
            document.getElementById('formPassword').submit();
        }
    });
});