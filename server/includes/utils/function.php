<?php
    function print_brand($con){
        $brand_result = $con -> query('SELECT DISTINCT Product_Brand FROM product ORDER BY Product_Brand ASC;');
        while($brand = $brand_result -> fetch_assoc()){
            echo '<li>
            <a href="/OPCS/en/all-product/products?brand='. $brand['Product_Brand'] .'" class="dropdown-item dropdown-item-custom">'. $brand['Product_Brand'] . '</a>
            </li>';
        }
    }
    
    function print_product($con, $sql_query){
        $search_result = $con->query($sql_query);
        while($row = $search_result->fetch_assoc()){

            if ($row["Product_Quantity"] > 0){
                $availability_data = "<i class=\"uil uil-check-circle norm-icon\"></i>In Stock";
            }
            else{
                $availability_data = "<i class=\"uil uil-times-circle norm-icon\"></i>Out of Stock";
            }
            $product_data = "<div class=\"product-frame ". $row['Category_ID'] ."\">
                                    <div class=\"product-img\">
                                        <a href=\"/OPCS/en/all-product/display_product?product_id=". $row['Product_ID'] ."\"><img src=\"/OPCS/data/product_images/". $row["Product_Image"]."\" alt=\"\"></a>
                                    </div>
                                    <div class=\"product-info\">
                                        <div class=\"product-name\">
                                            <a href=\"/OPCS/en/all-product/display_product?product_id=". $row['Product_ID'] ."\"> ". $row['Product_Name'] . "</a>
                                        </div>
                                        <div class=\"product-price\">
                                            <a href=\"/OPCS/en/all-product/display_product?product_id=". $row['Product_ID'] ."\">RM ". $row['Price']."</a>
                                        </div>
                                        <div class=\"product-status\">"
                                            . $availability_data .
                                        "</div>
                                    </div>
                                </div>";
            echo $product_data;
        }
    }

    function addToCart($conn, $product_id, $quantity, $user_id){
        $cart_check_query = "SELECT * FROM shopping_cart_item WHERE Product_ID= $product_id AND Cart_ID=(SELECT Cart_ID FROM shopping_cart WHERE User_ID = '$user_id' )";
        $runned_query = $conn -> query($cart_check_query);
        $num_row = $runned_query -> num_rows;
        if($num_row === 0) {
            $addToCart = "INSERT INTO shopping_cart_item (Product_ID, Quantity, Cart_ID) VALUES ($product_id, $quantity, (SELECT Cart_ID FROM shopping_cart WHERE User_ID = '$user_id'))";
            if ($conn->query($addToCart)) {
                echo "<script>
                        alert('Product added to cart successfully!');
                    </script>";
            }
        }
        else{
            echo "<script>alert('Product exist in cart!')</script>";
        }
    }

    function show_cart($con, $user_id){
        $show_cart_query = "SELECT
                            shopping_cart_item.Product_ID,
                            shopping_cart_item.Quantity,
                            product.Product_Name,
                            product.Price,
                            product.Product_Image,
                            product.Product_Quantity
                        FROM
                            shopping_cart_item
                        INNER JOIN product ON product.product_ID = shopping_cart_item.product_id
                        WHERE
                            cart_id =(
                            SELECT
                                Cart_ID
                            FROM
                                shopping_cart
                            WHERE
                                User_ID = '$user_id'
                        )";

        $user_cart = $con -> query($show_cart_query);
        if($user_cart->num_rows > 0){
            while($cart_item = $user_cart -> fetch_assoc()){
                echo "<div class=\"cart-item\">
                        <div class=\"cart-img\">
                            <img src=\"/OPCS/data/product_images/". $cart_item['Product_Image'] ."\">
                        </div>
                        <div class=\"cart-item-name\">
                            ". $cart_item['Product_Name'] ."
                            <br>
                            <br>
                            <p class=\"cart-item-price\">RM ". $cart_item['Price'] ."</p> 
                            <span class=\"warning warning-quantity\">You have reached the maximum quantity available for this item</span>
                            <span class=\"warning warning-stock\">Product is out of stock please remove in order to check out.</span>
                        </div>
                        <div class=\"cart-item-quantity\">
                            <div class=\"quantity-bar\">
                                <div class=\"minus\">
                                    <button type=\"button\" class=\"plus-minus-btn minus-btn\"><i class=\"uil uil-minus\"></i></button>
                                </div>
                                <input type=\"number\" hidden class=\"product-stock\" value=\"". $cart_item['Product_Quantity'] ."\">
                                <input type=\"number\" class=\"quantity-input\" value=\"". $cart_item['Quantity'] ."\" readonly>
                                <div class=\"plus\">
                                    <button type=\"button\" class=\"plus-minus-btn plus-btn\"><i class=\"uil uil-plus\"></i></button>
                                </div>
                            </div>
                            <div class=\"cart-btn\">
                                <a href=\"/OPCS/en/cart/update_cart?product_id=". $cart_item['Product_ID'] ."&amp;product_quantity=\" class=\"btn update-btn hide btn-danger\">Update</a>
                                <a href=\"/OPCS/en/cart/remove_item?product_id=". $cart_item['Product_ID'] ."\" class=\"remove-btn btn btn-danger\">Remove</a>
                            </div> 
                        </div>
                    </div>";
            }
        }
        else{
            echo "<div class=\"no-review\">There is nothing inside the cart</div>";
        }
    }


?>
