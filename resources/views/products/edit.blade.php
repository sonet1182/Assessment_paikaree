    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Edit Product</h3>
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data"
        class="ajax-form">
        @csrf
        @method('put')
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
            <input type="text" id="name" name="name"
                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded-md shadow-sm"
                value="{{ $product?->name ?? '' }}">
        </div>
        <div class="mb-4">
            <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price</label>
            <input type="number" id="price" name="price"
                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded-md shadow-sm"
                value="{{ $product?->price ?? '' }}">
        </div>
        <div class="mb-4">
            <label for="discount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Discount</label>
            <input type="number" id="discount" name="discount"
                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded-md shadow-sm"
                value="{{ $product?->discount ?? '' }}">
        </div>
        <div class="mb-4">
            <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Image</label>
            <input type="file" id="image" name="thumbnail"
                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded-md shadow-sm">
            <div id="image-preview" class="mt-2">
                @isset($product->thumbnail)
                    <img src="{{ asset($product->thumbnail) }}" alt="{{ $product->name }}" class="mt-2" height="50"
                        width="50">
                @endisset
            </div>
        </div>

        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
            <select name="status" id="status"
                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded-md shadow-sm">
                <option value="publish" @selected($product->status == 'publish')>Publish</option>
                <option value="unpublish" @selected($product->status == 'unpublish')>Unpublish</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="images" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Images</label>
            <input type="file" id="images" name="images[]"
                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded-md shadow-sm"
                multiple>

            <div class="flex space-x-1">
                @isset($product->images)
                    @foreach ($product->images as $image)
                        <img src="{{ asset($image->image) }}" alt="{{ $image->image }}" class="mt-2 h-20 w-20">
                    @endforeach
                @endisset
            </div>

            <div id="images-preview" class="mt-2 flex space-x-1"></div>
        </div>


        <div class="flex justify-end">
            <button type="button" class="close-modal px-4 py-2 bg-gray-500 text-white rounded mr-2">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Update</button>
        </div>
    </form>


    <script>
        document.getElementById('image').addEventListener('change', function(event) {
            const preview = document.getElementById('image-preview');
            preview.innerHTML = ''; // Clear any existing previews
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'h-20 w-20 object-cover rounded mt-2';
                    preview.appendChild(img);
                }
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('images').addEventListener('change', function(event) {
            const preview = document.getElementById('images-preview');
            preview.innerHTML = ''; // Clear any existing previews
            const files = event.target.files;
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'h-20 w-20 object-cover rounded mt-2';
                    preview.appendChild(img);
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
