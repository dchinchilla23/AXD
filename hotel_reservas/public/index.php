<!-- fechas usando html + php   -->
<form action="buscar.php" method="GET">
    <label>Ciudad:
        <select name="ciudad">
            <option value="Bogota">Bogota
            <option value="Cartagena">Cartagena</option>

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
                    <buttom type="submit">Buscar disponibilidad</buttom>
                    </from>