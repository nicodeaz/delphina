# 🚀 Quick Start Guide - Nail Studio Application

## ⚡ 5-Minute Setup

### 1. Database & Seed
```bash
cd c:\xampp\htdocs\delphina
php artisan migrate:fresh --seed
```

### 2. Start Laravel Server
```bash
php artisan serve
```

### 3. Access Application
```
http://localhost:8000
```

---

## 🔑 Test Credentials

### Admin Account
- **Email**: admin@example.com
- **Password**: password
- **Access**: `/admin/dashboard`

### Client Account  
- **Email**: client@example.com
- **Password**: password
- **Access**: All user features

---

## 📍 Key Pages

| Page | URL | Purpose |
|------|-----|---------|
| Homepage | `/` | Marketing page |
| Book Appointment | `/appointments` | Create booking |
| My Appointments | `/my-appointments` | View bookings |
| Profile | `/profile` | Edit user info |
| Admin Dashboard | `/admin/dashboard` | Manage bookings |

---

## 🎯 Key Features Quick Tour

### 1. **Homepage** (/)
- Beautiful hero section with trust indicators
- Services showcase (Manicure, Nail Art, Gel Extension)
- Why choose us section
- Gallery of work
- Call-to-action buttons

### 2. **Booking** (/appointments)
- Select service
- Choose date (next 21 days)
- Pick time slot
- Submit booking
- **Requires login**

### 3. **My Appointments** (/my-appointments)
- View all booked appointments
- See appointment status (pending/approved/cancelled)
- Cancel future appointments
- Check payment status
- Pay deposit (€15)

### 4. **Payment** (/payments/{appointment})
- View appointment details
- Choose payment method (card/transfer)
- Process payment
- Get confirmation

### 5. **Profile** (/profile)
- View/edit name, email, phone
- Change password (optional)
- Delete account (optional)

### 6. **Admin Dashboard** (/admin/dashboard)
- Statistics: total, pending, approved, revenue
- Manage appointments
- Approve or reject bookings
- Track payments

---

## 🔄 Typical Workflow

### Customer
1. Sign up → 2. Log in → 3. Book appointment → 4. Make €15 deposit → 5. Wait for approval

### Admin
1. Log in to `/admin/dashboard` → 2. View pending appointments → 3. Approve/reject → 4. Customer receives confirmation

---

## 📝 Services Available

Three sample services are pre-loaded:

| Service | Price | Duration |
|---------|-------|----------|
| Manicure | €25 | 60 min |
| Pedicure Premium | €35 | 90 min |
| Nail Art Complete | €50 | 120 min |

**Edit services** in the database or admin panel.

---

## 🎨 Customization

### Change Colors
Edit: `resources/views/layouts/app.blade.php`
```javascript
colors: {
    nude: '#F5E6D3',
    rose: '#E8C4D4',
    olive: '#8B9A7C',
}
```

### Change Business Info
Edit: `resources/views/layouts/app.blade.php`
- Footer: Company details, hours, phone, email, social links
- Navigation: Logo, menu items

### Add/Remove Services
- Create via admin panel (if available)
- Or edit database directly
- Services appear in booking form

### Change Payment Amount
Edit: `app/Models/Payment.php`
```php
const AMOUNT = 15.00; // Change this value
```

---

## ⚙️ Environment Configuration

Edit `.env` file:

```
APP_NAME="Nail Studio"
APP_ENV=production
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=delphina
DB_USERNAME=root
DB_PASSWORD=

MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Nail Studio"
```

---

## 🐛 Troubleshooting

### "Migration table not found"
```bash
php artisan migrate
```

### "Database not found"
Create database:
```bash
mysql -u root -e "CREATE DATABASE delphina;"
php artisan migrate --seed
```

### "Permission denied" errors
```bash
chmod -R 755 storage bootstrap/cache
```

### "Clear cache"
```bash
php artisan optimize:clear
php artisan view:clear
```

---

## 📊 Admin Tasks

### Approve an Appointment
1. Go to `/admin/dashboard`
2. Find appointment in table
3. Click status dropdown
4. Select "Approved"
5. Save

### View Payments
1. Go to `/admin/payments`
2. See all payment records
3. Check status (paid/pending)
4. View transaction IDs

### Manage Services
Services are seeded automatically. To add more:
1. Use admin panel (if implemented)
2. Or add directly via database

---

## 💾 Backup & Restore

### Backup Database
```bash
mysqldump -u root delphina > backup.sql
```

### Restore Database
```bash
mysql -u root delphina < backup.sql
```

---

## 🔄 Payment Integration

### Current: Simulated
The app simulates payment processing. For real payments, integrate:

**Option 1: Stripe** (Recommended)
```bash
composer require stripe/stripe-php
```
Then update `PaymentController::process()`

**Option 2: PayPal**
```bash
composer require paypal/checkout-sdk-php
```

---

## 📱 Mobile Testing

The app is fully responsive:
- Test on mobile: `http://localhost:8000`
- Use Chrome DevTools → Toggle Device Toolbar
- All pages work on mobile, tablet, desktop

---

## 🆘 Common Questions

**Q: How do I reset the database?**
A: Run `php artisan migrate:fresh --seed`

**Q: Can I change the deposit amount?**
A: Yes, edit `app/Models/Payment.php` constant

**Q: How do customers get approval notifications?**
A: Currently manual. To add email: implement queued jobs

**Q: Can I integrate real payment?**
A: Yes, with Stripe/PayPal in `PaymentController`

**Q: How many time slots are available?**
A: Currently shows all hours. Customize in `BookingController`

---

## 📞 Support

For issues:
1. Check `.env` configuration
2. Run `php artisan optimize:clear`
3. Check database migrations
4. Review server logs in `storage/logs/`

---

**✅ You're ready to go! Start managing appointments now!**
