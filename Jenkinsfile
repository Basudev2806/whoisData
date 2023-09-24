pipeline {
    agent any 
    
    stages{
        stage("Clone Code"){
            steps {
                sh "echo $PATH"
                echo "Cloning the code"
                git url:"https://github.com/Basudev2806/whoisData.git", branch: "master"
            }
        }
        stage("Build"){
            steps {
                echo "Building the image"
                sh "docker build -t whoisdata ."
            }
        }
        stage("Push to Docker Hub"){
            steps {
                echo "Pushing the image to docker hub"
                withCredentials([usernamePassword(credentialsId:"DockerHub",passwordVariable:"DockerHubPass",usernameVariable:"DockerHubUser")]){
                sh "docker tag whoisdata ${env.dockerHubUser}/whoisdata:latest"
                sh "docker login -u ${env.dockerHubUser} -p ${env.dockerHubPass}"
                sh "docker push ${env.dockerHubUser}/whoisdata:latest"
                }
            }
        }
        stage('Configure Environment') {
            steps {
                script {
                    // Add /usr/local/bin to the PATH
                    def customPath = '/usr/local/bin'
                    def currentPath = sh(script: 'echo $PATH', returnStdout: true).trim()
                    env.PATH = "${customPath}:${currentPath}"
                }
            }
        }
        stage("Deploy"){
            steps {
                echo "Deploying the container"
                sh "docker-compose down && docker-compose up -d"
                
            }
        }
    }
}