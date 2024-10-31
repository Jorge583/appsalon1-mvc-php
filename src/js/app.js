let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
    id:'',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}

document.addEventListener('DOMContentLoaded', function() {
    iniciarApp();
});

function iniciarApp() {
    mostrarSeccion();//muestra o coulta las secciones 
     tabs();//cambia la seccion cuando cambien los tabs
     botonesPaginador(); // agrega o quita los botones del paginador
     paginaSiguiente();
     paginaAnterior();

     consultarAPI(); //consulta la API en el backend de PHP

     idCliente();
     nombreCliente();// añade el nombre del cliente del objeto cita
     seleccionarFecha(); // añade la fecha de la cita en el objeto
     seleccionarHora();// añade la hora de la cita en el objeto


     mostrarResumen();//muestra el resumen de la cita

    // reservarCita();
}

function mostrarSeccion() {

    //ocultar la seccion que tenga ña clase para mostrar
   const seccionAnterior = document.querySelector('.mostrar');
        if(seccionAnterior) {
            seccionAnterior.classList.remove('mostrar');
         }
//selccionar laseccion con el paso
    const pasoSelector = `#paso-${paso}`;
    const seccion = document.querySelector(pasoSelector);
    seccion.classList.add('mostrar');

    //quita la clase actual al tab anterior 
    const tabAnterior = document.querySelector('.actual');
        if(tabAnterior) {
            tabAnterior.classList.remove('actual');
         }

    //resalta el tab actual
   const tab = document.querySelector(`[data-paso="${paso}"]`);
        tab.classList.add('actual');
}
function tabs() {
    const botones = document.querySelectorAll('.tabs button');
        botones.forEach( boton => {
        boton.addEventListener('click', function(e) {
            e.preventDefault();
            
            paso = parseInt( e.target.dataset.paso );
                mostrarSeccion();

                botonesPaginador();
           // if(paso === 3) {
           //     mostrarResumen();
           // }
                
       });
   });
}

    function botonesPaginador() {
        const paginaAnterior = document.querySelector('#anterior'); 
        const paginaSiguiente = document.querySelector('#siguiente');    
        if(paso === 1) {
            paginaAnterior.classList.add('ocultar');
            paginaSiguiente.classList.remove('ocultar');
        } else if (paso === 3){
            paginaAnterior.classList.remove('ocultar');
            paginaSiguiente.classList.add('ocultar');

            mostrarResumen();
        } else {
            paginaAnterior.classList.remove('ocultar');
            paginaSiguiente.classList.remove('ocultar');
        }

        mostrarSeccion();
    }


    function paginaAnterior() {
    const paginaAnterior = document.querySelector('#anterior');
        paginaAnterior.addEventListener('click', function(){
            if (paso <= pasoInicial) return;

            paso--;

            botonesPaginador();
        })
    }
    function paginaSiguiente() {
        const paginaSiguiente = document.querySelector('#siguiente');
        paginaSiguiente.addEventListener('click', function(){
            if (paso >= pasoFinal) return;

            paso++;

            botonesPaginador();
        })
    }
    async function consultarAPI() {

            try {
                const url = `${location.origin}/api/servicios`;
                const resultado = await fetch(url);
                const servicios = await resultado.json();
                mostrarServicios(servicios);

            } catch (error) {
                console.log(error);
            }
    }
    function mostrarServicios(servicios) {
        servicios.forEach( servicio => {
            const {id, nombre, precio } = servicio;

           const nombreServicio = document.createElement('P');
           nombreServicio.classList.add('nombre-servicio');
           nombreServicio.textContent = nombre;

           const precioServicio = document.createElement('P');
           precioServicio.classList.add('precio-servicio');
           precioServicio.textContent = `$${precio}`;

           const servicioDiv = document.createElement('DIV');
           servicioDiv.classList.add('servicio');
           servicioDiv.dataset.idServicio = id;
           servicioDiv.onclick = function () {
                seleccionarServicio(servicio);
           }

           servicioDiv.appendChild(nombreServicio);
           servicioDiv.appendChild(precioServicio);

           document.querySelector('#servicios').appendChild(servicioDiv)

        });

    }
    function seleccionarServicio(servicio) {
        const { id } = servicio;
        const { servicios } = cita;
        // identificar el elemento a que se la clik
        const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

        // comprobar si un servicio ya fue agragado
        if (servicios.some( agregado => agregado.id === id ) ) {
            // eliminarlo
            cita.servicios = servicios.filter( agregado => agregado.id !== id );
            divServicio.classList.remove('seleccionado');
        }else {
            // agregarlo
            cita.servicios = [...servicios, servicio];
            divServicio.classList.add('seleccionado');
        }

       // console.log(cita);
    }
function idCliente() {
        cita.id = document.querySelector('#id').value;
        
            }
function nombreCliente() {
    cita.nombre = document.querySelector('#nombre').value;
    
        }
function seleccionarFecha() {
    const inputFecha = document.querySelector('#fecha');
    inputFecha.addEventListener('input', function(e) {
        const dia = new Date(e.target.value).getUTCDay();
        if( [6, 0].includes(dia) ) {
            e.target.value = '';
            mostrarAlerta('sabado y domingo no abrimos', 'error', '.formulario');
        } else {
            cita.fecha = e.target.value;
        }
        
    });
}
function seleccionarHora() {
    const inputHora = document.querySelector('#hora');
    inputHora.addEventListener('input', function(e) {
        
        const horaCita = e.target.value;
        const hora = horaCita.split(":")[0];
        if (hora < 10 || hora > 18) {
            e.target.value = '';
            mostrarAlerta('hora No Valida', 'error', '.formulario');
        } else {
            cita.hora = e.target.value;

           // console.log(cita);
        }
    })
}
function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {
    //previene que se cre mas alerta
    const alertaPrevia = document.querySelector('.alerta');
    if (alertaPrevia) {
        alertaPrevia.remove();
    };

    // scripting para crear la alerta 
    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);

    const referencia = document.querySelector(elemento);
    referencia.appendChild(alerta);

    if (desaparece) {
        //aliminar la alertas
            setTimeout(() => {
            alerta.remove();
            }, 3000)
    }


}

function mostrarResumen() {
    const resumen = document.querySelector('.contenido-resumen');

    //limpiar el contenido de resumen
    while(resumen.firstChild) {
        resumen.removeChild(resumen.firstChild);
    }

        
    if(Object.values(cita).includes('') || cita.servicios.length === 0 ) {

        mostrarAlerta('hacen falta datos o servicios,fecha u hora', 'error', '.contenido-resumen', false);

        return;
    } 
    //formateasr el div de resumen
    const { nombre, fecha, hora, servicios } = cita;

   

    //heading para servicios en resumen 
    const headingServicios = document.createElement('H3');
    headingServicios.textContent = 'Resumen Servicios';
    resumen.appendChild(headingServicios);
// iterando y mostrando los servicio
    servicios.forEach(servicio => {
        const { id, precio, nombre } = servicio;
        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        const textoServicio = document.createElement('p');
        textoServicio.textContent = nombre;

        const precioServicio = document.createElement('p');
        precioServicio.innerHTML = `<span>Precio:</span> $${precio}`;

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);

        resumen.appendChild(contenedorServicio);
    })
     //heading de cita
    const headingCita = document.createElement('H3');
     headingCita.textContent = 'Resumen de Cita';
     resumen.appendChild(headingCita);
    
    const nombreCliente = document.createElement('p');
    nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;

    //formatear la fecha en español
    const fechaObj = new Date(fecha);
    const mes = fechaObj.getMonth();
    const dia = fechaObj.getDate() + 2;
    const year = fechaObj.getFullYear();

    const fechaUTC = new Date( Date.UTC(year, mes, dia));

    const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'}
    const fechaFormateada = fechaUTC.toLocaleDateString('es-VE', opciones);
    //console.log(fechaFormateada);


    const fechaCita = document.createElement('p');
    fechaCita.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`;

    const horaCita = document.createElement('p');
    horaCita.innerHTML = `<span>hora:</span> ${hora} Horas`;


    //boton para crear una cita
    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = 'Reservar Cita';
    botonReservar.onclick = reservarCita;

    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);
    resumen.appendChild(botonReservar);

}

async function reservarCita() {
   
   const { nombre, fecha, hora, servicios, id } = cita;
    //datos.append('nombre', nombre);
    const idServicios = servicios.map( servicio => servicio.id );
    //console.log(idServicios);
    //return;

    const datos = new FormData();
    datos.append('fecha', fecha);
    datos.append('hora', hora);
    datos.append('servicios', idServicios);
    datos.append('usuarioid', id);
    //console.log([...datos]);
    //return;
     try {
         //peticion hacia la api
         const url = `${location.origin}/api/citas`;
         const respuesta = await fetch(url, {
             method: 'POST',
             body: datos
          });
          //console.log(respuesta);
      
        const resultado = await respuesta.json();
        console.log(resultado); 
             if(resultado.resultado) {
                      Swal.fire({
                          icon: 'success',
                          title: "Cita Creada",
                          text: 'Tu Cita fue creada correctamente',
                          button: 'OK'
                        }).then( () => {
                            setTimeout(() => {
                             window.location.reload();
                        }, 3000);
                     })
                    }
            } catch (error) {
                  Swal.fire({
                      icon: 'error',
                      title: 'Error',
                      text: 'hubo un error al guardar la cita'
                  })
            }
   //consoleg.log([...datos]);para probar que se esta enviado los datos correcvtos
}