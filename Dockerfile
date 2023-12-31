# Use the base image for mattrayner/lamp
FROM mattrayner/lamp:latest

# Expose port 80 for web access
EXPOSE 80
# Set the working directory
WORKDIR /

ADD . /app
# ADD . /var/lib/mysql

# Mount the /app and /var/lib/mysql directories
#VOLUME /app
#VOLUME /var/lib/mysql

CMD ["/run.sh"]