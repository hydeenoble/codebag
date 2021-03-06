<?php
/**
 * Created by PhpStorm.
 * User: hydee
 * Date: 3/8/17
 * Time: 10:27 AM
 */
require_once '../core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';
//get brands from DB
$sql = "SELECT * FROM brand ORDER BY brand";
$results = $db->query($sql);
$errors = array();
//EDIT BRAND
if (isset($_GET['edit']) && !empty($_GET['edit'])){
    $edit_id = sanitize((int)$_GET['edit']);
    $sql3 = "SELECT * FROM brand WHERE id = '$edit_id'";
    $edit_result = $db->query($sql3);
    $eBrand = mysqli_fetch_assoc($edit_result);
}
//delete brand
if(isset($_GET['delete']) && !empty($_GET['delete'])){
    $delete_id = sanitize((int)$_GET['delete']);
    echo $delete_id;
    $sql = "DELETE FROM brand WHERE id = '$delete_id'";
    $db->query($sql);
    header('Location: brands.php');
}
//if add form is submitted
if(isset($_POST['add_submit'])){
    $new_brand = sanitize($_POST['brand']);
//    check if brand id blank
    if ($new_brand == ''){
        $errors[] .= "You must enter a brand!";
    }
//    check if brand exit in DB
    $sql2 = "SELECT * FROM brand WHERE brand = '$new_brand'";
    if (isset($_GET['edit'])){
        $sql2 = "SELECT * FROM brand WHERE brand = '$new_brand' AND id != '$edit_id'";
    }
    $result = $db->query($sql2);
    $count = mysqli_num_rows($result);

    if ($count > 0){
        $errors[] .= $new_brand. " already exist. Please Try to add another brand name.";
    }
//    display errors
    if(!empty($errors)){
        echo display_errors($errors);
    }else{
//        Add brand to DB
        $sql = "INSERT INTO brand (brand) VALUE ('$new_brand')";
        if (isset($_GET['edit'])){
            $sql = "UPDATE brand SET brand = '$new_brand' WHERE id = '$edit_id'";
        }
        $db->query($sql);
        header('Location: brands.php');
    }
}
?>

<h2 class="text-center">Brands</h2>
<hr>
<!--Brand from-->
<div class="text-center">
    <form action="brands.php<?=((isset($_GET['edit'])? '?edit='.$edit_id : '')); ?>" class="form-inline" method="post">
        <div class="form-group">
            <label for="brand"><?=((isset($_GET['edit'])? 'Edit' : 'Add A')); ?> Brand: </label>
            <?php
            if (isset($_GET['edit'])){
                $brand_value = sanitize($eBrand['brand']);
            }else{
                if (isset($_POST['brand'])){
                    $brand_value = sanitize($_POST['brand']);
                }else{
                    $brand_value = '';
                }
            }
            ?>
            <input type="text" name="brand" id="brand" class="form-control" value="<?=$brand_value?>">
            <?php if (isset($_GET['edit'])):?>
                <a href="brands.php" class="btn btn-default">Cancel</a>
            <?php endif; ?>
            <input type="submit" name="add_submit" value="<?=((isset($_GET['edit'])? 'Edit' : 'Add')); ?> Brand" class="btn btn-success">
        </div>
    </form>
</div>
<hr>


<table class="table table-bordered table-striped table-auto table-condensed">
    <thead>
    <th></th>
    <th>Brand</th>
    <th></th>
    </thead>
    <tbody>
    <?php while($brand = mysqli_fetch_assoc($results)) : ?>
        <tr>
            <td><a href="brands.php?edit=<?= $brand['id']?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a></td>
            <td><?= $brand['brand']?></td>
            <td><a href="brands.php?delete=<?= $brand['id']?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
<?php include 'includes/footer.php'?>
