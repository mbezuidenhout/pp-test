<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Interests') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div
                        x-data="tagInputComponent()"
                        x-init="sortTags()"
                        class="border p-4 rounded"
                    >
                        <label class="block font-bold mb-2">Interests</label>

                        <!-- Input -->
                        <input
                            x-model="input"
                            @keydown.enter.prevent="addTag()"
                            type="text"
                            placeholder="Type and press Enter..."
                            class="w-full px-3 py-2 rounded border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                        />

                        <!-- Tags Display -->
                        <div class="mt-2 gap-2 flex flex-wrap break-words" style="flex-wrap:wrap">
                            <template x-for="(tag, index) in tags" :key="tag">
                                <div class="bg-blue-200 dark:bg-gray-900 dark:text-gray-200 text-blue-800 px-2 py-1 rounded flex items-center">
                                    <span x-text="tag"></span>
                                    <button type="button" class="ml-1 text-red-600" @click="removeTag(index)">×</button>
                                </div>
                            </template>
                        </div>

                        <!-- Hidden Inputs for Submission -->
                        <template x-for="(tag, index) in tags" :key="'input-' + index">
                            <input type="hidden" name="interests[]" :value="tag">
                        </template>
                    </div>

                    <script>
                        function tagInputComponent() {
                            return {
                                input: '',
                                tags: @json($interests),
                                addTag() {
                                    let tag = this.input.trim();
                                    if (tag && !this.tags.includes(tag)) {
                                        this.tags.push(tag);
                                        this.sortTags();
                                        this.syncInterests();
                                    }
                                    this.input = '';
                                },
                                removeTag(index) {
                                    this.tags.splice(index, 1);
                                    this.syncInterests();
                                },
                                sortTags() {
                                    this.tags.sort((a, b) => a.localeCompare(b));
                                },
                                syncInterests() {
                                    fetch('{{ route('interests.sync') }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        body: JSON.stringify({ interests: this.tags })
                                    })
                                        .then(response => response.json())
                                        .then(data => {
                                            console.log('Interests synced:', data);
                                        })
                                        .catch(error => {
                                            console.error('Error syncing interests:', error);
                                        });
                                }
                            }
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
