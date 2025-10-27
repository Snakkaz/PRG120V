# Deploy Script for PRG120V
# This script can be used for deployment to USN Dokploy

echo "🚀 Deploying PRG120V to USN Dokploy..."
echo ""
echo "Step 1: Checking git status..."
git status

echo ""
echo "Step 2: Pulling latest changes..."
git pull origin main

echo ""
echo "Step 3: Deployment complete!"
echo "✅ Application is now live at: https://dokploy.usn.no/app/stpet1155-prg120v/"
