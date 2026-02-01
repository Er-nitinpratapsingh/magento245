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
                git(
                    branch: 'main',
                    url: 'git@github.com:Er-nitinpratapsingh/magento2.git',
                    credentialsId: 'github-ssh-key'
                )
            }
        }

        stage('Deploy Code to EC2') {
            steps {
                sshagent(['ec2-ssh-key']) {
                    sh '''
                      rsync -rz --delete \
                        --no-perms --no-owner --no-group \
                        -e "ssh -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null" \
                        --exclude=.git \
                        --exclude=var \
                        --exclude=generated \
                        --exclude=pub/static \
                        --exclude=pub/media \
                        ./ ${EC2_USER}@${EC2_HOST}:${APP_DIR}
                    '''
                }
            }
        }

        stage('Install Dependencies on EC2') {
            steps {
                sshagent(['ec2-ssh-key']) {
                   sh """
                    ssh ${EC2_USER}@${EC2_HOST} \
                      "cd ${APP_DIR} && sudo COMPOSER_IPRESOLVE=4 composer install \
                       --no-dev \
                       --prefer-dist \
                       --optimize-autoloader \
                       --no-interaction \
                       --no-progress"
                    """
                }
            }
        }

        stage('Magento Production Build on EC2') {
            steps {
                sshagent(['ec2-ssh-key']) {
                  sh """
                    ssh ${EC2_USER}@${EC2_HOST} "
                      cd ${APP_DIR} && \
                    
                      # 1️⃣ Ensure directories exist
                      mkdir -p var pub/static pub/media generated/code generated/metadata && \
                    
                      # 2️⃣ Fix ownership (Magento MUST run as www-data)
                      // sudo chown -R www-data:www-data ${APP_DIR} && \
                    
                      # 3️⃣ Clean old generated/cache content (safe BEFORE compile)
                      sudo rm -rf var/cache/* var/page_cache/* pub/static/* generated/* && \
                    
                      # 4️⃣ Permissions (use 775 ideally; 777 only if you absolutely must)
                      sudo chmod -R 775 var pub/static pub/media generated && \
                    
                      # 5️⃣ Magento deploy flow (always as www-data)
                      sudo -u www-data ${PHP_BIN} bin/magento maintenance:enable && \
                      sudo -u www-data ${PHP_BIN} bin/magento setup:upgrade && \
                      sudo -u www-data ${PHP_BIN} bin/magento setup:di:compile && \
                      sudo -u www-data ${PHP_BIN} bin/magento setup:static-content:deploy -f && \
                      sudo -u www-data ${PHP_BIN} bin/magento cache:flush && \
                      sudo -u www-data ${PHP_BIN} bin/magento maintenance:disable
                    "
                    """
                }
            }
        }
    }
}














