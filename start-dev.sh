#!/bin/bash

# Avvia il daemon Docker in primo piano
echo "Starting Docker daemon in the foreground..."
dockerd &
DOCKER_PID=$!

# Attendi che Docker sia completamente avviato
echo "Waiting for Docker to start..."
while ! docker info > /dev/null 2>&1; do
    sleep 1
done
echo "Docker is running."

# Avvia Docker Compose in modalità non detached
echo "Starting Docker Compose..."
docker compose up -d

echo "Starting npm..."
npm run dev

echo "Script completed successfully."
