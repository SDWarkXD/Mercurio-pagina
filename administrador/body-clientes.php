<?php session_start();       
    if(!$_SESSION['username'] || $_SESSION['tipoUsuario']!=0) header("Location:../index.php");
    $subCarp="../";
    require_once '../head.php';
    echo "<body>";
    require_once 'header-administrador.php';
?>

<?php
    //Handle delete
    if (isset($_GET['delete_id'])) {
        $delete_id = (int) $_GET['delete_id'];
    
        $link=mysqli_connect("localhost","root","");
        mysqli_select_db($link,"mercurioDB");
        $link->set_charset("utf8");

        //Colocar usuario en inactivo
        mysqli_query($link,"UPDATE users SET status = '1' WHERE id_user = '$delete_id'"); 
        //Eliminar productos disponibles del usuario 
        mysqli_query($link,"DELETE FROM productos WHERE id_user = '$delete_id' AND status = 0"); 
        //Eliminar las imagenes del producto 
        $ima=mysqli_query($link,"DELETE FROM imagenes WHERE id_user = '$delete_id'"); 
    }
?>

<script LANGUAGE="JavaScript">
    function confirmSubmit(){
        var eli=confirm("¿Está seguro de eliminar este usuario?");
        if (eli) return true ;
        else return false ;
    }
</script>

    <main class="principal">

        <div class="d-flex align-items-center justify-content-around">
            <hr class="linea-izq">
            <p class="titulos-espacios">Clientes</p>
            <hr class="linea-der">

        </div>

        <?php
            $link=mysqli_connect("localhost","root","");
            mysqli_select_db($link,"mercurioDB");
            $link->set_charset("utf8");
            $result=mysqli_query($link,"SELECT * from users where tipo=1 AND status = 0");
            echo "<div class='d-flex  flex-column  align-items-center justify-content-around clientes-registrados-container'>";
                while($row=mysqli_fetch_array($result)){
                    echo "<div class='clientes-registrados card-borde'>";
                    $id_u=$row['id_user'];
                    $nom=$row['nombre'];
                    $corr=$row['correo'];
                    $fecha=$row['fecha_de_registro'];

                    //Total de productos registrados
                    $res = mysqli_query($link,"SELECT COUNT(*) total FROM Productos WHERE id_user=$id_u");
                    $fila = mysqli_fetch_assoc($res);
                    $totProd =  $fila['total'];

                    //Total de intercambios realizados
                    $res = mysqli_query($link,"SELECT COUNT(*) total FROM Intercambios WHERE id_solicitante =$id_u"); //como solicitante
                    $fila = mysqli_fetch_assoc($res);
                    $totSoli =  $fila['total'];
                    $res = mysqli_query($link,"SELECT COUNT(*) total FROM Intercambios WHERE id_solicitado =$id_u"); //como solicitado
                    $fila = mysqli_fetch_assoc($res);
                    $totSoli1 =  $fila['total'];
                    $totInter = $totSoli + $totSoli1;
                    
                    echo "<div class='d-flex  flex-row align-self-start'> <img  class='card-mis-productos-imagen' src='../images/user.svg' >";
                    echo "<div class='d-flex  flex-column card-mis-productos-texto align-self-start'>
                            <p class='card-mis-productos-titulo'>$nom</p> <p class='card-mis-productos-titulo'>#Id. $id_u</p> <p class='card-mis-productos-titulo'>$corr</p> </div> ";
                    echo "<div class='d-flex  flex-column card-mis-productos-datos-finales col align-self-end' > ";                  
                    echo "<p  class='card-mis-productos-fecha col align-self-end'> TOTAL DE PRODUCTOS REGISTRADOS: $totProd</p> 
                        <p  class='card-mis-productos-fecha col align-self-end'> TOTAL DE INTERCAMBIOS REALIZADOS: $totInter</p> 
                        <p  class='card-mis-productos-fecha col align-self-end'> Fecha de registro: $fecha</p> </div></div></div> ";
                    echo "<div class='d-flex  flex-row align-self-start'><a onclick=\"return confirmSubmit()\"href=\"?delete_id={$row['id_user']}\"><img class='card-intercambios-imagen' src='../images/trash.svg'></a></div> ";
                }
            echo "</div>";
        ?>

        </div>

    </main>

<?php          
    require_once '../footer.php';
?>


</body>

</html>