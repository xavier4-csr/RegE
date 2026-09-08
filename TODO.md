# RegE Fix Plan (Option A - Comprehensive)

## Step 1: Controllers
- [x] Recreate missing base `Controller.php`
- [x] Rewrite `CompanyController.php`

## Step 2: Views - move into subdirectories to match controller references
- [x] Create `company/index.blade.php`
- [x] Create `company/show.blade.php`
- [x] Create `company/create.blade.php`
- [x] Create `company/edit.blade.php`
- [x] Create `partials/company-card.blade.php`
- [x] Create `auth/register.blade.php`
- [x] Create `auth/login.blade.php`
- [x] Create `auth/verify-email.blade.php`

## Step 3: Create missing views
- [x] Create `dashboard/index.blade.php`
- [x] Create `profile/show.blade.php`
- [x] Create `compliance/index.blade.php`
- [x] Create `auth/forgot-password.blade.php`
- [x] Create `contact.blade.php`

## Step 4: Cleanup duplicate top-level views
- [x] Delete top-level register/login/verify-email/create/edit/show/index/card.blade.php

## Step 5: Seeders & Factories
- [x] Fix `DatabaseSeeder.php` (use first_name/last_name, call ComplianceStepsSeeder)
- [x] Fix `UserFactory.php` (use first_name/last_name)
- [x] Populate `ComplianceStepsSeeder.php`

## Step 6: Verify
- [x] Confirmed DB config uses env `DB_CONNECTION` (MySQL 127.0.0.1:3306 from original error)
- [x] User must start MySQL + run `php artisan migrate --seed` to resolve connection error

## DONE - All code changes complete

