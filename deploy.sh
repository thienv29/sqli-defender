#!/bin/bash

echo "🚀 Starting sqli-defender deployment..."

# Stop and clean old containers/volumes
echo "🧹 Cleaning up old deployment..."
docker-compose down -v

# Update code
echo "🔄 Pulling latest code..."
git pull

# Start fresh
echo "🏗️ Starting new deployment..."
docker-compose up -d

# Wait for db
echo "⏳ Waiting for database to be ready..."
sleep 15

echo "✅ Deployment complete!"
echo "📱 Access at: http://localhost:8080"
echo "🔧 Database port: 33066"
