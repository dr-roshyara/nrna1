import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const buildDir = path.join(__dirname, '../public/build');

/**
 * Promise-based sleep — replaces Atomics.wait which only works in Worker threads.
 */
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}

/**
 * Recursive readdir — used as fallback for environments without { recursive: true } support.
 */
function readdirRecursive(dir) {
  const results = [];
  const entries = fs.readdirSync(dir, { withFileTypes: true });
  for (const entry of entries) {
    const fullPath = path.join(dir, entry.name);
    if (entry.isDirectory()) {
      results.push(...readdirRecursive(fullPath));
    } else {
      results.push(path.relative(buildDir, fullPath));
    }
  }
  return results;
}

/**
 * Wait for manifest.json to exist (poll-based).
 */
async function waitForManifest(maxAttempts = 15) {
  const manifestPath = path.join(buildDir, 'manifest.json');

  for (let attempt = 1; attempt <= maxAttempts; attempt++) {
    if (fs.existsSync(manifestPath)) {
      console.log(`✅ Manifest found after ${attempt} attempt(s)`);
      return true;
    }
    console.log(`⏳ Waiting for manifest.json (attempt ${attempt}/${maxAttempts})...`);
    await sleep(500);
  }

  console.error('❌ ERROR: manifest.json not found after', maxAttempts, 'attempts!');
  try {
    console.error('📁 Files in build directory:', fs.readdirSync(buildDir));
  } catch {
    console.error('📁 Build directory does not exist:', buildDir);
  }
  return false;
}

/**
 * Fix manifest.json paths (forward slashes for cross-platform).
 */
async function fixManifest() {
  const manifestPath = path.join(buildDir, 'manifest.json');

  const found = await waitForManifest();
  if (!found) {
    process.exit(1);
  }

  try {
    let manifest = JSON.parse(fs.readFileSync(manifestPath, 'utf8'));
    console.log('📄 Original manifest loaded');

    // Ensure all paths use forward slashes
    Object.keys(manifest).forEach((key) => {
      const entry = manifest[key];
      if (entry.file) {
        entry.file = entry.file.replace(/\\/g, '/');
      }
      if (entry.css) {
        entry.css = entry.css.map((css) => css.replace(/\\/g, '/'));
      }
      if (entry.imports) {
        entry.imports = entry.imports.map((imp) => imp.replace(/\\/g, '/'));
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

/**
 * Fix line endings in all build files (CRLF → LF for Linux compat).
 */
function fixLineEndings() {
  try {
    const files = fs.existsSync(buildDir)
      ? readdirRecursive(buildDir)
      : [];
    let fixedCount = 0;

    files.forEach((file) => {
      const filePath = path.join(buildDir, file);
      try {
        let content = fs.readFileSync(filePath, 'utf8');
        const originalLength = content.length;
        content = content.replace(/\r\n/g, '\n');
        if (content.length !== originalLength) {
          fs.writeFileSync(filePath, content, 'utf8');
          fixedCount++;
        }
      } catch {
        // Skip files that can't be read (binary, permissions, etc.)
      }
    });
    console.log(`✅ Line endings fixed in ${fixedCount} files`);
  } catch (error) {
    console.error('❌ Error fixing line endings:', error.message);
  }
}

/**
 * Fix permissions (best-effort on Windows, needed for Linux deployment).
 */
function fixPermissions() {
  try {
    const files = fs.existsSync(buildDir)
      ? readdirRecursive(buildDir)
      : [];

    files.forEach((file) => {
      const filePath = path.join(buildDir, file);
      try {
        const stat = fs.statSync(filePath);
        if (stat.isFile()) {
          fs.chmodSync(filePath, 0o644);
        } else if (stat.isDirectory()) {
          fs.chmodSync(filePath, 0o755);
        }
      } catch {
        // chmod may fail on Windows — that's acceptable
      }
    });
    console.log('✅ Permissions fixed');
  } catch (error) {
    console.error('❌ Error fixing permissions:', error.message);
  }
}

// ── Main —────────────────────────────────────────────────
console.log('🔧 Fixing build for cross-platform compatibility...');
console.log('📁 Build directory:', buildDir);

if (!fs.existsSync(buildDir)) {
  console.log('⚠️  Build directory does not exist — creating...');
  fs.mkdirSync(buildDir, { recursive: true });
  console.log('✅ Build directory created');
  console.log('✨ Nothing to fix (no prior build artifacts)');
  process.exit(0);
}

await fixManifest();
fixLineEndings();
fixPermissions();

console.log('✨ Build is now Linux-compatible!');
