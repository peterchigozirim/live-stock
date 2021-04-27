<div class="container pt-4">

<div class="alert alert-info">
    <strong>
        <span id="counter"></span> product in store.
    </strong>
</div>

<div class="container">
    <div class="row">
        <div class="col-lg-6 mb-4">
            <!-- Add Category card -->
            <div class="card">

                <div class="card-header">
                    <strong class="card-title">
                        <i class="fa fa-plus"></i> Add a Product
                    </strong>
                </div>

                <div class="card-body">
                    <form id="frm_add_product" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="product">Product</label>
                            <input type="text" placeholder="Enter product name" class="form-control" name="product" id="product" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="cat">Category</label>
                            <select name="cat" id="cat"  class="form-control" required>
                                <option value="df">Select Category</option>
                            </select>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="price">Price</label>
                                <input type="text" placeholder="Enter product price" class="form-control" name="price" id="price" required>
                            </div>

                            <div class="col-md-6">
                                <label for="discount">Discount <small>(If any!)</small></label>
                                <input type="text" placeholder="Enter Discount" class="form-control" name="discount" id="discount">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="desc">Product Description</label>
                            <textarea name="desc" id="desc" rows="5" class="form-control"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="image">Product Image</label>
                            <input type="file" name="image" id="image" class="form-control">
                        </div>

                        <div class="form-group">
                            <button class="btn btn-primary btn-sm">
                                <i class="fa fa-save"></i> Save Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <!-- List of Category card -->
            <div class="card">

                <div class="card-header">
                    <strong class="card-title">
                        <i class="fa fa-list"></i> List of Products
                    </strong>
                </div>

                <div class="card-body p-0" style="max-height:400px; overflow-y:auto; overflow-x:hidden;">
                    <ul class="list-group" id="complete_list">
                        
                    </ul>
                </div>
            </div>
        </div>
    </div>    
</div>

<!-- View Modal -->
    <div class="modal fade" id="customModal">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-body text-center">
                    
                </div>
            </div>
        </div>
    </div>

</div>


<script>

    function fetchProduct(){
        $.ajax({
            type: 'post',
            url: './backend/product_mngr.php',
            data: {action: 'fetch_products'},
            dataType: 'json',
            success: function(response){
                if(response.status === 'success'){
                    $list = '';
                    response.product_list.forEach(function (list) {
                        // console.log(list);
                        $list += `<li class="list-group-item row" id="${list.id}">
                                    <div class="row">
                                        <div class="col-4">
                                            <img src="../images/products/${list.image}" style="max-width:100%" />
                                        </div>
                                        <div class="col-8">
                                            <h3>${list.product}</h3>
                                            <p>

                                            </p>
                                            <span class="float-right py-1 px-2 del_product" role="button">
                                                <i class="fas fa-trash text-danger"></i>
                                            </span>
                                            <a href="?id=${list.id}#update_products" class="float-right py-1 px-2 update_product" role="button">
                                                <i class="fas fa-edit text-primary"></i>
                                            </a>
                                            <span class="float-right py-1 px-2 view_product" role="button">
                                                <i class="fas fa-eye text-success"></i>
                                            </span>
                                        </div>
                                    </div>
                                </li>`;
                    });
                    $('#complete_list').html($list)
                }
            }
        })
    }
    fetchProduct();
    
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

    // Submit Product Infomation
    $('#frm_add_product').on('submit', function(e){
        e.preventDefault();
        let image_file = document.getElementById('image').files[0];
        // instantiate an object of  the class FormData()
        let form_data = new FormData();
        // Append the data to the form data object
        form_data.append('image', image_file);
        form_data.append('product_name', $('#product').val());
        form_data.append('price',  $('#price').val());
        form_data.append('discount',  $('#discount').val());
        form_data.append('desc',  $('#desc').val());
        form_data.append('category',  $('#cat').val());
        form_data.append('action', 'save');

        $.ajax({
            type: 'post',
            url: './backend/product_mngr.php',
            data: form_data,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(response){
                if(response.status == 'success'){
                    $('#frm_add_product').trigger('reset');
                    alert(response.message)
                    fetchProduct();
                }else{
                    alert(response.message)
                }
            }
        })

    });

    $('#complete_list').on('click', '.view_product', function () {
        $id = $(this).parent('div').parent('div').parent('li').attr('id');
        $.ajax({
            type: 'post',
            url: './backend/product_mngr.php',
            data: {action:'view', id:$id},
            dataType: 'json',
            success: function(response){
                if(response.status == 'success'){
                    console.log(response);
                    $('#customModal').on('show.bs.modal', function(){
                        $('#customModal .modal-body').addClass('p-0')
                        $('#customModal .modal-body').html(`
                            <div class="card">
                                <img src="../images/products/${response.product.image}" class="card-img-top img-responsive" />
                                <div class="card-body p-0 text-left">
                                    <ul class="list-group p-0">
                                        <li class="list-group-item">
                                            <strong>Product Name:</strong> ${response.product.product}
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Price:</strong> ${response.product.price}
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Discount:</strong> ${response.product.discount}
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Description:</strong> ${response.product.description}
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Category:</strong> ${response.category}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        `);
                    });
                    $('#customModal').modal('show');
                }else{
                    $('#customModal').on('show.bs.modal', function(){
                        $('#customModal .modal-body').html(`
                            <i class="fa fa-times-circle text-danger" style="font-size:1.3rem"></i>
                            <strong>${response.message}</strong>
                        `);
                    });
                    $('#customModal').modal('show');
                }
            }
        });
    });

    $('#complete_list').on('click', '.del_product', function () {
        $id = $(this).parent('div').parent('div').parent('li').attr('id');
        $html = `
            <i class="fa fa-times-circle fa-2x text-danger"></i>
            <h4>Are you sure you want to delete this product?</h4>
            <button class="btn btn-primary btn-sm" data-dismiss="modal">
                <i class="fa fa-times-circle"></i> Cancel
            </button>
            <input type="hidden" id="category" value="${$id}">
            <button class="btn btn-danger btn-sm" id="confirmDelete">
                <i class="fa fa-trash"></i> Delete
            </button>
        `;

        $('#customModal').on('show.bs.modal', function(){
            $('#customModal .modal-body').html($html);
        });
        $('#customModal').modal('show');
    });

    function deleteProduct(proId) {
        $.ajax({
            type: 'post',
            url: './backend/product_mngr.php',
            data: {action:'delete', id:proId},
            dataType: 'json',
            success: function(response){
                if(response.status === 'success'){
                    $('#customModal').on('show.bs.modal', function(){
                        $('#customModal .modal-body').html(`
                            <i class="fa fa-check-circle text-success" style="font-size:1.3rem"></i>
                            <strong>Product deleted successfully</strong>
                        `);
                    });
                    $('#customModal').modal('show');
                    fetchProduct();
                }else{
                    $('#customModal').on('show.bs.modal', function(){
                        $('#customModal .modal-body').html(`
                            <i class="fa fa-times-circle text-danger" style="font-size:1.3rem"></i>
                            <strong>${response.message}</strong>
                        `);
                    });
                    $('#customModal').modal('show');
                }
            }
        });
    }

    $('.modal-body').on('click', '#confirmDelete', function(){
        $id = $(this).siblings('input').val();
        $('#customModal').modal('hide')
        $('#customModal').on('hidden.bs.modal', function(){
            deleteProduct($id)
        });
    });


</script>