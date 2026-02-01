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
                url: 'git@github.com:Er-nitinpratapsingh/magento2.git',
                credentialsId: 'github-ssh-key'
        }
    }

    stage('Deploy Code to EC2') {
        steps {
            sshagent(['ec2-ssh-key']) {
                sh '''
                  rsync -az --delete \
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
                sh '''
                  ssh ${EC2_USER}@${EC2_HOST} << 'EOF'
                    cd ${APP_DIR}
                    sudo -u www-data COMPOSER_IPRESOLVE=4 composer install \
                      --no-dev \
                      --prefer-dist \
                      --optimize-autoloader \
                      --no-interaction \
                      --no-progress
                  EOF
                '''
            }
<<<<<<< HEAD
        }
    }
=======
        } 
>>>>>>> d93c5e6 (update jenkins for prod)

    stage('Magento Setup (Developer Mode)') {
        steps {
            sshagent(['ec2-ssh-key']) {
                sh '''
                  ssh ${EC2_USER}@${EC2_HOST} << 'EOF'
                    cd ${APP_DIR}
                    php bin/magento setup:upgrade
                    php bin/magento cache:flush
                  EOF
                '''
            }
        }
    }
}

}





