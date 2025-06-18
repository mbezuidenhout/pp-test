<x-app-layout>
    @push('scripts')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    @endpush
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Create Person') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if ($errors->any())
                    <div class="mb-4 text-red-600">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form id="personForm" action="{{ $mode === 'edit' ? route('person.update', $person) : route('person.store') }}" method="POST" x-data="personForm">
                    @csrf
                    @if ($mode === 'edit')
                        @method('PUT')
                    @endif
                    <input type="hidden" name="_date_format" value="{{ $autoloadOptions['l10n']['date_format'] }}" />
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-200 font-bold mb-2" for="name">Name</label>
                        <input name="name" id="name" type="text" value="{{ old('name', $person->name) }}" required class="w-full px-3 py-2 rounded border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-200 font-bold mb-2" for="surname">Surname</label>
                        <input name="surname" id="surname" type="text" value="{{ old('surname', $person->surname) }}" required class="w-full px-3 py-2 rounded border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-200 font-bold mb-2" for="id_number">ID Number</label>
                        <input name="sa_id_number" id="sa_id_number" type="text" value="{{ old('sa_id_number', $person->sa_id_number) }}" required maxlength="13" class="w-full px-3 py-2 rounded border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white" x-model="sa_id_number" x-on:blur="validateSaIdNumberLength" x-on:input="validateSaIdNumber" />
                        <p x-show="error" x-text="error" class="text-red-600 text-sm mt-1"></p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-200 font-bold mb-2" for="id_number">Mobile Number</label>
                        <input name="mobile_number" id="mobile_number" type="text" value="{{ old('mobile_number', $person->mobile_number) }}" required maxlength="20" placeholder="+27123456789" class="w-full px-3 py-2 rounded border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-200 font-bold mb-2" for="email">Email</label>
                        <input name="email" id="email" type="email" value="{{ old('email', $person->email) }}" required class="w-full px-3 py-2 rounded border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-200 font-bold mb-2" for="birth_date">Birth Date</label>
                        <input
                            name="birth_date"
                            id="birth_date"
                            type="text"
                            required
                            maxlength="20"
                            class="w-full px-3 py-2 rounded border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                            placeholder="Select a date"
                            autocomplete="off"
                            x-data
                            x-init="flatpickr($el, {
            dateFormat: '{{ $autoloadOptions['l10n']['date_format'] }}',
            maxDate: 'today',
            altInput: false,
            altFormat: '{{ $autoloadOptions['l10n']['date_alt_format'] }}',
            defaultDate: '{{ old('birth_date', $person->birth_date_formatted) }}'
        })"
                        />
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-200 font-bold mb-2" for="language_id">Language</label>
                        <select name="language_code" id="language_code" required class="w-full px-3 py-2 rounded border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            <option value="">Select a language</option>
                            @foreach ($autoloadOptions['languages'] as $languageCode => $languageName)
                                <option value="{{ $languageCode }}" {{ old('language_code', $person->language_code) == $languageCode ? 'selected' : '' }}>{{ $languageName }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div x-data="tagInputComponent()" class="w-full">
                        <div class="flex flex-wrap gap-2 mb-2" style="flex-wrap: wrap">
                            <template x-for="(tag, index) in tags" :key="index">
                                <div class="bg-blue-200 dark:bg-gray-900 text-blue-800 dark:text-gray-200 px-2 py-1 rounded flex items-center">
                                    <span x-text="tag"></span>
                                    <button type="button" class="ml-1 text-red-600" @click="removeTag(index)">×</button>
                                </div>
                            </template>
                        </div>
                        <input
                            x-model="input"
                            @keydown.enter.prevent="addTag"
                            @keydown.tab.prevent="addTag"
                            @input="filterSuggestions"
                            class="w-full px-3 py-2 rounded border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                            placeholder="Start typing to add interests"
                            type="text"
                        />
                        <ul x-show="filteredSuggestions.length > 0" class="bg-white dark:bg-gray-800 border mt-1 rounded shadow text-sm max-h-40 overflow-auto">
                            <template x-for="(suggestion, index) in filteredSuggestions" :key="index">
                                <li @click="selectSuggestion(suggestion)" class="px-3 py-1 dark:text-white hover:bg-blue-100 dark:hover:bg-blue-700 cursor-pointer" x-text="suggestion"></li>
                            </template>
                        </ul>

                        <!-- Hidden Inputs for Submission -->
                        <template x-for="(tag, index) in tags" :key="'input-' + index">
                            <input type="hidden" name="interests[]" :value="tag">
                        </template>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded mr-2">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded">
                            {{ $mode === 'edit' ? 'Update Person' : 'Create Person' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function validateSouthAfricanID(id) {
            // Must be exactly 13 digits
            if (!/^\d{13}$/.test(id)) return false;

            // Luhn algorithm for checksum
            let sum = 0;
            for (let i = 0; i < 13; i++) {
                let digit = parseInt(id[i], 10);
                if ((i % 2) === 0) {
                    sum += digit;
                } else {
                    let double = digit * 2;
                    sum += double > 9 ? double - 9 : double;
                }
            }
            return (sum % 10) === 0;
        }

        function personForm() {
            return {
                sa_id_number: document.getElementById('sa_id_number')?.value || '',
                error: '',

                validateSaIdNumber() {
                    if (/^\d{13}$/.test(this.sa_id_number)) {
                        if (!validateSouthAfricanID(this.sa_id_number.trim())) {
                            this.error = 'Invalid South African ID number.';
                        } else {
                            this.error = '';

                            const dobPart = this.sa_id_number.substring(0, 6);
                            const year = parseInt(dobPart.substring(0, 2), 10);
                            const month = dobPart.substring(2, 4);
                            const day = dobPart.substring(4, 6);

                            // Determine century
                            const fullYear = year < 25 ? 2000 + year : 1900 + year;

                            // Format date
                            const jsDate = new Date(`${fullYear}-${month}-${day}`);

                            // Set the birth_date input value
                            document.getElementById('birth_date')._flatpickr.setDate(jsDate);
                        }
                    }
                },
                validateSaIdNumberLength() {
                    if (!/^\d{13}$/.test(this.sa_id_number)) {
                        this.error = 'ID Number must be exactly 13 digits.';
                    } else {
                        this.error = '';
                    }
                }
            };
        }

        function tagInputComponent() {
            return {
                tags: @json(old('interests', $person->interests ?? [] )),
                input: '',
                suggestions: @json($interests),
                filteredSuggestions: [],

                addTag() {
                    if (this.input.trim() && !this.tags.includes(this.input.trim())) {
                        this.tags.push(this.input.trim());
                    }
                    this.input = '';
                    this.filteredSuggestions = [];
                },
                removeTag(index) {
                    this.tags.splice(index, 1);
                },
                filterSuggestions() {
                    const search = this.input.toLowerCase();
                    this.filteredSuggestions = this.suggestions.filter(s => s.toLowerCase().includes(search) && !this.tags.includes(s));
                },
                selectSuggestion(suggestion) {
                    this.input = suggestion;
                    this.addTag();
                }
            };
        }
    </script>
</x-app-layout>
