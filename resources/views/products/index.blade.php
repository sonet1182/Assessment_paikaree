<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex items-center space-x-4 lg:float-end">
                    <input type="text" id="search"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 rounded-md shadow-sm"
                        placeholder="Search...">
                    <select id="sortOptions"
                        class="w-48 px-4 py-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 rounded-md shadow-sm">
                        <option value="">Sort By Price</option>
                        <option value="highest">Highest</option>
                        <option value="lowest">Lowest</option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Product Table</h3>
                        <a href="{{ route('products.create') }}"
                            class="open-modal px-4 py-1 bg-blue-500 text-white rounded ajax-click-page">Create New</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 border-b dark:border-gray-700"">ID</th>
                                    <th class="px-4 py-2 border-b dark:border-gray-700">Thumbnail</th>
                                    <th class="px-4 py-2 border-b dark:border-gray-700">Name</th>
                                    <th class="px-4 py-2 border-b dark:border-gray-700">Price</th>
                                    <th class="px-4 py-2 border-b dark:border-gray-700">Discount</th>
                                    <th class="px-4 py-2 border-b dark:border-gray-700">Status</th>
                                    <th class="px-4 py-2 border-b dark:border-gray-700">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                        <div id="tfoot"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create New Modal -->
    <div id="createNewModal"
        class="modal fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
        <div class="bg-white dark:bg-gray-800 p-6 w-3/4 max-w-2xl rounded shadow-lg modal-dialog">
        </div>
    </div>



    <script>
        loadProducts();

        $('#search').keyup(function() {
            let search = $(this).val();
            loadProducts(1, search);
        });

        $('#sortOptions').change(function() {
            let sort = $(this).val();
            loadProducts(1, '', sort);
        });

        function loadProducts($page = 1, $search = '', $sort = '') {
            var url = "{{ route('products.index') }}";
            url = url + '?page=' + $page;
            if ($search) {
                url = url + '&search=' + $search;
            }
            if ($sort) {
                url = url + '&sort=' + $sort;
            }
            $.ajax({
                url: url,
                type: "GET",
                success: function(response) {
                    let products = response.data;
                    let html = '';
                    products.forEach((product, index) => {
                        let editRoute = "{{ route('products.edit', ':id') }}";
                        editRoute = editRoute.replace(':id', product.id);
                        let deleteRoute = "{{ route('products.destroy', ':id') }}";
                        deleteRoute = deleteRoute.replace(':id', product.id);
                        html += `
                                <tr class="text-center">
    <td class="px-4 py-2 border-b dark:border-gray-700">${index + 1}</td>
    <td class="px-4 py-2 border-b dark:border-gray-700 flex justify-center">
        <img src="${product.thumbnail}" alt="${product.name}" class="h-10 w-10 object-cover rounded-full">
    </td>
    <td class="px-4 py-2 border-b dark:border-gray-700">${product.name}</td>
    <td class="px-4 py-2 border-b dark:border-gray-700">${product.price}</td>
    <td class="px-4 py-2 border-b dark:border-gray-700">${product.discount}%</td>
    <td class="px-4 py-2 border-b dark:border-gray-700">${product.status}</td>
    <td class="px-4 py-2 border-b dark:border-gray-700 flex justify-center space-x-1">
        <a class="open-modal px-4 py-1 bg-green-500 text-white rounded ajax-click-page" href="${editRoute}">Edit</a>
        <a class="px-4 py-1 bg-red-500 text-white rounded ajax-delete" href="${deleteRoute}">Delete</a>
    </td>
</tr>

                            `;
                    });
                    $('tbody').html(html);

                    loadPagination(response.meta);

                }
            });
        }

        function loadPagination(meta) {
            let pages = [];
            let startPage = Math.max(1, meta.current_page - 2);
            let endPage = Math.min(meta.last_page, meta.current_page + 2);

            if (startPage > 1) pages.push('...');
            for (let i = startPage; i <= endPage; i++) {
                pages.push(i);
            }
            if (endPage < meta.last_page) pages.push('...');

            let html = `
        <div class="md:flex w-full">
            <div class="mr-auto py-3">
                <p class="text-sm text-gray-700 dark:text-gray-300">
                    Showing ${meta.from} to ${meta.to} of ${meta.total} entries
                </p>
            </div>
            <div class="ml-auto py-3">
                <div class="flex space-x-1">
                    ${meta.current_page > 1
                        ? `<button class="px-4 py-2 bg-gray-200 text-gray-700 rounded" onclick="loadProducts(${meta.current_page - 1})">Previous</button>`
                        : '<button class="px-4 py-2 bg-gray-100 text-gray-400 rounded" disabled>Previous</button>'
                    }
                    ${pages.map(page => typeof page === 'number'
                        ? `<button class="px-4 py-2 ${meta.current_page === page ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700'} rounded" onclick="loadProducts(${page})">${page}</button>`
                        : `<span class="px-4 py-2 text-gray-500">...</span>`
                    ).join('')}
                    ${meta.current_page < meta.last_page
                        ? `<button class="px-4 py-2 bg-gray-200 text-gray-700 rounded" onclick="loadProducts(${meta.current_page + 1})">Next</button>`
                        : '<button class="px-4 py-2 bg-gray-100 text-gray-400 rounded" disabled>Next</button>'
                    }
                </div>
            </div>
        </div>
    `;

            // Insert the HTML into the page
            $('#tfoot').html(html);
        }
    </script>

</x-app-layout>
