<?php
class Modelo_pagina extends CI_Model {
	private $idioma_codigo;
	
	function __construct(){
		$idioma_codigo = 1;
        $this->idioma_codigo = $idioma_codigo;
		
		parent::__construct();
	}
    
	#obtener página
    public function get($id) {
		$where = (is_numeric($id)) ? "p.id_pagina = $id" : "pi.pagina_url like '".$id."'";
		
		$sql = $this->db->select('*')
				->from("np_pagina as p")
				->join("np_pagina_idioma as pi","p.id_pagina=pi.pagina_id")
				->where("pagina_estado = 1 and pi.pagina_idioma = '$this->idioma_codigo'")
				->where($where)
				->get();
				
        $resultado = $sql->row();

        if($resultado) {
			$obj = new stdClass();
            $obj->codigo = $resultado->id_pagina;
            $obj->padre = (!empty($resultado->pagina_padre)) ? $this->get($resultado->pagina_padre) : false;
            $obj->titulo = $resultado->pagina_titulo;
            $obj->url = $resultado->pagina_url;
            $obj->texto = $resultado->pagina_descripcion;
			
			return $obj;
        } else {
            return false;
        }
    }
    
    #LISTAR PAGINAS HIJAS
    public function listar($id) {
		$sql= $this->db->select('*')
					->from("np_pagina as p")
                    ->join("np_pagina_idioma as pi","p.id_pagina = pi.pagina_id")
                    ->where("p.pagina_estado = 1 and p.pagina_padre = ".$id." and pi.pagina_idioma = '$this->idioma_codigo'")
					->order_by("pagina_titulo asc")
					->get();
        
		$resultado = $sql->result();
		
		$listado = array();
        
		foreach($resultado as $aux) {
            $obj = new stdClass();
            $obj->codigo = $aux->pagina_id;
            $obj->padre = $aux->pagina_padre;
            $obj->fecha = $aux->pagina_fecha;
            $obj->titulo = $aux->pagina_titulo;
            $obj->url = $aux->pagina_url;
            $obj->texto = $aux->pagina_descripcion;

            $listado[] = $obj;
		}
        return $listado;
    }
}