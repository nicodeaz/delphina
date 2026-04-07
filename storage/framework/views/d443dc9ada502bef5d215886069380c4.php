

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-nude via-white to-gray-50 py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-16">
            <h1 class="text-5xl md:text-6xl font-serif font-bold text-gray-900 mb-4">Appointment Confirmed!</h1>
            <div class="flex justify-center mb-6">
                <div class="h-1 w-24 bg-gradient-to-r from-olive to-green-700 rounded"></div>
            </div>
            <p class="text-xl text-gray-600 font-light">
                Your appointment has been successfully booked.
            </p>
        </div>

        <!-- Appointment Details -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
            <h2 class="text-2xl font-serif font-bold text-gray-900 mb-6 text-center">Appointment Details</h2>

            <div class="grid md:grid-cols-2 gap-8">
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-600">Service:</span>
                        <span class="text-gray-900"><?php echo e($appointment->service->name); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-600">Date:</span>
                        <span class="text-gray-900"><?php echo e($appointment->date->format('l, F j, Y')); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-600">Time:</span>
                        <span class="text-gray-900"><?php echo e($appointment->time); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-600">Duration:</span>
                        <span class="text-gray-900"><?php echo e($appointment->service->duration); ?> minutes</span>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-600">Client:</span>
                        <span class="text-gray-900"><?php echo e($appointment->name); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-600">Email:</span>
                        <span class="text-gray-900"><?php echo e($appointment->email); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-600">Phone:</span>
                        <span class="text-gray-900"><?php echo e($appointment->phone); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-600">Location:</span>
                        <span class="text-gray-900">Dublin 24, Tallaght</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirmation Information -->
        <div class="bg-gradient-to-r from-olive to-green-700 rounded-2xl p-8 text-white text-center">
            <h2 class="text-2xl font-serif font-bold mb-4">✅ Booking Confirmed</h2>
            <p class="text-lg mb-6 text-white/90">
                Your appointment is recorded in our backend calendar and will be managed directly by our team.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <p class="text-sm text-white/80 mt-2">
                    If you want, you can add the event manually to your personal calendar.
                </p>
            </div>
        </div>

        <!-- What to Expect -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mt-8">
            <h2 class="text-2xl font-serif font-bold text-gray-900 mb-6 text-center">What to Expect</h2>

            <div class="grid md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-olive/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-clock text-olive text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Arrive 5-10 min early</h3>
                    <p class="text-gray-600 text-sm">Please arrive a few minutes before your appointment time.</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-olive/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-mobile-alt text-olive text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Keep your phone on</h3>
                    <p class="text-gray-600 text-sm">We'll contact you if there are any changes to your appointment.</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-olive/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-heart text-olive text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Relax and enjoy</h3>
                    <p class="text-gray-600 text-sm">Come prepared for a relaxing and professional experience.</p>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="text-center mt-12">
            <p class="text-gray-600 mb-4">
                Questions? Contact us at <a href="tel:+353123456789" class="text-olive hover:text-green-700 font-medium">+353 123 456 789</a>
            </p>
            <a href="<?php echo e(route('home')); ?>" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-olive to-green-700 text-white rounded-full font-bold hover:shadow-xl transition-all">
                <i class="fas fa-home mr-2"></i> Back to Home
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\delphina\resources\views/emails/appointment-confirmation.blade.php ENDPATH**/ ?>