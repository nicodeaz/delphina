

<?php $__env->startSection('title', 'Admin - Appointments - Delfi Nail Technician'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-serif font-bold text-gray-900">Appointments Management</h1>
            <p class="mt-2 text-gray-600">Manage all appointments and their statuses</p>
        </div>

        <!-- Appointments Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">All Appointments</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <?php if($appointment->user): ?>
                                        <?php echo e($appointment->user->name); ?>

                                    <?php else: ?>
                                        <?php echo e($appointment->name); ?>

                                    <?php endif; ?>
                                </div>
                                <div class="text-sm text-gray-500">
                                    <?php if($appointment->user): ?>
                                        <?php echo e($appointment->user->email); ?>

                                    <?php else: ?>
                                        <?php echo e($appointment->email); ?>

                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?php echo e($appointment->service->name); ?></div>
                                <div class="text-sm text-gray-500">€<?php echo e($appointment->service->price); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?php echo e($appointment->date->format('M j, Y')); ?></div>
                                <div class="text-sm text-gray-500"><?php echo e($appointment->time); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    <?php if($appointment->status === 'pending'): ?> bg-yellow-100 text-yellow-800
                                    <?php elseif($appointment->status === 'approved'): ?> bg-green-100 text-green-800
                                    <?php elseif($appointment->status === 'rejected'): ?> bg-red-100 text-red-800
                                    <?php else: ?> bg-gray-100 text-gray-800 <?php endif; ?>">
                                    <?php echo e(ucfirst($appointment->status)); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if($appointment->payment): ?>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        <?php if($appointment->payment->status === 'paid'): ?> bg-green-100 text-green-800
                                        <?php else: ?> bg-yellow-100 text-yellow-800 <?php endif; ?>">
                                        <?php echo e(ucfirst($appointment->payment->status)); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="text-sm text-gray-500">No payment</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <?php if($appointment->status === 'pending'): ?>
                                    <form method="POST" action="<?php echo e(route('admin.appointments.updateStatus', $appointment->id)); ?>" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="text-green-600 hover:text-green-900 mr-3">Approve</button>
                                    </form>
                                    <form method="POST" action="<?php echo e(route('admin.appointments.updateStatus', $appointment->id)); ?>" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="text-red-600 hover:text-red-900">Reject</button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-gray-500"><?php echo e(ucfirst($appointment->status)); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No appointments found.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if($appointments->hasPages()): ?>
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                <?php echo e($appointments->links()); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\delphina\resources\views/admin/appointments.blade.php ENDPATH**/ ?>