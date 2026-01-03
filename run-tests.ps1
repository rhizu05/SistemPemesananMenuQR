# Test Runner Script for QR Ordering System
# This script helps set up and run the automated test suite

Write-Host "🧪 QR Ordering System - Test Runner" -ForegroundColor Cyan
Write-Host "=====================================" -ForegroundColor Cyan
Write-Host ""

# Check if vendor directory exists
if (-not (Test-Path "vendor")) {
    Write-Host "⚠️  Vendor directory not found. Running composer install..." -ForegroundColor Yellow
    composer install
}

Write-Host "📋 Test Suite Options:" -ForegroundColor Green
Write-Host "1. Run all tests"
Write-Host "2. Run Authentication tests"
Write-Host "3. Run Admin Features tests"
Write-Host "4. Run Cashier Features tests"
Write-Host "5. Run Customer Features tests"
Write-Host "6. Run Kitchen Features tests"
Write-Host "7. Run Security tests"
Write-Host "8. Run tests with coverage"
Write-Host "9. Run tests in parallel (faster)"
Write-Host "0. Exit"
Write-Host ""

$choice = Read-Host "Select option (0-9)"

switch ($choice) {
    "1" {
        Write-Host "`n🚀 Running all tests..." -ForegroundColor Cyan
        php artisan test
    }
    "2" {
        Write-Host "`n🔐 Running Authentication tests..." -ForegroundColor Cyan
        php artisan test --filter=Authentication
    }
    "3" {
        Write-Host "`n👨‍💼 Running Admin Features tests..." -ForegroundColor Cyan
        php artisan test --filter=AdminFeatures
    }
    "4" {
        Write-Host "`n💰 Running Cashier Features tests..." -ForegroundColor Cyan
        php artisan test --filter=CashierFeatures
    }
    "5" {
        Write-Host "`n👥 Running Customer Features tests..." -ForegroundColor Cyan
        php artisan test --filter=CustomerFeatures
    }
    "6" {
        Write-Host "`n🍳 Running Kitchen Features tests..." -ForegroundColor Cyan
        php artisan test --filter=KitchenFeatures
    }
    "7" {
        Write-Host "`n🔒 Running Security tests..." -ForegroundColor Cyan
        php artisan test --filter=Security
    }
    "8" {
        Write-Host "`n📊 Running tests with coverage..." -ForegroundColor Cyan
        php artisan test --coverage
    }
    "9" {
        Write-Host "`n⚡ Running tests in parallel..." -ForegroundColor Cyan
        php artisan test --parallel
    }
    "0" {
        Write-Host "`n👋 Goodbye!" -ForegroundColor Green
        exit
    }
    default {
        Write-Host "`n❌ Invalid option!" -ForegroundColor Red
    }
}

Write-Host "`n✅ Test execution completed!" -ForegroundColor Green
Write-Host "`nFor more information, see TEST_EXECUTION_REPORT.md" -ForegroundColor Yellow
