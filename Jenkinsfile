pipeline {
    agent { docker 'php' }
    stages {
        stage('build') {
            steps {
                zsh 'php --version'
            }
        }
    }
}
