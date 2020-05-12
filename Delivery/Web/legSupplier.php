<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'].'/Delivery/App/Controller.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Delivery/App/Domain/LegSup.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Delivery/App/Repository/MySQLLegSupRepository.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Delivery/App/Service/LegSupService.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/Delivery/App/Domain/Supplier.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Delivery/App/Repository/MySQLSupplierRepository.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Delivery/App/Service/SupplierService.php');

if (!isset($_SESSION['username']))
	{
		$controller->redirect('login');
	}
$repository = new MySQLLegSupRepository();
$service = new LegSupService($repository);
$repository1 = new MySQLSupplierRepository();
$serviceS = new SupplierService($repository1);
?>

<!DOCTYPE html>
<html>
<head>
	<title>Legal Suppliers</title>
	<link  rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"  integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</head> 
<body>
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<a class="navbar-brand" href="./">Поставки</a>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item active">
					<a class="nav-link" href="./contracts.php">Договоры</a>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Поставщики</a>
					<div class="dropdown-menu">
						<a class="dropdown-item" href="./suppliers.php">Список поставщиков</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="./legSupplier.php">Юр. лица</a>
						<a class="dropdown-item" href="./privSupplier.php">Физ. лица</a>
					</div>
				</li>
				<li class="nav-item active">
					<a class="nav-link" href="./product.php">Поставленные продукты</a>
				</li>
			</ul>
			<form class="form-inline my-2 my-lg-0" action="logout.php" method="post">
				<button  class="btn  btn-outline-primary  my-2  my-sm-0" type="submit">Выйти</button>
			</form>
		</div>
	</nav>
	<div class="modal" tabindex="-1" role="dialog" id="alertModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger" role="alert">
      		<p id="error"></p>
      	</div>        
      </div>
    </div>
  </div>
</div>
	<div class="row my-3 mx-1">
		<div class="col-4">
			<ul class="list-group">
				<li class="list-group-item active">Юридические лица</li>
				<?php  foreach ($service->getAllLegSup() as $supplier) { ?>
					<li class="list-group-item">
						<a href="legSupplier.php?details=<?= $supplier->getId() ?>">
							#<?= $supplier->getId() ?>, <?= $serviceS->getSupplierByID($supplier->getId())->getName()?>
						</a>
					</li>
					<?php } ?>
			</ul>
		</div>
		<div class="col-8">
<?php
if (isset($_GET['details']))
{
	try
	{
		$supplier = @$service->getLegSupByID($_GET['details']);
		$name= @$serviceS->getSupplierByID($_GET['details']);
?>
<form>
			<div class="form-group row">
				<label  for="supplierNumber"  class="col-sm-3  col-formlabel">ID поставщика</label>
				<div class="col-sm-9">
					<input  type="text"  readonly  class="form-control-plaintext" id="supplierNumber" value="<?= $supplier->getId() ?>">
				</div>
			</div>
			<div class="form-group row">
		<label for="supplierName" class="col-sm-3 col-form-label">Имя поставщика</label>
		<div class="col-sm-9">
			<input  type="text"  readonly  class="form-control-plaintext" id="supplierName" value="<?= htmlspecialchars($name->getName())?>">
		</div>
	</div>
	<div class="form-group row">
		<label  for="taxnumber"  class="col-sm-3  col-formlabel">ИНН</label>
		<div class="col-sm-9">
			<input  type="text"  readonly  class="form-control-plaintext" id="taxnumber" value="<?= htmlspecialchars($supplier->getTaxNum()) ?>">
		</div>
	</div>
	<div class="form-group row">
		<label  for="vatnumber"  class="col-sm-3  col-formlabel">ИПН ПДВ</label>
		<div class="col-sm-9">
			<input  type="text"  readonly  class="form-control-plaintext" id="vatnumber" value="<?= htmlspecialchars($supplier->getVatNum()) ?>">
		</div>
	</div>
	<div class="form-group row">
		<label  for="address"  class="col-sm-3  col-formlabel">Адрес</label>
		<div class="col-sm-9">
			<input  type="text"  readonly  class="form-control-plaintext" id="address" value="<?= htmlspecialchars($name->getAddress()) ?>">
		</div>
	</div>
</form> 
<a class="btn btn-warning <?php if(strcmp($_SESSION['username'],'supply_manager')==0) echo 'disabled' ?> " href="legSupplier.php?edit=<?= $supplier->getId() ?>"  role="button"  >Редактировать</a>
<a class="btn btn-danger <?php if(strcmp($_SESSION['username'],'supply_manager')==0) echo 'disabled' ?>" href="legSupplier.php?delete=<?= $supplier->getId() ?>" role="button" >Удалить</a>
<?php
}
catch (Exception $e)
{
?><div  class="alert  alert-danger"  role="alert"><?=  $e->getMessage() ?>
</div>
<?php
}
}
else
{
if (isset($_GET['new']))
 	{?>
 		<form action="legSupplier.php" method="post">
			<div class="form-group row">
		<label for="supplierName" class="col-sm-3 col-form-label">Имя поставщика</label>
		<div class="col-sm-9">
			<select class="form-control" id="supplierName" name="name" required>
				<?php foreach ($serviceS->getAllSuppliers() as $supplier) { ?>
					<option value="<?= $supplier->getId() ?>" ><?= $supplier->getName() ?></option>
					<?php } ?>
			</select>
		</div>
	</div>
	<div class="form-group row">
		<label  for="taxnumber"  class="col-sm-3  col-formlabel">ИНН</label>
		<div class="col-sm-9">
			<input  type="text" class="form-control" id="taxnumber" name="taxnumber" required >
		</div>
	</div>
	<div class="form-group row">
		<label  for="vatnumber"  class="col-sm-3  col-formlabel">ИПН ПДВ</label>
		<div class="col-sm-9">
			<input  type="text" class="form-control" id="vatnumber" name="vatnumber" required >
		</div>
	</div>
	<button type="submit" class="btn btn-primary" name="saveS">Сохранить</button>
	<a class="btn btn-secondary" href="legSupplier.php ?>" role="button" >Назад</a> 
</form> 

<?php 	} else{
	if(isset($_GET['edit']))
	{
		try
 		{
 			$supplier = @$service->getLegSupByID($_GET['edit']);
 			$name= @$serviceS->getSupplierByID($_GET['edit']);?>
 			<form action="legSupplier.php" method="post">
 					<div class="form-group row">
				<label  for="supplierNumber"  class="col-sm-3  col-formlabel">ID поставщика</label>
				<div class="col-sm-9">
					<input  type="text"  readonly  class="form-control-plaintext" id="supplierNumber" name="id" value="<?= $supplier->getId() ?>">
				</div>
			</div>
			<div class="form-group row">
		<label for="supplierName" class="col-sm-3 col-form-label">Имя поставщика</label>
		<div class="col-sm-9">
			<select class="form-control" id="supplierName" name="name" disabled>
				<?php foreach ($serviceS->getAllSuppliers() as $supplierS) { ?>
					<option value="<?= $supplierS->getId() ?>"<?php if($name->getId()==$supplierS->getId()) echo "selected"; ?> ><?= $supplierS->getName() ?></option>
					<?php } ?>
			</select>
		</div>
		</div>
	<div class="form-group row">
		<label  for="taxnumber"  class="col-sm-3  col-form-label">ИНН</label>
		<div class="col-sm-9">
			<input  type="text" class="form-control" id="taxnumber" name="taxnumber" required value="<?= htmlspecialchars($supplier->getTaxNum())?>">
		</div>
	</div>
	<div class="form-group row">
		<label  for="vatnumber"  class="col-sm-3  col-formlabel">ИПН ПДВ</label>
		<div class="col-sm-9">
			<input  type="text" class="form-control" id="vatnumber" name="vatnumber" required value="<?= htmlspecialchars($supplier->getVatNum()) ?>">
		</div>
	</div>
	<div class="form-group row">
		<label  for="address"  class="col-sm-3  col-formlabel">Адрес</label>
		<div class="col-sm-9">
			<input  type="text" class="form-control-plaintext" readonly id="address" name="address" required value="<?= htmlspecialchars($name->getAddress()) ?>" >
		</div>
	</div>
	<button type="submit" class="btn btn-primary" name="updateS">Сохранить</button>
	<a class="btn btn-secondary" href="legSupplier.php?details=<?= $supplier->getId() ?>" role="button" >Назад</a> 
	</form>

 		<?php }
 		catch(Exception $e)
 		{?><div  class="alert  alert-danger"  role="alert"><?=  $e->getMessage() ?>
</div>
<?php
 		}
	}else{
?><a class="btn btn-success  <?php if(strcmp($_SESSION['username'],'supply_manager')==0) echo 'disabled' ?>" href="legSupplier.php?new" role="button">Новый поставщик</a><?php
 	}
 }
}
if (isset($_GET['delete']))
 {
 	try
 	{
 		$supplier = @$service->getLegSupByID($_GET['delete']);
 		$name= @$serviceS->getSupplierByID($_GET['delete']);?>
 		<div class="modal" tabindex="-1" role="dialog" id="deleteModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Внимание</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Вы действительно хотите удалить информацию о поставщике <?=$name->getName()?> как о юридическом лице?</p>
      </div>
      <div class="modal-footer">
      	<form action="legSupplier.php" method="post">
      		<input type="text" hidden name="number" value="<?=$supplier->getId()?>">
        <button type="submit" class="btn btn-primary" name="deleteS">Удалить</button>
    </form>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
	$('#deleteModal').modal('show');
</script>
<?php 	}
 	catch(Exception $e)
 	{
?><div  class="alert  alert-danger"  role="alert"><?=  $e->getMessage() ?>
</div>
<?php
}
}
if(isset($_POST['saveS']))
 {
 	try{
 		@$service->createLegSup($_POST['name'],$_POST['taxnumber'],$_POST['vatnumber']);
 		?>
 		<script type="text/javascript">
 			window.location.reload();
 			return 0;
 		</script>
 			<?php
 	}
 	catch(Exception $e)
 	{ 		
 		?>
 		
 		<script type="text/javascript">
 			$("#error").html("<?php echo $e->getMessage() ?>");
 				$('#alertModal').modal('show');
 		$('#alertModal').on('hidden.bs.modal', function (e) {
 			history.back();
})
</script>
<?php
 	} 	
 }
 if(isset($_POST['deleteS']))
 {
 	try{
 		@$service->deleteLegSup($_POST['number']);
 		?>
 		<script type="text/javascript">
 			window.location.reload();
 			return 0;
 		</script>
 			<?php
 	}
 	catch(Exception $e)
 	{ 		
 		?>
 		
 		<script type="text/javascript">
 			$("#error").html("<?php echo $e->getMessage() ?>");
 				$('#alertModal').modal('show');
 		$('#alertModal').on('hidden.bs.modal', function (e) {
 			history.back();
})
</script>
<?php
 	} 	
 }
 if(isset($_POST['updateS']))
 {
 	try{
 		@$service->updateLegSup($_POST['name'],$_POST['taxnumber'],$_POST['vatnumber'])
 		?>
 		<script type="text/javascript">
 			window.location.reload();
 			return 0;
 		</script>
 			<?php
 	}
 	catch(Exception $e)
 	{ 		
 		?>
 		
 		<script type="text/javascript">
 			$("#error").html("<?php echo $e->getMessage() ?>");
 				$('#alertModal').modal('show');
 		$('#alertModal').on('hidden.bs.modal', function (e) {
 			history.back();
})
</script>
<?php
 	} 	
 }
?>
</div>
</div>
</body>
</html>