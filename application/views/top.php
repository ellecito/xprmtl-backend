<?php if(isset($home_indicador)){ ?>
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="navbar-header"> 
	  <span class="navbar-brand">
		<img src="/imagenes/template/logo.png" width="101" height="40" alt="XPRMTL" />
	  </span> 
  </div>
</nav>
<?php }else{ ?>
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        <span class="navbar-brand"><img src="/imagenes/template/logo.png" width="101" height="40" alt="Enlace Solar" /></span>
    </div>
    
    <div class="collapse navbar-collapse navbar-ex1-collapse" id="menu">
        <ul class="nav navbar-nav navbar-right navbar-user">
            <li class="dropdown user-dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user"></i> <?php echo $this->session->userdata("usuario")->email; ?> <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="/perfil/"><i class="icon-user"></i> Perfil</a></li>
                    <li class="divider"></li>
                    <li><a href="/logout/"><i class="icon-power-off"></i> Cerrar sesión</a></li>
                </ul>
            </li>
        </ul>
    
        <ul class="nav navbar-nav side-nav">
			<li><a href="/productos/">Productos</a></li>
			<li><a href="/categorias/">Categorías</a></li>
			<li><a href="/noticias/">Noticias</a></li>
			<li><a href="/sliders/">Sliders</a></li>
            <li><a href="/contacto/">Respaldo Contactos</a></li>
            <li><a href="/pedidos/">Pedidos</a></li>
            <li><a href="/datos-empresa/">Datos XPRMTL</a></li>
            <li><a href="/paginas-editables/">Páginas Editables</a></li>
            <li><a href="/usuarios/">Usuarios</a></li>
        </ul>
    </div>
</nav>
<?php } ?>
