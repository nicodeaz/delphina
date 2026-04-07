

<?php $__env->startSection('title', 'Edit Available Date'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-beige-50 via-white to-olive-50 py-10">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-[2rem] bg-white p-6 shadow-xl ring-1 ring-stone-100 md:p-8">
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-olive">Admin availability</p>
            <h1 class="mt-3 text-3xl font-semibold text-gray-900">Edit available date</h1>
            <p class="mt-2 text-sm text-gray-500">Adjust the active schedule for this day, update notes, or shorten the open window.</p>

            <form method="POST" action="<?php echo e(route('admin.available-dates.update', $availableDate)); ?>" class="mt-8 space-y-6">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div>
                    <label for="date" class="mb-2 block text-sm font-medium text-gray-700">Date</label>
                    <input type="date" name="date" id="date" value="<?php echo e(old('date', $availableDate->date->format('Y-m-d'))); ?>"
                           class="w-full rounded-2xl border border-stone-300 px-4 py-3 focus:border-olive focus:outline-none focus:ring-2 focus:ring-olive/20 <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           required>
                    <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label for="start_time" class="mb-2 block text-sm font-medium text-gray-700">Start time</label>
                        <input type="time" name="start_time" id="start_time" value="<?php echo e(old('start_time', $availableDate->start_time)); ?>"
                               class="w-full rounded-2xl border border-stone-300 px-4 py-3 focus:border-olive focus:outline-none focus:ring-2 focus:ring-olive/20 <?php $__errorArgs = ['start_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               required>
                        <?php $__errorArgs = ['start_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="end_time" class="mb-2 block text-sm font-medium text-gray-700">End time</label>
                        <input type="time" name="end_time" id="end_time" value="<?php echo e(old('end_time', $availableDate->end_time)); ?>"
                               class="w-full rounded-2xl border border-stone-300 px-4 py-3 focus:border-olive focus:outline-none focus:ring-2 focus:ring-olive/20 <?php $__errorArgs = ['end_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               required>
                        <?php $__errorArgs = ['end_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div>
                    <label for="notes" class="mb-2 block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" id="notes" rows="4"
                              class="w-full rounded-2xl border border-stone-300 px-4 py-3 focus:border-olive focus:outline-none focus:ring-2 focus:ring-olive/20 <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                              placeholder="Special opening notes, breaks, or comments for this day..."><?php echo e(old('notes', $availableDate->notes)); ?></textarea>
                    <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:justify-between">
                    <a href="<?php echo e(route('admin.available-dates.index', ['month' => $availableDate->date->format('Y-m'), 'date' => $availableDate->date->format('Y-m-d')])); ?>" class="inline-flex items-center justify-center rounded-2xl border border-stone-200 px-5 py-3 font-semibold text-gray-700 transition hover:border-olive hover:text-olive">Cancel</a>
                    <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-olive px-5 py-3 font-semibold text-white shadow-lg transition hover:bg-green-700">
                        <i class="fas fa-save mr-2"></i>
                        Update availability
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\delphina\resources\views/admin/available-dates/edit.blade.php ENDPATH**/ ?>