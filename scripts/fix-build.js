import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const buildDir = path.join(__dirname, '../public/build');

// Fix manifest.json paths
function fixManifest() {
    const manifestPath = path.join(buildDir, 'manifest.json');
    if (!fs.existsSync(manifestPath)) return;
    
    let manifest = JSON.parse(fs.readFileSync(manifestPath, 'utf8'));
    
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
}

// Fix line endings in all build files
function fixLineEndings() {
    const files = fs.readdirSync(buildDir, { recursive: true });
    
    files.forEach(file => {
        const filePath = path.join(buildDir, file);
        if (fs.statSync(filePath).isFile()) {
            let content = fs.readFileSync(filePath, 'utf8');
            // Convert CRLF to LF
            content = content.replace(/\r\n/g, '\n');
            fs.writeFileSync(filePath, content, 'utf8');
        }
    });
    console.log('✅ Line endings fixed');
}

// Fix permissions
function fixPermissions() {
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
}

// Run all fixes
console.log('🔧 Fixing build for cross-platform compatibility...');
fixManifest();
fixLineEndings();
fixPermissions();
console.log('✨ Build is now Linux-compatible!');