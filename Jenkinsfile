pipeline {
    agent any

    options {
        timeout(time: 45, unit: 'MINUTES')
    }

    environment {
        EC2_HOST = "65.1.149.77"
        EC2_USER = "ubuntu"
        APP_DIR  = "/var/www/magento"
        PHP_BIN  = "/usr/bin/php"
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
                      set -e
                      set -o pipefail
                      trap "exit 1" INT TERM

                      rsync -rz --delete \
                        --no-perms --no-owner --no-group \
                        --rsync-path="sudo rsync" \
                        -e "ssh -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null" \
                        --exclude=.git \
                        --exclude=var \
                        --exclude=vendor \
                        --exclude=generated \
                        --exclude=pub/static \
                        --exclude=pub/media \
                        ./ ${EC2_USER}@${EC2_HOST}:${APP_DIR}
                    '''
                }
            }
        }

        stage('Magento Production Build on EC2') {
            steps {
                sshagent(['ec2-ssh-key']) {
                    sh """
                    set -e
                    set -o pipefail
                    trap "exit 1" INT TERM

                    ssh -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null \
                    ${EC2_USER}@${EC2_HOST} "
                      set -e
                      set -o pipefail

                      cd ${APP_DIR}

                      sudo chown -R www-data:www-data ${APP_DIR}

                      sudo -u www-data mkdir -p \
                        var \
                        pub/static \
                        pub/media \
                        generated/code \
                        generated/metadata

                      sudo -u www-data ${PHP_BIN} bin/magento maintenance:enable

                      sudo -u www-data rm -rf \
                        var/cache/* \
                        var/page_cache/* \
                        pub/static/*

                      sudo -u www-data ${PHP_BIN} bin/magento setup:di:compile

                      sudo -u www-data ${PHP_BIN} bin/magento setup:static-content:deploy -f

                      sudo -u www-data ${PHP_BIN} bin/magento setup:upgrade

                      sudo -u www-data ${PHP_BIN} bin/magento cache:flush

                      sudo -u www-data ${PHP_BIN} bin/magento maintenance:disable
                    "
                    """
                }
            }
        }
    }

    post {
        aborted {
            echo "⚠️ Build aborted — timeout or manual stop triggered"
        }
        failure {
            echo "❌ Build failed — check logs"
        }
    }
}
