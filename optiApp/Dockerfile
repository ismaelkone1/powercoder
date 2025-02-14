# Utiliser une image Java
FROM openjdk:17-jdk-slim

# Définir le répertoire de travail
WORKDIR /usr/src/app

# Copier les fichiers source dans le conteneur
COPY ./src /usr/src/app/src

COPY ./lib /usr/src/app/lib

# Laisser le conteneur ouvert pour la compilation manuelle
CMD ["bash"]
