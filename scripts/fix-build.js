import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const buildDir = path.join(__dirname, '../public/build');

// Wait for manifest.json to exist
function waitForManifest(maxAttempts = 10) {
    const manifestPath = path.join(buildDir, 'manifest.json');
    
    for (let attempt = 1; attempt <= maxAttempts; attempt++) {
        if (fs.existsSync(manifestPath)) {
            console.log(`✅ Manifest found after ${attempt} attempt(s)`);
            return true;
        }
        console.log(`⏳ Waiting for manifest.json (attempt ${attempt}/${maxAttempts})...`);
        // Wait 500ms between attempts
        Atomics.wait(new Int32Array(new SharedArrayBuffer(4)), 0, 0, 500);
    }
    
    console.error('❌ ERROR: manifest.json not found after', maxAttempts, 'attempts!');
    console.error('📁 Files in build directory:', fs.readdirSync(buildDir));
    return false;
}

// Fix manifest.json paths
function fixManifest() {
    const manifestPath = path.join(buildDir, 'manifest.json');
    
    if (!waitForManifest()) {
        process.exit(1); // Exit with error code
    }

    try {
        let manifest = JSON.parse(fs.readFileSync(manifestPath, 'utf8'));
        console.log('📄 Original manifest loaded');

        // Ensure all paths use forward slashes
        Object.keys(manifest).forEach(key => {
            const entry = manifest[key];
            if (entry.file) {
                entry.file = entry.file.replace(/\\/g, '/');
            }
            if (entry.css) {
                entry.css = entry.css.map(css => css.replace(/\\/g, '/'));
            }
            if (entry.imports) {
                entry.imports = entry.imports.map(imp => imp.replace(/\\/g, '/'));
            }
        });

        fs.writeFileSync(manifestPath, JSON.stringify(manifest, null, 2));
        console.log('✅ Manifest fixed');
        
        // Verify it was written
        if (fs.existsSync(manifestPath)) {
            console.log('✅ Manifest verified at:', manifestPath);
        }
    } catch (error) {
        console.error('❌ Error fixing manifest:', error.message);
        process.exit(1);
    }
}

// Fix line endings in all build files
function fixLineEndings() {
    try {
        const files = fs.readdirSync(buildDir, { recursive: true });
        let fixedCount = 0;

        files.forEach(file => {
            const filePath = path.join(buildDir, file);
            if (fs.statSync(filePath).isFile()) {
                let content = fs.readFileSync(filePath, 'utf8');
                const originalLength = content.length;
                content = content.replace(/\r\n/g, '\n');
                if (content.length !== originalLength) {
                    fs.writeFileSync(filePath, content, 'utf8');
                    fixedCount++;
                }
            }
        });
        console.log(`✅ Line endings fixed in ${fixedCount} files`);
    } catch (error) {
        console.error('❌ Error fixing line endings:', error.message);
    }
}

// Fix permissions
function fixPermissions() {
    try {
        const files = fs.readdirSync(buildDir, { recursive: true });

        files.forEach(file => {
            const filePath = path.join(buildDir, file);
            if (fs.statSync(filePath).isFile()) {
                fs.chmodSync(filePath, 0o644);
            } else {
                fs.chmodSync(filePath, 0o755);
            }
        });
        console.log('✅ Permissions fixed');
    } catch (error) {
        console.error('❌ Error fixing permissions:', error.message);
    }
}

// Run all fixes
console.log('🔧 Fixing build for cross-platform compatibility...');
console.log('📁 Build directory:', buildDir);

fixManifest();
fixLineEndings();
fixPermissions();

console.log('✨ Build is now Linux-compatible!');