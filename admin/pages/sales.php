<div class="container pt-4">
    <div class="alert alert-info">
        <strong>
            <span id="counter"></span> Sales
        </strong>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-6">

                 <div class="card shadow-lg rounded-lg">

                    <div class="card-header">
                        <strong class="card-title">
                            <i class="fa fa-plus"></i> Add Sales
                        </strong>
                    </div>

                    <div class="card-body">
                        <form id="frm_add_sales">
                            <div class="form-group">
                                <label for="customer">Customers Name</label>
                                <input type="text" name="customer" id="customer" placeholder="Enter Customers Name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="customer">Category</label>
                                <select name="cat" id="cat" class="form-control" required>
                                    <option value="df">Select Category</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="products">Products</label>
                                <select name="products" id="products" class="form-control" required>
                                    <option value="df">Select Products</option>
                                </select>
                            </div>
                            <!-- <div class="form-group">
                                <label for="product">Products</label>
                                <input type="text" name="products" id="products" placeholder="Enter product" class="form-control" required>
                            </div> -->
                            <div class="form-group">
                                <label for="quality">Quantity</label>
                                <input type="text" name="quan" id="quan" placeholder="Enter Quantity" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="customer">Price</label>
                                <input disabled type="text" name="price" id="price" value="0.0k " class="form-control" required>
                            </div>
                            <h5 >Customers Location</h5>
                            <hr>
                            <div class="form-group">
                                <label for="customer">Address</label>
                                <input type="text" name="address" id="address" placeholder="Enter Address" class="form-control" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer">City</label>
                                        <input type="text" name="city" id="city" placeholder="Enter City" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer">Region</label>
                                        <input type="text" name="region" id="region" placeholder="Enter Region" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            

                            <div class="form-group">
                                <button class="btn btn-primary btn-sm" id="submit_sales">
                                    <i class="fa fa-save" id="save"></i> Save Sales
                                </button>
                            </div>
                        </form>
                    </div>
                    </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-lg rounded-lg">

                    <div class="card-header">
                        <strong class="card-title">
                            <i class="fa fa-store mr-2 text-captialize"></i>latest Sales Records
                        </strong>
                    </div>

                    <div class="card-body">
                        <table class="table  table-borderless rounded-lg text-capitalize ">
                            <thead>
                            <tr class="shadow-sm ">
                                <th scope="col">Products</th>
                                <th scope="col" >Category</th>
                                <th scope="col">No. Of Sales</th>

                            </tr>
                            </thead>
                            <tbody id="sales_details">
                                <!-- <tr >
                                    <td>boiler</td>
                                    <td>Chicken</td>
                                    <td>4</td>
                                </tr> -->
                    
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

var pro_price = 0;

// Fetch Category
  function fetchCategory() {
        $.ajax({
            type: 'post',
            url: './backend/category_mngr.php',
            data: {action: 'fetch_category'},
            dataType: 'json',
            success: function(response){
                if(response.status === 'success'){
                    // Loading Category 1 with the html Response
                    // $('#cat').html(response.cat_list_html)
                    // Loading Category 2 with the Array Response by Looping through the array
                    $html = '<option value="">Select Category</option>';
                    response.cat_list.forEach(function(list){
                        $html += `<option value="${list.id}">${list.category}</option>`;
                    });
                    $('#cat').html($html);
                    // console.log(response.cat_list, response.cat_list_html);
                }
            }
        })
    }
    fetchCategory();
    // End

    // // Fetch Products
    function fetchProduct() {
        $.ajax({
            type: 'post',
            url: './backend/product_mngr.php',
            data: {action: 'fetch_products'},
            dataType: 'json',
            success: function(response){
                if(response.status === 'success'){
                    $html = '<option value="">Select Products</option>';
                    response.product_list.forEach(function(list){
                        $html += `<option value="${list.id}">${list.product}</option>`;
                    });
                    $('select[name=products]').html($html);
                }
            }
        })
    }
    fetchProduct();
// End Product Fetch

    // Fetch Amount
    function fetchAmount(proid){
        $.ajax({
            type: "post",
            url: "./backend/product_mngr.php",
            data: {action: 'view', id:proid},
            dataType: "json",
            success: function (response) {
                if (response.status == 'success') {
                    pro_price = response.product.price;
                }
                
            }
        });
    }

    $('select[name=products]').on('change', function () {
        $id = $(this).val();
        fetchAmount($id);
    });

    $('input[name=quan]').on('keyup', function () {
        $amount = 0;
        $qty = $(this).val();
        if ($qty != '') {
            $amount = pro_price * $qty
            $('input[name=price]').val($amount);
        }else{
            $('input[name=price]').val('');
        }
    })



// End Amount Fetch

// Form Submit
    $("#frm_add_sales").submit(function (e) { 
        e.preventDefault();
        $("#save").removeClass("fa-save");
        $("#save").addClass(" fa-loading fa-spin");
        let sales_data = new FormData();

        sales_data.append('customer_name', $('#customer').val());
        sales_data.append('category', $('#cat').val());
        sales_data.append('products', $('select[name=products]').val());
        sales_data.append('quantity', $('#quan').val());
        sales_data.append('price', $('#price').val());
        sales_data.append('address', $('#address').val());
        sales_data.append('city', $('#city').val());
        sales_data.append('region', $('#region').val());
        sales_data.append('action', 'save');


        $.ajax({
            type: "post",
            url: "./backend/sales_mngr.php",
            data: sales_data,
            dataType: "json",
            processData: false,
            contentType: false,
            success: function (response) {
                if(response.status == 'success'){
                    $('#frm_add_sales').trigger('reset');
                    alert(response.message)
                    // fetchProduct();
                }else{
                    alert(response.message)
                }
            }
        });
        
    });

    // end Form Save

    // fetch data
function saleData() {
    $.ajax({
        type: "post",
        url: "./backend/sales_mngr.php",
        data: {action: "fetch_sale"},
        dataType: "json",
        success: function (response) {
            if (response.status == 'success') {
                console.log(response.sales_list);
                $result=''
                response.sales_list.forEach(function(list){
                    $result += `<tr>
                                    <td>${list.details.product}</td>
                                    <td>${list.cat_details.category}</td>
                                    <td>${list.price}</td>
                                </tr>`;
                });
                $('#sales_details').html($result);
            }
        }
    });
}
saleData();

</script>