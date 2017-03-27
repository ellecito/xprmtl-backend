<?php

class Email extends CI_Model
{

    function __construct(){
		$this->load->model("datos_empresa/modelo_empresa", 'objContacto');
        parent::__construct();
    }

	private function getEmail(){
        if($contacto = $this->objContacto->obtener(array("de_codigo" => 1))){
			return $contacto;
		}else{
			return false;
		}
	}
	
	public function tracking($pedido){
		$contacto = $this->getEmail();
        $asunto = "Pedido N°" . $pedido->codigo . " despachado";
		$cuerpo = "Se ha despachazo exitosamente su pedido.<br /><br />
        Datos de pedido: <br /><br />";
		$cuerpo.= "<b>Tracking</b>: " . $this->input->post("tracking") ."<br/>";
		$cuerpo.= $pedido->detalle;
		$cuerpo.= "<br /><br />";
		$cuerpo.= "Para cualquier consulta, escribanos a " . $contacto->email . " o llamenos a nuestro telefono " . $contacto->telefono . ". Muchas gracias por comprar en XPRMTL";
		
        $this->email->from("no-reply@xprmtl.cl","XPRMTL");
        $this->email->to($contacto->email, $pedido->usuario->email);
        $this->email->cc($contacto->email_cc);
        $this->email->bcc($contacto->email_bcc);
        $this->email->subject($asunto." [".date('d/m/y')." ".date('H:i:s')."]");
        $this->email->message($cuerpo);
        $this->email->send();
    }

}
