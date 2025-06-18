<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('People') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('status') === 'person-deleted')
                        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                            Person successfully deleted.
                        </div>
                    @elseif (session('status') === 'person-updated')
                        <div class="mb-4 p-4 bg-blue-100 text-blue-800 rounded">
                            Person successfully updated.
                        </div>
                    @elseif (session('status') === 'person-created')
                        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                            Person successfully created.
                        </div>
                    @endif
                    <div class="mt-4">
                        <a href="{{ route('person.create') }}" class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            {{ __("Create New Person") }}
                        </a>
                        <div class="mt-6" x-data="peopleHandler(@js($people))">
                            <div class="mb-4">
                                <input type="text" x-model="searchTerm" placeholder="Search..." class="w-full px-4 py-2 border rounded dark:bg-gray-900 dark:text-white dark:border-gray-600" />
                            </div>
                            <table id="table-people" x-data="{ sortKey: '', sortAsc: true }" class="min-w-full bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600">
                                <thead>
                                <tr class="bg-gray-100 dark:bg-gray-800">
                                    <th @click="sortKey = 'name'; sortAsc = sortKey === 'name' ? !sortAsc : true" class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-100 cursor-pointer select-none">
                                        <span class="flex items-center space-x-1">
                                            Name
                                            <template x-if="sortKey === 'name'">
                                                <span>
                                                    <template x-if="sortAsc">
                                                        <x-icons.sort-asc />
                                                    </template>
                                                    <template x-if="!sortAsc">
                                                        <x-icons.sort-desc />
                                                    </template>
                                                </span>
                                            </template>
                                        </span>
                                    </th>
                                    <th @click="sortKey = 'surname'; sortAsc = sortKey === 'surname' ? !sortAsc : true" class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-100 cursor-pointer select-none">
                                        <span class="flex items-center space-x-1">
                                            Surname
                                            <template x-if="sortKey === 'surname'">
                                                <span>
                                                    <template x-if="sortAsc">
                                                        <x-icons.sort-asc />
                                                    </template>
                                                    <template x-if="!sortAsc">
                                                        <x-icons.sort-desc />
                                                    </template>
                                                </span>
                                            </template>
                                        </span>
                                    </th>
                                    <th @click="sortKey = 'sa_id_number'; sortAsc = sortKey === 'sa_id_number' ? !sortAsc : true" class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-100 cursor-pointer select-none">
                                        <span class="flex items-center space-x-1">
                                            ID Number
                                            <template x-if="sortKey === 'sa_id_number'">
                                                <span>
                                                    <template x-if="sortAsc">
                                                        <x-icons.sort-asc />
                                                    </template>
                                                    <template x-if="!sortAsc">
                                                        <x-icons.sort-desc />
                                                    </template>
                                                </span>
                                            </template>
                                        </span>
                                    </th>
                                    <th @click="sortKey = 'email'; sortAsc = sortKey === 'email' ? !sortAsc : true" class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-100 cursor-pointer select-none">
                                        <span class="flex items-center space-x-1">
                                            Email
                                            <template x-if="sortKey === 'email'">
                                                <span>
                                                    <template x-if="sortAsc">
                                                        <x-icons.sort-asc />
                                                    </template>
                                                    <template x-if="!sortAsc">
                                                        <x-icons.sort-desc />
                                                    </template>
                                                </span>
                                            </template>
                                        </span>
                                    </th>
                                    <th @click="sortKey = 'birth_date'; sortAsc = sortKey === 'birth_date' ? !sortAsc : true" class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-100 cursor-pointer select-none">
                                        <span class="flex items-center space-x-1">
                                            Birth Date
                                            <template x-if="sortKey === 'birth_date'">
                                                <span>
                                                    <template x-if="sortAsc">
                                                        <x-icons.sort-asc />
                                                    </template>
                                                    <template x-if="!sortAsc">
                                                        <x-icons.sort-desc />
                                                    </template>
                                                </span>
                                            </template>
                                        </span>
                                    </th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-100">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template x-for="person in paginatedPeople()" :key="person.id">
                                    <tr :id="'person-' + person.id" class="border-t border-gray-200 dark:border-gray-600">
                                        <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-100" x-text="person.name"></td>
                                        <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-100" x-text="person.surname"></td>
                                        <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-100" x-text="person.sa_id_number"></td>
                                        <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-100" x-text="person.email"></td>
                                        <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-100" x-text="person.birth_date_formatted"></td>
                                        <td class="px-4 py-2 text-sm text-right">
                                            <a :href="'{{ url('person') }}/' + person.id + '/edit'" class="text-blue-600 hover:text-blue-800 font-semibold mr-4">Edit</a>
                                            <button @click="deletePerson(person.id)" class="text-red-600 hover:text-red-800 font-semibold">Delete</button>
                                        </td>
                                    </tr>
                                </template>
                                </tbody>
                            </table>
                            <div class="flex items-center justify-between mt-4">
                                <button @click="if (currentPage > 1) currentPage--" class="px-4 py-2 bg-gray-300 dark:bg-gray-900 rounded hover:bg-gray-400" :disabled="currentPage === 1">Previous</button>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Page <span x-text="currentPage"></span> of <span x-text="Math.ceil(filteredPeople().length / itemsPerPage)"></span></span>
                                <button @click="if (currentPage < Math.ceil(filteredPeople().length / itemsPerPage)) currentPage++" class="px-4 py-2 bg-gray-300 dark:bg-gray-900 rounded hover:bg-gray-400" :disabled="currentPage >= Math.ceil(filteredPeople().length / itemsPerPage)">Next</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function peopleHandler(allPeople) {
            return {
                searchTerm: '',
                currentPage: 1,
                itemsPerPage: 10,
                allPeople: allPeople,
                deletePerson (id) {
                    if (confirm('Are you sure you want to delete this person?')) {
                        fetch(`/person/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                        }).then(response => {
                            if (response.ok) {
                                document.getElementById('person-' + id).remove();
                            } else {
                                alert('Failed to delete person.');
                            }
                        });
                    }
                },
                filteredPeople() {
                    if (!this.searchTerm) return this.allPeople;
                    const term = this.searchTerm.toLowerCase();
                    return this.allPeople.filter(person =>
                        Object.values(person).some(
                            val => val && val.toString().toLowerCase().includes(term)
                        )
                    );
                },
                sortedPeople() {
                    return this.filteredPeople().slice().sort((a, b) => {
                        let k = this.sortKey;
                        if (!k) return 0;
                        let x = a[k] ?? '', y = b[k] ?? '';
                        return this.sortAsc ? (x > y ? 1 : -1) : (x < y ? 1 : -1);
                    });
                },
                paginatedPeople() {
                    const start = (this.currentPage - 1) * this.itemsPerPage;
                    return this.sortedPeople().slice(start, start + this.itemsPerPage);
                },
            }
        }
    </script>
</x-app-layout>
