pipeline {
    agent any

    environment {
        EC2_HOST = "65.1.149.77"
        EC2_USER = "ubuntu"
        APP_DIR  = "/var/www/magento"
        PHP_BIN  = "/usr/bin/php"

        COMPOSER_IPRESOLVE = '4'
        COMPOSER_PROCESS_TIMEOUT = '2000'
        COMPOSER_NO_INTERACTION = '1'
    }

    stages {

        stage('Checkout Code') {
            steps {
                git branch: 'main',
                    url: 'git@github.com:Er-nitinpratapsingh/magento2.git'
            }
        }

        stage('Install Dependencies') {
            steps {
                sh '''
                  composer install \
                  --no-dev \
                  --prefer-dist \
                  --no-interaction \
                  --optimize-autoloader
                '''
            }
        }

        stage('Build Magento') {
            steps {
                sh '''
                  ${PHP_BIN} bin/magento setup:di:compile
                  ${PHP_BIN} bin/magento setup:static-content:deploy -f
                '''
            }
        }

        stage('Deploy to EC2') {
            steps {
                sshagent(['ec2-ssh-key']) {
                    sh '''
                      rsync -avz --delete \
                      --exclude=.git \
                      --exclude=var \
                      --exclude=pub/media \
                      ./ ${EC2_USER}@${EC2_HOST}:${APP_DIR}
                    '''
                }
            }
        }

        stage('Post Deploy Commands') {
            steps {
                sshagent(['ec2-ssh-key']) {
                    sh '''
                      ssh ${EC2_USER}@${EC2_HOST} << EOF
                        cd ${APP_DIR}
                        ${PHP_BIN} bin/magento maintenance:enable
                        ${PHP_BIN} bin/magento setup:upgrade
                        ${PHP_BIN} bin/magento cache:flush
                        ${PHP_BIN} bin/magento maintenance:disable
                      EOF
                    '''
                }
            }
        }
    }
}

