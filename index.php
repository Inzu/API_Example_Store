<?php


include("config.php"); 


//INZU API CALL
$json = file_get_contents("$api_base/store/product?api_key={$api_key}&page=1&page_rows=16");
$inzu = json_decode($json); 


//A loop for each product

foreach ($inzu->data as $product) { 

$variations=NULL;
$price=NULL;

$title=$product->title;


//A second loop if the product has variations

if(!$product->item_code){
	
	foreach ($product->item as $variation) { 
		
	///Build variations drop down
	$variations.="<option value=\"{$variation->item_code},{$variation->price_us}\">{$variation->variation_name} - &#36;{$variation->price_us}</option>";

	}

$price.=<<<EOD
<select>
$variations
</select>
EOD;


//End variations

}else{
	
$price=<<<EOD
<input name="item_code" type="hidden" value="{$product->item_code}" />
<input name="price" type="hidden" value="{$product->price_us}" />
&#36;{$product->price_us}
EOD;

}


//Item display

$items.=<<<EOD

<div class="item">

    <div>
        <div class="img"><img src="{$product->image_thumb}" border="0" /></div>
        <h4>{$title}</h4>
        <span >{$product->description}</span><br>
    </div>
   
    <div class="price-buy">
    <!--The price information and buy button must be in the same container!-->
    $price
    <a id="order-sample" href="javascript: void(0);" onClick="store_cart.updateCart(this)">BUY</a>
    </div>
    
</div>

EOD;

}

?>

<html>
<head>
<script type="text/javascript" src="add_item.js"></script>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
	
<div id="cart">
	
<strong>Cart</strong>

<div class="read-out">Items:  <span id="cart-size"></span></div>
<div class="read-out"><span>Total:  <?php echo $currency; ?></span><span id="cart-total"></span><a class="button cart-edit" href="cart_edit.php">edit</a><a class="button cart-checkout" id="cart-checkout" href="">checkout</a></div>

<div class="read-out" id="cart-updated"></div>

<script type="text/javascript">
var store_cart = new Inzu_cart("<?php echo $pay_url; ?>", "<?php echo $pay_callback; ?>");	
</script>

</div>

<div id="product_list">
<?php echo $items; ?>
</div>

</body>	
</html>


