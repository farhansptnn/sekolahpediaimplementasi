import fs from 'fs';
import path from 'path';
import { execSync } from 'child_process';

const uploadDir = './uploads';
if (!fs.existsSync(uploadDir)) {
    fs.mkdirSync(uploadDir, { recursive: true });
}

const schoolNames = [
    'SMA Negeri 1',
    'SMA Negeri 2', 
    'SMA Negeri 3',
    'SMK Negeri 1',
    'SMP Negeri 1'
];

const queries = ['school', 'classroom', 'education', 'building', 'library', 'campus'];

schoolNames.forEach((schoolName, idx) => {
    const sanitized = schoolName.replace(/[^a-zA-Z0-9_-]/g, '_');
    const fileName = `${sanitized}_${idx + 1}.jpg`;
    const filePath = path.join(uploadDir, fileName);
    
    // Skip if exists
    if (fs.existsSync(filePath)) {
        console.log(`✓ ${schoolName} - Already exists`);
        return;
    }
    
    // Download random Unsplash image
    const randomQuery = queries[Math.floor(Math.random() * queries.length)];
    const unsplashUrl = `https://source.unsplash.com/400x300/?${randomQuery}`;
    
    try {
        execSync(`curl -s "${unsplashUrl}" -o "${filePath}"`, { encoding: 'utf-8' });
        console.log(`✓ ${schoolName} - Downloaded`);
    } catch (e) {
        console.log(`✗ ${schoolName} - Failed`);
    }
});

console.log('Done!');

