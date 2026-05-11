# Magento 2.4.5 Project Setup Steps

## Required Services

| Service | Version |
|---|---|
| PHP | 8.1 |
| MySQL | 8.0 |
| Composer | 2.x |
| Elasticsearch | 7.x |
| Apache/Nginx | Latest |

---

# Step 1: Start Required Services

## Start Apache

```bash
sudo systemctl start apache2
```

---

## Start MySQL

```bash
sudo systemctl start mysql
```

---

## Start Elasticsearch

```bash
sudo systemctl start elasticsearch
```

---

# Step 2: Verify Services

## Verify PHP

```bash
php -v
```

---

## Verify Composer

```bash
composer --version
```

---

## Verify MySQL

```bash
mysql --version
```

---

## Verify Elasticsearch

```bash
curl localhost:9200
```

---

# Step 3: Clone Repository

```bash
git clone <repository-url>
```

---

# Step 4: Go to Project Directory

```bash
cd <project-folder>
```

---

# Step 5: Pull Latest Code

```bash
git pull origin <branch-name>
```

---

# Step 6: Install Composer Dependencies

```bash
composer install
```

---

# Step 7: Configure Environment File

```bash
cp app/etc/env.php.sample app/etc/env.php
```

---

# Step 8: Run Setup Upgrade

```bash
php bin/magento setup:upgrade
```

---

# Step 9: Compile Dependency Injection

```bash
php bin/magento setup:di:compile
```

---

# Step 10: Deploy Static Content

```bash
php bin/magento setup:static-content:deploy -f
```

---

# Step 11: Reindex

```bash
php bin/magento indexer:reindex
```

---

# Step 12: Flush Cache

```bash
php bin/magento cache:flush
```
