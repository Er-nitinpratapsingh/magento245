pipeline {
    agent any

    options {
        timeout(time: 60, unit: 'MINUTES')
    }

    environment {
        EC2_USER = "ubuntu"
        EC2_HOST = "13.200.12.191"
        MAGENTO_ROOT = "/var/www/magento"
        PHP = "/usr/bin/php"
        COMPOSER = "/usr/bin/composer"
        BRANCH = "main"
    }

    stages {

        stage('Deploy') {
            steps {
                sh """
                ssh ${EC2_USER}@${EC2_HOST} '
                    set -e
                    cd ${MAGENTO_ROOT}

                    echo "🔹 Enable maintenance"
                    ${PHP} bin/magento maintenance:enable || true

                    echo "🔹 Pull latest code"
                    git fetch origin
                    git reset --hard origin/${BRANCH}

                    echo "🔹 Composer install"
                    export COMPOSER_MEMORY_LIMIT=-1
                    sudo rm -rf generated/* var/cache/* var/page_cache/* var/di/*
                    ${COMPOSER} install --no-dev --optimize-autoloader --no-interaction
                    ${COMPOSER} dump-autoload -o

                    echo "🔹 Magento upgrade"
                    ${PHP} bin/magento setup:upgrade

                    echo "🔹 Compile DI"
                    ${PHP} bin/magento setup:di:compile

                    echo "🔹 Deploy static"
                    ${PHP} bin/magento setup:static-content:deploy -f

                    echo "🔹 Fix permissions"
                    sudo chown -R www-data:www-data .
                    sudo find var generated pub/static pub/media -type d -exec chmod 775 {} \\;
                    sudo find var generated pub/static pub/media -type f -exec chmod 664 {} \\;

                    echo "🔹 Disable maintenance"
                    ${PHP} bin/magento maintenance:disable

                    echo "✅ Magento production deployment successful!!!"
                '
                """
            }
        }
    }
}




