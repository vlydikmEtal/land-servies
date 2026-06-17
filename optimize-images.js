import fs from "fs";
import path from "path";
import sharp from "sharp";

const inputDir = path.resolve("assets/img/pictures");
const outputDir = path.resolve("assets/img/optimized");

console.log("cwd:", process.cwd());
console.log("input:", inputDir);

if (!fs.existsSync(outputDir)) {
  fs.mkdirSync(outputDir, { recursive: true });
}

const files = fs.readdirSync(inputDir);

const images = files.filter((f) =>
  [".jpg", ".jpeg", ".png", ".webp"].includes(path.extname(f).toLowerCase())
);

for (const file of images) {
  const inputPath = path.join(inputDir, file);
  const outputPath = path.join(outputDir, file.replace(/\.(jpg|jpeg|png)$/i, ".webp"));

  try {
    // 🔥 проверка что файл реально читается
    fs.accessSync(inputPath, fs.constants.R_OK);

    await sharp(inputPath)
      .webp({ quality: 75 })
      .toFile(outputPath);

    console.log("OK:", file);
  } catch (err) {
    console.log("FAIL:", inputPath, err.message);
  }
}

console.log("DONE");