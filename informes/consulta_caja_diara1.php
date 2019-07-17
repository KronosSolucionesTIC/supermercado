<?php
include("../lib/database.php");
include("../js/funciones.php");

//RECIBE LAS VARIABLES
$fec_ini = $_REQUEST['fec_ini'];
$fec_fin = $_REQUEST['fec_fin'];
?>
<html>
<head>
<link href="styles.css" rel="stylesheet" type="text/css" />
<link href="../css/styles.css" rel="stylesheet" type="text/css">
<title>INFORME CIERRE DE CAJA</title>
<script language="javascript">
function ver_facturacion(obj,boton)
{
	if(document.getElementById(obj).style.display =="none")
	{
		document.getElementById(obj).style.display ="inline";
		document.getElementById(boton).value ="Ocultar";
	}
	else {
		document.getElementById(obj).style.display ="none";
		document.getElementById(boton).value ="Ver Detalles";
	}
}
</script>
</head>
<body>
<?
if (!isset($_SESSION['global_2'])) {
   $codigo_usuario = $_SESSION['global_2'];
}
else
	$codigo_usuario = $_SESSION['global_2'];


if($guardar_todo==1) {

	$texto=$contexto."<br>Observaciones".$comentario;
	$sql="select cod_usu, nom_usu from usuario  where cod_usu=$codigo_usuario";
	$dbdatos= new  Database();
	$dbdatos->query($sql);
	if ($dbdatos->next_row())
		$nombre=$dbdatos->nom_usu;
		echo "Enviando alerta de cierre a usuario ".$nombre;
		enviar_alerta("Alerta Cierre de $nombre " , "  $texto  ");
	}
	
	$db_ver = new Database();
	$sql="select distinct punto_venta.cod_bod as valor , nom_bod as nombre 
	from punto_venta  inner join  bodega  on punto_venta.cod_bod=bodega.cod_bod ";
	$dbdatos= new  Database();
	$dbdatos->query($sql);
	$where_cli="";	
	$primeravez = true;
	while($dbdatos->next_row())
	{
		if ($primeravez) {
		   $where_cli=" and bodega1.cod_bod_cli in (";
		   $where_cli .= "'".$dbdatos->valor."'";
		   $primeravez = false;
		}
		else
		   $where_cli .= ", '".$dbdatos->valor."'";
	}	
	if ($primeravez == false) {	
		$where_cli.=" )";
	}

	$sql = "SELECT 
			m_factura.num_fac,
			m_factura.fecha,
			m_factura.tipo_pago,
			m_factura.tot_fac, 
			m_factura.tot_dev_mfac,
			bodega.cod_bod,
			usuario.nom_usu as responsable,
			CONCAT(bodega1.nom_bod,' ',bodega1.apel_bod) as cliente
		 FROM m_factura  
		 INNER JOIN bodega1 ON  (bodega1.cod_bod = m_factura.cod_cli) 
		 LEFT JOIN usuario ON ( usuario.cod_usu=m_factura.cod_usu)
		 INNER JOIN bodega ON bodega.cod_bod = m_factura.cod_bod 
		 WHERE  ( fecha >='$fec_ini' AND fecha <='$fec_fin' ) AND estado IS  NULL $where_cli";
	$db_ver->query($sql);	
	$cantidad_max=0;;
?>
<table width="731" border="1" cellpadding="2" cellspacing="1"   class="textoproductos1" align="center">
  <tr>
    <td class="ctablasup" align="center">CUENTA CAJA</td>
  </tr>
  <tr>
    <td align="center"><br></td>
  </tr>
  <tbody  id="facturacion" style="display:none" width="100%">
    <tr>
      <td width="11%" class="subtitulosproductos"><div align="center">Responsable</div></td>
      <td width="11%" class="subtitulosproductos"><div align="center">Factura No. </div></td>
      <td class="subtitulosproductos"><div align="center">Fecha</div></td>
      <td class="subtitulosproductos"><div align="center">Tipo Pago</div></td>
      <td class="subtitulosproductos"><div align="center">Cliente</div></td>
      <td class="subtitulosproductos"><div align="center">Valor</div></td>
    </tr>
    <?php 
	$total_fact_1=0;
	$max_credito = 0;
	$max_contado = 0;  
	while($db_ver->next_row()){ 
	    $valor = $db_ver->tot_fac - $db_ver->tot_dev_mfac;
		if($db_ver->tipo_pago=='Credito')
			$max_credito += $valor;	
		if($db_ver->tipo_pago=='Contado')
			$max_contado += $valor;
	    ?>
    <tr>
      <td class="textoproductos1"><div align="center">
          <?php echo $db_ver->responsable?>
          </div>
      <div align="center"></div></td>
      <td class="textoproductos1"><div align="center">
          <?php echo $db_ver->num_fac?>
      </div></td>
      <td width="6%" class="textoproductos1">
      <div align="center"><?php echo $db_ver->fecha?></div></td>
      <td width="23%" class="textoproductos1"><div align="center">
        <?php echo $db_ver->tipo_pago?>
      </div></td>
      <td width="23%" class="textoproductos1">
      <div align="center"><?php echo $db_ver->cliente?></div></td>
      <td class="textoproductos1" align="right"><?php echo number_format($valor,0,".",".")?></td>
      </tr>
    <?
		$cantidad_max += $valor;
		$total_fact_1 += $valor;
	} 	
	?>
  </tbody>
  <tr>
    <td class="subtitulosproductos"><div align="right"><b>TOTAL FACTURACION:
        <?php echo number_format($cantidad_max,0,".",".")?>
    </b></div></td>
  </tr>
  <tr>
    <td align="center"><input name="button2" type="button" class="botones"  id="btn_fac" onClick="ver_facturacion('facturacion','btn_fac')" value="Ver Detalles" /></td>
  </tr>
  <tr>
    <td  class="ctablasup" align="center">ANULACIONES</td>
  </tr>
  <tr>
    <td  align="center"><br></td>
  </tr>
  <tbody  id="facturacion3" style="display:none" width="100%">
    <tr>
      <td width="11%" class="subtitulosproductos"><div align="center">Responsable</div></td>
      <td width="11%" class="subtitulosproductos"><div align="center">Factura No. </div></td>
      <td class="subtitulosproductos"><div align="center">Fecha</div></td>
      <td class="subtitulosproductos"><div align="center">Tipo Pago</div></td>
      <td class="subtitulosproductos"><div align="center">Cliente</div></td>
      <td class="subtitulosproductos"><div align="center">Valor</div></td>
    </tr>
    <?
  $dba = new Database();
  $sqla = "SELECT *,CONCAT(nom_bod,' ',apel_bod) AS cliente FROM m_factura 
  INNER JOIN usuario ON usuario.cod_usu = m_factura.cod_usu
  INNER JOIN bodega1 ON bodega1.cod_bod = m_factura.cod_cli
  WHERE(fecha >='$fec_ini' AND fecha <='$fec_fin') AND estado='anulado'";
  $dba->query($sqla);
  	while($dba->next_row()){
		$valor_anul = $dba->tot_fac - $dba->tot_dev_mfac;
  ?>
    <tr>
      <td class="textoproductos1">
      <div align="center"><?php echo $dba->nom_usu?></div></td>
      <td class="textoproductos1">
      <div align="center"><?php echo $dba->num_fac?></div></td>
      <td class="textoproductos1"><div align="center"><?php echo $dba->fecha?></div></td>
      <td class="textoproductos1">
      <div align="center"><?php echo $dba->tipo_pago?></div></td>
      <td class="textoproductos1">
      <div align="center"><?php echo $dba->cliente?></div></td>
      <td class="textoproductos1" align="right"><?php echo number_format($valor_anul,0,".",".")?></td>
    </tr>
    <tr>
      <?php 
	$dbra = new Database();
	$sqlra = "SELECT * FROM razon_anulacion
	WHERE cod_razon = '$dba->razon_anulacion'";
	$dbra->query($sqlra);
	$dbra->next_row();
	?>
      <br>
      <td colspan="6" class="subtitulosproductos" align="right"><?php echo $dbra->desc_razon?>; <?php echo $dba->obs_anulacion?></td>
    </tr>
    <?php 
		$total_anul += $valor_anul;
	} 
	?>
   </tbody>
  <tr>
    <td class="subtitulosproductos"><div align="right"><b>TOTAL ANULACIONES:
        <?php echo number_format($total_anul,0,".",".")?>
    </b></div></td>
  </tr>
    
  <tr>
    <td align="center" ><input name="btn_fac3" type="button" class="botones"  id="btn_fac3" onClick="ver_facturacion('facturacion3','btn_fac3')" value="Ver Detalles" /></td>
  </tr>
  <tr>
    <td  class="ctablasup" align="center">ABONOS</td>
  </tr> 
  <tbody   id="abonos" style="display:none"  width="100%">
    <tr>
      <td width="11%" class="subtitulosproductos"><div align="center">Responsable</div></td>
      <td width="11%" class="subtitulosproductos"><div align="center">Abono No. </div></td>
      <td class="subtitulosproductos"><div align="center">Fecha</div></td>
      <td class="subtitulosproductos"><div align="center">Tipo Pago</div></td>
      <td colspan="2" class="subtitulosproductos"><div align="center">Cliente</div></td>
      <td class="subtitulosproductos"><div align="center">Valor</div></td>
    </tr>
    <?php 
	$sql = "SELECT 
	abono.cod_abo, abono.fec_abo, abono.val_abo,abono.tipo_pago, 
	CONCAT(bodega1.nom_bod,' ',bodega1.apel_bod) as cliente, 
	 ( select usuario.nom_usu  from usuario where   usuario.cod_usu = abono.cod_usu_abo) as responsable
	FROM abono  
	INNER JOIN bodega1 ON abono.cod_bod_Abo = bodega1.cod_bod 
	WHERE (abono.fec_abo >='$fec_ini'   AND  abono.fec_abo <='$fec_fin')  $where_cli AND bodega1.cod_bod_cli =225";
	
	$db_ver->query($sql);
	$max_abono=0; 
 	while($db_ver->next_row()){ 
		?>
    <tr>
      <?
				$dbtp = new Database();
				$sqltp = "SELECT * FROM tipo_pago 
				WHERE cod_tpag = '$db_ver->tipo_pago'"; 
				$dbtp->query($sqltp);
				$dbtp->next_row();
			?>
      <td class="textoproductos1"><div align="center">
        <?php echo $db_ver->responsable?>
      </div></td>
      <td class="textoproductos1"><div align="center">
        <?php echo $db_ver->cod_abo?>
      </div></td>
      <td width="6%" class="textoproductos1"><div align="center">
        <?php echo $db_ver->fec_abo?>
      </div></td>
      <td width="23%" class="textoproductos1"><div align="center">
        <?php echo $dbtp->nom_tpag?>
      </div></td>
      <td colspan="2" class="textoproductos1"><div align="center">
        <?php echo $db_ver->cliente?>
      </div></td>
      <td width="27%" class="textoproductos1" align="right"><?php echo number_format($db_ver->val_abo,0,".",".")?></td>
      </tr>
    <?
		$max_abono += $db_ver->val_abo;
		if($dbtp->cod_tpag == 4){
			$consig_abonos += $db_ver->val_abo ;	
		}
		if($dbtp->cod_tpag == 3){
			$cheques_abonos += $db_ver->val_abo ;	
		}
		if($dbtp->cod_tpag == 5){
			$data_abonos += $db_ver->val_abo;	
		}
		if($dbtp->cod_tpag == 7){
			$nomina_abonos += $db_ver->val_abo;	
		}
		if(($dbtp->cod_tpag == 2)){
			$max_efectivo += $db_ver->val_abo; 
		}
		if(($dbtp->cod_tpag == 6)){
			$net_abonos += $db_ver->val_abo; 
		}
	} ?>
  </tbody>
  <tr>
    <td class="subtitulosproductos"><div align="right"><b>TOTAL ABONOS: 
        <?php echo number_format($max_abono,0,".",".")?>
    </b></div></td>
  </tr>
  <tr>
    <td  align="center"><br>
    <input name="btn_abo" type="button" class="botones"  id="btn_abo" onClick="ver_facturacion('abonos','btn_abo')" value="Ver Detalles" /></td>
  </tr>
  <tr>
    <td  class="ctablasup" align="center">CONSIGNACIONES</td>
  </tr> 
  <?php $val_otros = 0 ?>
  <tbody   id="consignacion" style="display:none"  width="100%">
    <tr>
      <td width="11%" class="subtitulosproductos"><div align="center">Responsable</div></td>
      <td width="11%" class="subtitulosproductos"><div align="center">Factura No. </div></td>
      <td class="subtitulosproductos"><div align="center">Fecha</div></td>
      <td class="subtitulosproductos"><div align="center">Cliente</div></td>
      <td class="subtitulosproductos"><div align="center">Valor</div></td>
    </tr>
    <?php 
	$sql = "SELECT 
	  OP.fec_otro,
	  OP.val_otro,
	  OP.cod_fac,
	  CONCAT(bodega1.nom_bod,' ',bodega1.apel_bod) as cliente,
	  usuario.nom_usu
	  FROM
	  otros_pagos  OP 
	  LEFT JOIN bodega1 ON (OP.cod_cli_otro = bodega1.cod_bod)
	  LEFT JOIN usuario ON (OP.cod_usu_otro = usuario.cod_usu)  
	  WHERE  ( OP.fec_otro >='$fec_ini'   AND OP.fec_otro <='$fec_fin' ) AND cod_tpag_otro = 4 AND bodega1.cod_bod_cli = 225";
	  
	$db_ver->query($sql);
	$val_con=0; 
	while($db_ver->next_row()){ 
		?>
    <tr>
      <?
				$dbf = new Database();
				$sqlf = "SELECT * FROM m_factura 
				WHERE cod_fac = '$db_ver->cod_fac'"; 
				$dbf->query($sqlf);
				$dbf->next_row();
					if($dbf->tipo_pago == 'Credito'){
						$deducciones_credito += $db_ver->val_otro;
					}
			?>
      <td class="textoproductos1">
      <div align="center"><?php echo $db_ver->nom_usu?></div></td>
      <td class="textoproductos1"><div align="center">
        <?php echo $dbf->num_fac?>
      </div></td>
      <td width="6%" class="textoproductos1"><div align="center">
        <?php echo $db_ver->fec_otro?>
      </div></td>
      <td width="23%" class="textoproductos1"><div align="center">
        <?php echo $db_ver->cliente?>
      </div></td>
      <td class="textoproductos1" align="right"><?php echo number_format($db_ver->val_otro,0,".",".")?></td>
      </tr>
    <?
		$val_con += $db_ver->val_otro;
  	} ?>
  </tbody>
  <tr>
    <td class="subtitulosproductos"><div align="right"><b>TOTAL CONSIGNACIONES:
        <?php echo number_format($val_con,0,".",".")?>
    </b></div></td>
  </tr>
  <tr>
    <td align="center" ><input name="btn_con" type="button" class="botones"  id="btn_con" onClick="ver_facturacion('consignacion','btn_con')" value="Ver Detalles" /></td>
  </tr>
  </tr>
  <tr>
    <td  class="ctablasup" align="center">CHEQUES</td>
  </tr> 
  <tbody   id="cheques" style="display:none"  width="100%">
    <tr>
      <td width="11%" class="subtitulosproductos"><div align="center">Responsable</div></td>
      <td width="11%" class="subtitulosproductos"><div align="center">Factura No. </div></td>
      <td class="subtitulosproductos"><div align="center">Fecha</div></td>
      <td class="subtitulosproductos"><div align="center">Cliente</div></td>
      <td class="subtitulosproductos"><div align="center">Valor</div></td>
    </tr>
    <?php 
	$sql = "SELECT 
	  OP.fec_otro,
	  OP.val_otro,
	  OP.cod_fac,
	  CONCAT(bodega1.nom_bod,' ',bodega1.apel_bod) as cliente,
	  usuario.nom_usu
	  FROM
	  otros_pagos  OP 
	  LEFT JOIN bodega1 ON (OP.cod_cli_otro = bodega1.cod_bod)
	  LEFT JOIN usuario ON (OP.cod_usu_otro = usuario.cod_usu)  
	  WHERE  ( OP.fec_otro >='$fec_ini'   AND OP.fec_otro <='$fec_fin' ) AND cod_tpag_otro = 3 AND bodega1.cod_bod_cli = 225";
	
	$db_ver->query($sql);
	$val_che=0; 
 	while($db_ver->next_row()){ 
		?>
    <tr>
      <?
				$dbf = new Database();
				$sqlf = "SELECT * FROM m_factura 
				WHERE cod_fac = '$db_ver->cod_fac'"; 
				$dbf->query($sqlf);
				$dbf->next_row();
				if($dbf->tipo_pago == 'Credito'){
					$deducciones_credito += $db_ver->val_otro;
				}
			?>
      <td class="textoproductos1"><div align="center">
        <?php echo $db_ver->nom_usu?>
      </div></td>
      <td class="textoproductos1"><div align="center">
        <?php echo $dbf->num_fac?>
      </div></td>
      <td width="6%" class="textoproductos1"><div align="center">
        <?php echo $db_ver->fec_otro?>
      </div></td>
      <td width="23%" class="textoproductos1"><div align="center">
        <?php echo $db_ver->cliente?>
      </div></td>
      <td class="textoproductos1" align="right"><?php echo number_format($db_ver->val_otro,0,".",".")?></td>
      <?
		$val_che += $db_ver->val_otro;
	} ?>
  </tbody>
  <tr>
    <td class="subtitulosproductos"><div align="right"><b>TOTAL CHEQUES:
        <?php echo number_format($val_che,0,".",".")?>
    </b></div></td>
  </tr>
  <tr>
    <td  align="center"><br>
    <input name="btn_che" type="button" class="botones"  id="btn_che" onClick="ver_facturacion('cheques','btn_che')" value="Ver Detalles" /></td>
  </tr>
  <tr>
    <td  class="ctablasup" align="center">DATAFONO</td>
  </tr> 
  <tbody   id="datafono" style="display:none"  width="100%">
    <tr>
      <td width="11%" class="subtitulosproductos"><div align="center">Responsable</div></td>
      <td width="11%" class="subtitulosproductos"><div align="center">Factura No. </div></td>
      <td class="subtitulosproductos"><div align="center">Fecha</div></td>
      <td class="subtitulosproductos"><div align="center">Cliente</div></td>
      <td class="subtitulosproductos"><div align="center">Valor</div></td>
    </tr>
    <?php 
	$sql = "SELECT 
	  OP.fec_otro,
	  OP.val_otro,
	  OP.cod_fac,
	  CONCAT(bodega1.nom_bod,' ',bodega1.apel_bod) as cliente,
	  usuario.nom_usu
	  FROM
	  otros_pagos  OP 
	  LEFT JOIN bodega1 ON (OP.cod_cli_otro = bodega1.cod_bod)
	  LEFT JOIN usuario ON (OP.cod_usu_otro = usuario.cod_usu)  
	  WHERE  ( OP.fec_otro >='$fec_ini'   AND OP.fec_otro <='$fec_fin' ) AND cod_tpag_otro = 5 AND bodega1.cod_bod_cli = 225";
	
	$db_ver->query($sql);
	$val_data=0; 
 	while($db_ver->next_row()){ 
		?>
    <tr>
      <?
				$dbf = new Database();
				$sqlf = "SELECT * FROM m_factura 
				WHERE cod_fac = '$db_ver->cod_fac'"; 
				$dbf->query($sqlf);
				$dbf->next_row();
				if($dbf->tipo_pago == 'Credito'){
					$deducciones_credito += $db_ver->val_otro;
				}
			?>
      <td class="textoproductos1"><div align="center">
        <?php echo $db_ver->nom_usu?>
      </div></td>
      <td class="textoproductos1"><div align="center">
        <?php echo $dbf->num_fac?>
      </div></td>
      <td width="6%" class="textoproductos1"><div align="center">
        <?php echo $db_ver->fec_otro?>
      </div></td>
      <td width="23%" class="textoproductos1"><div align="center">
        <?php echo $db_ver->cliente?>
      </div></td>
      <td class="textoproductos1" align="right"><?php echo number_format($db_ver->val_otro,0,".",".")?></td>
      </tr>
    <?
		$val_data += $db_ver->val_otro;
	} ?>
  </tbody>
  <tr>
    <td class="subtitulosproductos"><div align="right"><b>TOTAL DATAFONO:
        <?php echo number_format($val_data,0,".",".")?>
    </b></div></td>
  </tr>
  <tr>
    <td  align="center"><br>
    <input name="btn_data" type="button" class="botones"  id="btn_data" onClick="ver_facturacion('datafono','btn_data')" value="Ver Detalles" /></td>
  </tr>
  <tr>
    <td class="subtitulosproductos">
      <table width="100%" border="0">
        <tr>
          <td><?php 
	
	$val_otros = $val_con + $val_che + $val_data;
	$sql = "SELECT SUM(valor_gasto) as total FROM gastos 
	WHERE  ( fecha_gasto >='$fec_ini' AND fecha_gasto <='$fec_fin' ) AND estado_gasto = 1";
	$db_ver->query($sql);
	if ($db_ver->next_row()){ 
		$total_gastos= (double) $db_ver->total;		
	}
		
	//echo $total_gastos."==========";
	$total_con = $val_con + $consig_abonos;
	$total_che = $val_che + $cheques_abonos;
	$total_data = $val_data + $data_abonos;
	$total_credito = $max_credito - $deducciones_credito;
	$contexto.=$dato_total="Total  Facturaci&oacute;n del per&iacute;odo de  &nbsp; $fec_ini  &nbsp;  al     &nbsp;$fec_fin por:";
	$contexto2.="$".number_format($total_fact_1,0,".",".");
	$contexto.="<br>";
	$contexto2.="<br>";
	$contexto.="Total venta neta credito:";
	$contexto2.="$".number_format($total_credito,0,".",".");
	$contexto.="<br>";
	$contexto2.="<br>";
	$contexto.="Total abonos: &nbsp;&nbsp;&nbsp;";
	$contexto2.="$".number_format($max_abono,0,".",".");
	$contexto.="<br>";
	$contexto2.="<br>";
	$contexto.="Total consignaciones: &nbsp;&nbsp;&nbsp;";
	$contexto2.="$".number_format($total_con,0,".",".");
	$contexto.="<br>";
	$contexto2.="<br>";
	$contexto.="Total cheques: &nbsp;&nbsp;&nbsp;";
	$contexto2.="$".number_format($total_che,0,".",".");
	$contexto.="<br>";
	$contexto2.="<br>";
	$contexto.="Total datafonos: &nbsp;&nbsp;&nbsp;";
	$contexto2.="$".number_format($total_data,0,".",".");
	$contexto.="<br>";
	$contexto2.="<br>";
	$contexto.="Total gastos: &nbsp;&nbsp;&nbsp;";
	$contexto2.="$".number_format($total_gastos,0,".",".");
	$contexto.="<br>";
	$contexto2.="<br>";
	$efectivo =($total_fact_1 + $max_abono)-($total_credito)-($total_con)-($total_che)-($total_data)-($total_gastos);
	$contexto.="<br>";
	$contexto2.="<br>";
	$contexto.="Efectivo del Per&iacute;odo:  &nbsp;&nbsp;&nbsp;";
	$contexto2.="$".number_format($efectivo,0,".",".");
	$contexto2.="<br>";
	$contexto2.="<br>";
	
	echo $contexto;
	

	echo "<br>";
	echo "<br>";
	?></td>
          <td><div align="right"><?php echo $contexto2;?></div></td>
        </tr>
        </table></td>
  </tr>
  <form method="POST" action="consulta_caja_diara1.php" name="enviar_correo">
    <tr>
      <td align="center" ><input name="button" type="button" class="botones"  onClick="window.print()" value="Imprimir" />
        <input name="button2" type="button" class="botones"  onClick="window.close()" value="Cerrar" />
        <input type="hidden" name="mapa" value="<?php echo $mapa?>"></td>
    </tr>
  </form>
</table>
<p>&nbsp;</p>
<table width="731" border="1" cellpadding="2" cellspacing="1"   class="textoproductos1" align="center">
    <?php 
	$deducciones_credito = 0;
	$total_fact_2 = 0;
	$max_credito = 0;
	$max_contado = 0;  
	while($db_ver->next_row()){ 
	    $valor = $db_ver->tot_fac - $db_ver->tot_dev_mfac;
		if($db_ver->tipo_pago=='Credito')
			$max_credito += $valor;	
		if($db_ver->tipo_pago=='Contado')
			$max_contado += $valor;
			$cantidad_max += $valor;
			$total_fact_2 += $valor;
	} 	

	$db_blaus = new Database();
  	$sql_blaus = "SELECT 
			m_factura.num_fac,
			m_factura.fecha,
			m_factura.tipo_pago,
			m_factura.tot_fac, 
			m_factura.tot_dev_mfac,
			bodega1.cod_ruta,
			usuario.nom_usu as responsable,
			CONCAT(bodega1.nom_bod,' ',bodega1.apel_bod) as cliente,
			usuario.cod_usu as codigo_usuario,
			bodega1.cod_bod as codigo_cliente
		 FROM m_factura  
		 INNER JOIN bodega1 ON  (bodega1.cod_bod = m_factura.cod_cli) 
		 INNER JOIN ruta ON ruta.cod_ruta = bodega1.cod_ruta
		 LEFT JOIN usuario ON ( usuario.cod_usu=m_factura.cod_usu) 
		 WHERE  ( fecha >='2014-12-04' AND fecha <='$fec_fin' )
		 AND estado IS  NULL $where_cli ";
	$db_blaus->query($sql_blaus);	
	$cantidad_max_blaus=0;
 
	while($db_blaus->next_row()){ 
		if(($db_blaus->codigo_usuario == 33)or($db_blaus->codigo_cliente == 970)or($db_blaus->cod_ruta == 31)){
				$valor_blaus = $db_blaus->tot_fac - $db_blaus->tot_dev_mfac;
			if($db_blaus->tipo_pago=='Credito')
				$max_credito += $valor_blaus;	
			if($db_blaus->tipo_pago=='Contado')
				$max_contado += $valor_blaus;
				$cantidad_max_blaus += $valor_blaus;
				$total_fact_2 += $valor_blaus;
		}
	} 	

  	$dba = new Database();
  	$sqla = "SELECT *,CONCAT(nom_bod,' ',apel_bod) AS cliente FROM m_factura 
  	INNER JOIN usuario ON usuario.cod_usu = m_factura.cod_usu
  	INNER JOIN bodega1 ON bodega1.cod_bod = m_factura.cod_cli
  	WHERE (fecha >= '2014-12-04' AND fecha <= '$fec_fin') AND estado='anulado' AND bodega1.cod_bod_cli = 225";
  	$dba->query($sqla);
  		while($dba->next_row()){
			$valor_anul = $dba->tot_fac - $dba->tot_dev_mfac;
			$dbra = new Database();
			$sqlra = "SELECT * FROM razon_anulacion
			WHERE cod_razon = '$dba->razon_anulacion'";
			$dbra->query($sqlra);
			$dbra->next_row();
		
			$total_anul += $valor_anul;
		} 

	$sql = "SELECT  
	m_factura.num_fac, m_factura.fecha, m_devolucion.val_del,
	CONCAT(bodega1.nom_bod,' ',bodega1.apel_bod) as cliente, 
	usuario.nom_usu 
	FROM m_devolucion 
	INNER JOIN m_factura ON m_devolucion.num_fac_dev = m_factura.cod_fac 
	INNER JOIN bodega1 ON m_factura.cod_cli = bodega1.cod_bod 
	LEFT JOIN usuario ON m_devolucion.cod_ven_dev = usuario.cod_usu 
	WHERE ( (fecha >= '2014-12-04' AND fecha <= '$fec_fin')) AND estado IS  NULL $where_cli  AND bodega1.cod_bod_cli = 225";
	$db_ver->query($sql);	
	$total_dev=0;
	$max_total=0;
	while($db_ver->next_row()){ 
		$total_dev += $db_ver->val_del;
		$max_total += $db_ver->val_del;
	} 
	
	$sql = "SELECT 
	abono.cod_abo, abono.fec_abo, abono.val_abo,abono.tipo_pago, 
	CONCAT(bodega1.nom_bod,' ',bodega1.apel_bod) as cliente, 
	 ( select usuario.nom_usu  from usuario where   usuario.cod_usu = abono.cod_usu_abo) as responsable
	FROM abono  
	INNER JOIN bodega1 ON abono.cod_bod_Abo = bodega1.cod_bod 
	WHERE (abono.fec_abo >='2014-12-04'   AND  abono.fec_abo <='$fec_fin')  $where_cli AND bodega1.cod_bod_cli != 340";
	
	$db_ver->query($sql);
	$max_abono=0; 
 	while($db_ver->next_row()){ 
		$dbtp = new Database();
		$sqltp = "SELECT * FROM tipo_pago 
		WHERE cod_tpag = '$db_ver->tipo_pago'"; 
		$dbtp->query($sqltp);
		$dbtp->next_row();

		$max_abono += $db_ver->val_abo;
		if($dbtp->cod_tpag == 4){
			$consig_abonos2 += $db_ver->val_abo ;	
		}
		if($dbtp->cod_tpag == 3){
			$cheques_abonos2 += $db_ver->val_abo ;	
		}
		if($dbtp->cod_tpag == 5){
			$data_abonos2 += $db_ver->val_abo;	
		}
		if($dbtp->cod_tpag == 7){
			$nomina_abonos2 += $db_ver->val_abo;	
		}
		if(($dbtp->cod_tpag == 2)){
			$max_efectivo2 += $db_ver->val_abo; 
		}
		if(($dbtp->cod_tpag == 6)){
			$net_abonos2 += $db_ver->val_abo; 
		}
	} 
	$val_otros = 0 ;
	$sql = "SELECT 
	  OP.fec_otro,
	  OP.val_otro,
	  OP.cod_fac,
	  CONCAT(bodega1.nom_bod,' ',bodega1.apel_bod) as cliente,
	  usuario.nom_usu
	  FROM
	  otros_pagos  OP 
	  LEFT JOIN bodega1 ON (OP.cod_cli_otro = bodega1.cod_bod)
	  LEFT JOIN usuario ON (OP.cod_usu_otro = usuario.cod_usu)  
	  WHERE  ( OP.fec_otro >='2014-12-04'   AND OP.fec_otro <='$fec_fin' ) AND cod_tpag_otro = 4 AND bodega1.cod_bod_cli = 225";
	  
	$db_ver->query($sql);
	$val_con2=0; 
	while($db_ver->next_row()){ 
		$dbf = new Database();
		$sqlf = "SELECT * FROM m_factura 
		WHERE cod_fac = '$db_ver->cod_fac'"; 
		$dbf->query($sqlf);
		$dbf->next_row();
			if($dbf->tipo_pago == 'Credito'){
				$deducciones_credito += $db_ver->val_otro;
			}
			
		$val_con2 += $db_ver->val_otro;
  	} 
	 
	$sql = "SELECT 
	  OP.fec_otro,
	  OP.val_otro,
	  OP.cod_fac,
	  CONCAT(bodega1.nom_bod,' ',bodega1.apel_bod) as cliente,
	  usuario.nom_usu
	  FROM
	  otros_pagos  OP 
	  LEFT JOIN bodega1 ON (OP.cod_cli_otro = bodega1.cod_bod)
	  LEFT JOIN usuario ON (OP.cod_usu_otro = usuario.cod_usu)  
	  WHERE ( OP.fec_otro >= '2014-12-04' AND OP.fec_otro <= '$fec_fin' )  AND cod_tpag_otro = 3 AND bodega1.cod_bod_cli = 225";
	
	$db_ver->query($sql);
	$val_che2=0; 
 	while($db_ver->next_row()){ 
		$dbf = new Database();
		$sqlf = "SELECT * FROM m_factura 
		WHERE cod_fac = '$db_ver->cod_fac'"; 
		$dbf->query($sqlf);
		$dbf->next_row();
		if($dbf->tipo_pago == 'Credito'){
			$deducciones_credito += $db_ver->val_otro;
		}
		$val_che2 += $db_ver->val_otro;
	} 
	
	$sql = "SELECT 
	  OP.fec_otro,
	  OP.val_otro,
	  OP.cod_fac,
	  CONCAT(bodega1.nom_bod,' ',bodega1.apel_bod) as cliente,
	  usuario.nom_usu
	  FROM
	  otros_pagos  OP 
	  LEFT JOIN bodega1 ON (OP.cod_cli_otro = bodega1.cod_bod)
	  LEFT JOIN usuario ON (OP.cod_usu_otro = usuario.cod_usu)  
	  WHERE ( OP.fec_otro >= '2014-12-04' AND OP.fec_otro <= '$fec_fin' ) AND cod_tpag_otro = 5 AND bodega1.cod_bod_cli = 225";
	
	$db_ver->query($sql);
	$val_data2=0; 
 	while($db_ver->next_row()){ 
		$dbf = new Database();
		$sqlf = "SELECT * FROM m_factura 
		WHERE cod_fac = '$db_ver->cod_fac'"; 
		$dbf->query($sqlf);
		$dbf->next_row();
		if($dbf->tipo_pago == 'Credito'){
			$deducciones_credito += $db_ver->val_otro;
		}
		$val_data2 += $db_ver->val_otro;
	} ?>
</table>
<p>&nbsp; </p>
<script language="javascript">
function guardar_correo(){
	document.getElementById('guardar_todo').value=1
	document.enviar_correo.submit();
}
function abrir(id){
	window.open("ver_abono.php?codigo="+id,"ventana","menubar=0,resizable=1,width=700,height=400,toolbar=0,scrollbars=yes")
}
  </script>
</body>
</html>