<?php
include("../lib/database.php");
include("../js/funciones.php");

//RECIBE LAS VARIABLES
$idlista = $_REQUEST['idlista'];

$db = new Database();
$db1 = new Database();
$sql = "delete from det_lista  where cod_lista= '$idlista'";
$db->query($sql);
$db->escribe_sql($sql);

for($i=0;$i<=$_REQUEST['ultimo'];$i++){
	if($_REQUEST["id_codigo_".$i]){
		$sql="INSERT INTO `det_lista` (`cod_lista`, `cod_pro`, `pre_reg`, `pre_list`) VALUES(".$idlista.",'".$_REQUEST["id_codigo_".$i]."','".$_REQUEST["id_valor_".$i]."', '".$_REQUEST["id_val_lista_".$i]."')";
		$db1->query($sql);
		$db->escribe_sql($sql);
	}
}

$mapa="LISTAS";
		
?>
<FORM method="POST" action="ver_lista.php" name="myForm">
<INPUT type="hidden" name="codigo" value="<?php echo $idlista?>"></FORM>
<SCRIPT>
	alert('SE HAN GUARDADO SATISFACTORIAMENTE SUS DATOS');
	window.close();
</SCRIPT>