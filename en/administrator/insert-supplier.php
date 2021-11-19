<?php
    include(__DIR__ . "/../../server/includes/utils/ClassLoader.php");
    include(__DIR__ . "/../../server/includes/utils/Conn.php");

    global $conn;

    $query = "INSERT INTO supplier(Supplier_Name) VALUES ('$_POST[newsupplier]')";
    if (!mysqli_query($conn,$query)){
        die('Error: ' . mysqli_error($conn));
    }

    echo '<script>alert("New supplier added!");
    window.location.href= "/OPCS/en/administrator/add-product";
    </script>';
    mysqli_close($conn);
?>