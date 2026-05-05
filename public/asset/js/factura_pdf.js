// Generar y descargar PDF automáticamente al cargar la página
window.onload = function(){
    const element =document.getElementById('facturaParaPDF');
    
    if (!element){
        console.error('No se encontró el elemento facturaParaPDF');
        return;
    }
    
    // Obtener el número de factura 
    const numeroFactura =element.getAttribute('data-numero-factura') || 'factura';
    
    const opt ={
        margin:[0.5, 0.5, 0.5, 0.5],
        filename:`factura_${numeroFactura}.pdf`,
        image:{ 
            type:'jpeg', 
            quality:0.98 
        },
        html2canvas:{
            scale: 2, 
            letterRendering:true, 
            useCORS: true 
        },
        jsPDF:{ 
            unit:'in', 
            format:'a4', 
            orientation:'portrait' 
        }
    };
    
    html2pdf().set(opt).from(element).save();
    
    // Cerrar la ventana después de 1.5 segundos
    setTimeout(function(){ 
        window.close(); 
    }, 1500);
};