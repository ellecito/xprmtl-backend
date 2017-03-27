<?php if ( ! defined('BASEPATH')) exit('No puede acceder a este archivo');

class Pedidos extends CI_Controller {
	
	function __construct(){
	
		parent::__construct();
		if(!$this->session->userdata("usuario")) redirect("/");
		$this->load->model("modelo_pedidos", "objPedidos");
		$this->load->model("email", "objEmail");
		#current
		$this->layout->current = 5;
	}
	
	public function index(){
		
		#Title
		$this->layout->title('Pedidos');
		
		#Metas
		$this->layout->setMeta('title','Pedidos');
		$this->layout->setMeta('description','Pedidos');
		
		$this->layout->js("/js/sistema/pedidos/index.js");
		
		$where = $and = $contenido['q_f'] = '';
		if($this->input->get('q')){
			$contenido['q_f'] = $q = $this->input->get('q');
			$where .= "CONCAT(cn_nombres, ' ', cn_apellidos) like '%$q%' or cn_email like '%$q%'  ";
		}

		#url
		$url = explode('?',$_SERVER['REQUEST_URI']);
		if(isset($url[1]))
		$contenido['url'] = $url = '/?'.$url[1];
		else
		$contenido['url'] = $url = '/';

		#paginacion
		$config['base_url'] = '/pedidos/';
		$config['total_rows'] = $this->objPedidos->total($where);
		$config['per_page'] = 15;
		$config['uri_segment'] = $segment = 2;
		$config['suffix'] = $url;
		$config['first_url'] = '/pedidos'.$url;

		$this->pagination->initialize($config);
		$page = ($this->uri->segment($segment))?$this->uri->segment($segment)-1:0;

		#contenido
		$contenido['pedidos'] = $this->objPedidos->listar($config['per_page'], $page, $where);
		$contenido['pagination'] = $this->pagination->create_links();
		
		#La vista siempre,  debe ir cargada al final de la función
		$this->layout->view('index', $contenido);
	}

	public function ver($codigo){

		$pedido = $this->objPedidos->obtener(array("pe_codigo" => $codigo));
		if(!$pedido) redirect('/pedidos/');

		#title
		$this->layout->title('Detalle Pedido');

		#metas
		$this->layout->setMeta('title','Detalle Pedido');
		$this->layout->setMeta('description','Detalle Pedido');
		$this->layout->setMeta('keywords','Detalle Pedido');

		#JS - Datepicker
		$this->layout->css('/js/jquery/ui/1.10.4/ui-lightness/jquery-ui-1.10.4.custom.min.css');
		$this->layout->js('/js/jquery/ui/1.10.4/jquery-ui-1.10.4.custom.min.js');
		$this->layout->js('/js/jquery/ui/1.10.4/jquery.ui.datepicker-es.js');

		#JS - Multiple select boxes
		$this->layout->css('/js/jquery/bootstrap-multi-select/dist/css/bootstrap-select.css');
		$this->layout->js('/js/jquery/bootstrap-multi-select/js/bootstrap-select.js');

		#JS - Editor
		$this->layout->js('/js/jquery/ckeditor-standard/ckeditor.js');

		#JS - Formulario
		$this->layout->js('/js/jquery/file-input/jquery.nicefileinput.min.js');
		$this->layout->js('/js/jquery/file-input/nicefileinput-init.js');

		#nav
		$this->layout->nav(array("Pedidos"=>"pedidos", "Detalle Pedido" =>"/"));

		$contenido['pedido'] = $pedido;

		$this->layout->view('ver', $contenido);

	}
	
	public function exportar(){
		

		#libreria PHPExcel en libraries
		require APPPATH."libraries/PHPExcel/PHPExcel.php";
		
		$and = $where = '';
		if($this->input->get('q')){
			$q = $this->input->get('q');
			$where .= "CONCAT(cn_nombres, ' ', cn_apellidos) like '%$q%' or cn_email = '$q'";
			$and = ' and ';
		}
		
		$pedidos = $this->objPedidos->listar();
		
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->
			getProperties()
				->setCreator("XRPMTL.cl")
				->setLastModifiedBy("XRPMTL.cl")
				->setTitle("Excel Pedidos")
				->setSubject("Excel Pedidos")
				->setDescription("Excel Pedidos")
				->setKeywords("Excel Pedidos")
				->setCategory("reportes");


		$styleArray = array(
			   'borders' => array(
					 'outline' => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN,
							'color' => array('argb' => '000000'),
					 ),
			   ),
			    'font'    => array(
				 'bold'      => true,
				 'italic'    => false,
				 'strike'    => false,
			 ),
			'alignment' => array(
					'wrap'       => true,
			  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'BABDBB')
			),
		);
		
		$styleArraInfo = array(
				'font'    => array(
				 'bold'      => false,
				 'italic'    => false,
				 'strike'    => false,
				 'size' => 10
				 ),
				 'borders' => array(
					 'outline' => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN,
							'color' => array('argb' => '000000'),
					 ),
			   ),
			   'alignment' => array(
					'wrap'       => true,
			  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			  )
		);
		
		
		$styleFont = array(
			 'font'    => array(
				 'bold'      => true,
				 'italic'    => false,
				 'strike'    => false,
			 ),
			'alignment' => array(
					'wrap'       => true,
			  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			),
		);

		$objPHPExcel->getActiveSheet()->getStyle('1:3')->applyFromArray($styleFont);
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Pedidos');
		
		$i=2;
		$letra = 'A';
		$objPHPExcel->getActiveSheet()->getColumnDimension($letra)->setWidth(35);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra.$i, 'Código');
		$objPHPExcel->getActiveSheet()->getStyle($letra++.$i)->applyFromArray($styleArray);
		
		$objPHPExcel->getActiveSheet()->getColumnDimension($letra)->setWidth(35);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra.$i, 'Fecha/Hora');
		$objPHPExcel->getActiveSheet()->getStyle($letra++.$i)->applyFromArray($styleArray);
		
		$objPHPExcel->getActiveSheet()->getColumnDimension($letra)->setWidth(35);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra.$i, 'Monto');
		$objPHPExcel->getActiveSheet()->getStyle($letra++.$i)->applyFromArray($styleArray);
		
		$objPHPExcel->getActiveSheet()->getColumnDimension($letra)->setWidth(40);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra.$i, 'Usuario');
		$objPHPExcel->getActiveSheet()->getStyle($letra++.$i)->applyFromArray($styleArray);
		
		$objPHPExcel->getActiveSheet()->getColumnDimension($letra)->setWidth(20);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra.$i, 'Estado');
		$objPHPExcel->getActiveSheet()->getStyle($letra++.$i)->applyFromArray($styleArray);
		
		$i++;
		foreach($pedidos as $pedido){

			$letra = "A";
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra.$i, $pedido->codigo);
			$objPHPExcel->getActiveSheet()->getStyle($letra++.$i)->applyFromArray($styleArraInfo);
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra.$i, formatearFecha(substr($pedido->fecha,0,10)) . " - " . substr($pedido->hora,0,5));
			$objPHPExcel->getActiveSheet()->getStyle($letra++.$i)->applyFromArray($styleArraInfo);
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra.$i, "$" . number_format($pedido->monto,0,",","."));
			$objPHPExcel->getActiveSheet()->getStyle($letra++.$i)->applyFromArray($styleArraInfo);
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra.$i, $pedido->usuario->nombres . " " . $pedido->usuario->apellidos);
			$objPHPExcel->getActiveSheet()->getStyle($letra++.$i)->applyFromArray($styleArraInfo);
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra.$i, $pedido->estado->nombre);
			$objPHPExcel->getActiveSheet()->getStyle($letra++.$i)->applyFromArray($styleArraInfo);
			
			$i++;

		}

		$objPHPExcel->getActiveSheet()->setTitle("Pedidos ".date("d-m-Y"));

		$objPHPExcel->setActiveSheetIndex(0);

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Pedidos - '.date('d/m/Y').'.xls"');
		header('Cache-Control: max-age=0');
		 
		$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
		$objWriter->save('php://output');
		exit;
		
	}
	
	public function pdf($pedido = false){
		if(!$pedido) redirect("/pedidos/");
		$pedido = $this->objPedidos->obtener(array("pe_codigo" => $pedido));
		if(!$pedido) redirect("/pedidos/");
		$html = '<div class="page-header">
				<h1>Pedido N° ' . $pedido->codigo . '</h1>
				</div>
				<form class="form-horizontal">
				  <div class="form-group">
					<label for="monto" class="col-sm-2 control-label">Monto</label>
					<div class="col-sm-10">
					  <input type="text" id="monto" readonly class="form-control" value="$' . number_format($pedido->monto,0,",",".") . '"/>
					</div>
				  </div>
			  <div class="form-group">
				<label for="estado" class="col-sm-2 control-label">Estado</label>
				<div class="col-sm-4">
				  <input type="text" id="estado" readonly class="form-control" value="' . $pedido->estado->nombre . '"  />
				</div>
				<label for="fecha" class="col-sm-2 control-label">Fecha</label>
				<div class="col-sm-4">
				  <div class="input-group date">
					<input id="datepicker" type="text" readonly class="form-control" value="' . formatearFecha(substr($pedido->fecha,0,10)) . ' - ' . substr($pedido->hora,0,5) . '"  />
					<span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
				</div>
			  </div>';
			  if($pedido->estado->codigo == 4){
			  $html.= '<div class="form-group">
				<label for="tracking" class="col-sm-2 control-label">Tracking</label>
				<div class="col-sm-4">
				  <input type="text" id="tracking" readonly class="form-control" value="' . $pedido->tracking . '"  />
				</div>
			  </div>';
			  }
			  $html.= '<div class="form-group">
				<label for="detalle" class="col-sm-2 control-label">Detalle</label>
				<div class="col-sm-10">
				  ' . $pedido->detalle . '
				</div>
			  </div>
			</form>
			<div class="page-header">
			  <h1>Usuario</h1>
			</div>
			<form class="form-horizontal">
			  <div class="form-group">
				<label for="nombre" class="col-sm-2 control-label">Nombre Completo</label>
				<div class="col-sm-10">
				  <input type="text" id="nombre" readonly class="form-control" value="' . $pedido->usuario->nombres . " " . $pedido->usuario->apellidos . '"/>
				</div>
			  </div>
			  <div class="form-group">
				<label for="telefono" class="col-sm-2 control-label">Telefono</label>
				<div class="col-sm-4">
				  <input type="text" id="telefono" readonly class="form-control" value="' . $pedido->usuario->telefono . '"  />
				</div>
				<label for="email" class="col-sm-2 control-label">Email</label>
				<div class="col-sm-4">
				  <input type="text" id="email" readonly class="form-control" value="' . $pedido->usuario->email . '"  />
				</div>
			  </div>
			  <div class="form-group">
				<label for="direccion" class="col-sm-2 control-label">Dirección</label>
				<div class="col-sm-10">
				  <input type="text" id="direccion" readonly class="form-control" value="' . $pedido->usuario->direccion . ", " . $pedido->usuario->comuna->nombre . ", " . $pedido->usuario->comuna->region->nombre . ", " . $pedido->usuario->comuna->region->pais->nombre . '"/>
				</div>
			  </div>
			</form>
			<div class="page-header">
			  <h1>Bitacora</h1>
			</div>
			<form class="form-horizontal">
			  <div class="form-group">
				<label for="respuesta" class="col-sm-2 control-label">Respuesta</label>
				<div class="col-sm-4">
				  <input type="text" id="respuesta" readonly class="form-control" value="';
				 if($pedido->bitacora->respuesta == 0) $html.= 'Aprobada'; 
				 else $html.= 'Rechazada';
				$html.= '"  />';
				$html.= '</div>
				<label for="final_numero_tarjeta" class="col-sm-2 control-label">Numero Tarjeta</label>
				<div class="col-sm-4">
				  <input type="text" id="final_numero_tarjeta" readonly class="form-control" value="************' . $pedido->bitacora->final_numero_tarjeta . '"  />
				</div>
			  </div>
			  <div class="form-group">
				<label for="tipo_pago" class="col-sm-2 control-label">Tipo Pago</label>
				<div class="col-sm-4">
				  <input type="text" id="tipo_pago" readonly class="form-control" value="';
					if($pedido->bitacora->tipo_pago == 'VD') $html.= 'Red Compra';
					else $html.= 'Crédito';
					$html.= ' - ';
					switch($pedido->bitacora->tipo_pago){
						case 'VN':
							$html.= 'Sin cuotas';
							break;
						case 'VC':
							$html.= 'Cuotas normales';
							break;
						case 'SI':
							$html.= 'Sin interés';
							break;
						case 'CI':
							$html.= 'Cuotas Comercio';
							break;
						case 'VD':
							$html.= 'Débito';
							break;
					}
				$html.= '"  />';
				$html.= '</div>
				<label for="numero_cuotas" class="col-sm-2 control-label">Numero Cuotas</label>
				<div class="col-sm-4">
				  <input type="text" id="numero_cuotas" readonly class="form-control" value="' . $pedido->bitacora->numero_cuotas . '"  />
				</div>
			  </div>
			  <div class="form-group">
				<label for="ip" class="col-sm-2 control-label">IP</label>
				<div class="col-sm-4">
				  <input type="text" id="ip" readonly class="form-control" value="' . $pedido->bitacora->ip . '"  />
				</div>
				<label for="fecha_transaccion" class="col-sm-2 control-label">Fecha Transaccion</label>
				<div class="col-sm-4">
				  <input type="text" id="fecha_transaccion" readonly class="form-control" value="' . $pedido->bitacora->fecha_transaccion . " " . $pedido->bitacora->hora_transaccion . '"  />
				</div>
			  </div>
			</form>';
		$rutaPdf = "/archivos/";
		if(!file_exists($_SERVER['DOCUMENT_ROOT'].$rutaPdf))
			mkdir($_SERVER['DOCUMENT_ROOT'].$rutaPdf, 0777);
		$rutaPdf .= "pdf/";
		if(!file_exists($_SERVER['DOCUMENT_ROOT'].$rutaPdf))
			mkdir($_SERVER['DOCUMENT_ROOT'].$rutaPdf, 0777);
		
		$nombrePdf = "pdf".time().'.pdf';	 	 
		require APPPATH."/libraries/mpdf/mpdf.php";
		
		$mpdf->use_embeddedfonts_1252 = true; // false is default
		
		ob_start();
		$mpdf=new mPDF('utf-8','A4','','',0,0,0,0,6,3); 
		$mpdf->SetDisplayMode('fullpage');
		$mpdf->SetTitle('Pedido ' . $pedido->codigo);
		$mpdf->SetAuthor('XPRMTL');
		$mpdf->WriteHTML(file_get_contents(APPPATH . "/css/bootstrap.css"), 1);
		$mpdf->WriteHTML($html, 2);
		$mpdf->Output($_SERVER['DOCUMENT_ROOT'].$rutaPdf.$nombrePdf,'F');
		redirect($rutaPdf.$nombrePdf);
	}
	
	public function tracking(){
		if($this->input->post()){
			$actualizar = array(
				"pe_tracking" => $this->input->post("tracking"),
				"ps_codigo" => 4
			);
			$where = array(
				"pe_codigo" => $this->input->post("codigo")
			);
			if($this->objPedidos->actualizar($actualizar, $where)){
				$this->objEmail->tracking();
				echo json_encode(array("result" => true));
				exit;
			}else{
				echo json_encode(array("result" => false, "msg" => "<div>Error al actualizar.</div>"));
				exit;
			}
		}else{
			redirect("/pedidos/");
		}
	}
	
}