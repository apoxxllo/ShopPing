        <!--**********************************
            Footer start
        ***********************************-->
        <div class="footer">
            <div class="copyright">
                <p>Copyright &copy; Designed & Developed by <a href="https://themeforest.net/user/quixlab">Quixlab</a>
                    2018</p>
            </div>
        </div>
        <!--**********************************
            Footer end
        ***********************************-->
        </div>
        <!--**********************************
        Main wrapper end
    ***********************************-->

        <!--**********************************
        Scripts
    ***********************************-->
        <script src="{{ asset('plugins/common/common.min.js') }}"></script>
        <script src="{{ asset('js/custom.min.js') }}"></script>
        <script src="{{ asset('js/settings.js') }}"></script>
        <script src="{{ asset('js/gleek.js') }}"></script>
        <script src="{{ asset('js/styleSwitcher.js') }}"></script>

        <!-- Chartjs -->
        <script src="{{ asset('plugins/chart.js/Chart.bundle.min.js') }}"></script>
        <!-- Circle progress -->
        <script src="{{ asset('plugins/circle-progress/circle-progress.min.js') }}"></script>
        <!-- Datamap -->
        <script src="{{ asset('plugins/d3v3/index.js') }}"></script>
        <script src="{{ asset('plugins/topojson/topojson.min.js') }}"></script>
        <script src="{{ asset('plugins/datamaps/datamaps.world.min.js') }}"></script>
        <!-- Morrisjs -->
        <script src="{{ asset('plugins/raphael/raphael.min.js') }}"></script>
        <script src="{{ asset('plugins/morris/morris.min.js') }}"></script>
        <!-- Pignose Calender -->
        <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
        <script src="{{ asset('plugins/pg-calendar/js/pignose.calendar.min.js') }}"></script>
        <!-- ChartistJS -->
        <script src="{{ asset('plugins/chartist/js/chartist.min.js') }}"></script>
        <script src="{{ asset('plugins/chartist-plugin-tooltips/js/chartist-plugin-tooltip.min.js') }}"></script>
        <script src="{{ asset('js/dashboard/dashboard-1.js') }}"></script>

        <script>
            $('#showAddCategoryModalBtn').click(function() {
                $('#addCategoryModal').modal('show');
            });
            $('#showAddProductModalBtn').click(function() {
                $('#addProductModal').modal('show');
            });
            $('.deleteCategoryBtn').click(function() {
                $('#deleteCategoryModal').modal('show');
                var id = $(this).data('id');
                var category = $(this).data('categoryname');

                // Set the data attribute 'categoryId' in the confirm delete button
                $('#confirmDeleteCategoryBtn').data('categoryid', id);

                // Set the category name in the modal output
                $('#output').text('Are you sure you want to delete this category? (' + category + ')');
            });
            $('#confirmDeleteCategoryBtn').click(function() {
                var categId = $(this).data('categoryid');
                var formId = $(this).data('formid');

                // Set the hidden input values
                $('#categoryId').val(categId);
                // $('#formId').val(formId);

                // Set the form action if needed (optional, based on your route configuration)
                var form = $('#deleteCategoryForm');
                form.attr('action', '{{ route("deleteCategory") }}');

                // Submit the form
                form.submit();
            });

            $('.deleteProductBtn').click(function() {
                var id = $(this).data('id');
                var productName = $(this).data('productname');

                // Set the data attribute 'bookId' in the confirm delete button
                $('#confirmDeleteBtn').data('productid', id);

                // Set the book name in the modal output
                $('#output').text('Are you sure you want to delete this product? (' + bookName + ')');
            });

            $('#confirmDeleteBtn').click(function() {
                // Get the bookId from the data attribute of the confirm delete button
                var productId = $(this).data('productid');

                $('#productId').val(productId);

                // Set the action attribute of the form with the bookId parameter
                var form = $('#deleteProductForm');
                form.attr('action', '{{route("deleteProduct")}}');
            });

            $('.editProductBtn').click(function() {
                var name = $(this).data('productname');
                var stock = $(this).data('stock');
                var price = $(this).data('price');
                var category = $(this).data('categoryid');
                var description = $(this).data('description');
                var shop = $(this).data('shopid');
                var id = $(this).data('id')
                $('#editProductName').val(name);
                $('#editProductStock').val(stock);
                $('#editProductPrice').val(price);
                $('#editProductCategory').val(category);
                $('#editProductDescription').val(description);
                $('#editProductShop').val(shop);
                $('#productIdUpdate').val(id);
            });

            $('.editCategoryBtn').click(function() {
                var title = $(this).data('name');
                var id = $(this).data('id');
                $('#editCategoryName').val(title);
                $('#categoryIdUpdate').val(id);
            });
        </script>

        </body>

        </html>
