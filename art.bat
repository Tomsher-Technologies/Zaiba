@echo OFF

set _tail=%*
call set _tail=%%_tail:*%1=%%

IF "%1"=="m"   start /WAIT /B php artisan migrate 
IF "%1"=="mb"  start /WAIT /B php artisan migrate:rollback
IF "%1"=="m:M" start /WAIT /B php artisan make:model %_tail%
IF "%1"=="m:C" start /WAIT /B php artisan make:controller %_tail%
IF "%1"=="m:m" start /WAIT /B php artisan make:migration %_tail%