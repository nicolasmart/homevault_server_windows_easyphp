@echo off
if exist C:\PHP\ext (
    echo PHP Libraries found.
) else (
    echo PHP Libraries are missing, copying now...
    mkdir C:\PHP
    xcopy eds-binaries\php\php713vc14x86x201130231812\ext\ C:\PHP\ext\ /E
)
echo Starting EasyPHP server...
start "EasyPHP DevServer" run-devserver.exe
echo Done!
pause