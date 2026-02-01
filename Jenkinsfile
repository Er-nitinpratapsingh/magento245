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
                    
                      # Ensure ownership once (do NOT comment this)
                      sudo chown -R www-data:www-data ${APP_DIR} && \
                    
                      # Ensure directories exist
                      sudo -u www-data mkdir -p \
                        var \
                        pub/static \
                        pub/media \
                        generated/code \
                        generated/metadata && \
                    
                      # Enable maintenance
                      sudo -u www-data ${PHP_BIN} bin/magento maintenance:enable && \
                    
                      # Clean ONLY safe directories
                      sudo -u www-data rm -rf \
                        var/cache/* \
                        var/page_cache/* \
                        pub/static/* && \
                    
                      # 🔥 DI compile MUST come immediately after cleanup
                      sudo -u www-data ${PHP_BIN} bin/magento setup:di:compile && \
                    
                      # Static content
                      sudo -u www-data ${PHP_BIN} bin/magento setup:static-content:deploy -f && \
                    
                      # Upgrade DB (safe after compile)
                      sudo -u www-data ${PHP_BIN} bin/magento setup:upgrade && \
                    
                      # Cache flush
                      sudo -u www-data ${PHP_BIN} bin/magento cache:flush && \
                    
                      # Disable maintenance
                      sudo -u www-data ${PHP_BIN} bin/magento maintenance:disable
                    "
                    """
                }
            }
        }
    }
}

















