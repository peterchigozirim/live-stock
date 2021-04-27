<?php

include_once "server_config.php";

$response = [];

$action = $_POST['action'];

if ($action === 'save') {
    $customer = $_POST['customer_name'];
    $category = $_POST['category'];
    $product = $_POST['products'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $region = $_POST['region'];

    $insert= $conn->query("INSERT INTO tbl_sales(customers_name, category, products, quantity, price, address, city, region)
        VALUE('$customer', '$category', '$product', '$quantity', '$price', '$address', '$city', '$region')");
        if ($insert) {
            $response = [
                'status' => 'success',
                'message' => 'Sales Record Saved'
            ];
            # code...
        }else{
            $response =[
                'status' => 'error',
                'message' => 'Record not save',
                'error' => $conn->error
            ];
        }

        
}elseif($action === "fetch_sale"){
    
    $fetch = $conn->query("SELECT * FROM tbl_sales");
        $list = [];
        while ($sales = $fetch->fetch_array()) {
            $sales['details'] = fetchProductById($sales['products'], $conn);
            $sales['cat_details'] = fetchCategoryById($sales['category'], $conn);
            array_push($list, $sales);
        }
        $response = [
            'status' => 'success',
            'sales_list' => $list
        ];
}

echo json_encode($response);

function fetchProductById($id, $conn)
{
    $getdata = $conn->query("SELECT * FROM tbl_products WHERE id='$id'");
    if ($getdata->num_rows > 0) {
        return $getdata->fetch_array();
    }else{
        return ['product'=> 'No Product'];
    }
}

function fetchCategoryById($id, $conn)
{
    $categories = $conn->query("SELECT * FROM tbl_category WHERE id= '$id'");
    if ($categories->num_rows>0) {
        return $categories->fetch_array();
    }else{
        return ['category' => 'no category'];
    }
}