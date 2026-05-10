# Magento 2.4.5 Project Setup Guide

This guide explains how to set up the existing Magento 2.4.5 project from the repository.

---

# Prerequisites

Install the following software before setup.

| Software | Version |
|---|---|
| PHP | 8.1 |
| Composer | 2.x |
| MySQL | 8.0 |
| Elasticsearch | 7.x |
| Apache/Nginx | Latest |
| Git | Latest |
| Redis (Optional) | Latest |
| RabbitMQ (Optional) | Latest |

---

# Step 1: Clone Repository

```bash
git clone <repository-url>
```

Example:

```bash
git clone https://github.com/company/project.git
```

Go to project directory:

```bash
cd project
```

---

# Step 2: Install Composer Dependencies

```bash
composer install
```

---

# Step 3: Copy Environment File

If `.env` or `env.php` is not included:

```bash
cp app/etc/env.php.sample app/etc/env.php
```

OR get the `env.php` file from the team.

---

# Step 4: Configure Database

Update database credentials in:

```text
app/etc/env.php
```

Example:

```php
'db' => [
    'connection' => [
        'default' => [
            'host' => 'localhost',
            'dbname' => 'magento245',
            'username' => 'root',
            'password' => 'root'
        ]
    ]
]
```

---

# Step 5: Create Database

Login to MySQL:

```bash
mysql -u root -p
```

Create database:

```sql
CREATE DATABASE magento245;
```

---

# Step 6: Import Database

```bash
mysql -u root -p magento245 < database.sql
```

---

# Step 7: Configure Base URL

Update base URL:

```sql
UPDATE core_config_data
SET value = 'http://localhost/project/'
WHERE path IN ('web/unsecure/base_url', 'web/secure/base_url');
```

---

# Step 8: Install Elasticsearch

Start Elasticsearch service:

```bash
sudo systemctl start elasticsearch
```

Verify:

```bash
curl localhost:9200
```

---

# Step 9: Set File Permissions

```bash
find var generated vendor pub/static pub/media app/etc -type f -exec chmod g+w {} +

find var generated vendor pub/static pub/media app/etc -type d -exec chmod g+ws {} +

chmod u+x bin/magento
```

---

# Step 10: Run Magento Commands

## Upgrade Setup

```bash
php bin/magento setup:upgrade
```

---

## Compile

```bash
php bin/magento setup:di:compile
```

---

## Deploy Static Content

```bash
php bin/magento setup:static-content:deploy -f
```

---

## Reindex

```bash
php bin/magento indexer:reindex
```

---

## Flush Cache

```bash
php bin/magento cache:flush
```

---

# Step 11: Enable Developer Mode

```bash
php bin/magento deploy:mode:set developer
```

---

# Step 12: Configure Hosts File

Add entry:

```text
127.0.0.1 project.local
```

File location:

## Linux/Mac

```text
/etc/hosts
```

## Windows

```text
C:\Windows\System32\drivers\etc\hosts
```

---

# Step 13: Configure Virtual Host

## Apache Example

```apache
<VirtualHost *:80>
    ServerName project.local
    DocumentRoot /var/www/project/pub

    <Directory /var/www/project/pub>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Restart Apache:

```bash
sudo systemctl restart apache2
```

---

# Step 14: Verify Setup

Open:

```text
http://project.local
```

Admin URL example:

```text
http://project.local/admin
```

---

# Important Magento Commands

## Cache

```bash
php bin/magento cache:clean
php bin/magento cache:flush
```

---

## Reindex

```bash
php bin/magento indexer:reindex
```

---

## Compile

```bash
php bin/magento setup:di:compile
```

---

## Upgrade

```bash
php bin/magento setup:upgrade
```

---

# Cron Setup

Install cron jobs:

```bash
php bin/magento cron:install
```

Verify:

```bash
crontab -l
```

---

# Common Issues

## Permission Issues

```bash
sudo chmod -R 777 var pub generated
```

---

## Static Content Issue

```bash
rm -rf pub/static/*
php bin/magento setup:static-content:deploy -f
```

---

## Compilation Error

```bash
rm -rf generated/*
php bin/magento setup:di:compile
```

---

## Elasticsearch Connection Error

Verify service:

```bash
sudo systemctl status elasticsearch
```

---

# Recommended Development Tools

- PHPStorm
- Docker
- Xdebug
- Redis
- RabbitMQ

---

# Notes

- Use developer mode for local setup.
- Never commit `app/etc/env.php`.
- Keep database dump updated.
- Run reindex after importing database.
