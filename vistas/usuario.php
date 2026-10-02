<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"]))
{
  header("Location: login.html");
}
else
{
require 'header.php';
if ($_SESSION['acceso']==1)
{
?>
<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper" role="main" aria-labelledby="tituloUsuario">        
        <!-- Main content -->
        <section class="content" aria-label="Gestión de usuarios">
            <div class="row">
              <div class="col-md-12">
                  <div class="box">
                    <div class="box-header with-border">
                          <h1 class="box-title" id="tituloUsuario">Usuarios <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)" aria-controls="listadoregistros formularioregistros" aria-expanded="false"><i class="fa fa-plus-circle"></i> Agregar</button></h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros" role="region" aria-live="polite" aria-label="Listado de usuarios">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover" aria-label="Listado de usuarios">
                          <thead>
                            <tr>
                              <th scope="col">Opciones</th>
                              <th scope="col">Nombre</th>
                              <th scope="col">Documento</th>
                              <th scope="col">Número</th>
                              <th scope="col">Teléfono</th>
                              <th scope="col">Email</th>
                              <th scope="col">Login</th>
                              <th scope="col">Foto</th>
                              <th scope="col">Estado</th>
                            </tr>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <tr>
                              <th scope="col">Opciones</th>
                              <th scope="col">Nombre</th>
                              <th scope="col">Documento</th>
                              <th scope="col">Número</th>
                              <th scope="col">Teléfono</th>
                              <th scope="col">Email</th>
                              <th scope="col">Login</th>
                              <th scope="col">Foto</th>
                              <th scope="col">Estado</th>
                            </tr>
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" id="formularioregistros" role="region" aria-label="Formulario de registro de usuario">
                        <form name="formulario" id="formulario" method="POST" aria-labelledby="tituloFormulario">
                          <h2 id="tituloFormulario" class="sr-only">Formulario de usuario</h2>
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="nombre">Nombre(*):</label>
                            <input type="hidden" name="idusuario" id="idusuario">
                            <input type="text" class="form-control" name="nombre" id="nombre" maxlength="100" placeholder="Nombre" required aria-required="true">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="tipo_documento">Tipo Documento(*):</label>
                            <select class="form-control select-picker" name="tipo_documento" id="tipo_documento" required aria-required="true">
                              <option value="DNI">DNI</option>
                              <option value="RUC">RUC</option>
                              <option value="CEDULA">CEDULA</option>
                            </select>
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="num_documento">Número(*):</label>
                            <input type="text" class="form-control" name="num_documento" id="num_documento" maxlength="20" placeholder="Documento" required aria-required="true">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="direccion">Dirección:</label>
                            <input type="text" class="form-control" name="direccion" id="direccion" placeholder="Dirección" maxlength="70">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="telefono">Teléfono:</label>
                            <input type="text" class="form-control" name="telefono" id="telefono" maxlength="20" placeholder="Teléfono">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" name="email" id="email" maxlength="50" placeholder="Email">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="cargo">Cargo:</label>
                            <input type="text" class="form-control" name="cargo" id="cargo" maxlength="20" placeholder="Cargo">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                              <label for="login">Login (*):</label>
                              <input type="text"
                                    class="form-control"
                                    name="login"
                                    id="login"
                                    maxlength="20"
                                    placeholder="Login"
                                    required
                                    aria-required="true">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                              <label for="clave">Clave (*):</label>
                              <input type="password"
                                    class="form-control"
                                    name="clave"
                                    id="clave"
                                    maxlength="64"
                                    minlength="10"
                                    placeholder="Clave"
                                    title="La clave debe tener al menos 10 caracteres."
                                    required
                                    aria-required="true">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="permisos">Permisos:</label>
                            <ul style="list-style: none;" id="permisos" role="list" aria-label="Permisos disponibles">
                              
                            </ul>
                          </div>

                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="imagen">Imagen:</label>
                            <input type="file" class="form-control" name="imagen" id="imagen" aria-describedby="imagenAyuda">
                            <input type="hidden" name="imagenactual" id="imagenactual">
                            <img src="" width="150px" height="120px" id="imagenmuestra" alt="Previsualización de la imagen del usuario">
                            <p id="imagenAyuda" class="help-block">Seleccione una imagen opcional para el usuario.</p>
                          </div>
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary" type="submit" id="btnGuardar" aria-label="Guardar usuario"><i class="fa fa-save"></i> Guardar</button>

                            <button class="btn btn-danger" onclick="cancelarform()" type="button" aria-label="Cancelar registro"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                          </div>
                        </form>
                    </div>
                    <!--Fin centro -->
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->
<?php
}
else
{
  require 'noacceso.php';
}
require 'footer.php';
?>

<script type="text/javascript" src="scripts/usuario.js"></script>
<?php 
}
ob_end_flush();
?>