#!/bin/bash

# Avvia Docker Compose in primo piano e attende il termine
echo "Starting Docker Compose..."
docker compose up -d

# Una volta che Docker Compose è terminato, esegui npm run dev
echo "Starting npm run dev..."
npm run dev
