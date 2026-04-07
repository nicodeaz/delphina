<?php
    $selectorId = $selectorId ?? 'serviceSelector';
    $inputName = $inputName ?? 'service_id';
    $selectionMode = $selectionMode ?? 'single'; // single|multiple
    $selectedServiceId = (int) ($selectedServiceId ?? 0);
    $selectedServiceIds = collect($selectedServiceIds ?? [])->map(fn ($id) => (int) $id)->all();
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

    $resolveCategory = function ($service) {
        $name = strtolower((string) $service->name);
        $dbCategory = strtolower((string) ($service->category ?? ''));

        if (in_array($dbCategory, ['gel', 'biab', 'soft_gel', 'polish', 'addon', 'consultation'], true)) {
            return $dbCategory;
        }

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

    $groupedServices = $services
        ->map(function ($service) use ($resolveCategory) {
            $service->ui_category = $resolveCategory($service);
            return $service;
        })
        ->groupBy('ui_category');
?>

<div id="<?php echo e($selectorId); ?>" class="space-y-4">
    <?php $__currentLoopData = $groupedServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $hasSelected = $selectionMode === 'multiple'
                ? $group->contains(fn ($service) => in_array((int) $service->id, $selectedServiceIds, true))
                : $group->contains(fn ($service) => (int) $service->id === $selectedServiceId);
        ?>
        <div class="overflow-hidden rounded-[1.75rem] border border-stone-200 bg-stone-50/80 shadow-sm">
            <button type="button" class="js-service-category-toggle flex w-full items-center justify-between gap-4 px-5 py-5 text-left transition hover:bg-white/80 md:px-6">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-2xl shadow-sm">
                        <?php echo e($categoryIcons[$category] ?? '💅'); ?>

                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-900"><?php echo e($categoryLabels[$category] ?? ucwords(str_replace('_', ' ', $category))); ?></p>
                        <p class="text-sm text-gray-500"><?php echo e($group->count()); ?> option<?php echo e($group->count() > 1 ? 's' : ''); ?></p>
                    </div>
                </div>
                <svg class="h-5 w-5 shrink-0 text-gray-400 transition-transform <?php echo e($loop->first || $hasSelected ? 'rotate-180' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div class="<?php echo e($loop->first || $hasSelected ? '' : 'hidden'); ?> border-t border-stone-200 bg-white" data-category-panel>
                <?php $__currentLoopData = $group; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $displayName = str_contains($service->name, ' - ')
                            ? trim(explode(' - ', $service->name, 2)[1])
                            : $service->name;
                        $isSelected = $selectionMode === 'multiple'
                            ? in_array((int) $service->id, $selectedServiceIds, true)
                            : (int) $service->id === $selectedServiceId;
                    ?>
                    <label class="js-service-option flex cursor-pointer items-start gap-4 border-l-4 <?php echo e($isSelected ? 'border-l-olive bg-olive/5' : 'border-l-transparent'); ?> px-5 py-4 transition hover:bg-stone-50 md:px-6"
                           data-service-id="<?php echo e($service->id); ?>"
                           data-service-name="<?php echo e($service->name); ?>"
                           data-display-name="<?php echo e($displayName); ?>"
                           data-price="<?php echo e($service->price); ?>"
                           data-duration="<?php echo e($service->duration); ?>"
                           data-category="<?php echo e($category); ?>">
                           <input type="<?php echo e($selectionMode === 'multiple' ? 'checkbox' : 'radio'); ?>"
                               name="<?php echo e($selectionMode === 'multiple' ? $inputName . '[]' : $inputName); ?>"
                               value="<?php echo e($service->id); ?>"
                               class="js-service-input mt-1 h-5 w-5 border-stone-300 text-olive focus:ring-olive"
                               <?php echo e($isSelected ? 'checked' : ''); ?>>
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                                <div>
                                    <p class="text-base font-semibold text-gray-900"><?php echo e($displayName); ?></p>
                                    <?php if(!empty($service->description)): ?>
                                        <p class="mt-1 text-sm leading-relaxed text-gray-500"><?php echo e($service->description); ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="flex items-center gap-3 md:pl-4">
                                    <span class="rounded-full bg-stone-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-gray-500"><?php echo e($service->duration); ?> min</span>
                                    <span class="text-lg font-semibold text-olive">€<?php echo e(number_format($service->price, 2)); ?></span>
                                </div>
                            </div>
                        </div>
                    </label>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php /**PATH C:\xampp\htdocs\delphina\resources\views/partials/service-selector.blade.php ENDPATH**/ ?>