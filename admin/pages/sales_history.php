<div class="container pt-4">
    <div class="alert alert-info">
        <strong>
            <span id="counter"></span> Sales History 
        </strong>
    </div>
    <div class="container">
        <table class="table  table-border shadow-lg rounded-lg text-capitalize ">
            <thead class="bg-dark text-white">
                <tr class=" ">
                    <th scope="col">Customers Name</th>
                    <th scope="col">Category</th>
                    <th scope="col">Products</th>
                    <th scope="col">quality</th>
                    <th scope="col" >Price</th>
                    <th scope="col" >Address</th>
                    <th scope="col" >City</th>
                    <th scope="col" >State</th>
                    <th scope="col" >Status</th>
                </tr>
            
            </thead>
            <tbody id="sales_details">
        
    
            </tbody>
        </table>
    </div>
</div>

<script>
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
                                    <td>${list.customers_name}</td>
                                    <td>${list.cat_details.category}</td>
                                    <td>${list.details.product}</td>
                                    <td>${list.quantity}</td>
                                    <td>${list.price}</td>
                                    <td>${list.address}</td>
                                    <td>${list.city}</td>
                                    <td>${list.Region}</td>
                                </tr>`;
                });
                $('#sales_details').html($result);
            }
        }
    });
}
saleData();
</script>