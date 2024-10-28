function mostrarSeccion() {
    //console.log('mostrar seccion ...')
    //ocultar la secciom que tenga la casemostrar
    //const seccionAnterior = document.querySelector('.mostrar');
    //seccionAnterior.classList('.mostrar';)
    const pasoSelector = `#paso-{$paso}`;
    const seccion = document.querySelector(pasoSelector);
    seccion.classList.add('mostrar');
}
