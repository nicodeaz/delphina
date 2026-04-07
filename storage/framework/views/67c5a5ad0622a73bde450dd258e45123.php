

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-nude via-white to-olive/10 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-light text-gray-900 mb-2">Services Management</h1>
                <p class="text-xl text-gray-600">Manage your nail services</p>
            </div>
            <a href="<?php echo e(route('admin.services.create')); ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-olive to-green-700 hover:from-olive/90 hover:to-green-700/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-olive-500 transition-all transform hover:scale-105 shadow-lg">
                <i class="fas fa-plus mr-2"></i>
                Add New Service
            </a>
        </div>

        <!-- Services Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__empty_1 = true; $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2"><?php echo e($service->name); ?></h3>
                            <p class="text-gray-600 text-sm leading-relaxed"><?php echo e(Str::limit($service->description, 100)); ?></p>
                        </div>
                        <div class="flex space-x-2 ml-4">
                            <a href="<?php echo e(route('admin.services.edit', $service)); ?>" class="p-2 text-olive hover:bg-olive/10 rounded-lg transition-colors">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?php echo e(route('admin.services.destroy', $service)); ?>" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this service?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                        <div class="flex items-center space-x-4">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-olive">€<?php echo e(number_format($service->price, 2)); ?></p>
                                <p class="text-xs text-gray-500">Price</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-green-700"><?php echo e($service->duration); ?></p>
                                <p class="text-xs text-gray-500">Minutes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full text-center py-12">
                <div class="text-6xl mb-4">💅</div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No services yet</h3>
                <p class="text-gray-600 mb-6">Create your first service to get started</p>
                <a href="<?php echo e(route('admin.services.create')); ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-olive to-green-700 hover:from-olive/90 hover:to-green-700/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-olive-500 transition-all transform hover:scale-105 shadow-lg">
                    <i class="fas fa-plus mr-2"></i>
                    Create First Service
                </a>
            </div>
            <?php endif; ?>
        </div>

        <!-- Back to Dashboard -->
        <div class="mt-8 text-center">
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-olive-500 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Dashboard
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\delphina\resources\views/admin/services/index.blade.php ENDPATH**/ ?>