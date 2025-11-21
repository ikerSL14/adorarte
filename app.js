
var carritoVisible = false;

if(document.readyState == 'loading'){
    document.addEventListener('DOMContentLoaded', ready)
}else{
    ready();
}

function ready(){
    
    var botonesEliminarItem = document.getElementsByClassName('btn-eliminar');
    for(var i=0;i<botonesEliminarItem.length; i++){
        var button = botonesEliminarItem[i];
        button.addEventListener('click',eliminarItemCarrito);
    }

    var botonesSumarCantidad = document.getElementsByClassName('sumar-cantidad');
    for(var i=0;i<botonesSumarCantidad.length; i++){
        var button = botonesSumarCantidad[i];
        button.addEventListener('click',sumarCantidad);
    }

    var botonesRestarCantidad = document.getElementsByClassName('restar-cantidad');
    for(var i=0;i<botonesRestarCantidad.length; i++){
        var button = botonesRestarCantidad[i];
        button.addEventListener('click',restarCantidad);
    }

    var botonesAgregarAlCarrito = document.getElementsByClassName('boton-item');
    for(var i=0; i<botonesAgregarAlCarrito.length;i++){
        var button = botonesAgregarAlCarrito[i];
        button.addEventListener('click', agregarAlCarritoClicked);
    }

    
// Obtener el modal
var modal = document.getElementById("modalPagar");

// Obtener el botón que abre el modal
var btnPagar = document.getElementsByClassName("btn-pagar")[0];
function mostrarTotalEnModal() {
    const totalElemento = document.getElementById('totalCarrito');
    if (!totalElemento) {
      console.error('Elemento #totalCarrito no encontrado en el DOM');
      return;
    }

    if (typeof totalCar !== 'undefined') {
      console.log('totalCar:', totalCar);
      totalElemento.textContent = `Total: ${totalCar}`;
    } else {
      console.warn('totalCar no está definido');
      totalElemento.textContent = 'Total: $0.00';
    }
  }

// Obtener el elemento <span> que cierra el modal
var span = document.getElementsByClassName("close-pagar")[0];

// Cuando el usuario haga clic en el botón, abrir el modal
btnPagar.onclick = function() {
  modal.style.display = "block";
  mostrarTotalEnModal();
 // Asignar listeners a los títulos desplegables aquí
  document.querySelectorAll('.titulo-seccion').forEach(titulo => {
    // Evitar agregar múltiples listeners si ya existen
    if (!titulo.dataset.listenerAdded) {
      titulo.addEventListener('click', () => {
        const seccion = titulo.parentElement;
        seccion.classList.toggle('activa');
      });
      titulo.dataset.listenerAdded = "true";
    }
  });
const btnGeolocalizacion = document.getElementById('btnGeolocalizacion');
const codigoPostalInput = document.getElementById('codigoPostal');
const direccionEnvioInput = document.getElementById('direccionEnvio');

btnGeolocalizacion.addEventListener('click', () => {
  if (!navigator.geolocation) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'La geolocalización no es soportada por tu navegador',
      background: '#fff',
      backdrop: `
        rgba(0,0,0,0.7)
        left top
        no-repeat
      `,
      timer: 3500,
      timerProgressBar: true,
      showConfirmButton: false,
    });
    
    return;
  }

  navigator.geolocation.getCurrentPosition(
    async (position) => {
      const { latitude, longitude } = position.coords;

      try {
        const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${latitude}&lon=${longitude}`);
        const data = await response.json();

        if (data.address) {
          // Código postal
          if (data.address.postcode) {
            codigoPostalInput.value = data.address.postcode;
          } else {
            Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'No se pudo obtener el código postal desde la ubicación.',
      background: '#fff',
      backdrop: `
        rgba(0,0,0,0.7)
        left top
        no-repeat
      `,
      timer: 3500,
      timerProgressBar: true,
      showConfirmButton: false,
    });
            
          }

          // Construir dirección completa
          const addr = data.address;
          const partes = [];

          if (addr.house_number) partes.push(addr.house_number);
          if (addr.road) partes.push(addr.road);
          if (addr.suburb) partes.push(addr.suburb);
          else if (addr.neighbourhood) partes.push(addr.neighbourhood);
          if (addr.city) partes.push(addr.city);
          else if (addr.town) partes.push(addr.town);
          if (addr.state) partes.push(addr.state);
          if (addr.postcode) partes.push(addr.postcode);
          if (addr.country) partes.push(addr.country);

          const direccionCompleta = partes.join(', ');

          direccionEnvioInput.value = direccionCompleta || '';
        } else {
            Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'No se pudo obtener la dirección desde la ubicación.',
      background: '#fff',
      backdrop: `
        rgba(0,0,0,0.7)
        left top
        no-repeat
      `,
      timer: 3500,
      timerProgressBar: true,
      showConfirmButton: false,
    });
        
        }
      } catch (error) {
        console.error('Error al obtener datos de ubicación:', error);
        Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Ocurrió un error al obtener los datos de ubicación.',
      background: '#fff',
      backdrop: `
        rgba(0,0,0,0.7)
        left top
        no-repeat
      `,
      timer: 3500,
      timerProgressBar: true,
      showConfirmButton: false,
    });
      }
    },
    (error) => {
        Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Debes permitir el acceso a tu ubicación para usar esta función.',
      background: '#fff',
      backdrop: `
        rgba(0,0,0,0.7)
        left top
        no-repeat
      `,
      timer: 3500,
      timerProgressBar: true,
      showConfirmButton: false,
    });
      console.error('Error de geolocalización:', error);
    },
    { enableHighAccuracy: true, timeout: 7000 }
  );
});
}

// Cuando el usuario haga clic en <span> (x), cerrar el modal
span.onclick = function() {
  modal.style.display = "none";
}

// Cuando el usuario haga clic en cualquier lugar fuera del modal, cerrarlo
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
const modalContent = document.querySelector('.modal-pagar-content');
const secciones = document.querySelectorAll('.seccion-desplegable');

secciones.forEach(seccion => {
  const titulo = seccion.querySelector('.titulo-seccion');
  titulo.addEventListener('click', () => {
    seccion.classList.toggle('activa');

    // Esperar un tick para que el DOM actualice la clase
    setTimeout(() => {
      const algunaActiva = Array.from(secciones).some(s => s.classList.contains('activa'));

      if (algunaActiva) {
        modalContent.classList.add('margin-reducido');
      } else {
        modalContent.classList.remove('margin-reducido');
      }
    }, 0);
  });
});

//Accion del boton pagar del modal
document.getElementById('btnPagarModal').addEventListener('click',pagarClicked)

//Guardar datos de envio
  const seccionEnvio = document.getElementById('seccionEnvio');
  const tituloEnvio = seccionEnvio.querySelector('.titulo-seccion');
  const codigoPostalInput = document.getElementById('codigoPostal');
  const direccionEnvioInput = document.getElementById('direccionEnvio');
  const seccionPago = document.getElementById('seccionPago');
  const tituloPago = seccionPago.querySelector('.titulo-seccion');
  
  // Recuperar datos guardados al cargar la página
  codigoPostalInput.value = localStorage.getItem('codigoPostal') || '';
  direccionEnvioInput.value = localStorage.getItem('direccionEnvio') || '';

  seccionEnvio.addEventListener('click', function(event) {
  if (event.target.tagName === 'INPUT') return;
  // Guardar datos al cerrar la sección
  if (!seccionEnvio.classList.contains('activa')) {
    localStorage.setItem('codigoPostal', codigoPostalInput.value);
    localStorage.setItem('direccionEnvio', direccionEnvioInput.value);
  }
});
tituloEnvio.addEventListener('click', () => {
  seccionEnvio.classList.toggle('activa');

  // Usar setTimeout para esperar que el toggle aplique el cambio
  setTimeout(() => {
    // Si la sección se cerró (no tiene clase 'activa'), validamos
    if (!seccionEnvio.classList.contains('activa')) {
      // Obtener valores de los campos
      const codigoPostal = document.getElementById('codigoPostal').value.trim();
      const direccionEnvio = document.getElementById('direccionEnvio').value.trim();
      const instruccionesEntrega = document.getElementById('instruccionesEntrega').value.trim();

      // Validar si alguno está vacío
      const incompleto = !codigoPostal || !direccionEnvio || !instruccionesEntrega;

      if (incompleto) {
        // Fondo rojo en el título
        tituloEnvio.style.backgroundColor = '#ffcccc'; // rojo claro
        seccionEnvio.style.borderColor = '#cc0000';    // borde rojo fuerte
      } else {
        // Fondo verde en el título
        tituloEnvio.style.backgroundColor = '#ccffcc'; // verde claro
        seccionEnvio.style.borderColor = '#009900';    // borde verde fuerte
      }
    } else {
      // Si la sección está abierta, puedes resetear el fondo si quieres
      tituloEnvio.style.backgroundColor = ''; // o el color original gris
      seccionEnvio.style.borderColor = '';
    }
  }, 0);
});
tituloPago.addEventListener('click', () => {
  seccionPago.classList.toggle('activa');

  setTimeout(() => {
    if (!seccionPago.classList.contains('activa')) {
      // Obtener valores de los campos
      const numeroTarjeta = document.getElementById('numeroTarjeta').value.trim();
      const cvv = document.getElementById('cvv').value.trim();
      const fechaExpiracion = document.getElementById('fechaExpiracion').value.trim();
      const nombreTarjeta = document.getElementById('nombreTarjeta').value.trim();

      // Validar si alguno está vacío
      const incompleto = !numeroTarjeta || !cvv || !fechaExpiracion || !nombreTarjeta;

      if (incompleto) {
        // Fondo rojo y borde rojo
        tituloPago.style.backgroundColor = '#ffcccc'; // rojo claro
        seccionPago.style.borderColor = '#cc0000';    // borde rojo fuerte
      } else {
        // Fondo verde y borde verde
        tituloPago.style.backgroundColor = '#ccffcc'; // verde claro
        seccionPago.style.borderColor = '#009900';    // borde verde fuerte
      }
    } else {
      // Resetear estilos cuando la sección está abierta
      tituloPago.style.backgroundColor = '';
      seccionPago.style.borderColor = '';
    }
  }, 0);
});
}
function validarFormularioPago() {
  // Obtén los valores de los inputs de Envío
  const codigoPostal = document.getElementById('codigoPostal').value.trim();
  const direccionEnvio = document.getElementById('direccionEnvio').value.trim();
  const instruccionesEntrega = document.getElementById('instruccionesEntrega').value.trim();

  // Obtén los valores de los inputs de Método de pago
  const numeroTarjeta = document.getElementById('numeroTarjeta').value.trim();
  const cvv = document.getElementById('cvv').value.trim();
  const fechaExpiracion = document.getElementById('fechaExpiracion').value.trim();
  const nombreTarjeta = document.getElementById('nombreTarjeta').value.trim();

  // Validar campos de Envío
  if (!codigoPostal || !direccionEnvio /* instruccionesEntrega puede ser opcional, ajusta si quieres */) {
    return false;
  }

  // Validar campos de Método de pago
  if (!numeroTarjeta || !cvv || !fechaExpiracion || !nombreTarjeta) {
    return false;
  }

  return true;
}
function obtenerItemsCarrito() {
  const items = document.querySelectorAll('.carrito-item');
  const carritoData = [];
  items.forEach(item => {
    carritoData.push({
      titulo: item.querySelector('.carrito-item-titulo').innerText,
      precio: parseFloat(item.querySelector('.carrito-item-precio').innerText.replace('$','')),
      cantidad: parseInt(item.querySelector('.carrito-item-cantidad').value) || 1
    });
  });
  return carritoData;
}
async function pagarClicked() {
  if (!validarFormularioPago()) {
    Swal.fire({
      icon: 'error',
      title: 'Información incompleta',
      text: 'Por favor, completa todos los campos requeridos en Envío y Método de pago antes de continuar.',
      background: '#fff',
      backdrop: `rgba(0,0,0,0.7) left top no-repeat`,
      timer: 3500,
      timerProgressBar: true,
      showConfirmButton: false,
    });
    return;
  }

  // OBTÉN LOS ITEMS DEL CARRITO ANTES DE LIMPIARLO
  const items = obtenerItemsCarrito();

  // Calcula el total
  const total = items.reduce((acc, item) => acc + (item.precio * item.cantidad), 0);

  // Recoge datos de envío y método de pago
  const direccion_envio = document.getElementById('direccionEnvio').value.trim();
  const metodo_pago = 'Tarjeta'; // O lo que corresponda

  // Envía al backend
  try {
    const response = await fetch('registrar_compra.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        items,
        total,
        direccion_envio,
        metodo_pago
      })
    });
    const result = await response.json();

    if (response.ok && result.success) {
      // Limpia el carrito visual y localStorage
      var carritoItems = document.getElementsByClassName('carrito-items')[0];
      while (carritoItems.hasChildNodes()) {
        carritoItems.removeChild(carritoItems.firstChild);
      }
      localStorage.removeItem('carrito');
      enviarCorreo3(total);
      mostrarTransicionExito();
      actualizarTotalCarrito();
      ocultarCarrito();
    } else {
      throw new Error(result.error || 'Error al registrar la compra');
    }
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: error.message,
      showConfirmButton: true,
    });
  }
}

function agregarAlCarritoClicked(event){
    var button = event.target;
    var item = button.parentElement;
    var titulo = item.getElementsByClassName('titulo-item')[0].innerText;
    var precio = item.getElementsByClassName('precio-item')[0].innerText;
    var imagenSrc = item.getElementsByClassName('img-item')[0].src;
    console.log(imagenSrc);

    agregarItemAlCarrito(titulo, precio, imagenSrc);
    Swal.fire({
            toast: true,
            position: 'top',
            icon: 'success',
            title: 'Producto agregado al carrito',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: '#fff',
            html: `
    <div style="display: flex; align-items: center;">
      <img src="${imagenSrc}" alt="Playera" style="width: 40px; height: 40px; object-fit: cover; margin-right: 10px; border-radius: 4px; border: 1px solid #ccc;">
      <span>${precio}</span>
    </div>
  `
        });
    hacerVisibleCarrito();
}

function hacerVisibleCarrito(){
    carritoVisible = true;
    var carrito = document.getElementsByClassName('carrito')[0];
    carrito.style.marginRight = '0';
    carrito.style.opacity = '1';

    var items =document.getElementsByClassName('contenedor-items')[0];
    items.style.width = '100%';
}

function agregarItemAlCarrito(titulo, precio, imagenSrc){
    var item = document.createElement('div');
    item.classList.add = ('item');
    var itemsCarrito = document.getElementsByClassName('carrito-items')[0];

    var nombresItemsCarrito = itemsCarrito.getElementsByClassName('carrito-item-titulo');
    for(var i=10;i < nombresItemsCarrito.length;i++){
        if(nombresItemsCarrito[i].innerText==titulo){
            Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'El item ya se encuentra en el carrito.',
      background: '#fff',
      backdrop: `
        rgba(0,0,0,0.7)
        left top
        no-repeat
      `,
      timer: 3500,
      timerProgressBar: true,
      showConfirmButton: false,
    });
            
            return;
        }
    }

    var itemCarritoContenido = `
        <div class="carrito-item">
            <img src="${imagenSrc}" width="80px" alt="">
            <div class="carrito-item-detalles">
                <span class="carrito-item-titulo">${titulo}</span>
                <div class="selector-cantidad">
                    <i class="fa-solid fa-minus restar-cantidad"></i>
                    <input type="text" value="1" class="carrito-item-cantidad" disabled>
                    <i class="fa-solid fa-plus sumar-cantidad"></i>
                </div>
                <span class="carrito-item-precio">${precio}</span>
            </div>
            <button class="btn-eliminar">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>
    `
    item.innerHTML = itemCarritoContenido;
    itemsCarrito.append(item);

     item.getElementsByClassName('btn-eliminar')[0].addEventListener('click', eliminarItemCarrito);

    var botonRestarCantidad = item.getElementsByClassName('restar-cantidad')[0];
    botonRestarCantidad.addEventListener('click',restarCantidad);

    var botonSumarCantidad = item.getElementsByClassName('sumar-cantidad')[0];
    botonSumarCantidad.addEventListener('click',sumarCantidad);

    actualizarTotalCarrito();
    guardarCarrito(); // ← Añadir al final
}
function sumarCantidad(event){
    var buttonClicked = event.target;
    var selector = buttonClicked.parentElement;
    console.log(selector.getElementsByClassName('carrito-item-cantidad')[0].value);
    var cantidadActual = selector.getElementsByClassName('carrito-item-cantidad')[0].value;
    cantidadActual++;
    selector.getElementsByClassName('carrito-item-cantidad')[0].value = cantidadActual;
    actualizarTotalCarrito();
    guardarCarrito(); // ← Añadir al final
}
function restarCantidad(event){
    var buttonClicked = event.target;
    var selector = buttonClicked.parentElement;
    console.log(selector.getElementsByClassName('carrito-item-cantidad')[0].value);
    var cantidadActual = selector.getElementsByClassName('carrito-item-cantidad')[0].value;
    cantidadActual--;
    if(cantidadActual>=1){
        selector.getElementsByClassName('carrito-item-cantidad')[0].value = cantidadActual;
        actualizarTotalCarrito();
    }
    guardarCarrito(); // ← Añadir al final
}

function eliminarItemCarrito(event){
    var buttonClicked = event.target;
    buttonClicked.parentElement.parentElement.remove();
    actualizarTotalCarrito();

    ocultarCarrito();
    guardarCarrito(); // ← Añadir al final
}
function ocultarCarrito(){
    var carritoItems = document.getElementsByClassName('carrito-items')[0];
    if(carritoItems.childElementCount==0){
        var carrito = document.getElementsByClassName('carrito')[0];
        carrito.style.marginRight = '-100%';
        carrito.style.opacity = '0';
        carritoVisible = false;
    
        var items =document.getElementsByClassName('contenedor-items')[0];
        items.style.width = '100%';
    }
}
function actualizarTotalCarrito(){
    var carritoContenedor = document.getElementsByClassName('carrito')[0];
    var carritoItems = carritoContenedor.getElementsByClassName('carrito-item');
    var total = 0;
    for(var i=0; i< carritoItems.length;i++){
        var item = carritoItems[i];
        var precioElemento = item.getElementsByClassName('carrito-item-precio')[0];
      var precio = parseFloat(precioElemento.innerText.replace('$', '').trim());
        var cantidadItem = item.getElementsByClassName('carrito-item-cantidad')[0];
        console.log(precio);
        var cantidad = cantidadItem.value;
        total = total + (precio * cantidad);
    }
    total = Math.round(total * 100)/100;

    document.getElementsByClassName('carrito-precio-total')[0].innerText = '$'+total.toLocaleString("es") + ".00";
    totalCar = document.getElementsByClassName('carrito-precio-total')[0].innerText;
}

function enviarCorreo() {
    // Get the total cost from the DOM
    const totalCarritoElement = document.getElementById('carrito-precio-total');
    if (!totalCarritoElement) {
      console.error('Error: Element not found: carrito-precio-total');
      return;
    }
  
    const totalCarrito = parseFloat(totalCarritoElement.textContent.replace('$', '').replace(',', ''));
  
    // Send an AJAX request to enviar.php
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'enviar.php');
    xhr.setRequestHeader('Content-Type', 'application/json'); // Use JSON for data
    xhr.onload = function() {
      if (xhr.status === 200) {
        console.log('Correo electrónico enviado con éxito');
      } else {
        console.error('Error al enviar correo electrónico:', xhr.statusText);
      }
    };
  
    // Send the total cost as JSON data
    xhr.send(JSON.stringify({ totalCarrito }));
  }

  function enviarCorreo2(totalCar){
    window.location.href= "enviar2.php?totalCarrito=" + '"' + totalCar + '"';
}

function enviarCorreo3(totalCar){
    window.location.href= "enviar3.php?totalCarrito=" + '"' + totalCar + '"';
}
// Función para guardar el carrito en localStorage
function guardarCarrito() {
    const items = document.querySelectorAll('.carrito-item');
    const carritoData = [];
  
    items.forEach(item => {
      carritoData.push({
        titulo: item.querySelector('.carrito-item-titulo').innerText,
        precio: item.querySelector('.carrito-item-precio').innerText,
        imagen: item.querySelector('img').src,
        cantidad: item.querySelector('.carrito-item-cantidad').value
      });
    });
  
    localStorage.setItem('carrito', JSON.stringify(carritoData));
  }
  
  // Función para cargar el carrito desde localStorage
  function cargarCarrito() {
    const carritoItems = document.querySelector('.carrito-items');

    // Limpiar carrito visible antes de agregar
    while (carritoItems.firstChild) {
      carritoItems.removeChild(carritoItems.firstChild);
    }
  
    // Obtener carrito guardado en localStorage
    const carritoData = JSON.parse(localStorage.getItem('carrito')) || [];
  
    // Agregar ítems guardados al carrito visible
    carritoData.forEach(item => {
      agregarItemAlCarrito(item.titulo, item.precio, item.imagen, item.cantidad);
    });
  }
  
  // Llama a cargarCarrito al iniciar la página
  document.addEventListener('DOMContentLoaded', cargarCarrito);
