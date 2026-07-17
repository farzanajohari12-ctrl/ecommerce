@extends('layouts.app')
@section('content')
<body id="page-top">
  <div id="wrapper">
    @include('layouts.sidebar') 
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        @include('layouts.topbar') 

        <div class="container-fluid">
            <div id="flash-message"></div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="mb-4">
                <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb bg-white px-0">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">E Commerce</li>
                    <li class="breadcrumb-item active" aria-current="page">Add Product</li>
                </ol>
                </nav>

                <h2 class="h4 font-weight-bold">Add a product</h2>
                <p class="text-muted">Orders placed across your store</p>
                </div>
                <div>
                
                <button type="button" class="btn btn-outline-secondary" onclick="confirmDiscard()">
                    Discard
                </button>
                <button type="button" class="btn btn-primary" onclick="submitProductForm('publish')">Publish product</button>
                </div>
            </div>

            <form id="productForm" action="{{ route('add-product.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="action" id="formAction" value="publish">

                <div class="row">
                <div class="col-lg-8">

                    <div class="form-group mb-4">
                    <label class="font-weight-bold">Product Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" placeholder="Write title here..." required>
                    </div>

                    <div class="form-group mb-4">
                    <label for="product_description" class="font-weight-bold">Product Description <span class="text-danger">*</span></label>
                    <input id="product_description" type="hidden" name="description">
                    <trix-editor 
                        input="product_description" 
                        class="trix-content form-control border"
                        style="min-height: 200px; max-height: 300px; overflow-y: auto;">
                    </trix-editor>
                    </div>

                    <div class="form-group mb-4">
                    <label class="font-weight-bold">Display images <span class="text-danger">*</span></label>
                    <div class="border p-4 text-center" style="border-style: dashed; cursor: pointer;">
                        <input type="file" name="images[]" class="d-none" id="imageUpload" accept="image/*" multiple>
                        <label for="imageUpload" class="d-block mb-0">
                        <img src="{{ asset('img/image-upload.svg') }}" alt="Upload" style="height: 40px;">
                        <p class="mt-2 mb-0 text-muted">
                            Drag your photos here or <span class="text-primary">Browse from device</span>
                        </p>
                        </label>
                    </div>
                    <div id="imagePreview" class="row mt-3"></div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Inventory</label>

                        <div class="border p-3 rounded mb-4">
                            <h6 class="font-weight-bold mb-3">Pricing</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Regular price (RM) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="price" class="form-control" placeholder="e.g. 59.00">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Sale price (RM) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="sale_price" class="form-control" placeholder="e.g. 45.00">
                                </div>
                            </div>
                        </div>

                        <label class="font-weight-bold">Stock Quantity</label>
                        <div class="border p-3 rounded">
                            <div class="mb-3">
                                <label class="form-label">Initial Stock <span class="text-danger">*</span></label>
                                <input type="number" name="stock_quantity" class="form-control" placeholder="e.g. 100" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">

                    <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="font-weight-bold mb-3">Organize</h6>

                        <div class="form-group mb-3">
                        <label>Category <span class="text-danger">*</span> <a href="#" data-toggle="modal" data-target="#addCategoryModal">Add new category</a></label>
                        <select name="category_id" id="categorySelect" class="form-control mb-2 generic-variant-select">
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        </div>

                        <div class="form-group mb-3">
                        <label>Tags <a href="#" data-toggle="modal" data-target="#addTagModal">Add new tags</a></label>
                        <select name="tags[]" id="tagSelect" class="form-control select2-multiple" multiple>
                            @foreach($tags as $tag)
                                <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                            @endforeach
                        </select>
                        </div>

                        <div class="form-group mb-3">
                        <label>Badges <a href="#" data-toggle="modal" data-target="#addBadgeModal">Add new badges</a></label>
                        <select name="badges[]" id="badgeSelect" class="form-control select2-multiple" multiple>
                            @foreach($badges as $badge)
                                <option value="{{ $badge->id }}">{{ $badge->name }}</option>
                            @endforeach
                        </select>
                        </div>
                    </div>
                    </div>

                    <div class="card">
                    <div class="card-body">
                        <h6 class="font-weight-bold mb-3">Variants</h6>
                        <div id="variantSection">
                        <div class="form-group mb-3 position-relative border p-2 rounded">
                            <label class="font-weight-bold">Variant Setup <span class="text-danger">*</span></label>
                            
                            <select name="variant_option[]" class="form-control mb-2 generic-variant-select">
                                <option value="">Select Attribute (e.g. Size)</option>
                                @foreach($variants as $variant)
                                    <option value="{{ $variant->id }}">{{ $variant->name }}</option>
                                @endforeach
                            </select>
                            <input type="text" name="variant_value[]" class="form-control" placeholder="e.g. S, M, L (Comma separated)">
                        </div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addVariant()">Add Option</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#addVariantAttributeModal">Create Attribute</button>
                        </div>
                    </div>
                    </div>

                </div>
                </div>
            </form>

        </div>

        <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-shadow modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header"><h5 class="modal-title">Add New Category</h5><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
                    <form id="ajaxCategoryForm"><div class="modal-body"><div class="form-group"><label>Category Name <span class="text-danger">*</span></label><input type="text" id="newCategoryName" class="form-control" required></div></div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button><button type="submit" class="btn btn-primary">Save Category</button></div></form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addTagModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-shadow modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header"><h5 class="modal-title">Add New Tag</h5><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
                    <form id="ajaxTagForm"><div class="modal-body"><div class="form-group"><label>Tag Name <span class="text-danger">*</span></label><input type="text" id="newTagName" class="form-control" required></div></div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button><button type="submit" class="btn btn-primary">Save Tag</button></div></form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addBadgeModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-shadow modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header"><h5 class="modal-title">Add New Badge</h5><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
                    <form id="ajaxBadgeForm"><div class="modal-body"><div class="form-group"><label>Badge Name <span class="text-danger">*</span></label><input type="text" id="newBadgeName" class="form-control" required></div></div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button><button type="submit" class="btn btn-primary">Save Badge</button></div></form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addVariantAttributeModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-shadow modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header"><h5 class="modal-title">Create Variant Attribute</h5><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
                    <form id="ajaxVariantAttributeForm"><div class="modal-body"><div class="form-group"><label>Attribute Name (e.g., Color, Size, Material) <span class="text-danger">*</span></label><input type="text" id="newVariantName" class="form-control" required></div></div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button><button type="submit" class="btn btn-primary">Save Attribute</button></div></form>
                </div>
            </div>
        </div>

        @include('layouts.footer')
    </div>
  </div>

  <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Global storage array for existing dynamic attributes to use when multiplying options
        let variantAttributesCache = @json($variants);

        function addVariant() {
            const variantSection = document.getElementById('variantSection');
            
            let optionsHtml = '<option value="">Select Attribute</option>';
            variantAttributesCache.forEach(attr => {
                optionsHtml += `<option value="${attr.id}">${attr.name}</option>`;
            });

            const html = `
            <div class="form-group mt-3 border p-2 rounded position-relative">
                <label>Option <a href="#" class="text-danger float-right" onclick="removeVariant(this)"> Remove</a></label>
                <select name="variant_option[]" class="form-control mb-2 generic-variant-select">
                    ${optionsHtml}
                </select>
                <input type="text" name="variant_value[]" class="form-control" placeholder="e.g. Red, Blue">
            </div>`;
            variantSection.insertAdjacentHTML('beforeend', html);
        }

        function removeVariant(el) {
            el.closest('.form-group').remove();
        }
    </script>

    <script>
        let selectedFiles = [];
        const MAX_IMAGES = 4;

        document.getElementById('imageUpload').addEventListener('change', function (event) {
            const newFiles = Array.from(event.target.files);
            const preview = document.getElementById('imagePreview');

            const totalImages = selectedFiles.length + newFiles.length;

            if (totalImages > MAX_IMAGES) {
                alert(`You can only upload up to ${MAX_IMAGES} images.`);
                event.target.value = '';
                return;
            }

            newFiles.forEach(file => {
                const exists = selectedFiles.some(f => f.name === file.name && f.size === file.size);

                if (!exists && selectedFiles.length < MAX_IMAGES) {
                    selectedFiles.push(file);

                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const col = document.createElement('div');
                        col.classList.add('col-md-3', 'mb-3', 'position-relative');

                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'img-thumbnail';
                        img.style.maxHeight = '150px';
                        img.style.width = '100%';

                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.innerHTML = '&times;';
                        removeBtn.className = 'btn btn-sm btn-danger position-absolute';
                        removeBtn.style.top = '5px';
                        removeBtn.style.right = '10px';

                        removeBtn.onclick = function () {
                            selectedFiles = selectedFiles.filter(f => !(f.name === file.name && f.size === file.size));
                            col.remove();
                        };

                        col.appendChild(img);
                        col.appendChild(removeBtn);
                        preview.appendChild(col);
                    };

                    reader.readAsDataURL(file);
                }
            });

            event.target.value = '';
        });
    </script>

    <script>
        function closeModal(modalId) {
            let modal = document.getElementById(modalId);

            modal.classList.remove('show');
            modal.style.display = 'none';

            document.body.classList.remove('modal-open');

            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());

            document.body.style.paddingRight = '';
        }

        function validateFormBeforeSubmit() {

            // 1. Title required
            if (!$('input[name="title"]').val().trim()) {
                showFlashMessage("Product title is required", "danger");
                return false;
            }

            // 2. Description required (Trix editor)
            let description = $('input[name="description"]').val();
            if (!description || description.trim() === "") {
                showFlashMessage("Product description is required", "danger");
                return false;
            }

            // 3. Category required
            if (!$('#categorySelect').val()) {
                showFlashMessage("Category is required", "danger");
                return false;
            }

            // 4. Regular price required
            if (!$('input[name="price"]').val()) {
                showFlashMessage("Regular price is required", "danger");
                return false;
            }

            // 5. Sale price required
            if (!$('input[name="sale_price"]').val()) {
                showFlashMessage("Sale price is required", "danger");
                return false;
            }

            // 6. Stock required
            if (!$('input[name="stock_quantity"]').val()) {
                showFlashMessage("Stock quantity is required", "danger");
                return false;
            }

            // 7. Image required (IMPORTANT)
            if (selectedFiles.length === 0) {
                showFlashMessage("At least one product image is required", "danger");
                return false;
            }

            // 8. Variant validation
            let valid = true;

            $('select[name="variant_option[]"]').each(function (index) {

                let attribute = $(this).val();
                let value = $('input[name="variant_value[]"]').eq(index).val();

                if (!attribute) {
                    showFlashMessage("Variant attribute is required", "danger");
                    valid = false;
                    return false;
                }

                if (!value || value.trim() === "") {
                    showFlashMessage("Variant value is required", "danger");
                    valid = false;
                    return false;
                }
            });

            return valid;
        }

        function showFlashMessage(message, type = 'error') {

            let icon = 'error';

            if (type === 'success') icon = 'success';
            if (type === 'warning') icon = 'warning';
            if (type === 'info') icon = 'info';

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: icon,
                title: message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        }

        function submitProductForm(action) {
            document.getElementById('formAction').value = action;

            // 🔥 VALIDATE FIRST
            if (!validateFormBeforeSubmit()) {
                return; // stop ajax
            }

            let form = document.getElementById('productForm');
            let formData = new FormData(form);

            selectedFiles.forEach(file => {
                formData.append('images[]', file);
            });

            $.ajax({
                url: "{{ route('add-product.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                error: function (xhr) {
                    console.log(xhr.responseText);
                    showFlashMessage("Upload failed", "danger");
                },
                success: function (res) {
                    showFlashMessage("Product saved successfully", "success");

                    let form = document.getElementById('productForm');

                    // 1. Reset normal form fields
                    form.reset();

                    // 2. Reset hidden action
                    document.getElementById('formAction').value = "publish";

                    // 3. Clear Trix editor
                    document.querySelector("trix-editor").editor.loadHTML("");
                    document.querySelector("input[name='description']").value = "";

                    // 4. Clear image preview + selected files
                    selectedFiles = [];
                    document.getElementById('imagePreview').innerHTML = "";

                    // 5. Reset Select2 (Category, Tags, Badges)
                    $('#categorySelect').val(null).trigger('change');
                    $('#tagSelect').val(null).trigger('change');
                    $('#badgeSelect').val(null).trigger('change');

                    // 6. Reset variants section (keep only first default block)
                    $('#variantSection').html(`
                        <div class="form-group mb-3 position-relative border p-2 rounded">
                            <label class="font-weight-bold">Variant Setup</label>

                            <select name="variant_option[]" class="form-control mb-2 generic-variant-select">
                                <option value="">Select Attribute (e.g. Size)</option>
                                ${variantAttributesCache.map(v =>
                                    `<option value="${v.id}">${v.name}</option>`
                                ).join('')}
                            </select>

                            <input type="text" name="variant_value[]" class="form-control" placeholder="e.g. S, M, L (Comma separated)">
                        </div>
                    `);

                    console.log(res);
                }
            });
        }

        // Set up universal Headers for Laravel Ajax verification token 
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // AJAX Handler for Category
        $('#ajaxCategoryForm').on('submit', function(e){
            e.preventDefault();

            let btn = $(this).find('button[type="submit"]');

            btn.prop('disabled', true).text('Saving...');

            $.ajax({
                url: "{{ route('categories.store') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    name: $('#newCategoryName').val()
                },

                success: function(data){

                    $('#categorySelect').append(
                        new Option(data.name, data.id, true, true)
                    );

                    $('#newCategoryName').val('');

                    closeModal('addCategoryModal');
                },

                error: function(xhr){
                    console.log(xhr.responseText);
                    alert('Error saving category');
                },

                complete: function(){
                    btn.prop('disabled', false).text('Save Category');
                }
            });
        });
        
        // AJAX Handler for Tags
        $('#ajaxTagForm').on('submit', function(e){
            e.preventDefault();

            let btn = $(this).find('button[type="submit"]');
            btn.prop('disabled', true).text('Saving...');

            $.ajax({
                url: "{{ route('tags.store') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    name: $('#newTagName').val()
                },

                success: function(data){

                    $('#tagSelect').append(
                        new Option(data.name, data.id, true, true)
                    );

                    $('#newTagName').val('');

                    closeModal('addTagModal'); // same style as category
                },

                error: function(xhr){
                    console.log(xhr.responseText);
                    alert('Error saving tag');
                },

                complete: function(){
                    btn.prop('disabled', false).text('Save Tag');
                }
            });
        });

        // AJAX Handler for Badges
        $('#ajaxBadgeForm').on('submit', function(e){
            e.preventDefault();

            let btn = $(this).find('button[type="submit"]');
            btn.prop('disabled', true).text('Saving...');

            $.ajax({
                url: "{{ route('badges.store') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    name: $('#newBadgeName').val()
                },

                success: function(data){

                    $('#badgeSelect').append(
                        new Option(data.name, data.id, true, true)
                    );

                    $('#newBadgeName').val('');

                    closeModal('addBadgeModal'); // consistent modal handler
                },

                error: function(xhr){
                    console.log(xhr.responseText);
                    alert('Error saving badge');
                },

                complete: function(){
                    btn.prop('disabled', false).text('Save Badge');
                }
            });
        });

        // AJAX Handler for Variant Attribute
        $('#ajaxVariantAttributeForm').on('submit', function(e){
            e.preventDefault();

            console.log('Form Submitted');

            let btn = $(this).find('button[type="submit"]');
            btn.prop('disabled', true).text('Saving...');

            $.ajax({
                url: "{{ route('variant-attributes.store') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    name: $('#newVariantName').val()
                },

                success: function(data){

                    // Add new option to all variant dropdowns
                    $('.generic-variant-select').append(
                        new Option(data.name, data.id, true, true)
                    );

                    // Update cache if needed
                    variantAttributesCache.push(data);

                    // Clear input
                    $('#newVariantName').val('');

                    // Close modal
                    closeModal('addVariantAttributeModal');
                },

                error: function(xhr){
                    console.log(xhr.responseText);
                    alert(xhr.responseJSON?.message || 'Error saving variant attribute');
                },

                complete: function(){
                    btn.prop('disabled', false).text('Save Variant');
                }
            });
        });

        function confirmDiscard() {
            if (confirm('Are you sure you want to discard your changes?')) {
                window.history.back();
            }
        }
    </script>

</body>
@endsection