{{--
    Booking detail/edit modal + confirmation dialog shared by the Agenda and
    the Bookings list. The host Alpine component merges bookingManager(config)
    into its own state (config needs: deposit, revolutLink).
--}}
    {{-- ============ Booking details / edit ============ --}}
    <x-sheet show="detail.open" close="closeBooking()" label="Booking">
        <template x-if="detail.item">
            <div class="p-6 pt-3 md:pt-6">
                <div class="mb-4 flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.25em] text-olive" x-text="formatDate(detail.item.date, true) + ' · ' + detail.item.time"></p>
                        <h3 class="mt-1 text-2xl font-semibold text-gray-900" x-text="detail.item.customer"></h3>
                        <span class="mt-1 inline-block rounded-full px-2.5 py-0.5 text-xs font-semibold" :class="statusClass(detail.item.status)" x-text="statusLabels[detail.item.status] || detail.item.status"></span>
                    </div>
                    <button type="button" @click="closeBooking()" class="flex h-9 w-9 items-center justify-center rounded-full text-gray-400 hover:bg-stone-100" aria-label="Close"><i class="fas fa-times"></i></button>
                </div>

                {{-- View mode --}}
                <div x-show="!detail.editing">
                    <div class="flex flex-wrap gap-2">
                        <a x-show="detail.item.phone" :href="whatsappLink(detail.item)" target="_blank" rel="noopener" class="inline-flex items-center rounded-full bg-green-50 px-3 py-2 text-xs font-semibold text-green-700"><i class="fab fa-whatsapp mr-1.5"></i>WhatsApp</a>
                        <a x-show="detail.item.phone" :href="'tel:' + detail.item.phone" class="inline-flex items-center rounded-full bg-stone-100 px-3 py-2 text-xs font-semibold text-gray-700"><i class="fas fa-phone mr-1.5"></i><span x-text="detail.item.phone"></span></a>
                        <a x-show="detail.item.email" :href="'mailto:' + detail.item.email" class="inline-flex max-w-full items-center rounded-full bg-stone-100 px-3 py-2 text-xs font-semibold text-gray-700"><i class="fas fa-envelope mr-1.5"></i><span class="truncate" x-text="detail.item.email"></span></a>
                    </div>
                    <p x-show="detail.item.notes" class="mt-3 rounded-2xl bg-amber-50/60 px-4 py-3 text-sm text-gray-700"><i class="fas fa-sticky-note mr-2 text-amber-500"></i><span x-text="detail.item.notes"></span></p>

                    <div class="mt-4 rounded-2xl bg-stone-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-400">Services · <span x-text="detail.item.duration"></span> min</p>
                        <p class="mt-1 text-sm font-medium text-gray-900" x-text="detail.item.services"></p>
                        <div class="mt-2 flex items-baseline justify-between">
                            <p class="text-lg font-bold text-olive">€<span x-text="Number(detail.item.total_price).toFixed(2)"></span></p>
                            <p class="text-xs text-gray-500" x-show="detail.item.payment_status === 'paid' && detail.item.balance_status !== 'paid'">To collect: €<span x-text="Math.max(0, detail.item.total_price - deposit).toFixed(2)"></span></p>
                        </div>
                    </div>

                    <div class="mt-3 grid grid-cols-2 gap-3">
                        <div class="rounded-2xl border p-3" :class="detail.item.payment_status === 'paid' ? 'border-green-200 bg-green-50' : 'border-amber-200 bg-amber-50'">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.15em]" :class="detail.item.payment_status === 'paid' ? 'text-green-700' : 'text-amber-700'">Deposit €15</p>
                            <p class="mt-1 text-sm font-bold" :class="detail.item.payment_status === 'paid' ? 'text-green-800' : 'text-amber-800'" x-text="detail.item.payment_status === 'paid' ? 'Paid' : 'Pending'"></p>
                            <button type="button" x-show="detail.item.payment_status !== 'paid' && detail.item.payment_id" @click="confirmDeposit(detail.item)" class="mt-2 w-full rounded-lg bg-amber-600 px-2 py-2 text-xs font-bold text-white hover:bg-amber-700">Confirm deposit</button>
                        </div>
                        <div class="rounded-2xl border p-3" :class="detail.item.balance_status === 'paid' ? 'border-emerald-200 bg-emerald-50' : 'border-stone-200 bg-stone-50'">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.15em]" :class="detail.item.balance_status === 'paid' ? 'text-emerald-700' : 'text-gray-500'">Rest of payment</p>
                            <p class="mt-1 text-sm font-bold" :class="detail.item.balance_status === 'paid' ? 'text-emerald-800' : 'text-gray-700'" x-text="detail.item.balance_status === 'paid' ? 'Paid in full' : 'Not paid yet'"></p>
                            <button type="button" @click="toggleBalance()" class="mt-2 w-full rounded-lg px-2 py-2 text-xs font-bold text-white" :class="detail.item.balance_status === 'paid' ? 'bg-gray-400 hover:bg-gray-500' : 'bg-emerald-600 hover:bg-emerald-700'" x-text="detail.item.balance_status === 'paid' ? 'Mark as unpaid' : 'Mark as paid'"></button>
                        </div>
                    </div>

                    <p class="mb-2 mt-5 text-xs font-semibold uppercase tracking-[0.2em] text-gray-400">Status</p>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="option in ['pending', 'approved', 'completed']" :key="option">
                            <button type="button" @click="setStatus(option)" class="rounded-full border px-3 py-2 text-xs font-semibold transition" :class="detail.item.status === option ? 'border-olive bg-olive text-white' : 'border-stone-200 text-gray-600 hover:border-olive'" x-text="statusLabels[option]"></button>
                        </template>
                    </div>

                    <div class="mt-6 grid grid-cols-3 gap-2 border-t border-stone-100 pt-5">
                        <button type="button" @click="startEdit()" class="rounded-2xl border border-stone-200 px-2 py-3 text-xs font-semibold text-gray-700 hover:border-olive hover:text-olive"><i class="fas fa-pen mb-1 block"></i>Edit / move</button>
                        <button type="button" x-show="detail.item.status !== 'cancelled'" @click="askCancel()" class="rounded-2xl border border-stone-200 px-2 py-3 text-xs font-semibold text-gray-700 hover:border-amber-400 hover:text-amber-700"><i class="fas fa-ban mb-1 block"></i>Cancel</button>
                        <button type="button" @click="askDelete()" class="rounded-2xl border border-stone-200 px-2 py-3 text-xs font-semibold text-red-600 hover:border-red-300 hover:bg-red-50"><i class="fas fa-trash mb-1 block"></i>Delete</button>
                    </div>
                </div>

                {{-- Edit mode --}}
                <form x-show="detail.editing" @submit.prevent="saveEdit()" class="space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700" for="edit_date">Day</label>
                            <input id="edit_date" type="date" x-model="detail.form.date" required class="agenda-input">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700" for="edit_time">Time</label>
                            <input id="edit_time" type="time" step="900" x-model="detail.form.time" required class="agenda-input">
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700" for="edit_name">Client name</label>
                        <input id="edit_name" type="text" x-model="detail.form.name" required class="agenda-input">
                        <p class="agenda-error" x-text="detail.errors.name?.[0]"></p>
                    </div>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700" for="edit_phone">Phone</label>
                            <input id="edit_phone" type="tel" x-model="detail.form.phone" class="agenda-input">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700" for="edit_email">Email</label>
                            <input id="edit_email" type="email" x-model="detail.form.email" class="agenda-input">
                            <p class="agenda-error" x-text="detail.errors.email?.[0]"></p>
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700" for="edit_notes">Notes</label>
                        <textarea id="edit_notes" rows="2" x-model="detail.form.notes" class="agenda-input"></textarea>
                    </div>
                    <div x-show="detail.conflict" class="rounded-2xl border border-amber-200 bg-amber-50 p-3 text-sm text-amber-900">
                        <p x-text="detail.conflict"></p>
                        <button type="button" @click="saveEdit(true)" class="mt-2 text-xs font-bold underline">Save anyway</button>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="detail.editing = false" class="flex-1 rounded-2xl border border-stone-200 px-4 py-3 font-semibold text-gray-700">Back</button>
                        <button type="submit" :disabled="detail.saving" class="flex-1 rounded-2xl bg-olive px-4 py-3 font-semibold text-white shadow-lg hover:bg-green-700 disabled:opacity-60" x-text="detail.saving ? 'Saving…' : 'Save changes'"></button>
                    </div>
                </form>
            </div>
        </template>
    </x-sheet>


    {{-- ============ Confirmation dialog ============ --}}
    <x-sheet show="confirmBox.open" close="confirmBox.open = false" max-width="md:max-w-sm" label="Confirm">
        <div class="p-6 pt-3 text-center md:pt-6">
            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full" :class="confirmBox.danger ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-600'">
                <i class="fas" :class="confirmBox.danger ? 'fa-trash' : 'fa-exclamation'"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900" x-text="confirmBox.title"></h3>
            <p class="mt-2 text-sm text-gray-600" x-text="confirmBox.message"></p>
            <div class="mt-6 flex gap-3">
                <button type="button" @click="confirmBox.open = false" class="flex-1 rounded-2xl border border-stone-200 px-4 py-3 font-semibold text-gray-700">Keep it</button>
                <button type="button" @click="runConfirm()" :disabled="confirmBox.busy" class="flex-1 rounded-2xl px-4 py-3 font-semibold text-white disabled:opacity-60" :class="confirmBox.danger ? 'bg-red-600 hover:bg-red-700' : 'bg-amber-600 hover:bg-amber-700'" x-text="confirmBox.busy ? 'Working…' : confirmBox.cta"></button>
            </div>
        </div>
    </x-sheet>
</div>

@once
@push('scripts')
<script>
    function bookingManager(config) {
        return {
            deposit: Number(config.deposit),
            revolutLink: config.revolutLink,
            statusLabels: { pending: 'Awaiting deposit', approved: 'Confirmed', completed: 'Done', rejected: 'Rejected', cancelled: 'Cancelled' },
            detail: { open: false, item: null, editing: false, form: { name: '', phone: '', email: '', date: '', time: '', notes: '' }, errors: {}, conflict: '', saving: false },
            confirmBox: { open: false, title: '', message: '', cta: '', danger: true, busy: false, action: null },

            // ---------- helpers ----------
            formatDate(dateString, long = false) {
                if (!dateString) return '';
                const options = long ? { weekday: 'long', day: 'numeric', month: 'long' } : { weekday: 'short', day: 'numeric', month: 'short' };
                return new Intl.DateTimeFormat('en-IE', options).format(new Date(dateString + 'T12:00:00'));
            },
            chipClass(item) {
                if (item.status === 'completed') return 'border-sky-500 bg-sky-50 text-sky-900';
                return item.payment_status === 'paid' ? 'border-green-600 bg-green-50 text-green-900' : 'border-amber-500 bg-amber-50 text-amber-900';
            },
            statusClass(status) {
                return {
                    pending: 'bg-amber-100 text-amber-800',
                    approved: 'bg-green-100 text-green-800',
                    completed: 'bg-sky-100 text-sky-800',
                }[status] || 'bg-stone-100 text-gray-700';
            },
            depositLabel(item) {
                if (item.balance_status === 'paid') return 'Paid in full';
                return item.payment_status === 'paid' ? 'Deposit paid' : 'Deposit pending';
            },
            whatsappLink(item, reminder = false) {
                const raw = (item.phone || '').trim();
                let digits = raw.replace(/\D/g, '');
                if (!raw.startsWith('+')) {
                    if (digits.startsWith('00')) digits = digits.slice(2);
                    else if (digits.startsWith('0')) digits = '353' + digits.slice(1);
                }
                const firstName = (item.name || item.customer || '').split(' ')[0];
                const text = reminder
                    ? `Hi ${firstName}! It's Delfi from Nails by Delphina 💅 To confirm your appointment on ${this.formatDate(item.date, true)} at ${item.time}, please send the €15 deposit here: ${this.revolutLink || ''} Thank you!`
                    : `Hi ${firstName}! It's Delfi from Nails by Delphina 💅 `;
                return `https://wa.me/${digits}?text=${encodeURIComponent(text)}`;
            },
            errorMessage(error, fallback) {
                return error.response?.data?.message || fallback;
            },
            reload(message) {
                if (message) sessionStorage.setItem('agendaToast', message);
                window.location.reload();
            },
            ask(title, message, cta, action, danger = true) {
                this.confirmBox = { open: true, title, message, cta, danger, busy: false, action };
            },
            async runConfirm() {
                if (!this.confirmBox.action) return;
                this.confirmBox.busy = true;
                try {
                    await this.confirmBox.action();
                } finally {
                    this.confirmBox.busy = false;
                }
            },

            // ---------- booking details ----------
            openBooking(item) {
                this.detail = { ...this.detail, open: true, item: { ...item }, editing: false, errors: {}, conflict: '', saving: false };
            },
            closeBooking() {
                this.detail.open = false;
            },
            startEdit() {
                const i = this.detail.item;
                this.detail.form = { name: i.name || i.customer, phone: i.phone || '', email: i.email || '', date: i.date, time: i.time, notes: i.notes || '' };
                this.detail.errors = {};
                this.detail.conflict = '';
                this.detail.editing = true;
            },
            async saveEdit(force = false) {
                this.detail.saving = true;
                this.detail.errors = {};
                try {
                    await axios.put(`/admin/bookings/${this.detail.item.id}`, { ...this.detail.form, force });
                    this.reload('Booking updated.');
                } catch (error) {
                    this.detail.saving = false;
                    if (error.response?.status === 409) {
                        this.detail.conflict = error.response.data.message;
                        return;
                    }
                    this.detail.errors = error.response?.data?.errors || {};
                    toastr.error(this.errorMessage(error, 'Could not save the booking.'));
                }
            },
            async setStatus(status) {
                if (!this.detail.item || this.detail.item.status === status) return;
                try {
                    await axios.patch(`/admin/appointments/${this.detail.item.id}/status`, { status });
                    this.reload('Status updated.');
                } catch (error) {
                    toastr.error('Could not update the status.');
                }
            },
            async confirmDeposit(item) {
                if (!item?.payment_id) return;
                try {
                    await axios.patch(`/admin/payments/${item.payment_id}/confirm`);
                    this.reload('Deposit confirmed — the client gets a confirmation email.');
                } catch (error) {
                    toastr.error('Could not confirm the deposit.');
                }
            },
            async toggleBalance() {
                const item = this.detail.item;
                const next = item.balance_status === 'paid' ? 'unpaid' : 'paid';
                try {
                    await axios.patch(`/admin/appointments/${item.id}/balance`, { balance_status: next });
                    this.reload(next === 'paid' ? 'Marked as paid in full.' : 'Marked as not paid.');
                } catch (error) {
                    toastr.error('Could not update the payment.');
                }
            },
            askCancel() {
                const item = this.detail.item;
                this.detail.open = false;
                this.ask('Cancel this appointment?', `${item.customer} · ${this.formatDate(item.date, true)} at ${item.time}. The time becomes free for other clients.`, 'Cancel appointment', async () => {
                    try {
                        await axios.patch(`/admin/appointments/${item.id}/cancel`);
                        this.reload('Appointment cancelled.');
                    } catch (error) {
                        toastr.error(this.errorMessage(error, 'Could not cancel this appointment.'));
                    }
                }, false);
            },
            askDelete() {
                const item = this.detail.item;
                this.detail.open = false;
                this.ask('Delete this booking?', 'It will be removed completely, including its deposit record. Use “Cancel” instead if you want to keep a record.', 'Delete', async () => {
                    try {
                        await axios.delete(`/admin/bookings/${item.id}`);
                        this.reload('Booking deleted.');
                    } catch (error) {
                        toastr.error(this.errorMessage(error, 'Could not delete this booking.'));
                    }
                });
            },
        };
    }

    document.addEventListener('DOMContentLoaded', () => {
        try {
            const message = sessionStorage.getItem('agendaToast');
            if (message) {
                sessionStorage.removeItem('agendaToast');
                toastr.success(message);
            }
        } catch (e) {}
    });
</script>
@endpush
@endonce
