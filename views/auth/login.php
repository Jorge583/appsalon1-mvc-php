<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia sesion con tus datos </p>

<?php include_once __DIR__ . "/../templates/alertas.php" ?>

<form class="formulario" method="POST" action="/">
    <div class="campo">
        <label for="email">Email</label>
        <input
            type="email"
            id="email"
            placeholder="Tu Email"
            name="email"
        />
    </div>
    <div class="campo">
        <label for="password"> Password</label>
        <input
            type="password"
            id="password"
            placeholder="Tu Password"
            name="password"
        />
    </div>
    <input type="submit" class="boton" value="Iniciar Seccion">
</form>
<div class="acciones">
    <a href="/crear-cuenta">¿Aun no tiene una cuenta ? crear una </a>
    <a href="/olvide-password">Olvidaste tu pessword?</a>
</div>