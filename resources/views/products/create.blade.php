    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Create Product</h3>
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
            <input type="text" id="name" name="name"
                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded-md shadow-sm">
        </div>
        <div class="mb-4">
            <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price</label>
            <input type="number" id="price" name="price"
                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded-md shadow-sm">
        </div>
        <div class="mb-4">
            <label for="discount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Discount</label>
            <input type="number" id="discount" name="discount"
                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded-md shadow-sm">
        </div>
        <div class="mb-4">
            <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Image</label>
            <input type="file" id="image" name="thumbnail"
                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded-md shadow-sm">
        </div>

        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
            <select name="status" id="status"
                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded-md shadow-sm">
                <option value="publish">Publish</option>
                <option value="unpublish">Unpublish</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="images" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Images</label>
            <input type="file" id="images" name="images[]"
                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded-md shadow-sm" multiple>
        </div>


        <div class="flex justify-end">
            <button type="button" class="close-modal px-4 py-2 bg-gray-500 text-white rounded mr-2">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Save</button>
        </div>
    </form>
