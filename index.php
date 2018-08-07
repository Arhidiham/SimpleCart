<?php
include_once 'Cart.php';
// ######## please do not alter the following code ########
$products = array(
    array("name" => "Sledgehammer", "price" => 125.75),
    array("name" => "Axe", "price" => 190.50),
    array("name" => "Bandsaw", "price" => 562.13),
    array("name" => "Chisel", "price" => 12.9),
    array("name" => "Hacksaw", "price" => 18.45)
);
// ##################################################
// get an instance of our cart
$cart = Cart::getInstance();
$cartContents = array();
// handle the action
if (isset($_REQUEST['action'])) {
    $key = null; // make sure we at least have the variable set
    if (isset($_REQUEST['product'])) {
        $key = $_REQUEST['product'];
    }
    // check if the product requested is valid
    if (isset($products[$key])) {
        if ($_REQUEST['action'] == 'add') { // check if we need to add
            $cart->add($key, $products[$key]);
        } elseif ($_REQUEST['action'] == 'remove') { // check if we need to remove
            $cart->remove($key);
        }
    }
}

$cartContents = $cart->getContent();
$cartTotal = $cart->getTotal();
?>
<html>
	<body>
		<div>Products</div>
		<table style="text-align: left;">
			<thead>
				<tr>
					<th style="width: 150px;">Name</th>
					<th style="width: 60px;">Price</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($products as $key => $product) { ?>
				<tr>
					<td><?=$product['name'];?></td>
					<td><?=number_format($product['price'], 2, '.', '');?></td>
					<td><a href="?action=add&product=<?= $key; ?>" style="display: inline-block;">Add</a></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<div style="padding-top: 10px;">Cart</div>
		<table style="text-align: left;">
			<thead>
				<tr>
					<th style="width: 150px;">Name</th>
					<th style="width: 60px;">Price</th>
					<th style="width: 60px;">Qty</th>
					<th style="width: 60px;">Total</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($cartContents as $key => $item) { ?>
				<?php $total = number_format(($item['count'] * $item['price']), 2, '.', ''); ?>
				<tr>
					<td><?=$item['name'];?></td>
					<td><?=number_format($item['price'], 2, '.', '');?></td>
					<td><?=$item['count'];?></td>
					<td><?=$total?></td>
					<td><a href="?action=remove&product=<?= $key; ?>" style="display: inline-block;">Remove</a></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<div style="width: 100%; overflow: hidden; padding-top: 10px;">
			<div style="display: inline-block; margin-right: 2px;">Final Total =</div>
			<div style="display: inline-block;"><?=number_format($cartTotal, 2, '.', '');?></div>
		</div>
	</body>
</html>