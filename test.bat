@echo off
echo Running Unit Tests with detailed output...
echo.
./vendor/bin/phpunit --verbose --testdox --colors=always
echo.
echo Test Summary:
echo - Green: Passed
echo - Yellow: Risky (test ran but may have issues)
echo - Red: Failed
echo - Blue: Skipped
echo.
pause