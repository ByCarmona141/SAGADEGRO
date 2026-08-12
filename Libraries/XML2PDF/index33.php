<!DOCTYPE html>
<html lang="es">
<head>
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<style>
.user-row {
    margin-bottom: 14px;
}

.user-row:last-child {
    margin-bottom: 0;
}

.dropdown-user {
    margin: 13px 0;
    padding: 5px;
    height: 100%;
}

.dropdown-user:hover {
    cursor: pointer;
}

.table-user-information > tbody > tr {
    border-top: 1px solid rgb(221, 221, 221);
}

.table-user-information > tbody > tr:first-child {
    border-top: 0;
}


.table-user-information > tbody > tr > td {
    border-top: 0;
}
.toppad
{margin-top:20px;
}
</style>
	
</head>

<body>
<?php $meses=array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");?>
<div class="container">
      <div class="row">
      <div class="col-md-5  toppad  pull-right col-md-offset-3 ">

<p class=" text-info"><?php echo $dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;?> </p>
      </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-3 toppad" >
   
   
          <div class="panel panel-info">
            <div class="panel-heading">
              <h3 class="panel-title">CONVIERTE TU XML en PDF</h3>
            </div>
            <div class="panel-body">
                
                <div class=" col-md-12 col-lg-12"> 
                
   		<?php if(!$hay_resultado){ ?>

                	<form class="form-horizontal" role="form" method="post" name="buscar" id="buscar" enctype="multipart/form-data" action="verpdf33.php" >
        <fieldset>
          <!-- Form Name -->
          <legend>Subir XML</legend>
			
          <div class="form-group">
                      <div class="col-sm-12">
              <input type="file" id="mixml" name="mixml"  class="form-control input-lg" placeholder="mi archivo xml">
            </div>
          </div>

          



          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <div class="pull-right">
              	<input type="hidden" name="MM_insert" id="MM_insert" value="form1" >
                <button type="submit" class="btn btn-primary">Subir</button>
              </div>
            </div>
          </div>

        </fieldset>
      </form>
      <?php } ?>
      
      	<br />
      	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- efectosfiscales -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-5795492094067118"
     data-ad-slot="5257335087"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
                </div>
            </div>
                 <div class="panel-footer">
                        <a href="mailto:contacto@efectosfiscales.mx"  data-original-title="Broadcast Message" data-toggle="tooltip" type="button" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-envelope"></i></a>
                        <span class="pull-right">
                           <a href="http://efectosfiscales.mx"> <img src="https://s3.amazonaws.com/efectosfiscales/images/logo_site.png" height="35px;" /> </a>
                        </span>
                    </div>
            
          </div>
        </div>
      </div>
    </div>
    
</body>
</html>