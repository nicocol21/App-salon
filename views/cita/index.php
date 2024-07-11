<h1 class="nombre-pagina">Crear Nueva Cita</h1>
<p class="descripcion-pagina">Elige Tus Servicios y Coloca Tus Datos.</p>

<?php include_once __DIR__ . '/../templates/barra.php'; ?>

<div id="app">

    <nav class="tabs">
        <button class="actual" type="button" data-paso="1">Servicios</button>
        <button type="button" data-paso="2">Información Cita</button>
        <button type="button" data-paso="3">Resumen</button>
    </nav>

    <div id="paso-1" class="seccion">
        <h2>Servicios</h2>
        <p class="text-center">Elige Tus Servicios a Continuación</p>
        <div id="servicios" class="listado-servicios"></div>
    </div>
    <div id="paso-2" class="seccion">
        <h2>Tus Datos y Cita</h2>
        <p class="text-center">Coloca Tus Datos y La Fecha De Tu Cita</p>

    <form action="" class="formulario">
        <div class="campo">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" placeholder="Tu Nombre"
            value="<?php echo $nombre; ?>" disabled> 
        </div>

        <div class="campo">
            <label for="fecha">Fecha</label>
            <input id="fecha" type="date" 
            min = "<?php echo date('Y-m-d', strtotime(' +1 day') ); ?>" >
        </div>
        <div class="campo">
            <label for="hora">Hora</label>
            <input id="hora" type="time">
        </div>
        <input type="hidden" id="id" value="<?php echo $id; ?>" >
    </form>
</div>
    <div id="paso-3" class="seccion contenido-resumen">
        <h2>Resumen</h2>
        <p class="text-center">Verifica Que la Información Sea Correcta</p>
    </div>
    <div class="paginacion">
        <button id="anterior" class="boton">&laquo; Anterior </button>
        <button id="siguiente" class="boton">Siguiente &raquo;</button>
    </div>
</div>

<?php
    $script = "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script src='build/js/app.js'></script>
    ";
?>