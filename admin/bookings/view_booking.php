<?php 
require_once('../../config.php');
?>
<?php 
if(!isset($_GET['id'])){
    $_settings->set_flashdata('error','Sin ID de reserva proporcionado');
    redirect('admin/?page=bookings');
}
$booking = $conn->query("SELECT r.*,concat(c.firstname,' ',c.lastname) as client,c.address,c.email,c.contact from `rent_list` r inner join clients c on c.id = r.client_id where r.id = '{$_GET['id']}' ");
if($booking->num_rows > 0){
    foreach($booking->fetch_assoc() as $k => $v){
        $$k = $v;
    }
}else{
    $_settings->set_flashdata('error','ID de reserva proporcionado es desconocido');
    redirect('admin/?page=bookings');
}
if(isset($bike_id)){
    $bike = $conn->query("SELECT b.*,c.category, bb.name as brand from `bike_list` b inner join categories c on b.category_id = c.id inner join brand_list bb on b.brand_id = bb.id where b.id = '{$bike_id}' ");
    if($bike->num_rows > 0){
        foreach($bike->fetch_assoc() as $k => $v){
            $bike_meta[$k]=stripslashes($v);
        }
    }
}
?>
<div class="conitaner-fluid px-3 py-2">
    <div class="row">
        <div class="col-md-6">
            <p><b>Nombre de Cliente:</b> <?php echo $client ?></p>
            <p><b>Correo de Cliente:</b> <?php echo $email ?></p>
            <p><b>Contacto de Cliente:</b> <?php echo $contact ?></p>
            <p><b>Dirección de Cliente:</b> <?php echo $address ?></p>
            <p><b>Fecha de recogida del alquiler:</b> <?php echo date("M d,Y" ,strtotime($date_start)) ?></p>
            <p><b>Fecha de devolución del alquiler:</b> <?php echo date("M d,Y" ,strtotime($date_end)) ?></p>
        </div>
        <div class="col-md-6">
            <p><b>Categoría Vehículo:</b> <?php echo $bike_meta['category'] ?></p>
            <p><b>Marca Vehículo:</b> <?php echo $bike_meta['brand'] ?></p>
            <p><b>Modelo Vehículo:</b> <?php echo $bike_meta['bike_model'] ?></p>            
        </div>
    </div>
    <div class="row">
        <div class="col-3">Estado Reserva:</div>
        <div class="col-auto">
        <?php 
            switch($status){
                case '0':
                    echo '<span class="badge badge-light text-dark">Pendiente</span>';
                break;
                case '1':
                    echo '<span class="badge badge-primary">Confirmado</span>';
                break;
                case '2':
                    echo '<span class="badge badge-danger">Cancelado</span>';
                break;
                case '3':
                    echo '<span class="badge badge-warning">Recogida</span>';
                break;
                case '4':
                    echo '<span class="badge badge-success">Devolución</span>';
                break;
                default:
                    echo '<span class="badge badge-danger">Cancelado</span>';
                break;
            }
        ?>
        </div>
            
    </div>
</div>
<div class="modal-footer">
    <?php if(!isset($_GET['view'])): ?>
    <button type="button" id="update" class="btn btn-sm btn-flat btn-primary">Editar</button>
    <?php endif; ?>
    <button type="button" class="btn btn-secondary btn-sm btn-flat" data-dismiss="modal">Cerrar</button>
</div>
<style>
    #uni_modal>.modal-dialog>.modal-content>.modal-footer{
        display:none;
    }
    #uni_modal .modal-body{
        padding:0;
    }
</style>
<script>
    $(function(){
        $('#update').click(function(){
            uni_modal("Editar Detalles de Reserva", "./bookings/manage_booking.php?id=<?php echo $id ?>")
        })
    })
</script>