<?
include "../lib/sesion.php";
include("../lib/database.php");
include("../conf/clave.php");				
	$db = new Database();
	$db_ver = new Database();
	$sql = "select *, DATE_ADD(fecha ,interval 30 day) as fac_fecha_vence   from m_factura where cod_fac=$codigo";
	$db_ver->query($sql);	
	if($db_ver->next_row())
	{ 
		$rem_fac=$db_ver->rem_fac;
		$cod_razon=$db_ver->cod_razon_fac;
		$fac_numero=$db_ver->num_fac;
		$fac_fecha=$db_ver->fecha;
		$tipo_fac=$db_ver->tipo_fac;
		$codigo_bod=$db_ver->bod_cli_fac;
		$codigo_cli=$db_ver->cod_cli;
		$codigo_razon=$db_ver->cod_razon_fac;
		$tipo_pago=$db_ver->tipo_pago;
		$fac_fecha_vence=$db_ver->fac_fecha_vence;
		$estado_factura=$db_ver->estado;
		$cod_usuario=$db_ver->cod_usu;
		$obs_fac =$db_ver->obs;
		$tot_fac = $db_ver->tot_fac;
	}

$codigo_salida=$codigo_cli;
$db_fac = new Database();
$sql ='select * from rsocial where cod_rso='.$codigo_razon ;
$db_fac->query($sql);
if($db_fac->next_row()){ 
	$razon=$db_fac->nom_rso;
	$nit=$db_fac->nit_rso;
	$telefono=$db_fac->tel_rso;
	$direccion=$db_fac->dir_rso;
	$ciudad=$db_fac->ciu_razon;
	$leyenda=$db_fac->desc1_rso;
	$leyenda2=$db_fac->desc2_rso;
	$logo=$db_fac->logo_rso;
	$regimen = $db_fac->reg_rso;
	$obs_fac = $db_ver->obs;
}




?>
<script language="javascript">
function imprimir(){
	window.print();
	window.close();
}
</script>
<title>FACTURA DE VENTA</title>
<style type="text/css">
<!--
.Estilo3 {
	font-size: 11px;
	font-family: "verdana";
	margin: 0px;
    padding: 0px;
}
-->
</style>

<?
if($estado_factura=="anulado")
	$anulacion="background='../imagenes/anulacion.gif'";
?>
<body onload='imprimir()'>
<TABLE  border="0" cellpadding="0" cellspacing="0"  width="100%" <?=$anulacion?> class='Estilo3'>
	<TR>
			  <TD align="center">
			    <tr>
			      <td colspan="3"><div align="center"><span class="Estilo3"><?=$razon?></span></div></td>
		        </tr>
			    <tr>
			      <td><span class="Estilo3">DIRECCION:</span></td>
			      <td colspan="2"><div align="right"><span class="Estilo3">
			        <?=$direccion?>
		          </span></div></td>
		        </tr>
			    <tr>
			      <td><span class="Estilo3">TELEFONO:</span></td>
			      <td colspan="2"><div align="right"><span class="Estilo3">
			        <?=$telefono?>
		          </span></div></td>
		        </tr>
			    <tr>
			      <td colspan="2"><span class="Estilo3">
			        <? if ($rem_fac=="remision") { echo " Remision:"; } ?>
			        <? if ($rem_fac=="factura") { echo " Factura de venta:"; } ?>
			        <? if($es_abono!="si"){?>
			        FACTURA DE VENTA No:</span></td>
			      <td width="8%"><div align="right"><span class="Estilo3">
				  <?  echo $fac_numero;?>
                  <?  }?>
		          </span></div></td>
		        </tr>
			    <tr>
			      <td><span class="Estilo3">FECHA:</span></td>
			      <td colspan="2"><div align="right"><span class="Estilo3"><? echo $fac_fecha; ?></span></div></td>
                </tr>
                <tr>
			      <td width="48%"><span class="Estilo3"><? if($es_abono!="si"){?>
			        TIPO PAGO:
			        </span></td>
                    <td colspan="2"><div align="right"><span class="Estilo3"><? echo $tipo_pago; ?>
		              <?  }  ?>
	                </span></div></td>
                    
		        </tr>
			    <? if($tipo_pago == "Credito"){?>
			    <tr>
			      <td><span class="Estilo3">FECHA DE VENCIMIENTO:</span></td>
			      <td colspan="3"><div align="right"><span class="Estilo3">
			        <?=$fac_fecha_vence?>
			        </span></div></td>
		        </tr>
			    <?  }  ?>
			    <tr>
			      <td colspan="4">
			        <tr>
			          <td><div align="justify"><span class="Estilo3">
			            <?=$leyenda?>
			            </span></div></td>
		            </tr>
			        </td>
		        </tr>
			</TD>
		  </TR>			
			<TR>
			  <TD>
                <tr >
                  <td width="58%" ><span class="Estilo3">DESCRIPCION</span></td>
                  <td width="12%" ><div align="center" class="Estilo3"><span class="Estilo3">CANT.</span></div></td>
                  <td colspan="2" ><div align="right" class="Estilo3">VALOR </div></td>
                </tr>
                <?
				$total=0;
				$sql = " select * from d_factura 
				left join tipo_producto on d_factura.cod_tpro=tipo_producto.cod_tpro
				left join marca on d_factura.cod_cat=marca.cod_mar left join peso on d_factura.cod_peso= peso.cod_pes 
				left join producto  on d_factura.cod_pro= producto.cod_pro 
				WHERE cod_mfac= $codigo";
					$db->query($sql);
					$estilo="formsleo";
					while($db->next_row()){ 
						//$db->fec_ent;
						if($estilo=="formsleo")
							$estilo="formsleo1";
						else
							$estilo="formsleo";
				?>
                <tr id="fila_0"  >
                  <td ><span class="Estilo3">
                  <? echo  substr($db->nom_pro,0,20)." - ".$db->nom_pes ; ?>
                  </span></td>
                  <td colspan="1" class="textotabla01" ><div align="right" class="Estilo3">
                      <div align="center">
                        <?=$db->cant_pro?>
                      </div>
                  </div></td>
                  <td width="17%" class="textotabla01"><div align="right" class="Estilo3"><span class="Estilo3">
                    <?=number_format($db->total_pro,0,".",".")?>
                  </span></div></td>
                </tr>
                <?
				$total++;
				  } 
				  
				  $sql = "SELECT SUM(total_pro) as total FROM d_factura WHERE cod_mfac= $codigo";
			$db->query($sql);
			if($db->next_row()){ 
				$total = $db->total;
			}	
				$base = $total / 1.16;
				$iva_reg=$total-  $base ; 
				$descuento = $total - $tot_fac;
				?>
                <tr id="fila_0"  >
                  <td colspan="1" class="textotabla01 Estilo3" >&nbsp;</td>
                  <td colspan="2" class="textotabla01"><div align="right" class="Estilo37"><span class="Estilo3"><span class="Estilo3"><span class="Estilo3"><span class="Estilo3"><span class="Estilo3"></span></span></span></span></span></div></td>
                </tr>
				
                <tr >
                  <td class="<?=$estilo?> Estilo3 Estilo4" ><span class="Estilo3">TOTAL </span></td>
                  <td class="Estilo3 <?=$estilo?> Estilo3" >&nbsp;</td>
                  <td colspan="2" class="<?=$estilo?> Estilo3 Estilo4" ><div align="right" class="Estilo3" ><?=number_format($tot_fac,0,".",".")?></div></td>
				</tr>  
</TABLE>
</body>