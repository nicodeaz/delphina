@extends('layouts.admin')

@section('title', 'Services')

@php
    $categoryLabels = [
        'gel' => 'Gel Nails',
        'biab' => 'BIAB',
        'soft_gel' => 'Soft Gel Extensions',
        'polish' => 'Gel Polish',
        'addon' => 'Add-ons & Extras',
        'consultation' => 'Consultations',
        'general' => 'General Services',
    ];
    $categoryIcons = [
        'gel' => '💅',
        'biab' => '✨',
        'soft_gel' => '🌸',
        'polish' => '🎨',
        'addon' => '➕',
        'consultation' => '🗓️',
        'general' => '💎',
    ];
    $categoryOrder = array_keys($categoryLabels);

    $resolveCategory = function ($service) {
        $name = strtolower((string) $service->name);

        return match (true) {
            str_contains($name, 'trial') || str_contains($name, 'consultation') => 'consultation',
            str_contains($name, 'biab') => 'biab',
            str_contains($name, 'soft') || str_contains($name, 'extension') => 'soft_gel',
            str_contains($name, 'polish') => 'polish',
            str_contains($name, 'add') || str_contains($name, 'repair') || str_contains($name, 'art') => 'addon',
            str_contains($name, 'gel') => 'gel',
            default => 'general',
        };
    };

    $displayName = function ($service) {
        return str_contains($service->name, ' - ')
            ? trim(explode(' - ', $service->name, 2)[1])
            : $service->name;
    };

    $groupedServices = $services
        ->sortBy(fn ($service) => strtolower($service->name))
        ->groupBy($resolveCategory)
        ->sortBy(function ($group, $category) use ($categoryOrder) {
            $position = array_search($category, $categoryOrder, true);
            return $position === false ? 999 : $position;
        });
@endphp

@section('content')
<div class="py-6 md:py-8" x-data="servicesManager()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-olive">Menu &amp; prices</p>
                <h1 class="mt-2 text-3xl font-semibold text-gray-900 md:text-4xl">Services</h1>
                <p class="mt-2 text-sm text-gray-600">What clients can choose on the booking page. Tap a price to change it quickly.</p>
            </div>
            <button
                type="button"
                @click="openCreateModal()"
                class="inline-flex items-center justify-center rounded-2xl bg-olive px-5 py-3 font-semibold text-white shadow-lg transition hover:bg-green-700"
            >
                <i class="fas fa-plus mr-2"></i>
                Add service
            </button>
        </div>

        @if($services->isEmpty())
            <div class="rounded-[1.75rem] bg-white p-12 text-center shadow-xl">
                <div class="text-6xl mb-4">💅</div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No services yet</h3>
                <p class="text-gray-600 mb-6">Create your first service to get started.</p>
                <button
                    type="button"
                    @click="openCreateModal()"
                    class="inline-flex items-center justify-center rounded-2xl bg-olive px-5 py-3 font-semibold text-white shadow-lg transition hover:bg-green-700"
                >
                    <i class="fas fa-plus mr-2"></i>
                    Add service
                </button>
            </div>
        @else
            @foreach($groupedServices as $category => $group)
                <section class="mb-10">
                    <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-gray-900">
                        <span>{{ $categoryIcons[$category] ?? '💎' }}</span>
                        {{ $categoryLabels[$category] ?? ucwords(str_replace('_', ' ', $category)) }}
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($group as $service)
                            <div class="bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300">
                                <div class="p-6">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-xl font-semibold text-gray-900 mb-2 break-words">{{ $displayName($service) }}</h3>
                                            <p class="text-gray-600 text-sm leading-relaxed">
                                                {{ $service->description !== '' ? Str::limit($service->description, 100) : 'No description yet.' }}
                                            </p>
                                        </div>
                                        <div class="flex shrink-0 space-x-1 ml-4">
                                            <button
                                                type="button"
                                                @click="openEditModal(@js($service->only(['id', 'name', 'description', 'price', 'duration'])))"
                                                class="p-2 text-olive hover:bg-olive/10 rounded-lg transition-colors"
                                                aria-label="Edit {{ $service->name }}"
                                            >
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            @if($service->appointments_count > 0)
                                                <button
                                                    type="button"
                                                    disabled
                                                    title="This service has bookings on record, so it can't be deleted. Rename it or change its price instead."
                                                    class="p-2 text-gray-300 rounded-lg cursor-not-allowed"
                                                    aria-label="Cannot delete {{ $service->name }}, it has bookings"
                                                >
                                                    <i class="fas fa-lock"></i>
                                                </button>
                                            @else
                                                <button
                                                    type="button"
                                                    @click="openDeleteModal(@js(['id' => $service->id, 'name' => $service->name]))"
                                                    class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                                    aria-label="Delete {{ $service->name }}"
                                                >
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                                        <div class="flex items-center space-x-4">
                                            <div
                                                class="text-center"
                                                x-data="inlinePriceEditor({{ $service->id }}, {{ $service->price }})"
                                            >
                                                <template x-if="!editing">
                                                    <button type="button" @click="startEditing()" class="group flex items-center gap-1.5 rounded-lg px-1 -mx-1 transition hover:bg-olive/10" title="Click to edit price">
                                                        <p class="text-2xl font-bold text-olive">€<span x-text="value.toFixed(2)"></span></p>
                                                        <i class="fas fa-pen text-[10px] text-olive/50 group-hover:text-olive"></i>
                                                    </button>
                                                </template>
                                                <template x-if="editing">
                                                    <div class="flex items-center gap-1">
                                                        <span class="text-lg font-bold text-olive">€</span>
                                                        <input
                                                            type="number"
                                                            step="0.01"
                                                            min="0"
                                                            x-model="draft"
                                                            x-ref="input"
                                                            @keydown.enter="save()"
                                                            @keydown.escape="cancel()"
                                                            class="w-20 rounded-lg border border-olive/30 px-2 py-1 text-lg font-bold text-olive focus:border-olive focus:outline-none focus:ring-1 focus:ring-olive"
                                                        >
                                                        <button type="button" @click="save()" class="flex h-7 w-7 items-center justify-center rounded-lg bg-olive text-white hover:bg-green-700" aria-label="Save price">
                                                            <i class="fas fa-check text-xs"></i>
                                                        </button>
                                                        <button type="button" @click="cancel()" class="flex h-7 w-7 items-center justify-center rounded-lg bg-gray-200 text-gray-600 hover:bg-gray-300" aria-label="Cancel">
                                                            <i class="fas fa-times text-xs"></i>
                                                        </button>
                                                    </div>
                                                </template>
                                                <p class="mt-1 text-xs text-gray-500">Price</p>
                                            </div>
                                            <div class="text-center">
                                                <p class="text-2xl font-bold text-green-700">{{ $service->duration }}</p>
                                                <p class="text-xs text-gray-500">Minutes</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endforeach
        @endif

        <!-- Back to Dashboard -->
        <div class="mt-4 text-center">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-olive-500 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Create / Edit modal -->
    <div
        x-show="modalOpen"
        x-cloak
        class="fixed inset-0 z-[70] flex items-end justify-center bg-black/50 md:items-center md:px-4 md:py-8"
        @keydown.escape.window="closeModal()"
    >
        <div
            x-show="modalOpen"
            @click.outside="closeModal()"
            x-transition
            class="max-h-[92vh] w-full overflow-y-auto rounded-t-[1.75rem] bg-white p-6 shadow-2xl md:max-w-lg md:rounded-[1.75rem]"
        >
            <div class="mb-4 flex items-start justify-between gap-3">
                <h3 class="text-2xl font-semibold text-gray-900" x-text="isEditing ? 'Edit service' : 'Add service'"></h3>
                <button type="button" @click="closeModal()" class="flex h-9 w-9 items-center justify-center rounded-full text-gray-400 transition hover:bg-stone-100 hover:text-gray-700" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form @submit.prevent="submitForm()" class="space-y-4">
                <div>
                    <label class="mb-1 block text-sm font-semibold text-gray-700" for="service-name">Name</label>
                    <input
                        id="service-name"
                        type="text"
                        x-model="form.name"
                        placeholder="e.g. BIAB - Overlay"
                        class="w-full rounded-xl border px-3 py-2.5 text-base focus:outline-none focus:ring-1"
                        :class="errors.name ? 'border-red-400 focus:border-red-400 focus:ring-red-400' : 'border-stone-200 focus:border-olive focus:ring-olive'"
                    >
                    <p x-show="errors.name" x-text="errors.name && errors.name[0]" class="mt-1 text-xs font-semibold text-red-500"></p>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold text-gray-700" for="service-description">Description</label>
                    <textarea
                        id="service-description"
                        x-model="form.description"
                        rows="3"
                        placeholder="Short description shown to clients (optional)"
                        class="w-full rounded-xl border px-3 py-2.5 text-base focus:outline-none focus:ring-1"
                        :class="errors.description ? 'border-red-400 focus:border-red-400 focus:ring-red-400' : 'border-stone-200 focus:border-olive focus:ring-olive'"
                    ></textarea>
                    <p x-show="errors.description" x-text="errors.description && errors.description[0]" class="mt-1 text-xs font-semibold text-red-500"></p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700" for="service-price">Price (€)</label>
                        <input
                            id="service-price"
                            type="number"
                            step="0.01"
                            min="0"
                            x-model.number="form.price"
                            class="w-full rounded-xl border px-3 py-2.5 text-base focus:outline-none focus:ring-1"
                            :class="errors.price ? 'border-red-400 focus:border-red-400 focus:ring-red-400' : 'border-stone-200 focus:border-olive focus:ring-olive'"
                        >
                        <p x-show="errors.price" x-text="errors.price && errors.price[0]" class="mt-1 text-xs font-semibold text-red-500"></p>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700" for="service-duration">Duration (min)</label>
                        <input
                            id="service-duration"
                            type="number"
                            step="5"
                            min="5"
                            max="600"
                            x-model.number="form.duration"
                            class="w-full rounded-xl border px-3 py-2.5 text-base focus:outline-none focus:ring-1"
                            :class="errors.duration ? 'border-red-400 focus:border-red-400 focus:ring-red-400' : 'border-stone-200 focus:border-olive focus:ring-olive'"
                        >
                        <p x-show="errors.duration" x-text="errors.duration && errors.duration[0]" class="mt-1 text-xs font-semibold text-red-500"></p>
                    </div>
                </div>

                <div>
                    <p class="mb-1 text-xs font-semibold uppercase tracking-wide text-gray-400">Quick pick</p>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="minutes in [30, 45, 60, 90, 120]" :key="minutes">
                            <button
                                type="button"
                                @click="form.duration = minutes"
                                class="rounded-full border px-3 py-1.5 text-xs font-semibold transition"
                                :class="form.duration === minutes ? 'border-olive bg-olive text-white' : 'border-stone-200 text-gray-600 hover:border-olive hover:text-olive'"
                                x-text="minutes + ' min'"
                            ></button>
                        </template>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button
                        type="button"
                        @click="closeModal()"
                        class="rounded-2xl border border-stone-200 px-5 py-2.5 font-semibold text-gray-700 transition hover:border-olive hover:text-olive"
                    >Cancel</button>
                    <button
                        type="submit"
                        :disabled="saving"
                        class="rounded-2xl bg-olive px-5 py-2.5 font-semibold text-white shadow-lg transition hover:bg-green-700 disabled:cursor-not-allowed disabled:opacity-60"
                    >
                        <span x-show="!saving" x-text="isEditing ? 'Save changes' : 'Create service'"></span>
                        <span x-show="saving"><i class="fas fa-spinner fa-spin mr-1"></i> Saving...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete confirmation modal -->
    <div
        x-show="deleteOpen"
        x-cloak
        class="fixed inset-0 z-[70] flex items-end justify-center bg-black/50 md:items-center md:px-4 md:py-8"
        @keydown.escape.window="closeDeleteModal()"
    >
        <div
            x-show="deleteOpen"
            @click.outside="closeDeleteModal()"
            x-transition
            class="w-full overflow-y-auto rounded-t-[1.75rem] bg-white p-6 shadow-2xl md:max-w-md md:rounded-[1.75rem]"
        >
            <div class="mb-2 flex items-start justify-between gap-3">
                <h3 class="text-xl font-semibold text-gray-900">Delete service?</h3>
                <button type="button" @click="closeDeleteModal()" class="flex h-9 w-9 items-center justify-center rounded-full text-gray-400 transition hover:bg-stone-100 hover:text-gray-700" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <p class="mb-6 text-sm text-gray-600">
                Delete <strong x-text="serviceToDelete?.name"></strong>? This can't be undone.
            </p>
            <div class="flex justify-end gap-3">
                <button
                    type="button"
                    @click="closeDeleteModal()"
                    class="rounded-2xl border border-stone-200 px-5 py-2.5 font-semibold text-gray-700 transition hover:border-olive hover:text-olive"
                >Cancel</button>
                <button
                    type="button"
                    @click="confirmDelete()"
                    :disabled="deleting"
                    class="rounded-2xl bg-red-600 px-5 py-2.5 font-semibold text-white shadow-lg transition hover:bg-red-700 disabled:cursor-not-allowed disabled:opacity-60"
                >
                    <span x-show="!deleting">Delete</span>
                    <span x-show="deleting"><i class="fas fa-spinner fa-spin mr-1"></i> Deleting...</span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function inlinePriceEditor(serviceId, initialPrice) {
        return {
            value: Number(initialPrice),
            draft: Number(initialPrice).toFixed(2),
            editing: false,

            startEditing() {
                this.draft = this.value.toFixed(2);
                this.editing = true;
                this.$nextTick(() => this.$refs.input?.focus());
            },

            cancel() {
                this.editing = false;
            },

            async save() {
                const price = Number(this.draft);
                if (Number.isNaN(price) || price < 0) {
                    toastr.error('Enter a valid price.');
                    return;
                }

                try {
                    await axios.patch(`/admin/services/${serviceId}/price`, { price });
                    this.value = price;
                    this.editing = false;
                    toastr.success('Price updated.');
                } catch (error) {
                    toastr.error('Could not update the price.');
                }
            },
        };
    }

    function servicesManager() {
        return {
            modalOpen: false,
            deleteOpen: false,
            isEditing: false,
            saving: false,
            deleting: false,
            errors: {},
            form: { id: null, name: '', description: '', price: '', duration: 60 },
            serviceToDelete: null,

            emptyForm() {
                return { id: null, name: '', description: '', price: '', duration: 60 };
            },

            openCreateModal() {
                this.isEditing = false;
                this.errors = {};
                this.form = this.emptyForm();
                this.modalOpen = true;
            },

            openEditModal(service) {
                this.isEditing = true;
                this.errors = {};
                this.form = {
                    id: service.id,
                    name: service.name,
                    description: service.description,
                    price: Number(service.price),
                    duration: Number(service.duration),
                };
                this.modalOpen = true;
            },

            closeModal() {
                if (this.saving) return;
                this.modalOpen = false;
            },

            async submitForm() {
                this.saving = true;
                this.errors = {};

                try {
                    if (this.isEditing) {
                        await axios.put(`/admin/services/${this.form.id}`, this.form);
                        toastr.success('Service updated.');
                    } else {
                        await axios.post('/admin/services', this.form);
                        toastr.success('Service created.');
                    }

                    window.location.reload();
                } catch (error) {
                    if (error.response && error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                        toastr.error('Please check the highlighted fields.');
                    } else {
                        toastr.error(error.response?.data?.message || 'Could not save this service.');
                    }
                    this.saving = false;
                }
            },

            openDeleteModal(service) {
                this.serviceToDelete = service;
                this.deleteOpen = true;
            },

            closeDeleteModal() {
                if (this.deleting) return;
                this.deleteOpen = false;
                this.serviceToDelete = null;
            },

            async confirmDelete() {
                if (!this.serviceToDelete) return;
                this.deleting = true;

                try {
                    await axios.delete(`/admin/services/${this.serviceToDelete.id}`);
                    toastr.success('Service deleted.');
                    window.location.reload();
                } catch (error) {
                    toastr.error(error.response?.data?.message || 'Could not delete this service.');
                    this.deleting = false;
                    this.deleteOpen = false;
                    this.serviceToDelete = null;
                }
            },
        };
    }
</script>
@endpush
@endsection
