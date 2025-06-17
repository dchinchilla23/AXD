<!-- fechas usando html + php   -->
 <section>
    <h2>Paso 1 Inicializar base de datos</h2>
    <form action="../setup/init_db.php" method="POST">
        <button type="submit"> Crear estructura + cargar hoteles</button>
    </form>
</section>

<section>
    <h2>Paso 2 Consulta disponibilidad</h2>
<form action="buscar.php" method="GET">
    <label>Ciudad:
        <select name="ciudad">
            <option value="Bogotá">Bogotá
            <option value="Cartagena">Cartagena</option>
            <option value="Barranquilla">Barranquilla</option>
            <option value="Cali">Cali</option>

        </select>
        <label>
            <label> Tipo de habitacion:
                <select name="tipo">
                    <option value="estandar">estandar
                    <option value="premiun">premiun</option>
                    <option value="vip">vip</option>
                </select>
                <label>

                    <label> Personas:
                        <input type="number" name="personas" min="1" require>
                    </label>

                    <label> Fecha de entrada:
                        <input type="date" name="inicio" require>
                    </label>

                    <label> Fecha de salida:
                        <input type="date" name="fin" require>
                    </label>
                    <button type="submit">Buscar disponibilidad</button>
                    </from>
</section>