
  ---
  🚨 Production Considerations

  Your current setup works on local Windows but production needs different configuration depending on your hosting type.

  Scenario Analysis

  ┌──────────────────┬──────────────────────┬─────────────────┐
  │      Setup       │    Current Status    │ Production Risk │
  ├──────────────────┼──────────────────────┼─────────────────┤
  │ Local Dev        │ ✅ Works             │ N/A             │
  ├──────────────────┼──────────────────────┼─────────────────┤
  │ Shared Hosting   │ ⚠️ Might fail        │ HIGH            │
  ├──────────────────┼──────────────────────┼─────────────────┤
  │ VPS/Cloud        │ ⚠️ Depends           │ MEDIUM          │
  ├──────────────────┼──────────────────────┼─────────────────┤
  │ Docker/Container │ ⚠️ Temporary files** │ HIGH            │
  └──────────────────┴──────────────────────┴─────────────────┘

  ---
  Production Setup Guide

  Option 1: Standard VPS/Shared Hosting (Recommended)

  Setup:

  // config/filesystems.php
  'disks' => [
      'public' => [
          'driver' => 'local',
          'root' => storage_path('app/public'),  // ✅ This is correct
          'url' => env('APP_URL').'/storage',
          'visibility' => 'public',
      ],
  ],

  'links' => [
      public_path('storage') => storage_path('app/public'),  // Create symlink
  ],

  On Production Server:

  # 1. Run migration/installation
  php artisan migrate

  # 2. Create the storage symlink (CRITICAL!)
  php artisan storage:link

  # 3. Set proper permissions
  chmod -R 755 storage/app/public
  chmod -R 755 public/storage  # The symlink

  # 4. Verify symlink exists
  ls -la public/
  # Should show: storage -> ../../storage/app/public

  Why this works:
  - ✅ Files stored in storage/app/public/ (persistent)
  - ✅ Symlink public/storage/ points there
  - ✅ URLs work: /storage/candidacy/.../*.jpg
  - ✅ Survives restarts (symlink is permanent)

  ---
  Option 2: Docker/Kubernetes (If containerized)

  # Dockerfile
  FROM php:8.3-fpm

  # ... other setup ...

  # Create storage symlink as part of startup
  RUN cd /app && php artisan storage:link || true

  # Set proper permissions
  RUN chmod -R 755 storage/app/public

  Or in entrypoint script:

  #!/bin/bash
  # entrypoint.sh

  cd /app

  # Always ensure symlink exists
  rm -f public/storage
  php artisan storage:link

  # Set permissions
  chmod -R 755 storage/app/public
  chmod -R 755 public/storage

  # Start app
  php-fpm

  Why this matters:
  - Container filesystem is ephemeral (resets on restart)
  - Symlink must be recreated each startup
  - || true prevents errors if already exists

  ---
  Option 3: Object Storage (AWS S3) (Best for scalability)

  If you have multiple servers or need high availability:

  // config/filesystems.php
  'default' => env('FILESYSTEM_DRIVER', 's3'),

  'disks' => [
      's3' => [
          'driver' => 's3',
          'key' => env('AWS_ACCESS_KEY_ID'),
          'secret' => env('AWS_SECRET_ACCESS_KEY'),
          'region' => env('AWS_DEFAULT_REGION'),
          'bucket' => env('AWS_BUCKET'),
          'url' => env('AWS_URL'),
          'endpoint' => env('AWS_ENDPOINT'),
      ],
  ],

  Advantages:
  - ✅ No symlink issues
  - ✅ Files shared across servers
  - ✅ CDN compatible
  - ✅ Automatic backups
  - ❌ Slight cost increase
  - ❌ Slightly slower (network I/O)

  ---
  Recommended Production Checklist

  Before Deploying:

  # 1. Test filesystem config
  php artisan config:cache  # This caches config values

  # 2. Ensure storage directory exists and is writable
  mkdir -p storage/app/public
  chmod -R 755 storage/app
  chmod -R 755 storage/app/public

  # 3. Create symlink
  php artisan storage:link
  ls -la public/storage  # Should show symlink arrow

  # 4. Test file upload
  php artisan tinker
  > Storage::disk('public')->put('test.txt', 'Hello');
  > Storage::disk('public')->exists('test.txt')  // Should be true

  # 5. Test file access
  curl http://production-url.com/storage/test.txt  // Should return "Hello"

  Production .env Settings:

  # config/filesystems.php uses these
  APP_URL=https://yourdomain.com
  FILESYSTEM_DRIVER=local  # or 's3' for object storage

  # If using S3
  AWS_ACCESS_KEY_ID=xxx
  AWS_SECRET_ACCESS_KEY=xxx
  AWS_DEFAULT_REGION=us-east-1
  AWS_BUCKET=your-bucket
  AWS_URL=https://your-bucket.s3.amazonaws.com

  Production Nginx Config:

  server {
      listen 80;
      server_name yourdomain.com;
      root /var/www/public;

      # Serve files from storage symlink
      location /storage {
          alias /var/www/storage/app/public;
          expires 30d;
          add_header Cache-Control "public, immutable";
      }

      # PHP handling
      location ~ \.php$ {
          fastcgi_pass php:9000;
          fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
          include fastcgi_params;
      }
  }

  Production Apache Config:

  <Directory /var/www/public/storage>
      Options Indexes FollowSymLinks
      AllowOverride All
      Require all granted
  </Directory>

  # If symlink not working, use Alias instead
  Alias /storage /var/www/storage/app/public

  ---
  Potential Issues & Solutions

  ┌───────────────────┬────────────────────────┬────────────────────────────────────────┐
  │       Issue       │         Cause          │                Solution                │
  ├───────────────────┼────────────────────────┼────────────────────────────────────────┤
  │ 404 on /storage/  │ Symlink not created    │ Run php artisan storage:link           │
  ├───────────────────┼────────────────────────┼────────────────────────────────────────┤
  │ Permission denied │ Wrong file permissions │ chmod -R 755 storage/app/public        │
  ├───────────────────┼────────────────────────┼────────────────────────────────────────┤
  │ Files disappear   │ Container restart      │ Create symlink in startup script       │
  ├───────────────────┼────────────────────────┼────────────────────────────────────────┤
  │ Symlink broken    │ Wrong path             │ Verify target: readlink public/storage │
  ├───────────────────┼────────────────────────┼────────────────────────────────────────┤
  │ Slow uploads      │ Local disk I/O         │ Use S3 instead                         │
  ├───────────────────┼────────────────────────┼────────────────────────────────────────┤
  │ Disk full         │ No cleanup             │ Implement file retention policy        │
  └───────────────────┴────────────────────────┴────────────────────────────────────────┘

  ---
  My Recommendation for Your Setup

  Current Setup (Small Organization):

  # 1. Use standard VPS (DigitalOcean, Linode, AWS EC2)
  # 2. Keep config/filesystems.php as fixed:
  'root' => storage_path('app/public')

  # 3. In deployment script:
  php artisan migrate
  php artisan storage:link
  chmod -R 755 storage/app/public

  # 4. Test before going live:
  curl https://yourdomain.com/storage/test.jpg

  Future (If Growing):

  # Switch to S3 when you have:
  # - Multiple servers
  # - High file volume
  # - Need for CDN distribution

  # Just change:
  'default' => env('FILESYSTEM_DRIVER', 's3')

  ---
  Quick Verification Script

  Create storage-check.php for production diagnostics:

  <?php
  // In public/storage-check.php

  $checks = [
      'storage_path exists' => is_dir(storage_path('app/public')),
      'symlink exists' => is_link(public_path('storage')),
      'symlink valid' => is_dir(public_path('storage')),
      'storage writable' => is_writable(storage_path('app/public')),
      'test file' => file_exists(storage_path('app/public/test.txt')),
  ];

  header('Content-Type: application/json');
  echo json_encode($checks, JSON_PRETTY_PRINT);
  ?>

  Access: https://yourdomain.com/storage-check.php

  ---