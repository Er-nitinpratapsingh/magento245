pipeline {
    agent any

    environment {
        EC2_USER = "ubuntu"
        EC2_HOST = "13.200.12.191"
        MAGENTO_ROOT = "/var/www/magento"
        PHP_BIN = "/usr/bin/php"
        COMPOSER_BIN = "/usr/bin/composer"
        GIT_BRANCH = "main"
    }

    stages {

        stage('Checkout Code') {
            steps {
                git branch: "${GIT_BRANCH}",
                    credentialsId: 'github-ssh-key',
                    url: 'git@github.com:Er-nitinpratapsingh/magento2.git'
            }
        }

        stage('Enable Maintenance Mode') {
            steps {
                sh """
                ssh ${EC2_USER}@${EC2_HOST} '
                    cd ${MAGENTO_ROOT} &&
                    ${PHP_BIN} bin/magento maintenance:enable
                '
                """
            }
        }

        stage('Deploy Code to EC2') {
            steps {
                sh """
                rsync -avz --delete \
                --exclude=.git \
                --exclude=var/cache \
                --exclude=var/page_cache \
                --exclude=var/session \
                ./ ${EC2_USER}@${EC2_HOST}:${MAGENTO_ROOT}
                """
            }
        }

        stage('Composer Install') {
            steps {
                sh """
                ssh ${EC2_USER}@${EC2_HOST} '
                    cd ${MAGENTO_ROOT} &&
                    ${COMPOSER_BIN} install \
                    --no-dev \
                    --optimize-autoloader
                '
                """
            }
        }

        stage('Magento Upgrade & Compile') {
            steps {
                sh """
                ssh ${EC2_USER}@${EC2_HOST} '
                    cd ${MAGENTO_ROOT} &&
                    ${PHP_BIN} bin/magento setup:upgrade &&
                    ${PHP_BIN} bin/magento setup:di:compile &&
                    ${PHP_BIN} bin/magento setup:static-content:deploy -f
                '
                """
            }
        }

        stage('Permissions') {
            steps {
                sh """
                ssh ${EC2_USER}@${EC2_HOST} '
                    cd ${MAGENTO_ROOT} &&
                    chown -R www-data:www-data . &&
                    find var generated pub/static pub/media app/etc -type f -exec chmod 664 {} \\; &&
                    find var generated pub/static pub/media app/etc -type d -exec chmod 775 {} \\;
                '
                """
            }
        }

        stage('Disable Maintenance Mode') {
            steps {
                sh """
                ssh ${EC2_USER}@${EC2_HOST} '
                    cd ${MAGENTO_ROOT} &&
                    ${PHP_BIN} bin/magento maintenance:disable
                '
                """
            }
        }
    }

    post {
        success {
            echo "🚀 Magento production deployment successful!"
        }
        failure {
            echo "❌ Deployment failed. Check logs."
        }
    }
}
