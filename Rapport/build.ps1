# Pandoc Build Script for QCM Project (DOCX Only)
# This script converts modular Markdown files into a professional DOCX report.

$outputDocx = "QCM_Report.docx"

$inputFiles = "00_title_page.md",
"01_toc.md",
"02_preliminary.md", 
"03_contexte.md", 
"04_methode.md", 
"05_analyse.md", 
"06_technical.md", 
"07_design.md", 
"08_realisation.md", 
"09_conclusion.md"

Write-Host "--- Starting Pandoc Build for QCM (DOCX) ---" -ForegroundColor Cyan

# Check if Pandoc is installed
if (!(Get-Command pandoc -ErrorAction SilentlyContinue)) {
    Write-Host "ERROR: Pandoc is not installed or not in PATH." -ForegroundColor Red
    exit 1
}

Write-Host "Generating DOCX: $outputDocx..." -ForegroundColor Yellow

# Build command with options:
# --number-sections : Automatically number headings (e.g., 3.1.2)
# --toc : Pandoc can generate its own TOC, but we use 01_toc.md for custom control
pandoc $inputFiles `
    --number-sections `
    --toc-depth=3 `
    -o $outputDocx

if ($LASTEXITCODE -eq 0) {
    Write-Host "-------------------------------------------" -ForegroundColor Cyan
    Write-Host "SUCCESS: $outputDocx has been updated." -ForegroundColor Green
    Write-Host "Files merged: $($inputFiles.Count)" -ForegroundColor Gray
    Write-Host "-------------------------------------------" -ForegroundColor Cyan
}
else {
    Write-Host "BUILD FAILED: Pandoc returned an error." -ForegroundColor Red
}

Write-Host "--- Build Process Finished ---" -ForegroundColor Cyan