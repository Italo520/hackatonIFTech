import fs from 'fs';
import path from 'path';
import zlib from 'zlib';

// Simple CRC32 implementation for PNG chunks
function makeCrcTable() {
    const cTable = [];
    for (let n = 0; n < 256; n++) {
        let c = n;
        for (let k = 0; k < 8; k++) {
            if (c & 1) {
                c = 0xedb88320 ^ (c >>> 1);
            } else {
                c = c >>> 1;
            }
        }
        cTable[n] = c;
    }
    return cTable;
}

const crcTable = makeCrcTable();

function crc32(buf) {
    let crc = 0 ^ (-1);
    for (let i = 0; i < buf.length; i++) {
        crc = (crc >>> 8) ^ crcTable[(crc ^ buf[i]) & 0xff];
    }
    return (crc ^ (-1)) >>> 0;
}

function makeChunk(type, data) {
    const len = data.length;
    const buf = Buffer.alloc(4 + 4 + len + 4);
    buf.writeUInt32BE(len, 0);
    buf.write(type, 4, 4, 'ascii');
    data.copy(buf, 8);
    const crcVal = crc32(buf.subarray(4, 8 + len));
    buf.writeUInt32BE(crcVal, 8 + len);
    return buf;
}

function createPng(width, height, isMaskable = false) {
    // 8-byte PNG signature
    const signature = Buffer.from([137, 80, 78, 71, 13, 10, 26, 10]);

    // IHDR
    const ihdr = Buffer.alloc(13);
    ihdr.writeUInt32BE(width, 0);
    ihdr.writeUInt32BE(height, 4);
    ihdr[8] = 8; // bit depth
    ihdr[9] = 6; // RGBA
    ihdr[10] = 0; // compression
    ihdr[11] = 0; // filter
    ihdr[12] = 0; // interlace

    const ihdrChunk = makeChunk('IHDR', ihdr);

    // Create raw pixel data (scanlines with filter byte 0)
    const rawData = Buffer.alloc((1 + width * 4) * height);
    
    // Draw stylish gradient background with location pin / compass icon
    const cx = width / 2;
    const cy = height / 2;
    const rOuter = (width * 0.44);

    let offset = 0;
    for (let y = 0; y < height; y++) {
        rawData[offset++] = 0; // Filter: None
        for (let x = 0; x < width; x++) {
            const dx = x - cx;
            const dy = y - cy;
            const dist = Math.sqrt(dx * dx + dy * dy);

            // Gradient: #005f73 (0, 95, 115) to #0a9396 (10, 147, 150)
            const t = (x + y) / (width + height);
            let r = Math.round(0 * (1 - t) + 10 * t);
            let g = Math.round(95 * (1 - t) + 147 * t);
            let b = Math.round(115 * (1 - t) + 150 * t);
            let a = 255;

            // Rounded rect icon shape if not maskable
            if (!isMaskable) {
                const cornerRadius = width * 0.22;
                const innerX = Math.max(Math.abs(x - cx) - (cx - cornerRadius), 0);
                const innerY = Math.max(Math.abs(y - cy) - (cy - cornerRadius), 0);
                const cornerDist = Math.sqrt(innerX * innerX + innerY * innerY);
                if (cornerDist > cornerRadius) {
                    a = 0;
                }
            }

            if (a > 0) {
                // Pin head / ring
                const pinHeadY = cy - height * 0.08;
                const distHead = Math.sqrt(dx * dx + (y - pinHeadY) * (y - pinHeadY));
                const pinR = width * 0.22;
                const innerPinR = width * 0.09;

                // Compass / Pin symbol in clean white (#ffffff)
                if (distHead < pinR) {
                    if (distHead > innerPinR) {
                        r = 255; g = 255; b = 255;
                    }
                } else if (y >= pinHeadY && y <= cy + height * 0.26) {
                    // Triangle point of pin
                    const halfW = (pinR) * (1 - (y - pinHeadY) / (height * 0.34));
                    if (Math.abs(dx) <= halfW && halfW > 0) {
                        r = 255; g = 255; b = 255;
                    }
                }

                // Small center accent in gold #ee9b00
                if (distHead <= innerPinR * 0.7) {
                    r = 238; g = 155; b = 0;
                }
            }

            rawData[offset++] = r;
            rawData[offset++] = g;
            rawData[offset++] = b;
            rawData[offset++] = a;
        }
    }

    const compressed = zlib.deflateSync(rawData);
    const idatChunk = makeChunk('IDAT', compressed);
    const iendChunk = makeChunk('IEND', Buffer.alloc(0));

    return Buffer.concat([signature, ihdrChunk, idatChunk, iendChunk]);
}

const iconsDir = path.resolve('public/icons');
if (!fs.existsSync(iconsDir)) {
    fs.mkdirSync(iconsDir, { recursive: true });
}

console.log('Gerando ícones PWA...');
fs.writeFileSync(path.join(iconsDir, 'icon-192x192.png'), createPng(192, 192));
fs.writeFileSync(path.join(iconsDir, 'icon-512x512.png'), createPng(512, 512));
fs.writeFileSync(path.join(iconsDir, 'icon-maskable-512x512.png'), createPng(512, 512, true));
fs.writeFileSync(path.join(iconsDir, 'apple-touch-icon.png'), createPng(180, 180));
fs.writeFileSync(path.resolve('public/favicon.ico'), createPng(64, 64));

// Also generate high quality SVG
const svgContent = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="512" height="512">
  <defs>
    <linearGradient id="pwaGrad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#005f73" />
      <stop offset="100%" stop-color="#0a9396" />
    </linearGradient>
    <filter id="pwaShadow" x="-10%" y="-10%" width="130%" height="130%">
      <feDropShadow dx="0" dy="8" stdDeviation="12" flood-color="#000000" flood-opacity="0.25"/>
    </filter>
  </defs>
  <rect width="512" height="512" rx="115" fill="url(#pwaGrad)"/>
  <g filter="url(#pwaShadow)">
    <path d="M256 90 C190 90 136 144 136 210 C136 295 256 422 256 422 C256 422 376 295 376 210 C376 144 322 90 256 90 Z" fill="#ffffff"/>
    <circle cx="256" cy="210" r="48" fill="#005f73"/>
    <circle cx="256" cy="210" r="22" fill="#ee9b00"/>
  </g>
</svg>`;

fs.writeFileSync(path.join(iconsDir, 'icon.svg'), svgContent);
fs.writeFileSync(path.resolve('public/favicon.svg'), svgContent);

console.log('Ícones PWA criados com sucesso em public/icons/');
