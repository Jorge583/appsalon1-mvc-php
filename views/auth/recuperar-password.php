<h1 class="nombre-pagina">Recuperar PASSWORD</h1>
<p class="descripcion-pagina">coloca tu nuevo password</p>
<?php
 include_once __DIR__ . "/../templates/alertas.php" 
 ?>

 <?php if ($error) return; ?>
<form class="formulario" method="POST">
    <div class="campo">
        <label for="password"> Password</label>
        <input
            type="password"
            id="password"
            name="password"
            placeholder="Tu nuevo Password"
            
        />
</div>
    <input type="submit" class="boton" value="Guardar nuevo Password">
</form>
<div class="acciones">
    <a href="/">¿Ya tiene tiene una cuenta ? iniciar Sesion </a>
    <a href="/crear-cuenta">¿aun no tiene cuenta?</a>
</div>
  
    