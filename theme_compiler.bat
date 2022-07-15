:: Ask for file name

@echo off
set /p file_name=Enter file name (no file extension): 
:: compile file without sourcemap
echo Compiling file...
sass --style=compressed --no-source-map sass/%file_name%.scss css/%file_name%.css