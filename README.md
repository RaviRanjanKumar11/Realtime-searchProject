# Realtime Search System (Laravel + MySQL + Elasticsearch + Redis + Queue)
# Project Overview

This project demonstrates a high-performance realtime search system built using Laravel, MySQL, Elasticsearch, Redis caching,
and Laravel Queues.

The system is designed to handle large datasets (up to 1 million records) while maintaining fast search responses and production-grade architecture.

# It includes:

Realtime search with caching
Elasticsearch integration with MySQL fallback
Background email notification using queues
Google OAuth authentication
Scalable and interview-ready design


# Tech Stack
Layer	Technology
Backend	Laravel 12
Frontend	Blade, HTML,tailiwindCss, Bootstrap, JavaScript
Database	MySQL
Search Engine	Elasticsearch
Cache	Redis / Database Cache
Queue	Laravel Queue (Database Driver)
Email	Mailtrap SMTP
Auth	Laravel Breeze + Google OAuth
OS	Windows (Local Development)
# Features Implemented
# Authentication

Email & Password login
Google OAuth login
Secure password hashing
CSRF protection
Auth-protected dashboard

# Realtime Search

Live search using AJAX
Minimum keyword length validation
Optimized response time
Pagination-ready design

# Caching Strategy

Search results cached using keyword hash
Cache TTL: 5 minutes
Reduces database & Elasticsearch load
Improves performance for repeated searches

# Elasticsearch Integration

Elasticsearch used as primary search engine
Multi-field search (name, email, city)
Bulk import command for large datasets
Automatic fallback to MySQL if Elasticsearch fails
Elastic → Cache → MySQL (fallback)

# Large Dataset Handling

Seeder supports 1 million records
Chunk-based processing (memory safe)
Elasticsearch bulk indexing
Optimized for interviews & scalability discussions

# Admin Email Notification (Queued)

Every search keyword triggers admin notification
Emails sent asynchronously
Search response is never blocked
Retry & failure handling included

# Queue System

Queue driver: database
Jobs retried automatically
Failed jobs stored for inspection
Production-grade fault tolerance



# .env Setup

APP_NAME=Laravel
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_DATABASE=realtime_search
DB_USERNAME=root
DB_PASSWORD=

QUEUE_CONNECTION=database
CACHE_STORE=database

MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=xxxxxx
MAIL_PASSWORD=xxxxxx
MAIL_FROM_ADDRESS=admin@realtimesearch.com
MAIL_FROM_NAME="Search Admin"



# Database Setup

php artisan migrate
php artisan db:seed


# Elasticsearch Setup (Local)

Install Elasticsearch
Start Elasticsearch server
Verify: http://localhost:9200


# Import Data

php artisan elastic:import


# Queue Setup

php artisan queue:table
php artisan queue:failed-table
php artisan migrate


# Run Queue Worker

php artisan queue:listen

# Email Notification Flow 

User searches keyword
Search response returned immediately
Job dispatched to queue
Admin receives email asynchronously
Failures logged and retried


# Failure Handling

Elasticsearch failure → MySQL fallback
Mail failure → Job retried automatically
Permanent failure → Stored in failed_jobs

# Highlights

Handles large data efficiently
Search never blocked by email
Caching + fallback strategy
Clean separation of concerns
Production-grade queue usage

# How to Run Locally

php artisan serve
php artisan queue:listen
# Open  http://127.0.0.1:8000
