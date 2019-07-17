<?php include("lib/database.php");?>
<?php include("js/funciones.php");?>
<?php 

//RECIBE LAS VARIABLES
$codigo = $_REQUEST['codigo'];
$guardar = $_REQUEST['guardar'];
$insertar = $_REQUEST['insertar'];
$eliminar = $_REQUEST['eliminar'];
$editar = $_REQUEST['editar'];

if($codigo==0) 
   $codigo=-10; 
if ($codigo!="") {
	    $sql ="SELECT * FROM bodega1 WHERE cod_bod= $codigo";
		$dbdatos= new  Database();
		$dbdatos->query($sql);
		$dbdatos->next_row();
}

if($guardar==1 and $codigo==-10) { // RUTINA PARA  INSERTAR REGISTROS NUEVOS
	$campos="(nom_bod, apel_bod,max_cos_bod, iden_bod,digito_bod,dir_bod,tel_bod, dpto_cli,ciu_bod,mail_bod, tipo_bod ,propia, cod_lista ,dias_traslado ,dias_credito,cod_covinoc, fec_covinoc, cupo_au_covinoc, cupo_traslados,cod_bod_cli,regimen_cli,cod_ruta,estado_bodega1)";
	
	 $valores="('".$_REQUEST['nombresito']."','".$_REQUEST['apellidos']."','".$_REQUEST['max_traslado']."', '".$_REQUEST['identificacion']."','".$_REQUEST['digito']."','".$_REQUEST['direccion']."','".$_REQUEST['telefono']."','".$_REQUEST['departamento']."','".$_REQUEST['ciudad']."', '".$_REQUEST['correo']."', '1','".$_REQUEST['propia']."','".$_REQUEST['lista']."', '".$_REQUEST['ven_traslado']."','".$_REQUEST['ven_factura']."','".$_REQUEST['cod_covinoc']."','".$_REQUEST['fec_covinoc']."','".$_REQUEST['max_credito']."','".$_REQUEST['max_traslado']."','".$_REQUEST['bodega']."','".$_REQUEST['v_regimen']."','".$_REQUEST['ruta']."','1')" ;  
	
	$error=1;
	$id_cli=insertar_maestro("bodega1",$campos,$valores); 
	
	if ($error==1) {
		header("Location: con_bodegas.php?confirmacion=1&editar=$editar&insertar=$insertar&eliminar=$eliminar"); 
	}
	else
		echo "<script language='javascript'> alert('Hay un error en los Datos, Intente Nuevamente ') </script>" ; 
}

if($guardar==1 and $codigo!=0) { // RUTINA PARA EDITAR REGISTROS 
	$campos="nom_bod='".$_REQUEST['nombresito']."',apel_bod='".$_REQUEST['apellidos']."', max_cos_bod='".$_REQUEST['max_traslado']."', iden_bod='".$_REQUEST['identificacion']."',digito_bod='".$_REQUEST['digito']."',dir_bod='".$_REQUEST['direccion']."',  tel_bod='".$_REQUEST['telefono']."', dpto_cli='".$_REQUEST['departamento']."',ciu_bod='".$_REQUEST['ciudad']."', mail_bod='".$_REQUEST['correo']."', propia='".$_REQUEST['propia']."',  cod_lista='".$_REQUEST['lista']."', dias_traslado='".$_REQUEST['ven_traslado']."', dias_credito= '".$_REQUEST['ven_factura']."', cod_covinoc= '".$_REQUEST['cod_covinoc']."', fec_covinoc= '".$_REQUEST['fec_covinoc']."', cupo_au_covinoc= '".$_REQUEST['max_credito']."', cupo_traslados= '".$_REQUEST['max_traslado']."' , cod_bod_cli= '".$_REQUEST['bodega']."', regimen_cli= '".$_REQUEST['v_regimen']."', cod_ruta = '".$_REQUEST['ruta']."'";
	
	$error=editar("bodega1",$campos,'cod_bod',$codigo); 
	if ($error==1) {
		
		header("Location: con_bodegas.php?confirmacion=2&editar=$editar&insertar=$insertar&eliminar=$eliminar"); 
	}
	else
		echo "<script language='javascript'> alert('Hay un error en los Datos, Intente Nuevamente ') </script>" ; 
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="css/styles.css" rel="stylesheet" type="text/css" />
<link href="css/stylesforms.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
.Estilo1 {font-size: 12px}
</style> 

<?php inicio() ?>

<script language="javascript">
function datos_completos(){  
if (document.getElementById('nombresito').value == "" || document.getElementById('ruta').value == 0 || document.getElementById('identificacion').value == ""  )
	return false;
else
	return true;
}

function cargar_ciudad(departamento,ciudad) {
var combo=document.getElementById(ciudad);
combo.options.length=0;
var cant=0;
combo.options[cant] = new Option('Seleccione...','0'); 
cant++;
<?
		$i=0;
		$sqlc ="SELECT * FROM `ciudad` ";		
		$dbc= new  Database();
		$dbc->query($sqlc);
		while($dbc->next_row()){ 
		echo "if(document.getElementById(departamento).value==$dbc->departamento){ ";	
		echo "combo.options[cant] = new Option('$dbc->desc_ciudad','$dbc->cod_ciudad'); ";	
		echo "cant++; } ";
		}
?>
}
</script>
</head>
<body <?php echo $sis?>>
<form  name="forma" id="forma" action="man_bodegas.php"  method="post">
<table width="624" border="0" cellspacing="0" cellpadding="0" align="center" >
  <tr>
    <td bgcolor="#D1D8DE"><table width="100%" height="46" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td bgcolor="#FFFFFF">&nbsp;</td>
        <td bgcolor="#FFFFFF">&nbsp;</td>
        <td bgcolor="#FFFFFF" >&nbsp;</td>
        <td bgcolor="#FFFFFF" >&nbsp;</td>
        <td bgcolor="#FFFFFF" >&nbsp;</td>
        <td bgcolor="#FFFFFF" >&nbsp;</td>
        <td bgcolor="#FFFFFF" >&nbsp;</td>
        <td bgcolor="#FFFFFF" >&nbsp;</td>
        <td bgcolor="#FFFFFF" >&nbsp;</td>
        <td valign="middle" bgcolor="#FFFFFF" >&nbsp;</td>
        <td valign="middle" bgcolor="#FFFFFF">&nbsp;</td>
        <td valign="middle" bgcolor="#FFFFFF">&nbsp;</td>
      </tr>
      <tr>
         <td width="5" height="19">&nbsp;</td>
        <td width="20" ><img src="imagenes/icoguardar.png" alt="Nuevo Registro" width="16" height="16" border="0"  onclick="cambio_guardar()" style="cursor:pointer"/></td>
        <td width="61" class="ctablaform">Guardar</td>
        <td width="21" class="ctablaform"><a href="con_bodegas.php?confirmacion=0&editar=<?php echo $editar?>&insertar=<?php echo $insertar?>&eliminar=<?php echo $eliminar?>"><img src="imagenes/cancel.png" alt="Cancelar" width="16" height="16" border="0" /></a></td>
        <td width="65" class="ctablaform">Cancelar </td>
        
        <td width="21" class="ctablaform"></td>
        <td width="60" class="ctablaform">&nbsp;</td>
        <td width="24" valign="middle" class="ctablaform">&nbsp;</td>
        <td width="193" valign="middle"><label>
          <input type="hidden" name="editar"   id="editar"   value="<?php echo $editar?>">
		  <input type="hidden" name="insertar" id="insertar" value="<?php echo $insertar?>">
		  <input type="hidden" name="eliminar" id="eliminar" value="<?php echo $eliminar?>">
          <input type="hidden" name="codigo" id="codigo" value="<?php echo $codigo?>" />
        </label></td>
        <td width="67" valign="middle">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="4" valign="bottom"><img src="imagenes/lineasup3.gif" alt="." width="100%" height="4" /></td>
  </tr>
  <tr>
    <td class="textotabla1 Estilo1">CLIENTES:</td>
  </tr>
  <tr>
    <td><img src="imagenes/lineasup3.gif"  width="100%" height="4" /></td>
  </tr>
  <tr>
    <td bgcolor="#D1D8DE" valign="top">
	<table width="629" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="105" class="textotabla1">Nombres:</td>
        <td width="159"><input name="nombresito" id="nombresito" type="text" class="textfield2"  value="<?php echo $dbdatos->nom_bod?>" />
          <span class="textorojo">*</span></td>
        <td width="20" align="left" class="textorojo">&nbsp;</td>
        <td width="84" class="textotabla1">Nit/CC:</td>
        <td width="220"><input name="identificacion" id="identificacion" type="text" class="textfield2" onkeypress="return validaInt_evento(this)" value="<?php echo $dbdatos->iden_bod?>"  />
          <input name="digito" id="digito" type="text" maxlength="1" class="textfield0010" onkeypress="return validaInt_evento(this)" value="<?php echo $dbdatos->digito_bod?>"  />
          <span class="textorojo">*</span></td>
        <td width="41" class="textorojo">&nbsp;</td>
        </tr>
      <tr>
        <td class="textotabla1">Apellidos:</td>
        <td><input name="apellidos" id="apellidos" type="text" class="textfield2"  value="<?php echo $dbdatos->apel_bod?>" /></td>
        <td class="textorojo">&nbsp;</td>
        <td class="textotabla1">&nbsp;</td>
        <td>&nbsp;</td>
        <td class="textorojo">&nbsp;</td>
      </tr>
      <tr>
        <td class="textotabla1">Direccion:</td>
        <td><input name="direccion" id="direccion" type="text" class="textfield2"  value="<?php echo $dbdatos->dir_bod?>" /></td>
        <td class="textorojo">&nbsp;</td>
        <td class="textotabla1">Telefonos:</td>
        <td><input name="telefono" id="telefono" type="text" class="textfield2"  value="<?php echo $dbdatos->tel_bod?>" /></td>
        <td class="textorojo">&nbsp;</td>
        </tr>
      <tr>
        <td class="textotabla1">Departamento:</td>
        <td><?php combo_evento("departamento","departamento","cod_departamento","desc_departamento",$dbdatos->dpto_cli,"onchange='cargar_ciudad(\"departamento\",\"ciudad\")'","desc_departamento"); ?></td>
        <td class="textorojo">&nbsp;</td>
        <td class="textotabla1">Ciudad:</td>
        <td><?php 
		if ($codigo == -10) {
			combo_evento_where("ciudad","ciudad","cod_ciudad","desc_ciudad","","","");
		}
		else {
		combo_evento_where("ciudad","ciudad","cod_ciudad","desc_ciudad",$dbdatos->ciu_bod,""," where departamento = $dbdatos->dpto_cli ");
		}?></td>
        <td class="textorojo">&nbsp;</td>
      </tr>
      <tr>
        <td class="textotabla1">E-mail:</td>
        <td><input name="correo" id="correo" type="text" class="textfield2"  value="<?php echo $dbdatos->mail_bod?>" /></td>
        <td class="textorojo">&nbsp;</td>
        <td class="textotabla1">&nbsp;</td>
        <td>&nbsp;</td>
        <td class="textorojo">&nbsp;</td>
        </tr>
	  <tr>
	    <td class="textotabla1">Fecha consulta: </td>
	    <td><input name="fec_covinoc" id="fec_covinoc" type="text" class="textfield2" readonly="-1"  value="<?php echo $dbdatos->fec_covinoc?>" />
	      <img src="imagenes/date.png" alt="Calendario" name="calendario" width="18" height="18" id="calendario" style="cursor:pointer"/></td>
	    <td class="textorojo">&nbsp;</td>
	    <td class="textotabla1">Dias factura: </td>
	    <td><input name="ven_factura" id="ven_factura" onkeypress="return validaInt('%d', this,event)"  type="text" class="textfield2"  value="<?php echo $dbdatos->dias_credito?>" /></td>
	    <td class="textorojo">&nbsp;</td>
	    </tr>
	  <tr>
	    <td class="textotabla1">L.Precio:</td>
	    <td><?php combo("lista","listaprecio","cos_list","nom_list",$dbdatos->cod_lista); ?></td>
	    <td class="textorojo">&nbsp;</td>
	    <td class="textotabla1">Valor credito: </td>
	    <td><input name="max_credito" id="max_credito" type="text" class="textfield2" onkeypress="return validaInt('%d', this,event)"  value="<?php echo $dbdatos->cupo_au_covinoc?>" /></td>
	    <td class="textorojo">&nbsp;</td>
	    </tr>
		 <tr>
	    <td class="textotabla1">Bodega:</td>
	    <td><?php combo("bodega","bodega","cod_bod","nom_bod",$dbdatos->cod_bod_cli); ?></td>
	    <td class="textorojo">&nbsp;</td>
	    <td class="textotabla1">Ruta:</td>
	    <td class="textotabla1">
		<?php 
		combo_evento_where("ruta","ruta","cod_ruta","desc_ruta",$dbdatos->cod_ruta,""," where estado_ruta = 1");
		?>
		<span class="textorojo">*</span></td>
	    <td class="textorojo">&nbsp;</td>
	    </tr>
		 <tr>
		   <td class="textotabla1"><div align="left">Condicion Tributaria:</div></td>
		   <td colspan="5" class="textotabla1"><table width="296" border="0" align="left" cellspacing="0" class="linea_gris">
               <tr>
                 <th width="96" scope="col"><div align="left" class="textotabla1">R&eacute;gimen Comun: </div></th>
                 <?
				if ($dbdatos->regimen_cli=="COMUN"){
$comun="checked='checked'";}
else {
$simplificado="checked='checked'";}
?>
                 <th width="38" bgcolor="f0f0f0" scope="col"><div align="center">
                     <?php if ($codigo==0) { ?>
                     <input name="v_regimen" type="radio"  value="COMUN" onclick="marcado=true" />
                     <?php } else {?>
                     <input name="v_regimen" type="radio"<?php echo $comun?>  value="COMUN" />
                     <?php } ?>
                 </div></th>
                 <th width="112" scope="col"><label class="arial12">
                    <div align="center" class="textotabla1">R&eacute;gimen Simplificado: </div>
                    </label>
                  <label></label></th>
                 <th width="42" bgcolor="f0f0f0" scope="col"> <div align="center">
                     <?php if ($codigo==0) { ?>
                     <input name="v_regimen" type="radio"  value="SIMPLIFICADO" onclick="marcado=true" />
                     <?php } else {?>
                     <input name="v_regimen" type="radio"<?php echo $simplificado?>  value="SIMPLIFICADO" />
                     <?php } ?>
                 </div></th>
               </tr>
                      </table></td>
	      </tr>
		
	  	  <tr>
        <td colspan="6" valign="bottom"></td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><img src="imagenes/lineasup3.gif"  width="100%" height="4" /></td>
  </tr>
  <tr>
    <td height="30"  > <input type="hidden" name="guardar" id="guardar" />
	</td>
  </tr>
</table>
</form> 
</body>
<script type="text/javascript">
			
			Calendar.setup(
				{
					inputField  : "fec_covinoc",      
					ifFormat    : "%Y-%m-%d",    
					button      : "calendario" ,  
					align       :"T3",
					singleClick :true
				}
			);
			
</script>
</html>