<h1 class="nombre-pagina">Olvide PASSWORD</h1>
<p class="descripcion-pagina">Reesteblacer tu password escribiendo tu email a continuacion </p>
<?php
 include_once __DIR__ . "/../templates/alertas.php" 
 ?>
<form class="formulario" action="/olvide-password" method="POST">
    <div class="campo">
        <label for="email">E-mail</label>
            <input 
                type="email"
                id="email"
                name="email"
                placeholder="tu E-mail"
            />
    </div>
    <input type="submit" value="Enviar Intrucciones" class="boton" >
</form>
    <div class="acciones">
        <a href="/">¿Ya tienes una cuenta ? Inicia sesion </a>
        <a href="/crear-cuenta">Aun no tienes una cuenta? Crea una</a>
    </div>
    