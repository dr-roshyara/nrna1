#!/bin/bash

echo "🔄 Fixing Organization references in all files..."

# Fix class names in PHP files
find app tests -name "*.php" -exec sed -i 's/class OrganizationController/class OrganisationController/g' {} \;
find app tests -name "*.php" -exec sed -i 's/class StoreOrganizationRequest/class StoreOrganisationRequest/g' {} \;
find app tests -name "*.php" -exec sed -i 's/class OrganizationCreatedMail/class OrganisationCreatedMail/g' {} \;

# Fix test class names
find tests -name "*.php" -exec sed -i 's/class OrganizationCreation/class OrganisationCreation/g' {} \;
find tests -name "*.php" -exec sed -i 's/class EnsureOrganizationMemberTest/class EnsureOrganisationMemberTest/g' {} \;

# Fix method names
find app tests -name "*.php" -exec sed -i 's/validOrganizationData/validOrganisationData/g' {} \;

# Fix namespace imports
find app tests -name "*.php" -exec sed -i 's/App\\Mail\\OrganizationCreatedMail/App\\Mail\\OrganisationCreatedMail/g' {} \;
find app tests -name "*.php" -exec sed -i 's/App\\Http\\Requests\\StoreOrganizationRequest/App\\Http\\Requests\\StoreOrganisationRequest/g' {} \;

# Fix log messages
find app tests -name "*.php" -exec sed -i 's/EnsureOrganization:/EnsureOrganisation:/g' {} \;

echo "✅ Fixes applied!"
